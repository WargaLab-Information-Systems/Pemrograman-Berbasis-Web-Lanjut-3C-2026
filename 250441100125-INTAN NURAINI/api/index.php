<?php

$basePath = dirname(__DIR__);

// Perbaiki PATH_INFO yang dipotong oleh Vercel.
$requestUriPath = parse_url(
    $_SERVER['REQUEST_URI'] ?? '/',
    PHP_URL_PATH
);

if (
    is_string($requestUriPath) &&
    str_starts_with($requestUriPath, '/api/') &&
    !str_starts_with($requestUriPath, '/api/index.php')
) {
    $_SERVER['PATH_INFO'] = $requestUriPath;
}

// Lokasi cache sementara untuk Vercel.
$cachePath = sys_get_temp_dir() . '/laravel-cache';

if (!is_dir($cachePath)) {
    mkdir($cachePath, 0777, true);
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
    require $basePath . '/vendor/autoload.php';

    $app = require $basePath . '/bootstrap/app.php';

    $kernel = $app->make(
        \Illuminate\Contracts\Http\Kernel::class
    );

    $request = \Illuminate\Http\Request::capture();

    $response = $kernel->handle($request);

    $response->send();

    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');

    echo "ERROR SAAT MEMPROSES REQUEST\n";
    echo "Jenis: " . get_class($e) . "\n";
    echo "Pesan: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Baris: " . $e->getLine() . "\n";

    while ($previous = $e->getPrevious()) {
        echo "\nPENYEBAB SEBELUMNYA:\n";
        echo get_class($previous) . "\n";
        echo $previous->getMessage() . "\n";
        $e = $previous;
    }
}
