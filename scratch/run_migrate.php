<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

try {
    echo "Testing DB connection...\n";
    DB::connection()->getPdo();
    echo "Connected successfully to " . DB::connection()->getDatabaseName() . "\n";

    echo "Running migrate:fresh...\n";
    $exit = Artisan::call('migrate:fresh', ['--force' => true]);
    echo Artisan::output();
    echo "Exit code: $exit\n";

    echo "Running db:seed...\n";
    $exitSeed = Artisan::call('db:seed', ['--force' => true]);
    echo Artisan::output();
    echo "Seed Exit code: $exitSeed\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
