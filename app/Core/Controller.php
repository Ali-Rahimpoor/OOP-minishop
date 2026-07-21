<?php

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    protected function redirect(string $url): never
    {
        if($url === '/'){
            $url = site_url('');            
        }
        header("Location: {$url}");
        exit;
    }
}