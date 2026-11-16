<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

// Siapkan bootstrap dan cache di direktori sementara Vercel
$runtimeBootstrapPath = sys_get_temp_dir() . '/laravel-bootstrap';
$runtimeCachePath = $runtimeBootstrapPath . '/cache';

if (!is_dir($runtimeCachePath)) {
    mkdir($runtimeCachePath, 0777, true);
}

// Salin daftar provider Laravel ke lokasi bootstrap sementara
if (is_file(__DIR__ . '/providers.php')) {
    copy(
        __DIR__ . '/providers.php',
        $runtimeBootstrapPath . '/providers.php'
    );
}

$app = Application::configure(basePath: dirname(__DIR__))
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

// Arahkan Laravel ke bootstrap sementara
$app->useBootstrapPath($runtimeBootstrapPath);

return $app;
