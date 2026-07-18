<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): void
    {
        $file = BASE_PATH . "/views/{$view}.php";

        if (! file_exists($file)) {

            throw new \RuntimeException("View [{$view}] not found.");

        }

        extract($data);

        require $file;
    }
}