<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OrderService
{
    public function getTotalOrders(): int
    {
        return Order::count();
    }

    public function getPendingOrders(): int
    {
        return Order::where('status', Order::STATUS_PENDING)->count();
    }

    public function getTotalCustomers(): int
    {
        return Order::distinct('customer_email')->count('customer_email');
    }

    public function getOrderStats(): array
    {
        return \Cache::remember('order_stats_' . today()->format('Y-m-d'), 300, function () {
            $total = $this->getTotalOrders();
            $pending = $this->getPendingOrders();
            $processing = Order::processing()->count();
            $dispatched = Order::dispatched()->count();
            $completed = Order::delivered()->count();

            return [
                'total_orders' => $total,
                'pending_orders' => $pending,
                'processing_orders' => $processing,
                'dispatch_orders' => $dispatched,
                'completed_orders' => $completed,
                'total_customers' => $this->getTotalCustomers(),
                'today_orders' => Order::whereDate('created_at', today())->count(),
                'today_orders_revenue' => Order::whereDate('created_at', today())->sum('total_amount'),
                'week_orders' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'week_orders_revenue' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount'),
                'month_orders' => Order::whereYear('created_at', now()->year)
                                      ->whereMonth('created_at', now()->month)
                                      ->count(),
                'total_revenue' => Order::delivered()->sum('total_amount'),
            ];
        });
    }

    public function getFilteredOrders(array $filters, $perPage = 15)
    {
        $query = Order::with(['user', 'orderItems']);

        // Status filter
        if (isset($filters['status']) && $filters['status'] !== '') {
            if ($filters['status'] === 'pending') {
                $query->pending();
            } elseif ($filters['status'] === 'dispatch') {
                $query->dispatched();
            } elseif ($filters['status'] === 'delivered') {
                $query->delivered();
            } elseif ($filters['status'] === 'processing') {
                $query->processing();
            }
        }

        // Date filters
        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['today']) && $filters['today']) {
            $query->today();
        }

        // Customer filter
        if (isset($filters['customer']) && $filters['customer']) {
            $query->where(function($q) use ($filters) {
                $q->where('customer_name', 'like', '%' . $filters['customer'] . '%')
                  ->orWhere('customer_email', 'like', '%' . $filters['customer'] . '%');
            });
        }

        // Order number filter
        if (isset($filters['order_number']) && $filters['order_number']) {
            $query->where('order_number', 'like', '%' . $filters['order_number'] . '%');
        }

        // Amount range filters
        if (isset($filters['min_amount']) && $filters['min_amount']) {
            $query->where('total_amount', '>=', $filters['min_amount']);
        }

        if (isset($filters['max_amount']) && $filters['max_amount']) {
            $query->where('total_amount', '<=', $filters['max_amount']);
        }

        // Sort order
        if (isset($filters['sort_by'])) {
            $direction = $filters['sort_direction'] ?? 'desc';
            switch($filters['sort_by']) {
                case 'date':
                    $query->orderBy('created_at', $direction);
                    break;
                case 'customer':
                    $query->orderBy('customer_name', $direction);
                    break;
                case 'amount':
                    $query->orderBy('total_amount', $direction);
                    break;
                case 'status':
                    $query->orderBy('status', $direction);
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($perPage);
    }

    public function updateOrderStatus(int $id, string $status): ?Order
    {
        $order = Order::find($id);
        if (!$order) return null;

        $order->transitionTo($status);
        return $order;
    }

    public function bulkUpdateOrderStatus(array $orderIds, string $status): int
    {
        $orders = Order::whereIn('id', $orderIds)->get();
        $updated = 0;

        foreach ($orders as $order) {
            if ($order->transitionTo($status)) {
                $updated++;
            }
        }

        return $updated;
    }

    public function getOrderDetails(int $orderId)
    {
        $order = Order::with(['user', 'orderItems.itemable'])->find($orderId);

        if (!$order) return null;

        $formattedItems = $order->orderItems->map(function($item) {
            return [
                'name' => $item->item_name,
                'quantity' => $item->quantity,
                'price' => $item->unit_price,
                'total' => $item->total_price,
                'type' => $item->itemable ? ($item->itemable instanceof \App\Models\ProductVariant ? 'variant' : 'product') : 'unknown'
            ];
        });

        return [
            'order' => $order,
            'formatted_items' => $formattedItems
        ];
    }

    public function getPendingOrdersForDispatch(): Collection
    {
        return Order::pending()
                    ->with('user')
                    ->orderBy('created_at', 'asc')
                    ->take(10)
                    ->get();
    }

    public function getDispatchedOrders(): Collection
    {
        return Order::dispatched()
                    ->with('user')
                    ->orderBy('dispatch_date', 'desc')
                    ->take(10)
                    ->get();
    }

    public function getRecentDeliveries(): Collection
    {
        return Order::delivered()
                    ->with('user')
                    ->orderBy('delivery_date', 'desc')
                    ->take(10)
                    ->get();
    }

    public function getStatusWorkflowStats(): array
    {
        return [
            'workflow_steps' => Order::STATUS_LABELS,
            'transition_counts' => [
                'pending_to_dispatch' => Order::pending()->whereNotNull('updated_at')->count(),
                'dispatch_to_delivery' => Order::dispatched()->whereNotNull('delivery_date')->count(),
            ],
            'average_processing_time' => round(Order::delivered()->avg('processing_days') ?: 0, 1),
            'on_time_delivery_rate' => $this->calculateOnTimeDeliveryRate(),
        ];
    }

    private function calculateOnTimeDeliveryRate(): float
    {
        $totalDelivered = Order::delivered()->count();
        if ($totalDelivered === 0) return 0;

        $onTimeDelivered = Order::delivered()
                               ->whereNotNull('delivery_date')
                               ->whereNotNull('dispatch_date')
                               ->whereRaw('TIMESTAMPDIFF(DAY, dispatch_date, delivery_date) <= 3')
                               ->count();

        return round(($onTimeDelivered / $totalDelivered) * 100, 1);
    }

    public function createOrder(array $data): Order
    {
        return Order::create($data);
    }

    public function updateOrder(int $id, array $data): ?Order
    {
        $order = Order::find($id);
        if (!$order) return null;

        $order->update($data);
        return $order;
    }

    public function deleteOrder(int $id): bool
    {
        $order = Order::find($id);
        if (!$order || !$order->canDelete()) return false;

        return $order->delete();
    }

    public function calculateDashboardMetrics(): array
    {
        $today = today();
        $thisWeek = [now()->startOfWeek(), now()->endOfWeek()];

        return [
            'today_orders_count' => Order::whereDate('created_at', $today)->count(),
            'today_orders_revenue' => Order::whereDate('created_at', $today)->sum('total_amount'),
            'week_orders_count' => Order::whereBetween('created_at', $thisWeek)->count(),
            'week_orders_revenue' => Order::whereBetween('created_at', $thisWeek)->sum('total_amount'),
            'pending_orders' => Order::pending()->count(),
            'processing_orders' => Order::processing()->count(),
            'total_revenue' => Order::delivered()->sum('total_amount'),
            'average_order_value' => Order::where('status', '!=', Order::STATUS_PENDING)
                                         ->avg('total_amount') ?: 0,
        ];
    }

}
