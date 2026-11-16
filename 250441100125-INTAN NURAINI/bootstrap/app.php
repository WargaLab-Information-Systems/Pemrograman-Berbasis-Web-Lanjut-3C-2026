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

if (!is_dir($runtimeCachePath)) {
    mkdir($runtimeCachePath, 0777, true);
}

if (!is_writable($runtimeCachePath)) {
    throw new RuntimeException('Folder bootstrap sementara tidak dapat ditulis.');
}

if (is_file(__DIR__ . '/providers.php')) {
    copy(
        __DIR__ . '/providers.php',
        $runtimeBootstrapPath . '/providers.php'
    );
}

// Buat aplikasi dan atur bootstrap SEBELUM mendaftarkan provider.
$app = new Application($basePath);
$app->useBootstrapPath($runtimeBootstrapPath);

// Konfigurasi builder Laravel 13.
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
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
