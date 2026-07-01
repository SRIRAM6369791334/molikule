<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$adminEmail = 'kokilavani864@gmail.com';

try {
    // 1. Welcome Email
    $user = new User(['name' => 'Test User', 'email' => 'test@example.com']);
    Mail::send('emails.welcome', ['user' => $user], function($m) use ($adminEmail) {
        $m->to($adminEmail)->subject('Test: Welcome Email');
    });
    echo "Welcome sent\n";

    // 2. OTP Email
    Mail::send('emails.otp', ['otp' => '123456'], function($m) use ($adminEmail) {
        $m->to($adminEmail)->subject('Test: OTP Email');
    });
    echo "OTP sent\n";

    // 3. Reset Password
    Mail::send('emails.reset-password', ['resetLink' => url('reset-password/dummy-token')], function($m) use ($adminEmail) {
        $m->to($adminEmail)->subject('Test: Reset Password Email');
    });
    echo "Reset Password sent\n";

    // 4. Contact Form
    $contactData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '9876543210',
        'subject' => 'Product Inquiry',
        'message' => 'I would like to know more about your auto care products.'
    ];
    Mail::send('emails.contact-form', ['data' => $contactData], function($m) use ($adminEmail) {
        $m->to($adminEmail)->subject('Test: Contact Form Email');
    });
    echo "Contact Form sent\n";

    // 5. Nexus Certification
    $nexusEnquiry = new \stdClass();
    $nexusEnquiry->name = 'Jane Smith';
    $nexusEnquiry->contact_no = '1234567890';
    $nexusEnquiry->email = 'jane@company.com';
    $nexusEnquiry->company_name = 'Green Solutions Inc.';
    $nexusEnquiry->segment = 'Facility Management';
    $nexusEnquiry->thoughts = "We are interested in certifying our team.\nPlease send details.";
    Mail::send('emails.nexus-certification-enquiry', ['enquiry' => $nexusEnquiry], function($m) use ($adminEmail) {
        $m->to($adminEmail)->subject('Test: Nexus Certification Email');
    });
    echo "Nexus sent\n";

    // 6. Order Confirmation
    $order = Order::first();
    if (!$order) {
        $order = new Order([
            'order_number' => 'ORD-TEST-123',
            'created_at' => now(),
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'address' => '123 Test St',
            'city' => 'Test City',
            'state' => 'Test State',
            'pincode' => '123456',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'subtotal' => 1000,
            'tax' => 180,
            'shipping_cost' => 50,
            'total_amount' => 1230,
            'order_status' => 'pending'
        ]);
    }
    Mail::send('emails.order-confirmation', ['order' => $order], function($m) use ($adminEmail) {
        $m->to($adminEmail)->subject('Test: Order Confirmation');
    });
    echo "Order Confirmation sent\n";

    // 7. Admin Order Notification
    Mail::send('emails.admin-order-notification', ['order' => $order], function($m) use ($adminEmail) {
        $m->to($adminEmail)->subject('Test: Admin Order Notification');
    });
    echo "Admin Order Notification sent\n";

    echo "\nSUCCESS: All emails sent to $adminEmail!\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
