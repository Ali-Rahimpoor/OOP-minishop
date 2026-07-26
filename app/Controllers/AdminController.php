<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        if (!Auth::isAdmin()) {
            $this->redirect('/login');    
        }                
    }

    public function dashboard(): void
    {
        $this->view('admin/dashboard', [
            'username' => Auth::username(),
        ]);
    }
}