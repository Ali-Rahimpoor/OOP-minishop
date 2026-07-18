<?php

use App\Controllers\HomeController;
use App\Controllers\AdminAuthController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/login', [AdminAuthController::class, 'show']);

$router->post('/login', [AdminAuthController::class, 'login']);