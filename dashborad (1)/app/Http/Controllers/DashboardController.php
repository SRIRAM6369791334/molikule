<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Banner;
use App\Services\ProductService;
use App\Services\OrderService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private $productService;
    private $orderService;

    public function __construct(ProductService $productService, OrderService $orderService)
    {
        $this->productService = $productService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        // Basic analytics - calculate from actual data
        $totalSales = Order::sum('total_amount');
        $totalOrders = $this->orderService->getTotalOrders();
        $avgOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Today's revenue
        $todayRevenue = Order::whereDate('created_at', Carbon::today())->sum('total_amount');
        $yesterdayRevenue = Order::whereDate('created_at', Carbon::yesterday())->sum('total_amount');

        // Product analytics
        $totalProducts = $this->productService->getTotalProducts();
        $activeProducts = $this->productService->getTotalActiveProducts();
        $totalVariants = \App\Models\ProductVariant::count();
        $lowStockCount = $this->productService->getLowStockCount();

        // Categories and brands count
        $totalCategories = Category::count();
        $totalBrands = Brand::count();

        // Total customers (from orders to be accurate)
        $totalCustomers = $this->orderService->getTotalCustomers();
        $activeCustomers = $this->orderService->getTotalCustomers(); // same

        // Invoice system removed

        // Pending orders
        $pendingOrders = $this->orderService->getPendingOrders();

        // Categories and brands for filters
        $categories = Category::select('category_id', 'category_name')->get();
        $brands = Brand::select('brand_id', 'brand_name')->get();

        // Order delivery rate
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $deliveryRate = $totalOrders > 0 ? ($deliveredOrders / $totalOrders) * 100 : 0;

        // Recent orders (for potential dashboard display) - eager load relationships
        $recentOrders = Order::with(['user:id,name,email'])->latest()->take(5)->get();

        // Top selling products (mock data for now, can be enhanced later) - eager load relationships
        $products = Product::with(['category:category_id,category_name', 'brand:brand_id,brand_name'])->latest()->take(8)->get();

        $banners = Banner::where('is_active', true)->get();

        return view('dashboard', compact(
            'totalSales',
            'banners',
            'totalOrders',
            'avgOrderValue',
            'todayRevenue',
            'yesterdayRevenue',
            'totalProducts',
            'activeProducts',
            'totalVariants',
            'lowStockCount',
            'totalCategories',
            'totalBrands',
            'totalCustomers',
            'activeCustomers',
            'pendingOrders',
            'categories',
            'brands',
            'deliveredOrders',
            'deliveryRate',
            'recentOrders',
            'products'
        ));
    }
}
   
