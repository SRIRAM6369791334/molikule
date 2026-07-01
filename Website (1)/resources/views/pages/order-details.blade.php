@extends('layouts.app')

@section('content')
    <!-- Premium Hero Section -->
    <section class="premium-hero-section" style="padding: 80px 0 100px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); position: relative; overflow: hidden;">
        <div id="heroParticles" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></div>
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle at 20% 50%, rgba(187, 215, 0, 0.15) 0%, transparent 50%); z-index: 2;"></div>
        <div class="auto-container" style="position: relative; z-index: 3; text-align: center;">
            <div class="hero-badge" style="display: inline-flex; align-items: center; gap: 10px; background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); padding: 10px 25px; border-radius: 100px; color: #bbd700; font-size: 14px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 20px;">
                <i class="fas fa-box-open"></i> Order Tracking
            </div>
            <h1 style="font-size: 48px; font-weight: 900; color: #ffffff; margin-bottom: 15px;">Order #{{ $order->order_number }}</h1>
            <p style="font-size: 18px; color: #94a3b8; max-width: 600px; margin: 0 auto;">Placed on {{ $order->created_at->format('M d, Y') }} at {{ $order->created_at->format('h:i A') }}</p>
            <div style="margin-top: 30px;">
                <a href="{{ route('my-account') }}" style="color: #ffffff; font-weight: 700; display: inline-flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.1); padding: 12px 25px; border-radius: 50px; backdrop-filter: blur(10px); transition: all 0.3s;" onmouseover="this.style.background='#bbd700'; this.style.color='#0f172a'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#ffffff'">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </section>

    <!-- Order Content Section -->
    <section class="order-details-section" style="padding: 80px 0; background: #f8fafc;">
        <div class="auto-container">
            <div class="row">
                <!-- Main Order Info -->
                <div class="col-lg-8">
                    <!-- Status Card -->
                    <div style="background: #ffffff; padding: 40px; border-radius: 30px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.02); margin-bottom: 30px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 40px;">
                            <h3 style="font-size: 24px; font-weight: 900; color: #0f172a;">Order Status</h3>
                            @php
                                $statusColor = [
                                    'pending' => ['bg' => '#fff7ed', 'text' => '#c2410c', 'icon' => 'clock'],
                                    'processing' => ['bg' => '#f0f9ff', 'text' => '#0369a1', 'icon' => 'sync'],
                                    'completed' => ['bg' => '#f0fdf4', 'text' => '#15803d', 'icon' => 'check-circle'],
                                    'cancelled' => ['bg' => '#fef2f2', 'text' => '#b91c1c', 'icon' => 'times-circle']
                                ][$order->status] ?? ['bg' => '#f8fafc', 'text' => '#64748b', 'icon' => 'info-circle'];
                            @endphp
                            <div style="display: inline-flex; align-items: center; gap: 10px; background: {{ $statusColor['bg'] }}; color: {{ $statusColor['text'] }}; padding: 12px 25px; border-radius: 100px; font-size: 14px; font-weight: 800; text-transform: uppercase;">
                                <i class="fas fa-{{ $statusColor['icon'] }}"></i> {{ $order->status_label }}
                            </div>
                        </div>

                        <!-- Custom Timeline -->
                        <div class="timeline-wrapper" style="position: relative; display: flex; justify-content: space-between; padding: 0 20px;">
                            <div style="position: absolute; top: 15px; left: 40px; right: 40px; height: 4px; background: #f1f5f9; z-index: 0;"></div>
                            @php
                                $steps = [
                                    ['label' => 'Ordered', 'icon' => 'shopping-basket', 'active' => true],
                                    ['label' => 'Processing', 'icon' => 'cogs', 'active' => in_array($order->status, ['processing', 'completed'])],
                                    ['label' => 'Shipped', 'icon' => 'shipping-fast', 'active' => in_array($order->status, ['shipped', 'completed'])],
                                    ['label' => 'Delivered', 'icon' => 'home', 'active' => $order->status === 'completed']
                                ];
                            @endphp
                            @foreach($steps as $step)
                                <div style="position: relative; z-index: 1; text-align: center; width: 80px;">
                                    <div style="width: 34px; height: 34px; background: {{ $step['active'] ? '#bbd700' : '#ffffff' }}; border: 3px solid {{ $step['active'] ? '#bbd700' : '#f1f5f9' }}; color: {{ $step['active'] ? '#0f172a' : '#cbd5e1' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; transition: all 0.4s;">
                                        <i class="fas fa-{{ $step['icon'] }}" style="font-size: 14px;"></i>
                                    </div>
                                    <span style="font-size: 12px; font-weight: 800; color: {{ $step['active'] ? '#0f172a' : '#94a3b8' }}; text-transform: uppercase;">{{ $step['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Items Card -->
                    <div style="background: #ffffff; padding: 40px; border-radius: 30px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                        <h3 style="font-size: 24px; font-weight: 900; color: #0f172a; margin-bottom: 30px;">Order Items</h3>
                        <div class="table-responsive">
                            <table class="table" style="margin-bottom: 0;">
                                <thead>
                                    <tr style="border-bottom: 2px solid #f8fafc;">
                                        <th style="padding: 15px 0; border: none; color: #64748b; font-size: 12px; font-weight: 800; text-transform: uppercase;">Product</th>
                                        <th style="padding: 15px 0; border: none; color: #64748b; font-size: 12px; font-weight: 800; text-transform: uppercase; text-align: center;">Price</th>
                                        <th style="padding: 15px 0; border: none; color: #64748b; font-size: 12px; font-weight: 800; text-transform: uppercase; text-align: center;">Qty</th>
                                        <th style="padding: 15px 0; border: none; color: #64748b; font-size: 12px; font-weight: 800; text-transform: uppercase; text-align: right;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        @php
                                            $product = null;
                                            $variant = null;
                                            if ($item->itemable_type === \App\Models\ProductVariant::class) {
                                                $variant = $item->itemable;
                                                $product = $variant->product;
                                            } else {
                                                $product = $item->itemable;
                                            }
                                        @endphp
                                        <tr style="border-bottom: 1px solid #f8fafc;">
                                            <td style="padding: 25px 0; border: none;">
                                                <div style="display: flex; align-items: center; gap: 20px;">
                                                    <div style="width: 70px; height: 70px; background: #f8fafc; border-radius: 15px; overflow: hidden; border: 1px solid #f1f5f9;">
                                                        <img src="{{ $product->image_url ?? asset('assets/images/resource/shop-1.png') }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                    <div>
                                                        <h4 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 5px;">{{ $product->name }}</h4>
                                                        @if($variant)
                                                            <span style="font-size: 12px; color: #64748b; font-weight: 600; background: #f1f5f9; padding: 4px 10px; border-radius: 50px;">{{ $variant->name }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="padding: 25px 0; border: none; text-align: center; font-weight: 700; color: #64748b;">{{ formatPrice($item->price) }}</td>
                                            <td style="padding: 25px 0; border: none; text-align: center; font-weight: 800; color: #0f172a;">x{{ $item->quantity }}</td>
                                            <td style="padding: 25px 0; border: none; text-align: right; font-weight: 900; color: #bbd700; font-size: 16px;">{{ formatPrice($item->total) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="col-lg-4">
                    <!-- Receipt Summary -->
                    <div style="background: #ffffff; padding: 40px; border-radius: 30px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.02); margin-bottom: 30px;">
                        <h3 style="font-size: 20px; font-weight: 900; color: #0f172a; margin-bottom: 25px;">Order Summary</h3>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                            <span style="color: #64748b; font-weight: 600;">Subtotal</span>
                            <span style="color: #0f172a; font-weight: 800;">{{ formatPrice($order->subtotal) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                            <span style="color: #64748b; font-weight: 600;">Tax</span>
                            <span style="color: #0f172a; font-weight: 800;">{{ formatPrice($order->tax_total) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                            <span style="color: #64748b; font-weight: 600;">Shipping</span>
                            <span style="color: #0f172a; font-weight: 800;">{{ formatPrice($order->shipping_total) }}</span>
                        </div>
                        @if($order->discount_total > 0)
                            <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                                <span style="color: #ef4444; font-weight: 600;">Discount</span>
                                <span style="color: #ef4444; font-weight: 800;">-{{ formatPrice($order->discount_total) }}</span>
                            </div>
                        @endif
                        <div style="margin-top: 25px; padding-top: 25px; border-top: 2px dashed #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 18px; font-weight: 900; color: #0f172a;">Grand Total</span>
                            <span style="font-size: 24px; font-weight: 900; color: #bbd700;">{{ formatPrice($order->grand_total) }}</span>
                        </div>
                    </div>

                    <!-- Shipping Address Card -->
                    <div style="background: #ffffff; padding: 40px; border-radius: 30px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                            <div style="width: 40px; height: 40px; background: rgba(187, 215, 0, 0.1); color: #bbd700; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3 style="font-size: 20px; font-weight: 900; color: #0f172a;">Shipping Address</h3>
                        </div>
                        <div style="color: #64748b; font-size: 15px; line-height: 1.8; font-weight: 600;">
                            <p style="color: #0f172a; font-weight: 800; margin-bottom: 5px;">{{ $order->shipping_name }}</p>
                            <p>{{ $order->shipping_address_line_1 }}</p>
                            @if($order->shipping_address_line_2) <p>{{ $order->shipping_address_line_2 }}</p> @endif
                            <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}</p>
                            <p style="margin-top: 10px;"><i class="fas fa-phone-alt" style="margin-right: 8px;"></i> {{ $order->shipping_phone }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Particle Engine
            const container = document.getElementById('heroParticles');
            if (container) {
                for (let i = 0; i < 40; i++) {
                    const particle = document.createElement('div');
                    const size = Math.random() * 3 + 1;
                    particle.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        background: rgba(187, 215, 0, ${Math.random() * 0.4 + 0.2});
                        border-radius: 50%;
                        left: ${Math.random() * 100}%;
                        top: ${Math.random() * 100}%;
                        opacity: 0;
                        animation: floatParticle ${Math.random() * 15 + 10}s infinite linear;
                        animation-delay: ${Math.random() * 5}s;
                    `;
                    container.appendChild(particle);
                }
            }
        });
    </script>
    <style>
        @keyframes floatParticle {
            0% { transform: translateY(0) scale(1); opacity: 0; }
            20% { opacity: 0.8; }
            80% { opacity: 0.8; }
            100% { transform: translateY(-200px) scale(0); opacity: 0; }
        }
    </style>
    @endpush
@endsection
