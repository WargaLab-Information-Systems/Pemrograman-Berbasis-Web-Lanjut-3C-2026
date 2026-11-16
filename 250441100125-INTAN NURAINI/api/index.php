<?php

$basePath = dirname(__DIR__);

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

header('Content-Type: text/plain; charset=utf-8');

try {
    require $basePath . '/vendor/autoload.php';

    $app = require $basePath . '/bootstrap/app.php';

    $kernel = $app->make(
        \Illuminate\Contracts\Http\Kernel::class
    );

    $kernel->bootstrap();

    echo "BOOTSTRAP BERHASIL\n";
    echo "PHP: " . PHP_VERSION . "\n";
    echo "Laravel: " . $app->version() . "\n";
    echo "View terdaftar: ";
    var_export($app->bound('view'));

    echo "\nView dapat dipanggil: ";
    echo get_class($app->make('view'));

} catch (\Throwable $e) {
    http_response_code(500);

    echo "BOOTSTRAP GAGAL\n";
    echo "Jenis: " . get_class($e) . "\n";
    echo "Pesan: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Baris: " . $e->getLine() . "\n";

    if ($e->getPrevious()) {
        echo "Penyebab sebelumnya: "
            . $e->getPrevious()->getMessage() . "\n";
    }
}