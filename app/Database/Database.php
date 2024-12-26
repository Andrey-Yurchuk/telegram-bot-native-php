<?php

namespace App\Database;

use App\Utils\EnvLoader;
use PDO;
use PDOException;

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        EnvLoader::load();

        $host = getenv('DB_HOST');
        $db = getenv('DB_DATABASE');
        $user = getenv('DB_USERNAME');
        $pass = getenv('DB_PASSWORD');

        $dsn = "mysql:host=$host;dbname=$db";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            error_log('Ошибка подключения к базе данных: ' . $e->getMessage());
            exit('Ошибка подключения к базе данных');
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}

