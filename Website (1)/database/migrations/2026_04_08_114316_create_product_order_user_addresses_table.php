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
        Schema::create('product_order_user_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('guest_user_id')->nullable();
            $table->string('order_id'); // This matches the code-generated order string ID
            $table->string('address_line_one')->nullable();
            $table->string('address_line_two')->nullable();
            $table->string('landmark')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('address_phone_number')->nullable();
            $table->unsignedInteger('address_type_id')->nullable();
            $table->string('address_type_name')->nullable();
            $table->string('address_type_others_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_order_user_addresses');
    }
};
