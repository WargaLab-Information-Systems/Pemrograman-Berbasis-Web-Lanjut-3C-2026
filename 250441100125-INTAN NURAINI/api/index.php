<?php

$basePath = dirname(__DIR__);

if (isset($_GET['vercel-check']) && $_GET['vercel-check'] === '1') {
    header('Content-Type: text/plain; charset=utf-8');

    echo "=== VERCEL DIAGNOSTIC ===\n";
    echo "PHP Version: " . PHP_VERSION . "\n";
    echo "Base Path: " . $basePath . "\n\n";

    $files = [
        'vendor/autoload.php',
        'bootstrap/app.php',
        'bootstrap/providers.php',
        'config/app.php',
        'vendor/laravel/framework/src/Illuminate/View/ViewServiceProvider.php',
    ];

    foreach ($files as $file) {
        echo $file . ': ';
        echo file_exists($basePath . '/' . $file)
            ? "EXISTS\n"
            : "MISSING\n";
    }

    exit;
}

require $basePath . '/public/index.php';
