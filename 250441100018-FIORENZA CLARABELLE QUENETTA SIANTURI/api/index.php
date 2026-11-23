<?php

// Filesystem Vercel bersifat read-only, kecuali /tmp.
$tmp = '/tmp/storage';
foreach (['framework/views', 'framework/cache/data', 'framework/sessions', 'logs'] as $d) {
    @mkdir("$tmp/$d", 0777, true);
}

$vars = [
    'APP_CONFIG_CACHE'     => '/tmp/config.php',
    'APP_EVENTS_CACHE'     => '/tmp/events.php',
    'APP_PACKAGES_CACHE'   => '/tmp/packages.php',
    'APP_ROUTES_CACHE'     => '/tmp/routes.php',
    'APP_SERVICES_CACHE'   => '/tmp/services.php',
    'VIEW_COMPILED_PATH'   => "$tmp/framework/views",
    'LARAVEL_STORAGE_PATH' => $tmp,
    'LOG_CHANNEL'          => 'stderr',
];
foreach ($vars as $k => $v) {
    putenv("$k=$v");
    $_ENV[$k] = $v;
    $_SERVER[$k] = $v;
}

require __DIR__ . '/../public/index.php';
