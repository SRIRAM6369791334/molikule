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
        Schema::create('order_full_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable(); // Foreign key to main orders table ID
            $table->string('order_number')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_phone')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->text('product_description')->nullable();
            $table->text('product_specification')->nullable();
            $table->string('product_image')->nullable();
            $table->string('product_slug')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('category_name')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->string('variant_name')->nullable();
            $table->string('variant_image')->nullable();
            $table->string('variant_slug')->nullable();
            $table->string('variant_value')->nullable();
            $table->string('variant_size_value')->nullable();
            $table->decimal('variant_offer_price', 15, 2)->nullable();
            $table->decimal('variant_mrp_price', 15, 2)->nullable();
            $table->integer('variant_quantity')->nullable();
            $table->integer('order_quantity')->nullable();
            $table->decimal('order_unit_price', 15, 2)->nullable();
            $table->decimal('order_total_price', 15, 2)->nullable();
            $table->decimal('order_subtotal', 15, 2)->nullable();
            $table->decimal('order_gst_amount', 15, 2)->nullable();
            $table->decimal('order_delivery_charge', 15, 2)->nullable();
            $table->decimal('order_discount_amount', 15, 2)->nullable();
            $table->decimal('order_grand_total', 15, 2)->nullable();
            $table->string('order_status')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->json('shipping_address')->nullable();
            $table->json('billing_address')->nullable();
            $table->timestamp('order_created_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_full_details');
    }
};
