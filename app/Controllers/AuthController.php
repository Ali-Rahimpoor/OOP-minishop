<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Repo\UsersRepository;

class AuthController extends Controller
{    
    public function showLogin(): void
    {
        if (Auth::isAdmin()) {
            $this->redirect('?action=admin');
        }
        $this->view('login');
    }
    public function showRegister():void
    {
        if(Auth::check()){
            Auth::logout();     
        }
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
        $repo = new UsersRepository();
        $user = $repo->findByUsername($username);
        if($user){
            $this->view('register',['error'=>'نام کاربری تکراری هست']);
            exit;
        }
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $user = $repo->create([
            'username' => $username,
            'password' => $hashed,
            'role'     => 'user'
        ]);

        if (!$user) {
            $this->view('register', ['error' => 'خطایی رخ داد، دوباره تلاش کنید.']);
            return;
        }        
        Auth::login($user);
        $this->redirect('?action=register');
    }

    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $this->view('login', ['error' => 'نام کاربری و رمز عبور را وارد کنید.']);            
            return;
        }
        $repo = new UsersRepository();

        $user = $repo->findByUsername($username);

        if (!$user || !password_verify($password, $user->password)) {
            
            $this->view('login', ['error' => 'نام کاربری یا رمز عبور اشتباه است.']);
            return;
        }

        Auth::login($user);
        
        $this->redirect('?action=login');
    }

    public function logout(): void
    {
        Auth::logout();
        
        $this->redirect('?action=logout');
    }
}