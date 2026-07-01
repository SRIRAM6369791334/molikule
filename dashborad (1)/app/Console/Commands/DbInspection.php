<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DbInspection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:db-inspection {--save : Save output to file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inspect database schema for cleanup analysis';

    /**
     * Analyze actual data usage in columns to identify empty/unused fields
     */
    private function analyzeColumnDataUsage($tableName, $columns)
    {
        $this->info("📊 Data Usage Analysis:");

        // Sample a subset of rows for analysis (limit to prevent long queries on large tables)
        $totalRows = DB::table($tableName)->count();
        $sampleSize = min(1000, $totalRows); // Sample up to 1000 rows for analysis

        if ($sampleSize === 0) {
            $this->line("  No rows to analyze");
            return;
        }

        // Get sample data
        $sampleQuery = DB::table($tableName)->limit($sampleSize);
        $sampleData = $sampleQuery->get();

        $usageStats = [];
        $nonEmptyColumns = [];

        foreach ($columns as $col) {
            $colName = $col->COLUMN_NAME;
            $nonNull = 0;
            $nonEmpty = 0;
            $isDefault = 0;

            foreach ($sampleData as $row) {
                $value = $row->$colName;

                // Count non-null values
                if ($value !== null) {
                    $nonNull++;

                    // Count non-empty values (not null, empty string, or default)
                    if (!is_string($value) || trim($value) !== '') {
                        $nonEmpty++;
                    }

                    // Check if value equals default
                    if ($col->COLUMN_DEFAULT !== null && $value == $col->COLUMN_DEFAULT) {
                        $isDefault++;
                    }
                } else {
                    // NULL values that might be using default implicit behavior
                    if ($col->COLUMN_DEFAULT === null) {
                        // NULL but no default - likely unused
                    }
                }
            }

            $usagePercentage = $totalRows > 0 ? round(($nonEmpty / $totalRows) * 100, 1) : 0;
            $nullRate = $totalRows > 0 ? round((($totalRows - $nonNull) / $totalRows) * 100, 1) : 0;

            $usageStats[$colName] = [
                'usage_pct' => $usagePercentage,
                'null_pct' => $nullRate,
                'used' => $nonNull,
                'empty' => $totalRows - $nonNull,
                'is_default' => $isDefault,
                'sample_size' => $sampleSize
            ];

            // Track columns with actual usage
            if ($usagePercentage > 0) {
                $nonEmptyColumns[] = $colName;
            }
        }

        // Display summary first
        $emptyColumns = array_filter($usageStats, function($stat) {
            return $stat['usage_pct'] == 0;
        });
        $lowUsageColumns = array_filter($usageStats, function($stat) {
            return $stat['usage_pct'] > 0 && $stat['usage_pct'] < 10;
        });
        $highUsageColumns = array_filter($usageStats, function($stat) {
            return $stat['usage_pct'] >= 80;
        });

        $this->line("  Empty columns (0% usage): " . count($emptyColumns));
        $this->line("  Low usage columns (<10%): " . count($lowUsageColumns));
        $this->line("  Well-used columns (≥80%): " . count($highUsageColumns));
        $this->line("  Columns with some data: " . count($nonEmptyColumns) . " of " . count($columns));

        // Show problematic columns
        if (!empty($emptyColumns)) {
            $this->line("  ⚠️  ALWAYS EMPTY: " . implode(', ', array_keys($emptyColumns)));
        }

        if (!empty($lowUsageColumns)) {
            $this->line("  ⚡ LOW USAGE (<10%): " . implode(', ', array_keys($lowUsageColumns)));
        }

        // If requested, show detailed breakdown (only if --save flag or small tables)
        if ($totalRows <= 500 || $this->option('save')) {
            $this->line("  📋 Detailed Usage:");
            foreach ($usageStats as $colName => $stat) {
                $usageBar = $this->createUsageBar($stat['usage_pct']);
                $nullBar = $this->createUsageBar($stat['null_pct'], 'red');

                $this->line(sprintf("    %-20s: %s%5.1f%% used | %s%5.1f%% null | %s",
                    $colName,
                    $usageBar,
                    $stat['usage_pct'],
                    $nullBar,
                    $stat['null_pct'],
                    $stat['usage_pct'] == 0 ? '❌ UNUSED' : ($stat['usage_pct'] < 10 ? '⚠️ LOW' : '✅ OK')
                ));
            }
        }

        $this->line('');
    }

    /**
     * Create a visual usage bar
     */
    private function createUsageBar($percentage, $color = 'green')
    {
        $bars = 10;
        $filled = round(($percentage / 100) * $bars);
        $empty = $bars - $filled;

        $filledChar = $color === 'red' ? '🔴' : '🟢';
        $emptyChar = '⚪';

        return str_repeat($filledChar, $filled) . str_repeat($emptyChar, $empty);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== DATABASE SCHEMA & DATA USAGE INSPECTION ===');
        $this->line('Analyzing schema and data usage patterns...');
        $this->line('');

        try {
            // Get all tables
            $tables = DB::select("
                SELECT
                    TABLE_NAME,
                    TABLE_ROWS,
                    AVG_ROW_LENGTH,
                    DATA_LENGTH,
                    INDEX_LENGTH,
                    CREATE_TIME,
                    UPDATE_TIME,
                    TABLE_COMMENT
                FROM
                    information_schema.TABLES
                WHERE
                    TABLE_SCHEMA = DATABASE()
                ORDER BY
                    TABLE_NAME
            ");

            $this->info('TOTAL TABLES: ' . count($tables));
            $this->line('');

            foreach ($tables as $table) {
                $tableName = $table->TABLE_NAME;

                $this->info("▼ TABLE: {$tableName}");
                $this->line(str_repeat("-", 50));

                // Table info
                $dataSize = $table->DATA_LENGTH / 1024;
                $this->line("Rows: {$table->TABLE_ROWS} | Data Size: " . number_format($dataSize, 2) . " KB");
                if ($table->TABLE_COMMENT) {
                    $this->line("Comment: {$table->TABLE_COMMENT}");
                }
                $this->line('');

                // Get columns
                $columns = DB::select("
                    SELECT
                        COLUMN_NAME,
                        COLUMN_TYPE,
                        IS_NULLABLE,
                        COLUMN_DEFAULT,
                        COLUMN_KEY,
                        EXTRA,
                        COLUMN_COMMENT
                    FROM
                        information_schema.COLUMNS
                    WHERE
                        TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?
                    ORDER BY
                        ORDINAL_POSITION
                ", [$tableName]);

                $this->info("Columns (" . count($columns) . "):");
                foreach ($columns as $col) {
                    $nullable = $col->IS_NULLABLE === 'YES' ? 'NULL' : 'NOT NULL';
                    $default = $col->COLUMN_DEFAULT !== null ? " DEFAULT '{$col->COLUMN_DEFAULT}'" : '';
                    $key = $col->COLUMN_KEY ? " [{$col->COLUMN_KEY}]" : '';
                    $extra = $col->EXTRA ? " {$col->EXTRA}" : '';

                    $this->line("  - {$col->COLUMN_NAME} {$col->COLUMN_TYPE} {$nullable}{$default}{$key}{$extra}");
                    if ($col->COLUMN_COMMENT) {
                        $this->line("    Comment: {$col->COLUMN_COMMENT}");
                    }
                }

                $this->line('');

                // Get indexes
                $indexes = DB::select("
                    SELECT
                        INDEX_NAME,
                        COLUMN_NAME,
                        NON_UNIQUE,
                        SEQ_IN_INDEX
                    FROM
                        information_schema.STATISTICS
                    WHERE
                        TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?
                    ORDER BY
                        INDEX_NAME, SEQ_IN_INDEX
                ", [$tableName]);

                if ($indexes) {
                    $this->info("Indexes:");
                    $indexGroups = [];
                    foreach ($indexes as $idx) {
                        $indexGroups[$idx->INDEX_NAME][] = $idx->COLUMN_NAME;
                    }

                    foreach ($indexGroups as $name => $cols) {
                        // Check if unique
                        $uniqueIndexes = array_filter($indexes, fn($i) => $i->INDEX_NAME === $name && $i->NON_UNIQUE == 0);
                        $unique = count($uniqueIndexes) > 0 ? 'UNIQUE' : '';
                        $this->line("  - {$unique} {$name}: (" . implode(', ', $cols) . ")");
                    }
                }

                $this->line('');

                // DATA USAGE ANALYSIS - Show column usage statistics
                if ($table->TABLE_ROWS > 0) {
                    $this->analyzeColumnDataUsage($tableName, $columns);
                }

                $this->line(str_repeat("=", 60));
                $this->line('');
            }

            // Summary of prioritized tables for cleanup analysis
            $prioritizedTables = ['products', 'product_variants', 'product_variant_attributes', 'banners', 'orders', 'order_items', 'brands', 'categories'];
            $this->info('=== PRIORITY TABLES FOR CLEANUP ANALYSIS ===');
            foreach ($prioritizedTables as $priority) {
                $found = false;
                foreach ($tables as $table) {
                    if (strpos($table->TABLE_NAME, $priority) !== false || $table->TABLE_NAME === $priority) {
                        $this->line("- {$table->TABLE_NAME}");
                        $found = true;
                    }
                }
                if (!$found) {
                    $this->line("- {$priority} (not found in DB)");
                }
            }

            $this->info('Database inspection completed successfully!');

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
}
}