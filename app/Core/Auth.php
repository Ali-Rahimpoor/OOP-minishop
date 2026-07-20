<?php

namespace App\Core;

use App\Models\User;

/**
 * مدیریت نشست کاربر (لاگین/لاگ‌اوت) بر پایه Session.
 * در فاز یک، تنها کاربری که در سیستم لاگین می‌شود ادمین است،
 * اما ساختار برای پشتیبانی از نقش‌های دیگر در آینده آماده است.
 */
class Auth
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }    
    public static function login(User $user): void
    {
        self::start();
        session_regenerate_id(true);
        $_SESSION['user_id']  = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role']     = $user->role;
    }

    public static function logout(): void
    {
        self::start();
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
    }

    public static function check(): bool
    {
        self::start();
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin(): bool
    {
        self::start();
        return self::check() && ($_SESSION['role'] ?? '') === 'admin';
    }

    public static function username(): ?string
    {
        self::start();
        return $_SESSION['username'] ?? null;
    }

    /**
     * اگر کاربر ادمین نبود، به صفحه لاگین هدایت می‌شود و اجرای اسکریپت متوقف می‌شود.
     */
    public static function requireAdmin(string $redirectTo = 'login.php'): void
    {
        if (!self::isAdmin()) {
            header('Location: ' . $redirectTo);
            exit;
        }
    }
}
