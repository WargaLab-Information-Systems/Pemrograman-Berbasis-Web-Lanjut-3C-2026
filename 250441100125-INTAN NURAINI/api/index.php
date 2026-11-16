<?php

$basePath = dirname(__DIR__);

error_log('[VERCEL DIAG] Base path: ' . $basePath);

$files = [
    'vendor/autoload.php',
    'bootstrap/app.php',
    'bootstrap/providers.php',
    'bootstrap/cache/services.php',
    'bootstrap/cache/packages.php',
    'bootstrap/cache/config.php',
    'config/app.php',
];

foreach ($files as $file) {
    $path = $basePath . '/' . $file;

    error_log(
        '[VERCEL DIAG] ' . $file . ': ' .
        (file_exists($path) ? 'EXISTS' : 'MISSING')
    );
}

error_log('[CHECK] Laravel bootstrap starting');

require $basePath . '/public/index.php';
