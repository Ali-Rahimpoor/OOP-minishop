<?php

namespace App\Core;

class Router
{
    /**
     * لیست تمام Route ها
     */
    private array $routes = [];

    /**
     * ثبت Route از نوع GET
     */
    public function get(string $uri, array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    /**
     * ثبت Route از نوع POST
     */
    public function post(string $uri, array $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    /**
     * ثبت Route
     */
    private function addRoute(string $method, string $uri, array $action): void
    {
        $this->routes[$method][$uri] = $action;
    }

    /**
     * اجرای Route
     */
    public function dispatch(string $uri, string $method): void
    {
        // حذف Query String
        $uri = parse_url($uri, PHP_URL_PATH);

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            exit('404 - Page Not Found');
        }

        [$controller, $action] = $this->routes[$method][$uri];

        $controller = new $controller();

        call_user_func([$controller, $action]);
    }
}