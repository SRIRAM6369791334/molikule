@extends('layouts.app')

@section('content')
    <!-- page-title -->
    <section class="page-title"
        style="padding: 80px 0 60px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); position: relative; overflow: hidden;">
        <div
            style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: #bbd700; opacity: 0.05; border-radius: 50%;">
        </div>
        <div class="auto-container">
            <div class="content-box" style="text-align: center;">
                <h1 style="font-size: 48px; font-weight: 900; color: #0f172a; margin-bottom: 15px; letter-spacing: -2px;">
                    Account</h1>
                <ul class="bread-crumb"
                    style="display: inline-flex; gap: 12px; font-weight: 800; font-size: 14px; text-transform: uppercase; letter-spacing: 2px; background: #ffffff; padding: 12px 30px; border-radius: 50px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);">
                    <li><a href="{{ route('home') }}" style="color: #64748b; transition: color 0.3s;"
                            onmouseover="this.style.color='#bbd700'" onmouseout="this.style.color='#64748b'">Home</a></li>
                    <li style="color: #0f172a;">My Account</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- account-section -->
    <section class="account-section pt_80 pb_120" style="background: #fdfdfd;">
        <div class="auto-container">
            <div class="row clearfix">
                <!-- Sidebar -->
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="account-sidebar"
                        style="background: #ffffff; padding: 40px 30px; border-radius: 35px; box-shadow: 0 25px 60px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; position: sticky; top: 120px; transition: all 0.3s;">
                        <div class="user-profile centred mb_40">
                            <div style="position: relative; width: 110px; height: 110px; margin: 0 auto 20px;">
                                <div
                                    style="width: 100%; height: 100%; border-radius: 50%; overflow: hidden; border: 4px solid #bbd700; padding: 5px; background: #fff; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(187, 215, 0, 0.2);">
                                    <div
                                        style="width: 100%; height: 100%; border-radius: 50%; background: #f8fafc; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user-astronaut" style="font-size: 45px; color: #cbd5e1;"></i>
                                    </div>
                                </div>
                                <div
                                    style="position: absolute; bottom: 5px; right: 5px; width: 30px; height: 30px; background: #bbd700; border-radius: 50%; border: 4px solid #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #0f172a; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                    <i class="fas fa-crown"></i>
                                </div>
                            </div>
                            <h4 style="font-weight: 900; color: #0f172a; margin-bottom: 4px; letter-spacing: -0.5px;">
                                {{ $user->name }}</h4>
                            <p style="color: #94a3b8; font-size: 14px; font-weight: 600;">{{ $user->email }}</p>
                        </div>

                        <ul class="account-nav tab-buttons clearfix" style="list-style: none; padding: 0; margin: 0;">
                            <li class="tab-btn active-btn" data-tab="#dashboard"
                                style="display: flex; align-items: center; gap: 15px; padding: 16px 25px; border-radius: 20px; margin-bottom: 8px; font-weight: 800; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; color: #64748b;">
                                <i class="fas fa-chart-pie" style="font-size: 18px;"></i> Overview
                            </li>
                            <li class="tab-btn" data-tab="#orders"
                                style="display: flex; align-items: center; gap: 15px; padding: 16px 25px; border-radius: 20px; margin-bottom: 8px; font-weight: 800; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; color: #64748b;">
                                <i class="fas fa-shopping-cart" style="font-size: 18px;"></i> Orders
                            </li>
                            <li class="tab-btn" data-tab="#profile"
                                style="display: flex; align-items: center; gap: 15px; padding: 16px 25px; border-radius: 20px; margin-bottom: 8px; font-weight: 800; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; color: #64748b;">
                                <i class="fas fa-id-card" style="font-size: 18px;"></i> My Profile
                            </li>
                            {{-- <li class="tab-btn" data-tab="#address"
                                style="display: flex; align-items: center; gap: 15px; padding: 16px 25px; border-radius: 20px; margin-bottom: 8px; font-weight: 800; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; color: #64748b;">
                                <i class="fas fa-map-pin" style="font-size: 18px;"></i> Addresses
                            </li> --}}
                            <li class="tab-btn" data-tab="#wishlist"
                                style="display: flex; align-items: center; gap: 15px; padding: 16px 25px; border-radius: 20px; margin-bottom: 8px; font-weight: 800; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; color: #64748b;">
                                <i class="fas fa-gem" style="font-size: 18px;"></i> Wishlist
                            </li>
                            {{-- <li class="tab-btn" data-tab="#security"
                                style="display: flex; align-items: center; gap: 15px; padding: 16px 25px; border-radius: 20px; margin-bottom: 25px; font-weight: 800; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; color: #64748b;">
                                <i class="fas fa-shield-virus" style="font-size: 18px;"></i> Security
                            </li> --}}
                            <li style="border-top: 2px solid #f8fafc; padding-top: 20px;">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf</form>
                                <a href="javascript:void(0)" onclick="confirmLogout()"
                                    style="display: flex; align-items: center; gap: 15px; padding: 16px 25px; border-radius: 20px; font-weight: 800; color: #f43f5e; transition: all 0.3s; background: #fff1f2;">
                                    <i class="fas fa-power-off" style="font-size: 18px;"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9 col-md-12 col-sm-12">
                    <div class="tabs-content" style="background: transparent;">

                        <!-- Dashboard Tab -->
                        <div class="tab active-tab" id="dashboard">
                            <div class="dashboard-content">
                                <div style="margin-bottom: 40px; position: relative;">
                                    <h2
                                        style="font-size: 38px; font-weight: 900; color: #0f172a; letter-spacing: -1.5px; margin-bottom: 10px;">
                                        Greetings, <span style="color: #bbd700;">{{ explode(' ', $user->name)[0] }}</span>!
                                    </h2>
                                    <p style="color: #64748b; font-weight: 600; font-size: 16px;">Welcome to your Molikule
                                        dashboard. Manage your orders and preferences with ease.</p>
                                </div>

                                <div class="row clearfix mb_50">
                                    <div class="col-lg-4 col-md-6 col-sm-12 mb_25">
                                        <div class="stat-card"
                                            style="background: #ffffff; padding: 30px; border-radius: 30px; border: 1px solid #f1f5f9; box-shadow: 0 15px 40px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 25px;">
                                            <div
                                                style="width: 65px; height: 65px; background: #f0fdf4; color: #22c55e; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 24px; box-shadow: 0 8px 20px rgba(34, 197, 94, 0.1);">
                                                <i class="fas fa-shopping-bag"></i>
                                            </div>
                                            <div>
                                                <h4
                                                    style="font-weight: 900; color: #0f172a; font-size: 28px; margin-bottom: 0; line-height: 1;">
                                                    {{ $stats['total_orders'] }}</h4>
                                                <p
                                                    style="margin: 0; color: #94a3b8; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px;">
                                                    Total Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 mb_25">
                                        <div class="stat-card"
                                            style="background: #ffffff; padding: 30px; border-radius: 30px; border: 1px solid #f1f5f9; box-shadow: 0 15px 40px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 25px;">
                                            <div
                                                style="width: 65px; height: 65px; background: #fff1f2; color: #f43f5e; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 24px; box-shadow: 0 8px 20px rgba(244, 63, 94, 0.1);">
                                                <i class="fas fa-heart"></i>
                                            </div>
                                            <div>
                                                <h4
                                                    style="font-weight: 900; color: #0f172a; font-size: 28px; margin-bottom: 0; line-height: 1;">
                                                    {{ $stats['total_wishlist'] }}</h4>
                                                <p
                                                    style="margin: 0; color: #94a3b8; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px;">
                                                    Wishlist</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 mb_25">
                                        <div class="stat-card"
                                            style="background: #ffffff; padding: 30px; border-radius: 30px; border: 1px solid #f1f5f9; box-shadow: 0 15px 40px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 25px;">
                                            <div
                                                style="width: 65px; height: 65px; background: #f0f9ff; color: #0ea5e9; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 24px; box-shadow: 0 8px 20px rgba(14, 165, 233, 0.1);">
                                                <i class="fas fa-shopping-cart"></i>
                                            </div>
                                            <div>
                                                <h4
                                                    style="font-weight: 900; color: #0f172a; font-size: 28px; margin-bottom: 0; line-height: 1;">
                                                    {{ cartCount() }}</h4>
                                                <p
                                                    style="margin: 0; color: #94a3b8; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px;">
                                                    Live Cart</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="recent-orders-card"
                                    style="background: #ffffff; padding: 40px; border-radius: 35px; border: 1px solid #f1f5f9; box-shadow: 0 20px 50px rgba(0,0,0,0.03);">
                                    <div
                                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                                        <h4 style="font-weight: 900; color: #0f172a; letter-spacing: -0.5px;">Recently
                                            Acquired</h4>
                                        <button onclick="switchTab('#orders')"
                                            style="background: #bbd700; color: #0f172a; border: none; padding: 10px 25px; border-radius: 50px; font-weight: 800; font-size: 13px; transition: all 0.3s; box-shadow: 0 8px 20px rgba(187, 215, 0, 0.2);">Explore
                                            History</button>
                                    </div>
                                    @if($orders->isEmpty())
                                        <div style="text-align: center; padding: 40px 0;">
                                            <i class="fas fa-shopping-cart"
                                                style="font-size: 40px; color: #e2e8f0; margin-bottom: 15px;"></i>
                                            <p style="color: #94a3b8; font-weight: 600;">Your order history is currently a blank
                                                canvas.</p>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table" style="margin: 0;">
                                                <thead>
                                                    <tr
                                                        style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #94a3b8; border-bottom: 1px solid #f1f5f9;">
                                                        <th style="padding: 15px 0; border: none; font-weight: 900;">Ref ID</th>
                                                        <th style="padding: 15px 0; border: none; font-weight: 900;">Date</th>
                                                        <th style="padding: 15px 0; border: none; font-weight: 900;">Status</th>
                                                        <th
                                                            style="padding: 15px 0; border: none; font-weight: 900; text-align: right;">
                                                            Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($orders->take(3) as $order)
                                                        <tr style="transition: all 0.3s;">
                                                            <td
                                                                style="padding: 20px 0; border: none; border-bottom: 1px solid #f8fafc; font-weight: 800; color: #0f172a;">
                                                                #{{ $order->order_number }}</td>
                                                            <td
                                                                style="padding: 20px 0; border: none; border-bottom: 1px solid #f8fafc; color: #64748b; font-weight: 600;">
                                                                {{ $order->created_at->format('M d, Y') }}</td>
                                                            <td
                                                                style="padding: 20px 0; border: none; border-bottom: 1px solid #f8fafc;">
                                                                <span
                                                                    style="font-size: 10px; font-weight: 900; background: #bbd70020; color: #7e9100; padding: 4px 12px; border-radius: 50px; text-transform: uppercase;">{{ $order->status_label }}</span>
                                                            </td>
                                                            <td
                                                                style="padding: 20px 0; border: none; border-bottom: 1px solid #f8fafc; text-align: right; font-weight: 900; color: #0f172a; font-size: 16px;">
                                                                {{ formatPrice($order->total_amount) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Orders Tab -->
                        <div class="tab" id="orders">
                            <div style="margin-bottom: 40px;">
                                <h3 style="font-size: 32px; font-weight: 900; color: #0f172a; letter-spacing: -1px;">
                                    Order History</h3>
                                <!-- <p style="color: #64748b; font-weight: 600;">Monitor your current logistics and revisit past
                                    acquisitions.</p> -->
                            </div>

                            @if($orders->isEmpty())
                                <div
                                    style="text-align: center; padding: 100px 40px; background: #ffffff; border-radius: 45px; border: 2px dashed #e2e8f0;">
                                    <div
                                        style="width: 80px; height: 80px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                                        <i class="fas fa-box-open" style="font-size: 35px; color: #cbd5e1;"></i>
                                    </div>
                                    <h4 style="color: #0f172a; font-weight: 900; margin-bottom: 10px;">The vault is empty</h4>
                                    <p
                                        style="color: #64748b; margin-bottom: 35px; max-width: 400px; margin-left: auto; margin-right: auto;">
                                        Start your collection by exploring our latest innovative offerings.</p>
                                    <a href="{{ route('shop') }}" class="theme-btn"
                                        style="background: #bbd700; color: #0f172a; border-radius: 50px; font-weight: 900; padding: 15px 40px; box-shadow: 0 10px 25px rgba(187, 215, 0, 0.3);">Discover
                                        Now</a>
                                </div>
                            @else
                                <div class="order-history-container"
                                    style="background: #ffffff; border-radius: 35px; border: 1px solid #f1f5f9; box-shadow: 0 25px 70px rgba(0,0,0,0.03); overflow: hidden;">
                                    <div class="table-responsive">
                                        <table class="table"
                                            style="margin: 0; min-width: 800px; border-collapse: separate; border-spacing: 0;">
                                            <thead>
                                                <tr style="background: #fafbfc;">
                                                    <th
                                                        style="padding: 25px; border: none; font-size: 11px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px;">
                                                        Order ID</th>
                                                    <th
                                                        style="padding: 25px; border: none; font-size: 11px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px;">
                                                        Timestamp</th>
                                                    <th
                                                        style="padding: 25px; border: none; font-size: 11px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px;">
                                                        Price</th>
                                                    <th
                                                        style="padding: 25px; border: none; font-size: 11px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px;">
                                                        Live Status</th>
                                                    <th
                                                        style="padding: 25px; border: none; font-size: 11px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; text-align: right;">
                                                        Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orders as $order)
                                                    <tr class="order-row" style="cursor: pointer; transition: background 0.3s;"
                                                        onclick="toggleOrderDetails('order-full-{{ $order->id }}')">
                                                        <td
                                                            style="padding: 25px; border-bottom: 1px solid #f8fafc; font-weight: 800; color: #0f172a;">
                                                            #{{ $order->order_number }}</td>
                                                        <td
                                                            style="padding: 25px; border-bottom: 1px solid #f8fafc; color: #64748b; font-weight: 700;">
                                                            {{ $order->created_at->format('d M, Y') }}</td>
                                                        <td
                                                            style="padding: 25px; border-bottom: 1px solid #f8fafc; font-weight: 900; color: #0f172a; font-size: 16px;">
                                                            {{ formatPrice($order->total_amount) }}</td>
                                                        <td style="padding: 25px; border-bottom: 1px solid #f8fafc;">
                                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                                <div
                                                                    style="width: 8px; height: 8px; border-radius: 50%; background: {{ $order->status == 'delivered' ? '#22c55e' : ($order->status == 'pending' ? '#eab308' : '#3b82f6') }};">
                                                                </div>
                                                                <span
                                                                    style="font-size: 11px; font-weight: 800; color: #0f172a; text-transform: uppercase;">{{ $order->status_label }}</span>
                                                            </div>
                                                        </td>
                                                        <td
                                                            style="padding: 25px; border-bottom: 1px solid #f8fafc; text-align: right;">
                                                            <button class="view-btn"
                                                                style="background: #f1f5f9; border: none; width: 45px; height: 45px; border-radius: 12px; color: #0f172a; font-weight: 800; transition: all 0.3s; display: inline-flex; align-items: center; justify-content: center;">
                                                                <i class="fas fa-eye" style="font-size: 16px;"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr id="order-full-{{ $order->id }}" class="detail-row"
                                                        style="display: none; background: #fafbfc;">
                                                        <td colspan="5" style="padding: 40px; border-bottom: 1px solid #f1f5f9;">
                                                            <div class="row">
                                                                <div class="col-lg-8">
                                                                    <div class="items-list mb_30">
                                                                        <h6
                                                                            style="font-weight: 900; color: #0f172a; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                                                                            <i class="fas fa-list-ul" style="color: #bbd700;"></i>
                                                                            Products Items
                                                                        </h6>
                                                                        <div
                                                                            style="background: #fff; border-radius: 25px; padding: 10px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                                                                            @foreach($order->items as $item)
                                                                                <div
                                                                                    style="display: flex; align-items: center; gap: 20px; padding: 20px; border-bottom: 1px solid #f8fafc; @if($loop->last) border-bottom: none; @endif">
                                                                                    <div
                                                                                        style="width: 80px; height: 80px; background: #f8fafc; border-radius: 20px; flex-shrink: 0; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid #f1f5f9;">
                                                                                        @php
                                                                                            $imgUrl = asset('assets/images/shop/shop-15.png'); // Default
                                                                                            if ($item->itemable) {
                                                                                                if ($item->itemable_type == 'App\Models\ProductVariant') {
                                                                                                    $imgUrl = $item->itemable->variant_image_full_url ?? ($item->itemable->product->image_full_url ?? $imgUrl);
                                                                                                } else {
                                                                                                    $imgUrl = $item->itemable->image_full_url ?? $imgUrl;
                                                                                                }
                                                                                            }
                                                                                        @endphp
                                                                                        <img src="{{ $imgUrl }}"
                                                                                            alt="{{ $item->item_name }}"
                                                                                            style="width: 100%; height: 100%; object-fit: cover;">
                                                                                    </div>
                                                                                    <div style="flex: 1;">
                                                                                        <h6
                                                                                            style="font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                                                                                            {{ $item->item_name }}</h6>
                                                                                        <p
                                                                                            style="margin: 0; color: #94a3b8; font-size: 13px; font-weight: 700;">
                                                                                            Qty: {{ $item->quantity }}
                                                                                            <span
                                                                                                style="margin: 0 10px; opacity: 0.3;">|</span>
                                                                                            Rate: {{ formatPrice($item->unit_price) }}
                                                                                        </p>
                                                                                    </div>
                                                                                    <div style="text-align: right;">
                                                                                        <span
                                                                                            style="font-weight: 900; color: #0f172a; font-size: 16px;">{{ formatPrice($item->total_price) }}</span>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="summary-card mb_30">
                                                                        <h6
                                                                            style="font-weight: 900; color: #0f172a; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                                                                            <i class="fas fa-file-invoice-dollar"
                                                                                style="color: #bbd700;"></i> Price
                                                                        </h6>
                                                                        <div
                                                                            style="background: #fff; border-radius: 25px; padding: 25px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                                                                            <div
                                                                                style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; font-weight: 700;">
                                                                                <span style="color: #94a3b8;">Sub-Total</span>
                                                                                <span
                                                                                    style="color: #0f172a;">{{ formatPrice($order->total_amount - $order->shipping_cost + $order->discount_amount) }}</span>
                                                                            </div>
                                                                            @if($order->discount_amount > 0)
                                                                                <div
                                                                                    style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; font-weight: 700;">
                                                                                    <span style="color: #f43f5e;">Promo Yield</span>
                                                                                    <span
                                                                                        style="color: #f43f5e;">-{{ formatPrice($order->discount_amount) }}</span>
                                                                                </div>
                                                                            @endif
                                                                            <div
                                                                                style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 14px; font-weight: 700;">
                                                                                <span style="color: #94a3b8;">Shiping Fee</span>
                                                                                <span
                                                                                    style="color: #0f172a;">{{ formatPrice($order->shipping_cost) }}</span>
                                                                            </div>
                                                                            <div
                                                                                style="display: flex; justify-content: space-between; border-top: 2px solid #f8fafc; padding-top: 15px; margin-top: 5px;">
                                                                                <span
                                                                                    style="font-weight: 900; color: #0f172a; font-size: 15px;">Total<br>Amount</span>
                                                                                <span
                                                                                    style="font-weight: 900; color: #bbd700; font-size: 22px; letter-spacing: -1px;">{{ formatPrice($order->total_amount) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="shipping-info" style="margin-top: 35px; border-top: 2px solid #f8fafc; padding-top: 25px;">
                                                                        <h6 style="font-weight: 900; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                                                                            <i class="fas fa-map-marker-alt" style="color: #bbd700;"></i> Dispatch Coordinates
                                                                        </h6>
                                                                        <div style="background: #f8fafc; border-radius: 20px; padding: 20px; border: 1px solid #f1f5f9;">
                                                                            <p style="margin-bottom: 8px; color: #0f172a; font-weight: 900; font-size: 16px; letter-spacing: -0.5px;">{{ $order->customer_name }}</p>
                                                                            <p style="margin: 0; color: #64748b; font-size: 14px; line-height: 1.6; font-weight: 600;">{{ $order->shipping_address }}</p>
                                                                            <div style="display: flex; align-items: center; gap: 15px; margin-top: 15px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                                                                                <div style="font-size: 13px; font-weight: 800; color: #0f172a;">
                                                                                    <span style="color: #94a3b8; font-weight: 700; margin-right: 5px;">ZIP:</span> {{ $order->pincode }}
                                                                                </div>
                                                                                <div style="width: 1px; height: 12px; background: #cbd5e1;"></div>
                                                                                <div style="font-size: 13px; font-weight: 800; color: #0f172a;">
                                                                                    <i class="fas fa-phone-alt" style="font-size: 11px; color: #bbd700; margin-right: 5px;"></i> {{ $order->customer_phone }}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="pagination-wrapper mt_50" style="display: flex; justify-content: center;">
                                    {{ $orders->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        </div>

                        <!-- Profile Tab -->
                        <div class="tab" id="profile">
                            <div style="margin-bottom: 40px;">
                                <h3 style="font-size: 32px; font-weight: 900; color: #0f172a; letter-spacing: -1px;">
                                    Profile Info</h3>
                                {{-- <p style="color: #64748b; font-weight: 600;">Refine your core profile metadata and
                                    communication channels.</p> --}}
                            </div>

                            <div
                                style="background: #ffffff; padding: 50px; border-radius: 40px; border: 1px solid #f1f5f9; box-shadow: 0 25px 70px rgba(0,0,0,0.03);">
                                <form action="{{ route('account.update') }}" method="POST" id="profileForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb_30">
                                            <label
                                                style="display: block; font-size: 11px; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 1.5px;">Full
                                                Identification</label>
                                            <div style="position: relative;">
                                                <i class="fas fa-signature"
                                                    style="position: absolute; left: 20px; top: 18px; color: #bbd700;"></i>
                                                <input type="text" name="name" value="{{ $user->name }}"
                                                    style="width: 100%; padding: 16px 20px 16px 50px; border-radius: 18px; border: 2px solid #f1f5f9; font-weight: 700; color: #0f172a; background: #fafbfc; transition: all 0.3s;"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb_30">
                                            <label
                                                style="display: block; font-size: 11px; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 1.5px;">Comms
                                                Terminal (Phone)</label>
                                            <div style="position: relative;">
                                                <i class="fas fa-mobile-alt"
                                                    style="position: absolute; left: 20px; top: 18px; color: #bbd700;"></i>
                                                <input type="text" name="phone" value="{{ $user->phone }}"
                                                    style="width: 100%; padding: 16px 20px 16px 50px; border-radius: 18px; border: 2px solid #f1f5f9; font-weight: 700; color: #0f172a; background: #fafbfc; transition: all 0.3s;"
                                                    placeholder="Connect your mobile">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb_40">
                                            <label
                                                style="display: block; font-size: 11px; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 1.5px;">Network
                                                Node (Email)</label>
                                            <div style="position: relative;">
                                                <i class="fas fa-envelope-open"
                                                    style="position: absolute; left: 20px; top: 18px; color: #cbd5e1;"></i>
                                                <div
                                                    style="width: 100%; padding: 16px 20px 16px 50px; border-radius: 18px; border: 2px solid #f8fafc; font-weight: 700; color: #94a3b8; background: #f8fafc;">
                                                    {{ $user->email }}
                                                </div>
                                            </div>
                                            <p style="margin-top: 8px; font-size: 12px; color: #94a3b8; font-weight: 600;">
                                                Email node is immutable for security purposes.</p>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="theme-btn"
                                                style="background: #0f172a; color: #ffffff; border-radius: 50px; font-weight: 900; padding: 18px 50px; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s; box-shadow: 0 15px 30px rgba(15, 23, 42, 0.2);">Update Profile</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Address Tab -->
                        <div class="tab" id="address">
                            <div
                                style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px;">
                                <div>
                                    <h3 style="font-size: 32px; font-weight: 900; color: #0f172a; letter-spacing: -1px;">
                                        Geospatial Nodes</h3>
                                    <p style="color: #64748b; font-weight: 600;">Manage your primary and secondary
                                        shipping/billing coordinates.</p>
                                </div>
                                <button onclick="openAddressModal()"
                                    style="background: #bbd700; color: #0f172a; border: none; border-radius: 50px; font-weight: 900; padding: 14px 30px; font-size: 14px; box-shadow: 0 10px 25px rgba(187, 215, 0, 0.2); transition: all 0.3s;">Initialize
                                    New Node</button>
                            </div>

                            <div class="row">
                                @forelse($addresses as $addr)
                                    <div class="col-md-6 mb_30">
                                        <div class="address-card"
                                            style="background: #ffffff; padding: 35px; border-radius: 35px; border: 1px solid #f1f5f9; position: relative; transition: all 0.4s; height: 100%; display: flex; flex-direction: column; box-shadow: 0 10px 40px rgba(0,0,0,0.02);">
                                            <div
                                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                                <div
                                                    style="width: 45px; height: 45px; background: #f8fafc; border-radius: 15px; display: flex; align-items: center; justify-content: center; color: #bbd700;">
                                                    <i class="fas {{ $addr->address_type_id == 1 ? 'fa-home' : ($addr->address_type_id == 2 ? 'fa-building' : 'fa-map-marker-alt') }}"
                                                        style="font-size: 18px;"></i>
                                                </div>
                                                <span
                                                    style="background: #f1f5f9; color: #64748b; padding: 6px 16px; border-radius: 50px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">
                                                    {{ $addr->address_type_id == 1 ? 'Home' : ($addr->address_type_id == 2 ? 'Work' : 'Other') }}
                                                </span>
                                            </div>
                                            <h5
                                                style="font-weight: 900; color: #0f172a; margin-bottom: 12px; letter-spacing: -0.5px;">
                                                {{ $addr->address_username }}</h5>
                                            <div
                                                style="color: #64748b; font-size: 14px; line-height: 1.8; margin-bottom: 30px; flex: 1; font-weight: 600;">
                                                <p style="margin-bottom: 0;">{{ $addr->address_line_one }}</p>
                                                @if($addr->address_line_two)
                                                <p style="margin-bottom: 0;">{{ $addr->address_line_two }}</p> @endif
                                                <p style="margin-bottom: 0;">{{ $addr->city }}, {{ $addr->state }}</p>
                                                <p style="color: #0f172a; font-weight: 800;">Zip: {{ $addr->pincode }}</p>
                                                <p style="margin-top: 15px; color: #0f172a;"><i class="fas fa-phone"
                                                        style="font-size: 12px; margin-right: 8px; color: #bbd700;"></i>
                                                    {{ $addr->address_phone_number }}</p>
                                            </div>
                                            <div
                                                style="display: flex; gap: 20px; border-top: 2px solid #f8fafc; padding-top: 25px;">
                                                <button onclick='openAddressModal(@json($addr))'
                                                    style="background: transparent; border: none; font-size: 14px; font-weight: 900; color: #bbd700; cursor: pointer; padding: 0; display: flex; align-items: center; gap: 8px;">
                                                    <i class="fas fa-edit"></i> Edit Node
                                                </button>
                                                <form action="{{ route('account.address.delete', $addr->id) }}" method="POST"
                                                    id="delete-addr-{{ $addr->id }}" style="display: none;">
                                                    @csrf @method('DELETE')
                                                </form>
                                                <button onclick="confirmDeleteAddress({{ $addr->id }})"
                                                    style="background: transparent; border: none; font-size: 14px; font-weight: 900; color: #f43f5e; cursor: pointer; padding: 0; display: flex; align-items: center; gap: 8px;">
                                                    <i class="fas fa-trash-alt"></i> Purge
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div
                                            style="text-align: center; padding: 80px; background: #fff; border-radius: 40px; border: 3px dashed #f1f5f9;">
                                            <i class="fas fa-map-marked"
                                                style="font-size: 40px; color: #e2e8f0; margin-bottom: 20px;"></i>
                                            <p style="color: #94a3b8; font-weight: 700; font-size: 18px;">No coordinates
                                                registered in our network.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Wishlist Tab -->
                        <div class="tab" id="wishlist">
                            <div style="margin-bottom: 40px;">
                                <h3 style="font-size: 32px; font-weight: 900; color: #0f172a; letter-spacing: -1px;">Curated
                                    Collection</h3>
                                {{-- <p style="color: #64748b; font-weight: 600;">Elite items synchronized to your desire list.
                                </p> --}}
                            </div>

                            @if($wishlistProducts->isEmpty())
                                <div
                                    style="text-align: center; padding: 100px 40px; background: #ffffff; border-radius: 45px; border: 2px dashed #e2e8f0;">
                                    <div
                                        style="width: 80px; height: 80px; background: #fff1f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                                        <i class="fas fa-heart" style="font-size: 35px; color: #f43f5e;"></i>
                                    </div>
                                    <h4 style="color: #0f172a; font-weight: 900; margin-bottom: 10px;">Wishlist</h4>
                                    <p style="color: #64748b; margin-bottom: 35px;">Populate your collection with our premium
                                        selections.</p>
                                    <a href="{{ route('shop') }}" class="theme-btn"
                                        style="background: #bbd700; color: #0f172a; border-radius: 50px; font-weight: 900; padding: 15px 40px; box-shadow: 0 10px 25px rgba(187, 215, 0, 0.3);">Explore
                                        Shop</a>
                                </div>
                            @else
                                <div class="row clearfix">
                                    @foreach($wishlistProducts as $wishProduct)
                                        <div class="col-lg-4 col-md-6 col-sm-12 mb_40">
                                            <div class="wish-item-wrap" style="transition: all 0.4s;">
                                                <x-product-card :product="$wishProduct" style="featured" :noCol="true" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Security Tab -->
                        <div class="tab" id="security">
                            <div style="margin-bottom: 40px;">
                                <h3 style="font-size: 32px; font-weight: 900; color: #0f172a; letter-spacing: -1px;">
                                    Firewall & Access</h3>
                                <p style="color: #64748b; font-weight: 600;">Hardening your portal against unauthorized
                                    intrusion.</p>
                            </div>

                            <div
                                style="background: #ffffff; padding: 50px; border-radius: 40px; border: 1px solid #f1f5f9; box-shadow: 0 25px 70px rgba(0,0,0,0.03); margin-bottom: 40px;">
                                <h5
                                    style="font-weight: 900; color: #0f172a; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;">
                                    <i class="fas fa-key" style="color: #bbd700;"></i> Rotate Security Keys
                                </h5>
                                <form action="{{ route('account.password') }}" method="POST" id="passwordForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb_25">
                                            <label
                                                style="display: block; font-size: 11px; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 1.5px;">Current
                                                Key</label>
                                            <input type="password" name="current_password"
                                                style="width: 100%; padding: 16px 20px; border-radius: 18px; border: 2px solid #f1f5f9; font-weight: 700; background: #fafbfc; transition: all 0.3s;"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb_25">
                                            <label
                                                style="display: block; font-size: 11px; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 1.5px;">New
                                                Entropy Key</label>
                                            <input type="password" name="password"
                                                style="width: 100%; padding: 16px 20px; border-radius: 18px; border: 2px solid #f1f5f9; font-weight: 700; background: #fafbfc; transition: all 0.3s;"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb_40">
                                            <label
                                                style="display: block; font-size: 11px; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 1.5px;">Re-verify
                                                Key</label>
                                            <input type="password" name="password_confirmation"
                                                style="width: 100%; padding: 16px 20px; border-radius: 18px; border: 2px solid #f1f5f9; font-weight: 700; background: #fafbfc; transition: all 0.3s;"
                                                required>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="theme-btn"
                                                style="background: #bbd700; color: #0f172a; border-radius: 50px; font-weight: 900; padding: 18px 50px; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 15px 30px rgba(187, 215, 0, 0.2);">Deploy
                                                New Keys</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div
                                style="background: #fff1f2; padding: 40px; border-radius: 35px; border: 2px dashed #fecaca;">
                                <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 20px;">
                                    <div
                                        style="width: 50px; height: 50px; background: #f43f5e; color: #fff; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                        <i class="fas fa-radiation"></i>
                                    </div>
                                    <div>
                                        <h5 style="color: #9f1239; font-weight: 900; margin-bottom: 4px;">Nuclear
                                            Termination</h5>
                                        <p style="color: #e11d48; font-size: 14px; font-weight: 700; margin: 0;">
                                            Self-destruct sequence for account deletion. Non-reversible.</p>
                                    </div>
                                </div>
                                <button type="button" onclick="confirmDeleteAccount()"
                                    style="background: #f43f5e; color: #fff; border: none; padding: 15px 35px; border-radius: 50px; font-weight: 900; text-transform: uppercase; font-size: 13px; letter-spacing: 1px; box-shadow: 0 10px 25px rgba(244, 63, 94, 0.3); transition: all 0.3s;">Initiate
                                    Self-Destruct</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* General Layout & Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&display=swap');

        .account-section,
        .page-title {
            font-family: 'Outfit', sans-serif !important;
        }

        /* Navigation Transitions */
        .account-nav .tab-btn.active-btn {
            background: #bbd700;
            color: #0f172a !important;
            box-shadow: 0 15px 30px rgba(187, 215, 0, 0.25);
            transform: scale(1.05);
        }

        .account-nav .tab-btn:hover:not(.active-btn) {
            background: #f8fafc;
            color: #0f172a !important;
            transform: translateX(10px);
        }

        /* Tab Content Animation */
        .tab {
            display: none;
        }

        .tab.active-tab {
            display: block;
        }

        /* Order Row Hover */
        .order-row:hover {
            background: #f8fafc !important;
        }

        .order-row:hover .view-btn {
            background: #bbd700 !important;
            transform: scale(1.05);
        }

        /* Form Interaction */
        input:focus {
            outline: none;
            border-color: #bbd700 !important;
            box-shadow: 0 0 0 5px rgba(187, 215, 0, 0.15) !important;
            background: #fff !important;
        }

        /* Stats Lift */
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.06) !important;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* Address Card Lift */
        .address-card:hover {
            transform: translateY(-8px);
            border-color: #bbd700 !important;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.05) !important;
        }

        /* Custom Scrollbar for responsiveness */
        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        /* SweetAlert Customization */
        .swal2-popup {
            border-radius: 40px !important;
            padding: 40px !important;
        }

        .swal2-title {
            font-family: 'Outfit', sans-serif !important;
            font-weight: 900 !important;
        }

        .swal2-confirm {
            border-radius: 50px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
        }

        .swal2-cancel {
            border-radius: 50px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // GSAP Entrance
            gsap.from(".account-sidebar", { duration: 1, x: -50, opacity: 0, ease: "power4.out" });
            gsap.from(".tabs-content", { duration: 1, x: 50, opacity: 0, ease: "power4.out", delay: 0.2 });

            // Tab Switching Logic
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabs = document.querySelectorAll('.tab');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const target = this.getAttribute('data-tab');

                    if (document.querySelector(target).classList.contains('active-tab')) return;

                    tabBtns.forEach(b => b.classList.remove('active-btn'));
                    tabs.forEach(t => t.classList.remove('active-tab'));

                    this.classList.add('active-btn');
                    const targetTab = document.querySelector(target);
                    targetTab.classList.add('active-tab');

                    // Animate tab content entrance
                    gsap.fromTo(targetTab, { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.5, ease: "power2.out" });

                    // Update URL Hash
                    window.location.hash = target;
                    window.scrollTo({ top: 150, behavior: 'smooth' });
                });
            });

            // Handle Hash in URL
            if (window.location.hash) {
                const activeBtn = document.querySelector(`.tab-btn[data-tab="${window.location.hash}"]`);
                if (activeBtn) activeBtn.click();
            }
        });

        function switchTab(target) {
            const btn = document.querySelector(`.tab-btn[data-tab="${target}"]`);
            if (btn) btn.click();
        }

        function toggleOrderDetails(orderId) {
            const el = document.getElementById(orderId);
            const isHidden = el.style.display === 'none';

            // Close all
            document.querySelectorAll('.detail-row').forEach(row => {
                if (row.id !== orderId) row.style.display = 'none';
            });

            // Open current if it was hidden
            if (isHidden) {
                el.style.display = 'table-row';
                gsap.fromTo(el, { opacity: 0, scaleY: 0 }, { opacity: 1, scaleY: 1, duration: 0.4, transformOrigin: "top", ease: "power2.out" });
            } else {
                el.style.display = 'none';
            }
        }

        function confirmLogout() {
            Swal.fire({
                title: 'Terminate Session?',
                text: "Are you ready to disconnect from the portal?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Yes, Logout',
                cancelButtonText: 'Abort',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        function confirmDeleteAddress(id) {
            Swal.fire({
                title: 'Purge Node?',
                text: "This coordinate will be permanently erased from our network.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Confirm Purge',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-addr-' + id).submit();
                }
            });
        }

        function openAddressModal(address = null) {
            const isEdit = address !== null;
            const title = isEdit ? 'Modify Existing Node' : 'Initialize New Node';
            const action = isEdit ? `/my-account/address/${address.id}` : '/my-account/address';

            Swal.fire({
                title: `<span style="font-weight:900; color:#0f172a; font-family: 'Outfit', sans-serif;">${title}</span>`,
                html: `
                        <form id="swalAddressForm" action="${action}" method="POST" class="text-start" style="font-family: 'Outfit', sans-serif;">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label style="font-size:11px; font-weight:900; color:#94a3b8; text-transform:uppercase; letter-spacing:1px">Full Identifier</label>
                                    <input type="text" name="address_username" class="swal2-input m-0 w-100" style="font-size:14px; border-radius:15px; border:2px solid #f1f5f9; padding:15px" value="${isEdit ? address.address_username : ''}" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label style="font-size:11px; font-weight:900; color:#94a3b8; text-transform:uppercase; letter-spacing:1px">Comms Link</label>
                                    <input type="tel" name="address_phone_number" class="swal2-input m-0 w-100" style="font-size:14px; border-radius:15px; border:2px solid #f1f5f9; padding:15px" value="${isEdit ? address.address_phone_number : ''}" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label style="font-size:11px; font-weight:900; color:#94a3b8; text-transform:uppercase; letter-spacing:1px">Node Category</label>
                                    <select name="address_type_id" class="swal2-select m-0 w-100" style="font-size:14px; border-radius:15px; border:2px solid #f1f5f9; height: 50px;">
                                        <option value="1" ${isEdit && address.address_type_id == 1 ? 'selected' : ''}>Home Hub</option>
                                        <option value="2" ${isEdit && address.address_type_id == 2 ? 'selected' : ''}>Work Hub</option>
                                        <option value="3" ${isEdit && address.address_type_id == 3 ? 'selected' : ''}>Neutral Node</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-4">
                                    <label style="font-size:11px; font-weight:900; color:#94a3b8; text-transform:uppercase; letter-spacing:1px">Primary Coordinate (Line 1)</label>
                                    <input type="text" name="address_line_one" class="swal2-input m-0 w-100" style="font-size:14px; border-radius:15px; border:2px solid #f1f5f9; padding:15px" value="${isEdit ? address.address_line_one : ''}" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label style="font-size:11px; font-weight:900; color:#94a3b8; text-transform:uppercase; letter-spacing:1px">City</label>
                                    <input type="text" name="city" class="swal2-input m-0 w-100" style="font-size:14px; border-radius:15px; border:2px solid #f1f5f9; padding:15px" value="${isEdit ? address.city : ''}" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label style="font-size:11px; font-weight:900; color:#94a3b8; text-transform:uppercase; letter-spacing:1px">Pincode</label>
                                    <input type="text" name="pincode" class="swal2-input m-0 w-100" style="font-size:14px; border-radius:15px; border:2px solid #f1f5f9; padding:15px" value="${isEdit ? address.pincode : ''}" required>
                                </div>
                            </div>
                        </form>
                    `,
                showCancelButton: true,
                confirmButtonText: isEdit ? 'Authorize Update' : 'Initialize Node',
                confirmButtonColor: '#bbd700',
                cancelButtonColor: '#cbd5e1',
                width: '700px',
                preConfirm: () => {
                    const form = document.getElementById('swalAddressForm');
                    if (!form.checkValidity()) {
                        Swal.showValidationMessage('Please fill all required coordinates');
                        return false;
                    }
                    form.submit();
                }
            });
        }

        function confirmDeleteAccount() {
            Swal.fire({
                title: 'Terminate Presence?',
                text: "This will permanently purge your identity from our database. All data will be lost.",
                icon: 'warning',
                input: 'text',
                inputPlaceholder: 'Type "DELETE" to confirm',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                confirmButtonText: 'Initiate Deletion',
                preConfirm: (value) => {
                    if (value !== 'DELETE') {
                        Swal.showValidationMessage('Verification failed. Type DELETE.');
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Identity Purged', 'Your account is being scheduled for deletion.', 'success');
                }
            });
        }
    </script>
@endsection