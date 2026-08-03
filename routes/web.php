<?php

use App\Controllers\HomeController;
use App\Controllers\AdminAuthController;
use App\Controllers\ProductController;
use App\Controllers\AdminController;
use App\Controllers\CartController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/product', [ProductController::class, 'index']);
$router->post('/products/store',[ProductController::class,'store']);
$router->get('/products/{id}',[ProductController::class,'index']);
$router->post('/products/{id}',[ProductController::class,'update']);
$router->post('/delete/{id}',[ProductController::class,'delete']);

$router->get('/register',[AdminAuthController::class,'showregister']);
$router->get('/login', [AdminAuthController::class, 'show']);
$router->post('/register',[AdminAuthController::class,'register']);
$router->post('/login', [AdminAuthController::class, 'login']);
$router->post('/logout', [AdminAuthController::class, 'logout']);
$router->get('/admin', [AdminController::class, 'dashboard']);

$router->post('/cart/add/{id}',[CartController::class,'add']);
$router->get('/cart',[CartController::class,'index']);