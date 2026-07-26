<?php

use App\Controllers\HomeController;
use App\Controllers\AdminAuthController;
use App\Controllers\ProductController;
use App\Controllers\AdminController;
$router->get('/', [HomeController::class, 'index']);
$router->get('/product', [ProductController::class, 'index']);
$router->post('/products/store',[ProductController::class,'store']);
$router->get('/products/{id}',[ProductController::class,'index']);
$router->post('/products/{id}',[ProductController::class,'update']);
$router->post('/delete/{id}',[ProductController::class,'delete']);

$router->get('/login', [AdminAuthController::class, 'show']);
$router->post('/login', [AdminAuthController::class, 'login']);
$router->get('/logout', [AdminAuthController::class, 'logout']);
$router->get('/admin', [AdminController::class, 'dashboard']);