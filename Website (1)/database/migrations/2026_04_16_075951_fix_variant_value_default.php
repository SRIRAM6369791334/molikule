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
        // Fix 1: variant_value is NOT NULL with no default.
        // Our new Flavour/Volume form does not submit variant_value,
        // so inserts would fail. Give it a default empty string.
        \DB::statement("ALTER TABLE product_variants MODIFY COLUMN variant_value VARCHAR(255) NOT NULL DEFAULT ''");

        // Fix 2: value column stores the Flavour (free text).
        // Old/existing variants may have no value, so make it nullable
        // to avoid constraint errors on old records.
        \DB::statement("ALTER TABLE product_variants MODIFY COLUMN value VARCHAR(255) NULL DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert variant_value back to no default (NOT NULL, no default)
        \DB::statement("ALTER TABLE product_variants MODIFY COLUMN variant_value VARCHAR(255) NOT NULL");

        // Revert value back to NOT NULL
        \DB::statement("ALTER TABLE product_variants MODIFY COLUMN value VARCHAR(255) NOT NULL");
    }
};
