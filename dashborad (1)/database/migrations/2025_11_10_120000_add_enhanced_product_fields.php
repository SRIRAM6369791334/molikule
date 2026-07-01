<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Products table - add additional enhanced fields with existence checks
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'part_number')) {
                $table->string('part_number')->nullable()->after('sku');
            }
            if (!Schema::hasColumn('products', 'made_in')) {
                $table->string('made_in')->nullable()->after('part_number');
            }
            if (!Schema::hasColumn('products', 'warranty')) {
                $table->string('warranty')->nullable()->after('condition');
            }
            if (!Schema::hasColumn('products', 'dimension')) {
                $table->text('dimension')->nullable()->after('height');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['part_number', 'made_in', 'warranty', 'dimension']);
        });
    }
};