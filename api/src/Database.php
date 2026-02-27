<?php
namespace App;

use PDO;
use PDOException;

class Database
{
    private $pdo;

    public function __construct(array $config = null)
    {
        if ($config === null) {
            $config = require __DIR__ . '/../../config.php';
            $config = $config['db'];
        }

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $config['host'], $config['port'], $config['name']);
        try {
            $this->pdo = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    // For tests allow passing custom DSN
    public static function fromDsn(string $dsn, string $user = null, string $pass = null): PDO
    {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
