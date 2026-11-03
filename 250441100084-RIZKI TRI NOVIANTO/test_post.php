<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

foreach (Illuminate\Support\Facades\Route::getRoutes() as $route) {
    if ($route->uri() === 'api/buku') {
        echo $route->methods()[0] . " " . $route->uri() . " -> " . $route->getActionName() . "\n";
    }
}
