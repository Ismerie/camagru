<?php

class Database {
    private static ?PDO $instance = null;

    private static function loadEnv($path): void {
        if (!file_exists($path)) {
            throw new Exception(".env file not found at $path");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            list($name, $value) = explode('=', $line, 2);
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            self::loadEnv(__DIR__ . '/../../.env');

            $dbHost = getenv('MYSQL_HOST');
            $dbName = getenv('MYSQL_DATABASE');
            $dbUser = getenv('MYSQL_USER');
            $dbPass = getenv('MYSQL_PASSWORD');

            $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $dbUser, $dbPass);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die(json_encode(['error' => 'Database connection failed']));
            }
        }

        return self::$instance;
    }
}
