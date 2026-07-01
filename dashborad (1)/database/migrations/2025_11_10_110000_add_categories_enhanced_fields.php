<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Categories table - add any missing enhanced fields
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'seo_title')) {
                $table->string('seo_title')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('categories', 'seo_description')) {
                $table->text('seo_description')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('categories', 'parent_name')) {
                $table->string('parent_name')->nullable()->after('parent_id');
            }
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'parent_name']);
        });
    }
};