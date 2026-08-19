<?php

$runtimePaths = [
    'APP_CONFIG_CACHE' => '/tmp/laravel/config.php',
    'APP_EVENTS_CACHE' => '/tmp/laravel/events.php',
    'APP_PACKAGES_CACHE' => '/tmp/laravel/packages.php',
    'APP_ROUTES_CACHE' => '/tmp/laravel/routes.php',
    'APP_SERVICES_CACHE' => '/tmp/laravel/services.php',
    'VIEW_COMPILED_PATH' => '/tmp/laravel/views',
];

foreach ($runtimePaths as $key => $path) {
    if (getenv($key) === false || getenv($key) === '') {
        putenv($key.'='.$path);
        $_ENV[$key] = $path;
        $_SERVER[$key] = $path;
    }
}

foreach (array_unique(array_map('dirname', $runtimePaths)) as $directory) {
    if (! is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
}

if (! is_dir('/tmp/laravel/views')) {
    mkdir('/tmp/laravel/views', 0755, true);
}

require __DIR__.'/../public/index.php';
