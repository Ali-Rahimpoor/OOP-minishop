<?php

namespace App\Core;

class Logger
{
    private static string $logFile = BASE_PATH . '/storage/logs/error.log';

    /**
     * ثبت یک خطا در فایل لاگ.
     * $context می‌تواند هر اطلاعات اضافه‌ای (مثل نام متد، ورودی‌ها) باشد.
     */
    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    private static function write(string $level, string $message, array $context): void
    {
        $dir = dirname(self::$logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $date = date('Y-m-d H:i:s');
        $contextString = !empty($context) ? ' | ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';

        $line = "[{$date}] {$level}: {$message}{$contextString}" . PHP_EOL;

        file_put_contents(self::$logFile, $line, FILE_APPEND);
    }

    /**
     * ثبت کامل یک Exception، شامل پیام، فایل، خط، و Stack Trace
     */
    public static function exception(\Throwable $e, array $context = []): void
    {
        $message = sprintf(
            '%s in %s:%d',
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );

        $context['trace'] = $e->getTraceAsString();

        self::write('EXCEPTION', $message, $context);
    }
}