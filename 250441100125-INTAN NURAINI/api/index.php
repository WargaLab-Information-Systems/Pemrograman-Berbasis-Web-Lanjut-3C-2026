<?php

$basePath = dirname(__DIR__);

$queryString = $_SERVER['QUERY_STRING'] ?? '';

if (str_contains($queryString, 'debug_uri=1')) {
    header_remove();

    http_response_code(200);
    header('Content-Type: text/plain; charset=utf-8');

    echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? '-') . "\n";
    echo "PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? '-') . "\n";
    echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? '-') . "\n";
    echo "QUERY_STRING: " . $queryString . "\n";

    exit;
}

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
