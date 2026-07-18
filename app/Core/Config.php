<?php

namespace App\Core;

class Config
{
    private static array $config = [];

    public static function load(): void
    {
        foreach (glob(BASE_PATH . '/config/*.php') as $file) {

            $name = pathinfo($file, PATHINFO_FILENAME);

            self::$config[$name] = require $file;

        }
    }

    public static function get(string $file, string $key): mixed
    {
        return self::$config[$file][$key] ?? null;
    }
}