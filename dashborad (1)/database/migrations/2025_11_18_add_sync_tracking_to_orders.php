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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'sync_failed')) {
                $table->boolean('sync_failed')->default(false)->after('version');
            }
            if (!Schema::hasColumn('orders', 'last_sync_error')) {
                $table->text('last_sync_error')->nullable()->after('sync_failed');
            }
            if (!Schema::hasColumn('orders', 'last_sync_error_at')) {
                $table->timestamp('last_sync_error_at')->nullable()->after('last_sync_error');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'sync_failed')) {
                $table->dropColumn('sync_failed');
            }
            if (Schema::hasColumn('orders', 'last_sync_error')) {
                $table->dropColumn('last_sync_error');
            }
            if (Schema::hasColumn('orders', 'last_sync_error_at')) {
                $table->dropColumn('last_sync_error_at');
            }
        });
    }
};
