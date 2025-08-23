<?php

declare(strict_types=1);

namespace App\Utils;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    /**
     * The constructor is private to prevent direct creation of object.
     */
    private function __construct()
    {
    }

    /**
     * Gets the single instance of the PDO connection.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $db = $_ENV['DB_DATABASE'] ?? 'recruiter_ai';
            $user = $_ENV['DB_USERNAME'] ?? 'root';
            $pass = $_ENV['DB_PASSWORD'] ?? '';
            $charset = 'utf8mb4';

            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // In a real app, you'd log this error.
                // For now, we just rethrow it.
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$instance;
    }

    /**
     * Prevent cloning of the instance.
     */
    private function __clone()
    {
    }

    /**
     * Prevent unserialization of the instance.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * A method to set the PDO instance, primarily for testing purposes.
     * @param PDO|null $pdo The PDO instance to use, or null to reset.
     */
    public static function setInstance(?PDO $pdo): void
    {
        self::$instance = $pdo;
    }
}
