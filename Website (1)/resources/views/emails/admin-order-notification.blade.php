<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order - #{{ $order->order_number }}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f4f7f6; color: #334155; }
        
        .email-container { max-width: 600px; margin: 0 auto; width: 100%; }
        .email-card { background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); margin-top: 30px; margin-bottom: 30px; }
        
        /* Hero Banner Style */
        .header { background-color: #1368B4; padding: 40px 30px; text-align: center; }
        .logo-wrapper { background: #ffffff; padding: 15px 25px; border-radius: 8px; display: inline-block; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header-text { color: #ffffff; font-size: 24px; font-weight: 700; margin-top: 20px; margin-bottom: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        
        .content { padding: 30px 40px 40px; }
        
        .alert { background-color: #eaf4fa; border-left: 4px solid #bbd700; padding: 15px 20px; border-radius: 0 6px 6px 0; margin-bottom: 25px; color: #1368B4; font-weight: 500; font-size: 14px; }
        
        .section-title { font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 10px; margin-top: 25px; }
        
        /* Customer Details Table */
        .details-table { width: 100%; font-size: 14px; border-collapse: collapse; margin-bottom: 20px; }
        .details-table th { width: 35%; text-align: left; padding: 10px 0; color: #64748b; font-weight: 500; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .details-table td { padding: 10px 0; color: #0f172a; font-weight: 500; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .icon { display: inline-block; width: 20px; text-align: center; margin-right: 5px; font-size: 16px; }
        
        /* Items Table */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 14px; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; }
        .items-table th { background-color: #f8fafc; padding: 12px 10px; text-align: left; color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 600; border-bottom: 1px solid #e2e8f0; }
        .items-table td { padding: 15px 10px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: top; }
        
        .product-image { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #e2e8f0; }
        .product-title { font-weight: 600; color: #0f172a; font-size: 14px; margin-bottom: 2px; }
        .product-variant { font-size: 11px; color: #64748b; }
        
        .total-row td { background-color: #f8fafc; font-weight: 600; color: #1368B4; font-size: 15px; border-top: 2px solid #e2e8f0; border-bottom: none; }
        
        /* Button */
        .btn-wrapper { text-align: center; margin: 40px 0 10px; }
        .btn { display: inline-block; background-color: #1368B4; color: #ffffff !important; font-weight: 600; text-decoration: none; padding: 14px 28px; border-radius: 6px; font-size: 14px; transition: background-color 0.3s; }
        
        /* Footer */
        .footer { padding: 30px; text-align: center; background-color: #f1f5f9; font-size: 13px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body style="background-color: #f4f7f6; margin: 0 !important; padding: 0 !important;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f7f6; padding: 20px;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container">
                    <tr>
                        <td align="center">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="email-card">
                                <!-- Hero Banner -->
                                <tr>
                                    <td align="center" class="header">
                                        <div class="logo-wrapper">
                                            <a href="https://molikule.com" target="_blank">
                                                <img src="{{ asset('assets/images/logo1.png') }}" alt="Molikule Green Care" width="150" style="display: block; max-width: 150px;">
                                            </a>
                                        </div>
                                        <h1 class="header-text">New Order Alert</h1>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="content">
                                        <div class="alert">
                                            Order <strong>#{{ $order->order_number }}</strong> has been placed on Molikule.
                                        </div>

                                        <div class="section-title">Customer Details</div>
                                        <table class="details-table" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <th><span class="icon">👤</span> Name:</th>
                                                <td>{{ $order->customer_name }}</td>
                                            </tr>
                                            <tr>
                                                <th><span class="icon">📧</span> Email:</th>
                                                <td><a href="mailto:{{ $order->customer_email }}" style="color: #1368B4; text-decoration: none;">{{ $order->customer_email }}</a></td>
                                            </tr>
                                            <tr>
                                                <th><span class="icon">📞</span> Phone:</th>
                                                <td>{{ $order->customer_phone }}</td>
                                            </tr>
                                            @if(isset($order->shipping_address))
                                            <tr>
                                                <th><span class="icon">🏢</span> Address:</th>
                                                <td><span style="font-weight: normal; color: #475569;">{{ $order->shipping_address }}</span></td>
                                            </tr>
                                            @endif
                                        </table>

                                        <div class="section-title">Order Items</div>
                                        <table class="items-table" cellspacing="0" cellpadding="0">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Product</th>
                                                    <th style="text-align: center;">Qty</th>
                                                    <th style="text-align: right;">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->items as $item)
                                                <tr>
                                                    <td width="60" style="padding-right: 0;">
                                                        @php
                                                            $imgUrl = asset('assets/images/default-product.png');
                                                            if($item->itemable && isset($item->itemable->image) && $item->itemable->image) {
                                                                $imgUrl = asset('storage/' . $item->itemable->image);
                                                            } elseif($item->itemable && isset($item->itemable->product) && isset($item->itemable->product->image) && $item->itemable->product->image) {
                                                                $imgUrl = asset('storage/' . $item->itemable->product->image);
                                                            }
                                                        @endphp
                                                        <img src="{{ $imgUrl }}" class="product-image" alt="Product">
                                                    </td>
                                                    <td>
                                                        <div class="product-title">{{ $item->item_name }}</div>
                                                        @if(is_array($item->item_options) && count($item->item_options) > 0)
                                                            @foreach($item->item_options as $key => $val)
                                                                <div class="product-variant">{{ ucfirst($key) }}: {{ $val }}</div>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center; vertical-align: middle;">{{ $item->quantity }}</td>
                                                    <td style="text-align: right; vertical-align: middle;">{{ function_exists('formatPrice') ? formatPrice($item->price * $item->quantity) : '$'.number_format($item->price * $item->quantity, 2) }}</td>
                                                </tr>
                                                @endforeach
                                                <tr class="total-row">
                                                    <td colspan="3" style="text-align: right; padding: 12px 10px;">Order Total</td>
                                                    <td style="text-align: right; padding: 12px 10px;">{{ function_exists('formatPrice') ? formatPrice($order->total_amount) : '$'.number_format($order->total_amount, 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="section-title">Payment Info</div>
                                        <table class="details-table" cellpadding="0" cellspacing="0" style="margin-bottom: 0;">
                                            <tr>
                                                <th><span class="icon">💳</span> Method:</th>
                                                <td>{{ strtoupper($order->payment_method) }}</td>
                                            </tr>
                                            @if(isset($order->payment_status))
                                            <tr>
                                                <th><span class="icon">✅</span> Status:</th>
                                                <td><span style="background: #f1f5f9; padding: 3px 8px; border-radius: 4px; font-weight: 600; color: #475569; font-size: 12px;">{{ ucfirst($order->payment_status) }}</span></td>
                                            </tr>
                                            @endif
                                        </table>

                                        <div class="btn-wrapper">
                                            <a href="{{ env('MAIN_URL', url('/')) }}/admin/orders/{{ $order->id }}" class="btn">View Order in Admin Panel</a>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="footer">
                                        <p style="margin: 0 0 5px;">This is an automated notification, please do not reply.</p>
                                        <p style="margin: 0;">&copy; {{ date('Y') }} Molikule Green Care. All rights reserved.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
