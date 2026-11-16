<?php

$basePath = dirname(__DIR__);

if (isset($_GET['vercel-check']) && $_GET['vercel-check'] === '2') {
    header('Content-Type: text/plain; charset=utf-8');

    try {
        require $basePath . '/vendor/autoload.php';

        $app = require $basePath . '/bootstrap/app.php';

        echo "=== TES BOOTSTRAP LARAVEL ===\n";
        echo "PHP: " . PHP_VERSION . "\n";

        $kernel = $app->make(
            \Illuminate\Contracts\Console\Kernel::class
        );

        $kernel->bootstrap();

        echo "Laravel bootstrap: BERHASIL\n";
        echo "ViewServiceProvider: ";
        echo class_exists(
            \Illuminate\View\ViewServiceProvider::class
        ) ? "ADA\n" : "TIDAK ADA\n";

        echo "View provider terdaftar: ";
        echo isset(
            $app->getLoadedProviders()[
                \Illuminate\View\ViewServiceProvider::class
            ]
        ) ? "YA\n" : "TIDAK\n";

        echo "Binding view: ";
        echo $app->bound('view') ? "ADA\n" : "TIDAK ADA\n";

        echo "Services cache: ";
        echo $app->getCachedServicesPath() . "\n";

    } catch (\Throwable $e) {
        echo "BOOTSTRAP GAGAL\n";
        echo "Error: " . get_class($e) . "\n";
        echo "Pesan: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . "\n";
        echo "Baris: " . $e->getLine() . "\n";
    }

    exit;
}

require $basePath . '/public/index.php';
