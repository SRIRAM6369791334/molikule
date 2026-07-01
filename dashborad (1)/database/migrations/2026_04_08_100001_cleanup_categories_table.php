<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Remove unwanted columns
            $toDrop = [
                'og_type', 'index_status', 'change_frequency',
                'product_count', 'view_count',
                'description', 'category_content', 'category_sidebar_content',
                'short_description', 'content',
                'meta_title', 'meta_description', 'meta_keywords',
            ];
            foreach ($toDrop as $col) {
                if (Schema::hasColumn('categories', $col)) {
                    $table->dropColumn($col);
                }
            }

            // Rename image_url -> image if needed
            if (Schema::hasColumn('categories', 'image_url') && !Schema::hasColumn('categories', 'image')) {
                $table->renameColumn('image_url', 'image');
            }
        });
    }

    public function down(): void
    {
        // Not reversible (data already dropped)
    }
};
