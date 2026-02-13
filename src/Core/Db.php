<?php

declare(strict_types=1);

class Db
{
    private static ?PDO $instance = null;

    public static function get(): PDO
    {
        if (self::$instance === null) {
            $host = Config::get('DB_HOST', 'localhost');
            $name = Config::get('DB_NAME', 'hairdresser_booking');
            $user = Config::get('DB_USER', 'root');
            $pass = Config::get('DB_PASSWORD', '');

            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $host, $name);

            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return self::$instance;
    }
}
