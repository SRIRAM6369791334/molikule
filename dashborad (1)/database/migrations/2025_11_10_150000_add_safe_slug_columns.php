<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            if (!Schema::hasColumn('brands', 'slug')) {
                $table->string('slug', 255)->unique()->nullable()->after('brand_name');
            }
        });
        $this->addIndexIfNotExists('brands', ['slug'], 'brands_slug_index');

        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'slug')) {
                $table->string('slug', 255)->unique()->nullable()->after('category_name');
            }
        });
        $this->addIndexIfNotExists('categories', ['slug'], 'categories_slug_index');

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug', 255)->unique()->nullable()->after('name');
            }
        });
        $this->addIndexIfNotExists('products', ['slug'], 'products_slug_index');
    }

    private function addIndexIfNotExists(string $table, $columns, string $indexName = null): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName) {
                if ($indexName) {
                    $blueprint->index($columns, $indexName);
                } else {
                    $blueprint->index($columns);
                }
            });
        } catch (\Exception $e) {
            if (!str_contains($e->getMessage(), 'Duplicate key name') && !str_contains($e->getMessage(), 'already exists')) {
            }
        }
    }

    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            if (Schema::hasColumn('brands', 'slug')) {
                $table->dropIndex(['slug']);
                $table->dropColumn('slug');
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'slug')) {
                $table->dropIndex(['slug']);
                $table->dropColumn('slug');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'slug')) {
                $table->dropIndex(['slug']);
                $table->dropColumn('slug');
            }
        });
    }
};