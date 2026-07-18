<?php

namespace App\Core;

class Router
{
    /**
     * لیست Route ها
     */
    private array $routes = [];

    /**
     * ثبت Route های GET
     */
    public function get(string $uri, array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    /**
     * ثبت Route های POST
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
        $this->routes[$method][$this->normalize($uri)] = $action;
    }

    /**
     * اجرای Route
     */
    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        $uri = $this->normalize($uri);

        if (! isset($this->routes[$method][$uri])) {

            http_response_code(404);

            exit('404 | Page Not Found');

        }

        [$controller, $action] = $this->routes[$method][$uri];

        $controller = new $controller();

        $controller->$action();
    }

    /**
     * حذف اسلش اضافی
     */
    private function normalize(string $uri): string
    {
        $uri = trim($uri, '/');

        return $uri === '' ? '/' : '/' . $uri;
    }
}