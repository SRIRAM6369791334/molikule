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
        Schema::dropIfExists('product_records');
        
        Schema::create('product_records', function (Blueprint $table) {
            $table->id();
            
            // Searchable Display Columns
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->string('category_name');
            $table->string('brand_name');
            
            // Comprehensive Snapshot Data (All values from all tables)
            $table->json('product_full_data');
            $table->json('category_full_data');
            $table->json('brand_full_data');
            $table->json('variants_full_data');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_records');
    }
};
