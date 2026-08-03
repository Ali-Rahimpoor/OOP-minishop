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
    public function showregister():void
    {
        $this->view('register');
    }
    public function register():void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $this->view('register', ['error' => 'نام کاربری و رمز عبور را وارد کنید.']);
            return;
        }
        $user = User::findByUsername($username);
        if($user){
            $this->view('register',['error'=>'نام کاربری تکراری هست']);
        }
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $user = User::create([
            'username' => $username,
            'password' => $hashed,
            'role'     => 'user'
        ]);

        if (!$user) {
            $this->view('register', ['error' => 'خطایی رخ داد، دوباره تلاش کنید.']);
            return;
        }        
        $this->redirect(site_url('/?action=register'));
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
        
        $this->redirect(site_url('/?action=login'));
    }

    public function logout(): void
    {
        Auth::logout();
        
        $this->redirect(site_url('/?action=logout'));
    }
}