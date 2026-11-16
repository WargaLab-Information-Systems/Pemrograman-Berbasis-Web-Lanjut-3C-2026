<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\ApplicationBuilder;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

$basePath = dirname(__DIR__);

$runtimeBootstrapPath = sys_get_temp_dir() . '/laravel-bootstrap';
$runtimeCachePath = $runtimeBootstrapPath . '/cache';

$runtimeStoragePath = sys_get_temp_dir() . '/laravel-storage';

$directories = [
    $runtimeCachePath,
    $runtimeStoragePath . '/app',
    $runtimeStoragePath . '/framework/cache/data',
    $runtimeStoragePath . '/framework/sessions',
    $runtimeStoragePath . '/framework/testing',
    $runtimeStoragePath . '/framework/views',
    $runtimeStoragePath . '/logs',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    if (!is_writable($directory)) {
        throw new RuntimeException(
            'Folder tidak dapat ditulis: ' . $directory
        );
    }
}

if (is_file(__DIR__ . '/providers.php')) {
    copy(
        __DIR__ . '/providers.php',
        $runtimeBootstrapPath . '/providers.php'
    );
}

$app = new Application($basePath);

$app->useBootstrapPath($runtimeBootstrapPath);
$app->useStoragePath($runtimeStoragePath);

$builder = (new ApplicationBuilder($app))
    ->withKernels()
    ->withEvents()
    ->withCommands()
    ->withProviders();

return $builder
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Gunakan penanganan exception bawaan Laravel.
    })
    ->create();
