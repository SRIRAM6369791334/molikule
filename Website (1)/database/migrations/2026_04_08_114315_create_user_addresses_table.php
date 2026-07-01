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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address_username')->nullable();
            $table->string('address_first_name')->nullable();
            $table->string('address_last_name')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('guest_user_id')->nullable(); // Can be IP or UUID
            $table->string('address_line_one')->nullable();
            $table->string('address_line_two')->nullable();
            $table->string('landmark')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->string('area_name')->nullable();
            $table->string('city')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('pincode')->nullable();
            $table->unsignedBigInteger('pincode_id')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('address_phone_number')->nullable();
            $table->unsignedInteger('address_type_id')->default(1);
            $table->string('address_type_name')->default('Home');
            $table->string('address_type_others_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
