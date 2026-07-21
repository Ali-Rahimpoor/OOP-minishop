<?php

use App\Controllers\HomeController;
use App\Controllers\AdminAuthController;
use App\Controllers\ProductController;
$router->get('/', [HomeController::class, 'index']);
$router->get('/product', [ProductController::class, 'index']);
$router->get('/login', [AdminAuthController::class, 'show']);
$router->post('/products/store',[ProductController::class,'store']);
$router->post('/login', [AdminAuthController::class, 'login']);