<?php

$basePath = dirname(__DIR__);

$cachePath = sys_get_temp_dir() . '/laravel-cache';

if (!is_dir($cachePath)) {
    mkdir($cachePath, 0777, true);
}

if (!is_writable($cachePath)) {
    http_response_code(500);
    exit('Direktori cache sementara tidak dapat ditulis.');
}

$cacheFiles = [
    'APP_PACKAGES_CACHE' => 'packages.php',
    'APP_SERVICES_CACHE' => 'services.php',
    'APP_CONFIG_CACHE' => 'config.php',
    'APP_ROUTES_CACHE' => 'routes.php',
    'APP_EVENTS_CACHE' => 'events.php',
];

foreach ($cacheFiles as $key => $file) {
    $path = $cachePath . '/' . $file;

    putenv($key . '=' . $path);
    $_ENV[$key] = $path;
    $_SERVER[$key] = $path;
}

try {
    require $basePath . '/public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');

    echo "ERROR UTAMA LARAVEL:\n";

    do {
        echo "\nJenis: " . get_class($e);
        echo "\nPesan: " . $e->getMessage();
        echo "\nFile: " . $e->getFile();
        echo "\nBaris: " . $e->getLine() . "\n";

        $e = $e->getPrevious();
    } while ($e !== null);
}
