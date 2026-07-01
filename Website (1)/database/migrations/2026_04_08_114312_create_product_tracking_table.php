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
        Schema::create('product_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('order_id'); // String order number lookup
            $table->string('delivery_status')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('channel_id')->nullable();
            $table->string('shiprocket_order_id')->nullable();
            $table->string('shiprocket_shipment_id')->nullable();
            $table->string('awb_code')->nullable();
            $table->text('tracking_url')->nullable();
            $table->string('delivered_date')->nullable();
            $table->integer('return_requested')->default(0);
            $table->string('return_approval_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tracking');
    }
};
