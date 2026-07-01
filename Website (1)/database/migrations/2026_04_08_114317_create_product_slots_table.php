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
        Schema::create('product_slots', function (Blueprint $table) {
            $table->id();
            $table->date('delivery_date')->nullable();
            $table->string('order_id'); // String order number lookup
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('product_varient_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('order_name')->nullable();
            $table->string('product_image')->nullable();
            $table->decimal('product_rate', 15, 2)->default(0);
            $table->decimal('gst_amt', 15, 2)->default(0);
            $table->decimal('gst_per', 5, 2)->default(0);
            $table->decimal('product_value', 15, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->decimal('product_total', 15, 2)->default(0);
            $table->decimal('shipping', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->string('size_value')->nullable();
            $table->string('color_value')->nullable();
            $table->integer('delivery_status')->default(0);
            $table->integer('preorder')->default(0);
            $table->date('dispatch_date')->nullable();
            $table->timestamp('order_delivered_time')->nullable();
            $table->unsignedBigInteger('deliver_person_id')->nullable();
            $table->integer('is_cancelled')->default(0);
            $table->text('cancel_reason')->nullable();
            $table->integer('approve_staus')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_slots');
    }
};
