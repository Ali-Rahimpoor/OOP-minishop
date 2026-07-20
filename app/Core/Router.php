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
   public function dispatch(string $uri, string $httpMethod): void
    {
        // فقط مسیر URL را دریافت کن
        $uri = parse_url($uri, PHP_URL_PATH);

        // حذف پوشه پروژه (در صورت اجرا داخل SubFolder)
        $uri = str_replace('/oop-minishop', '', $uri);

        // یکسان سازی اسلش ها
        $uri = $this->normalize($uri);

        // اگر هیچ Route ای برای این متد ثبت نشده بود
        if (!isset($this->routes[$httpMethod])) {
            http_response_code(404);
            exit('404 | Page Not Found');
        }

        foreach ($this->routes[$httpMethod] as $route => $handler) {

            // تبدیل Route به Regex
            $pattern = preg_replace(
                '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
                '([^/]+)',
                $route
            );

            $pattern = '#^' . $pattern . '$#';

            // بررسی تطابق URL
            if (!preg_match($pattern, $uri, $matches)) {
                continue;
            }

            // حذف کل رشته Match شده
            array_shift($matches);

            [$controllerClass, $action] = $handler;

            $controller = new $controllerClass();

            $controller->$action(...$matches);

            return;
        }

        http_response_code(404);
        exit('404 | Page Not Found');
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