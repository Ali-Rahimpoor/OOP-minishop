<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\User;

class AdminAuthController extends Controller
{
    public function show(): void
    {
        if (Auth::isAdmin()) {
            $this->redirect('admin');
        }
        
        $this->view('login');
    }

    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $this->view('login', ['error' => 'نام کاربری و رمز عبور را وارد کنید.']);
            return;
        }

        $user = User::findByUsername($username);

        if (!$user || !password_verify($password, $user->password)) {
            
            $this->view('login', ['error' => 'نام کاربری یا رمز عبور اشتباه است.']);
            return;
        }

        Auth::login($user);
        
        $this->redirect('admin');
    }

    public function logout(): void
    {
        Auth::logout();
        
        $this->redirect('login');
    }
}