<?php

use App\Controllers\HomeController;
use App\Controllers\AdminAuthController;
use App\Controllers\ProductController;
$router->get('/', [HomeController::class, 'index']);
$router->get('/products', [ProductController::class, 'index']);
$router->get('/login', [AdminAuthController::class, 'show']);

$router->post('/login', [AdminAuthController::class, 'login']);