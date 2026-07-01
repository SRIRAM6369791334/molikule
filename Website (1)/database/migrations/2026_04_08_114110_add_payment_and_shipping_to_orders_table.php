<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('razorpay_order_id')->nullable()->after('payment_status');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');
            
            $table->string('shiprocket_order_id')->nullable()->after('razorpay_signature');
            $table->string('shiprocket_shipping_id')->nullable()->after('shiprocket_order_id');
            $table->string('awb_code')->nullable()->after('shiprocket_shipping_id');
            $table->string('courier_name')->nullable()->after('awb_code');
            $table->text('label_url')->nullable()->after('courier_name');
            $table->text('invoice_url')->nullable()->after('label_url');
            
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('total_amount');
            $table->text('order_notes')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'razorpay_order_id', 
                'razorpay_payment_id', 
                'razorpay_signature',
                'shiprocket_order_id', 
                'shiprocket_shipping_id', 
                'awb_code', 
                'courier_name',
                'label_url', 
                'invoice_url',
                'shipping_cost',
                'order_notes'
            ]);
        });
    }
};
