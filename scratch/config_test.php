<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $dbConfig = config('database');
    echo "CONFIG DB DRIVER: " . config('database.default') . "\n";
    echo "SQLITE PATH: " . config('database.connections.sqlite.database') . "\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
