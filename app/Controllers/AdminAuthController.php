<?php

namespace App\Controllers;

use App\Core\Controller;

class AdminAuthController extends Controller
{
    public function show(): void
    {
        echo "Login Page";
    }

    public function login(): void
    {
        echo "Login Process";
    }
}