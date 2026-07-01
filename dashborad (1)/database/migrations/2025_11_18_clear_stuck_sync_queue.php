<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear old pending records from real_time_sync_queue (older than 7 days)
        // These are likely stuck and not being processed
        DB::table('real_time_sync_queue')
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subDays(7))
            ->delete();

        // Also mark any processing records as failed if they're older than 1 day
        // (indicates a crashed/stuck process)
        DB::table('real_time_sync_queue')
            ->where('status', 'processing')
            ->where('processed_at', '<', now()->subDay())
            ->update([
                'status' => 'failed',
                'error_message' => 'Auto-marked as failed - process timeout after 24 hours',
                'updated_at' => now()
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a cleanup-only migration, no need to reverse
        // All deleted records are old and stale
    }
};
