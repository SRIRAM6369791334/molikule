<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Delivered - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #334155; margin: 0; padding: 0; background-color: #f8fafc; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(15,23,42,0.05); border: 1px solid #e2e8f0; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 40px 30px; text-align: center; color: white; border-bottom: 5px solid #bbd700; }
        .header h1 { margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { margin: 10px 0 0; color: #ffffffaa; font-size: 16px; }
        .content { padding: 40px 30px; }
        .footer { background-color: #f1f5f9; padding: 30px; text-align: center; font-size: 13px; color: #64748b; border-top: 1px solid #e2e8f0; }
        .order-info { background: #f8fafc; padding: 25px; border-radius: 12px; margin-bottom: 30px; border: 1px solid #e2e8f0; }
        .item-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .item-table th { text-align: left; border-bottom: 2px solid #e2e8f0; padding: 12px 10px; color: #0f172a; font-weight: 700; }
        .item-table td { padding: 15px 10px; border-bottom: 1px solid #e2e8f0; color: #475569; }
        .total-row { font-weight: 800; font-size: 18px; color: #0f172a; }
        .status-badge { display: inline-block; padding: 6px 16px; background: #bbd700; color: #0f172a; border-radius: 50px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .btn { display: inline-block; padding: 14px 30px; background: #0f172a; color: #ffffff !important; text-decoration: none; border-radius: 50px; font-weight: 700; margin-top: 20px; box-shadow: 0 4px 6px rgba(15,23,42,0.2); }
        .logo-text { font-size: 22px; font-weight: 900; color: #bbd700; margin-bottom: 15px; display: block; text-transform: uppercase; letter-spacing: 2px; }
        .delivery-icon { font-size: 48px; margin-bottom: 10px; display: block; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <span class="logo-text">Molikule Green Care</span>
            <span class="delivery-icon">✅</span>
            <h1>Order Delivered!</h1>
            <p>Your package has arrived</p>
        </div>
        <div class="content">
            <p>Hello {{ $order->customer_name }},</p>
            <p>Fantastic news! Your order <strong>#{{ $order->order_number }}</strong> has been <strong>successfully delivered</strong> to your specified address.</p>
            
            <div class="order-info">
                <strong>Delivery Details:</strong><br>
                Status: <span class="status-badge">DELIVERED</span><br>
                Delivery Date: {{ $order->delivery_date ? $order->delivery_date->format('M j, Y \a\t g:i A') : date('M j, Y') }}<br>
                Address: {{ $order->shipping_address }}
            </div>

            <table class="item-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($formattedItems as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>₹{{ number_format($item['total'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="2" style="text-align: right; padding-top: 20px;">Total Amount:</td>
                        <td style="padding-top: 20px;">₹{{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <div style="text-align: center; margin-top: 40px;">
                <p>We hope you're satisfied with our service! Your feedback helps us improve.</p>
                <a href="{{ url('/feedback?order=' . $order->id) }}" class="btn">⭐ Leave Feedback</a>
            </div>

            <p style="margin-top: 40px;">Thank you for choosing Molikule Green Care. We look forward to serving you again!</p>
            <p>Best regards,<br>The Molikule Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Molikule Green Care. All rights reserved.<br>
            <a href="{{ url('/') }}" style="color: #64748b; text-decoration: none;">Visit our website</a>
        </div>
    </div>
</body>
</html>