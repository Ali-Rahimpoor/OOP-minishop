<?php

namespace App\Core;

abstract class Controller
{
    public function __construct()
    {
        Auth::start();
    }

    /**
     * رندر یک فایل ویو از پوشه views/ به همراه دیتای مربوطه
     */
    protected function view(string $view, array $data = []): void
    {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View یافت نشد: {$view}");
        }

        extract($data);
        require $viewFile;
    }

    protected function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }

    protected function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    /**
     * پیام یک‌بارمصرف (Flash) برای نمایش موفقیت/خطا بعد از ریدایرکت
     */
    protected function flash(string $type, string $message): void
    {
        $_SESSION['_flash'][$type] = $message;
    }

    protected function getFlash(string $type): ?string
    {
        $message = $_SESSION['_flash'][$type] ?? null;
        unset($_SESSION['_flash'][$type]);
        return $message;
    }

    /**
     * نگهداری مقادیر فرم بعد از خطای اعتبارسنجی، برای پر کردن دوباره فیلدها
     */
    protected function setOldInput(array $data): void
    {
        $_SESSION['_old'] = $data;
    }

    protected function old(string $key, $default = '')
    {
        return $_SESSION['_old'][$key] ?? $default;
    }

    protected function clearOldInput(): void
    {
        unset($_SESSION['_old']);
    }
}
