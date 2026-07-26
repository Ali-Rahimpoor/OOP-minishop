<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;

    private PDO $connection;

    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            Config::get('database', 'host'),
            Config::get('database', 'dbname'),
            Config::get('database', 'charset')
        );

        try {            
            $this->connection = new PDO(
                $dsn,
                Config::get('database', 'username'),
                Config::get('database', 'password'),
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );

        } catch (PDOException $e) {

            throw new PDOException($e->getMessage());

        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {

            self::$instance = new self();

        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    private function __clone(): void
    {
    }
}