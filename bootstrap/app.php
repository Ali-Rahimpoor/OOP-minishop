<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Router;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

Config::load();

date_default_timezone_set(
    Config::get('app', 'timezone')
);

$router = new Router();

require BASE_PATH . '/routes/web.php';

$router->dispatch(
    $_SERVER['REQUEST_URI'],
    $_SERVER['REQUEST_METHOD']
);