<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - {{ $order->order_number }}</title>
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
        
        /* Product Table */
        .item-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        .item-table th { text-align: left; border-bottom: 2px solid #e2e8f0; padding: 12px 10px; color: #64748b; text-transform: uppercase; font-size: 12px; font-weight: 600; }
        .item-table td { padding: 15px 10px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: top; }
        .product-image { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; background: #f8fafc; }
        .product-title { font-weight: 600; color: #0f172a; font-size: 15px; margin-bottom: 4px; }
        .product-variant { font-size: 12px; color: #64748b; margin-bottom: 2px; }
        
        /* Order Info Box */
        .order-info { background-color: #f8fafc; border-left: 4px solid #bbd700; padding: 15px 20px; border-radius: 0 8px 8px 0; margin-bottom: 25px; font-size: 14px; }
        
        /* Totals */
        .total-row { font-weight: 700; font-size: 16px; color: #1368B4; }
        
        /* Button */
        .btn-wrapper { text-align: center; margin: 35px 0 25px; }
        .btn { display: inline-block; background-color: #bbd700; color: #1e3a34 !important; font-weight: 700; text-decoration: none; padding: 15px 35px; border-radius: 8px; font-size: 16px; box-shadow: 0 4px 6px rgba(187, 215, 0, 0.2); }
        
        /* Footer with Socials and Support */
        .footer { padding: 30px; text-align: center; background-color: #f1f5f9; font-size: 13px; color: #64748b; border-top: 1px solid #e2e8f0; }
        .support-info { font-weight: 600; color: #0f172a; margin-bottom: 15px; font-size: 14px; }
        .social-icons { margin: 15px 0; }
        .social-icons a { display: inline-block; margin: 0 10px; color: #1368B4; text-decoration: none; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
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
                                <!-- Hero Section -->
                                <tr>
                                    <td align="center" class="header">
                                        <div class="logo-wrapper">
                                            <a href="{{ url('/') }}" target="_blank">
                                                <img src="{{ asset('assets/images/logo1.png') }}" alt="Molikule Green Care" width="150" style="display: block; max-width: 150px;">
                                            </a>
                                        </div>
                                        <h1 class="header-text">Thank You For Your Order!</h1>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="content">
                                        <p style="font-size: 16px; margin-bottom: 25px;">Hello <strong>{{ $order->customer_name }}</strong>,</p>
                                        <p style="font-size: 15px; margin-bottom: 25px;">We've received your order and we're getting it ready for shipment. Here are your order details:</p>
                                        
                                        <div class="order-info">
                                            <table width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td width="50%" style="padding-bottom: 8px;"><strong style="color: #0f172a;">Order #:</strong> {{ $order->order_number }}</td>
                                                    <td width="50%" style="padding-bottom: 8px;"><strong style="color: #0f172a;">Date:</strong> {{ $order->created_at->format('M d, Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong style="color: #0f172a;">Payment:</strong> {{ strtoupper($order->payment_method) }}</td>
                                                    <td width="50%"><strong style="color: #0f172a;">Status:</strong> {{ strtoupper($order->status) }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <!-- Items Table with Images and Attributes -->
                                        <table class="item-table" cellpadding="0" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Product</th>
                                                    <th style="text-align: center;">Qty</th>
                                                    <th style="text-align: right;">Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->items as $item)
                                                <tr>
                                                    <td width="70" style="padding-right: 0;">
                                                        @php
                                                            $imgUrl = asset('assets/images/default-product.png');
                                                            if($item->itemable && isset($item->itemable->image) && $item->itemable->image) {
                                                                $imgUrl = asset('storage/' . $item->itemable->image);
                                                            } elseif($item->itemable && isset($item->itemable->product) && isset($item->itemable->product->image) && $item->itemable->product->image) {
                                                                $imgUrl = asset('storage/' . $item->itemable->product->image);
                                                            }
                                                        @endphp
                                                        <img src="{{ $imgUrl }}" class="product-image" alt="Product Image">
                                                    </td>
                                                    <td>
                                                        <div class="product-title">{{ $item->item_name }}</div>
                                                        @if(is_array($item->item_options) && count($item->item_options) > 0)
                                                            @foreach($item->item_options as $key => $val)
                                                                <div class="product-variant">{{ ucfirst($key) }}: {{ $val }}</div>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center; vertical-align: middle; font-weight: 600;">{{ $item->quantity }}</td>
                                                    <td style="text-align: right; vertical-align: middle;">{{ formatPrice($item->total_price) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" style="text-align: right; padding-top: 20px; color: #64748b;">Subtotal:</td>
                                                    <td style="text-align: right; padding-top: 20px;">{{ formatPrice($order->total_amount - $order->shipping_cost + $order->coupon_discount) }}</td>
                                                </tr>
                                                @if($order->coupon_discount > 0)
                                                <tr>
                                                    <td colspan="3" style="text-align: right; color: #1368B4;">Discount:</td>
                                                    <td style="text-align: right; color: #1368B4;">-{{ formatPrice($order->coupon_discount) }}</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td colspan="3" style="text-align: right; color: #64748b;">Shipping:</td>
                                                    <td style="text-align: right;">{{ formatPrice($order->shipping_cost) }}</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <td colspan="3" style="text-align: right; padding-top: 15px;">Grand Total:</td>
                                                    <td style="text-align: right; padding-top: 15px; color: #1368B4;">{{ formatPrice($order->total_amount) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        
                                        <!-- Tracking Button -->
                                        <div class="btn-wrapper">
                                            <a href="{{ url('/account/orders/' . $order->id) }}" class="btn">Track Your Order</a>
                                        </div>

                                        <div style="font-weight: 600; color: #0f172a; margin-top: 30px; margin-bottom: 10px; font-size: 15px;">Shipping Address:</div>
                                        <div style="color: #475569; font-size: 14px; line-height: 1.6; background: #f8fafc; padding: 15px; border-radius: 6px;">
                                            {{ $order->shipping_address }}
                                        </div>

                                    </td>
                                </tr>
                                
                                <!-- Footer Section -->
                                <tr>
                                    <td class="footer">
                                        <div class="support-info">
                                            Need help? Call Support: <a href="tel:+918220000000" style="color: #1368B4;">+91 822 000 0000</a><br>
                                            <span style="font-size: 12px; font-weight: normal; color: #94a3b8;">(Mon-Sat, 9 AM to 6 PM)</span>
                                        </div>
                                        
                                        <div class="social-icons">
                                            <a href="https://facebook.com/molikule">Facebook</a> &bull; 
                                            <a href="https://instagram.com/molikule">Instagram</a> &bull; 
                                            <a href="https://linkedin.com/company/molikule">LinkedIn</a>
                                        </div>
                                        
                                        <p style="margin: 0; margin-top: 20px;">&copy; {{ date('Y') }} Molikule Green Care. All rights reserved.</p>
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
