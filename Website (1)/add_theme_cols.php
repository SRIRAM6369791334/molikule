<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::table('categories', function (Blueprint $table) {
    if (!Schema::hasColumn('categories', 'theme_primary_color')) {
        $table->string('theme_primary_color')->nullable();
        $table->string('theme_light_color')->nullable();
        $table->string('theme_bg_image')->nullable();
        $table->string('theme_bg_overlay')->nullable();
        $table->string('theme_border_radius')->nullable();
    }
});
echo "Columns added successfully!\n";
