<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $toDrop = [
                'brand_type', 'is_verified', 'is_premium',
                'product_count', 'view_count', 'follower_count',
                'rating_average', 'rating_count',
                'og_type', 'index_status', 'change_frequency',
            ];
            foreach ($toDrop as $col) {
                if (Schema::hasColumn('brands', $col)) {
                    $table->dropColumn($col);
                }
            }

            // Rename logo_url -> logo if needed
            if (Schema::hasColumn('brands', 'logo_url') && !Schema::hasColumn('brands', 'logo')) {
                $table->renameColumn('logo_url', 'logo');
            }
        });
    }

    public function down(): void {}
};
