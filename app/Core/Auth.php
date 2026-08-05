<?php
namespace App\Core;
use App\Models\User;
class Auth
{   
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }
    public static function login(User $user): void
    {        
        session_regenerate_id(true);
        $_SESSION['user_id']  = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role']     = $user->role;        
    }
    public static function logout(): void
    {        
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
    public static function check(): bool
    {        
        return isset($_SESSION['user_id']);
    }
    public static function isAdmin(): bool
    {        
        return self::check() && (self::user_role()) === 'admin';
    }
    public static function user_id(): ?int
    {        
        if(!$_SESSION['user_id']){
            header('Location: ' . site_url('login'));
            exit;
        }
        return $_SESSION['user_id'];
    }
    public static function username(): ?string
    {
        if(!$_SESSION['username']){
            header('Location: ' . site_url('login'));
            exit;
        }
        return $_SESSION['username'];
    }
    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            header('Location: ' . site_url('login'));
            exit;
        }
    }
    public static function user_role(): ?string
    {
        if(!$_SESSION['role']){
            header('Location: ' . site_url('login'));
            exit;
        }
        return $_SESSION['role'];
    }
}