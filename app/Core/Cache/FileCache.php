<?php

namespace App\Core\Cache;

class FileCache implements CacheInterface
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR);

        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    public function get(string $key): mixed
    {
        $file = $this->getFilePath($key);

        if (!file_exists($file)) {
            return null;
        }

        $cache = json_decode(file_get_contents($file), true);

        if (!$cache) {
            return null;
        }

        if ($cache['expires'] < time()) {
            unlink($file);
            return null;
        }

        return $cache['data'];
    }

    public function set(string $key, mixed $value, int $ttl = 86400): bool
    {
        $file = $this->getFilePath($key);

         $cache = [
            'expires' => time() + $ttl,
            'data'    => $value,
        ];

        return file_put_contents(
            $file,
            json_encode($cache, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        ) !== false;
    }

    public function delete(string $key): bool
    {
        $file = $this->getFilePath($key);

        if (!file_exists($file)) {
            return false;
        }

        return unlink($file);
    }

    public function deleteByPrefix(string $prefix): int
    {
        $count = 0;

        foreach (glob($this->path . DIRECTORY_SEPARATOR . $prefix . '*.json') as $file) {

            if (unlink($file)) {
                $count++;
            }

        }

        return $count;
    }

    private function getFilePath(string $key): string
    {
        return $this->path . DIRECTORY_SEPARATOR . $key . '.json';
    }
}