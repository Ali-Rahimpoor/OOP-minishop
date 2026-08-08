<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Controllers\CartController;

// USERS
$router->get('/register',[AuthController::class,'showRegister']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/register',[AuthController::class,'register']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// HOME
$router->get('/', [HomeController::class, 'index']);

// PRODUCTS
$router->get('/product', [ProductController::class, 'index']);
$router->get('/products/{id}',[ProductController::class,'index']);
$router->post('/products/store',[ProductController::class,'store']);
$router->post('/products/{id}',[ProductController::class,'update']);
$router->post('/delete/{id}',[ProductController::class,'delete']);

// CART
$router->get('/cart',[CartController::class,'index']);
$router->post('/cart/add/{id}',[CartController::class,'add']);
$router->post('/cart/update/{id}',[CartController::class,'update']);
$router->post('/cart/remove/{id}',[CartController::class,'remove']);
$router->post('/cart/checkout',[CartController::class,'checkout']);
$router->post('/cart/place-order',[CartController::class,'placeOrder']);