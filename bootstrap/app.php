<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Router;
use App\Core\Auth;
Config::load();
date_default_timezone_set(
    Config::get('app', 'timezone')
);
Auth::start();
$router = new Router();
require BASE_PATH . '/app/Helpers/url.php';
require BASE_PATH . '/app/Helpers/view.php';
require BASE_PATH . '/routes/web.php';

$router->dispatch(
    $_SERVER['REQUEST_URI'],
    $_SERVER['REQUEST_METHOD']
);