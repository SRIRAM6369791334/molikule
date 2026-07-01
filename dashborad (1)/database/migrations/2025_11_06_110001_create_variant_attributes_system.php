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
        // 1. Create variant_attributes table
        Schema::create('variant_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // "Size", "Color", "Material"
            $table->string('slug', 100)->unique(); // "size", "color", "material"
            $table->enum('input_type', ['text', 'color', 'image', 'dropdown', 'radio', 'swatch'])->default('dropdown');
            $table->text('description')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->boolean('use_in_product_listing')->default(true);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('is_active');
            $table->index('sort_order');
        });

        // 2. Create variant_attribute_values table
        Schema::create('variant_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id');
            $table->string('value', 100); // "Small", "Red", "Cotton"
            $table->string('slug', 100); // "small", "red", "cotton"
            $table->string('display_name', 100)->nullable(); // "Small (S)", "Bright Red"
            $table->string('short_code', 20)->nullable(); // "S", "M", "L", "RED", "BLU"
            $table->string('color_code', 7)->nullable(); // "#FF0000" for colors
            $table->string('image_url', 255)->nullable(); // For image swatches
            $table->decimal('price_modifier', 10, 2)->default(0); // +5.00 or -2.00
            $table->enum('price_modifier_type', ['fixed', 'percentage'])->default('fixed');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('attribute_id')
                  ->references('id')
                  ->on('variant_attributes')
                  ->onDelete('cascade');

            $table->index('attribute_id');
            $table->index('slug');
            $table->index('is_active');
            $table->index('sort_order');
            $table->unique(['attribute_id', 'slug']);
        });

        // 3. Create product_variant_attribute_values pivot table
        Schema::create('product_variant_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedBigInteger('value_id')->nullable();
            $table->string('custom_value', 255)->nullable(); // For custom/one-off values
            $table->timestamps();

            $table->foreign('variant_id')
                  ->references('id')
                  ->on('product_variants')
                  ->onDelete('cascade');

            $table->foreign('attribute_id')
                  ->references('id')
                  ->on('variant_attributes')
                  ->onDelete('cascade');

            $table->foreign('value_id')
                  ->references('id')
                  ->on('variant_attribute_values')
                  ->onDelete('set null');

            $table->index('variant_id');
            $table->index('attribute_id');
            $table->index('value_id');
            $table->unique(['variant_id', 'attribute_id']);
        });

        // 4. Add variant_value field to product_variants (quick win)
        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'variant_value')) {
                $table->string('variant_value', 100)->nullable()->after('variant_unit');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'variant_value')) {
                $table->dropColumn('variant_value');
            }
        });

        Schema::dropIfExists('product_variant_attribute_values');
        Schema::dropIfExists('variant_attribute_values');
        Schema::dropIfExists('variant_attributes');
    }
};

