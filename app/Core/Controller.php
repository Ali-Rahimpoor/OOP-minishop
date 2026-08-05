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
            header('Location:' . site_url('') );
            exit;         
        }
        header("Location: " . site_url($url));
        exit;
    }
}