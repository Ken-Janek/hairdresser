<?php

declare(strict_types=1);

class Db
{
    private static ?PDO $instance = null;

    public static function get(): PDO
    {
        if (self::$instance === null) {
            $databaseUrl = Config::get('DATABASE_URL');
            
            if ($databaseUrl) {
                // Parse DATABASE_URL format: mysql://user:password@host:port/dbname
                $parsed = parse_url($databaseUrl);
                $host = $parsed['host'] ?? 'localhost';
                $port = $parsed['port'] ?? 3306;
                $user = $parsed['user'] ?? 'root';
                $pass = $parsed['pass'] ?? '';
                $name = ltrim($parsed['path'] ?? '/', '/');
                
                $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $name);
                error_log("Using DATABASE_URL: host=$host, port=$port, db=$name");
            } else {
                // Fallback to individual environment variables
                $host = Config::get('DB_HOST') ?? 'localhost';
                $port = Config::get('DB_PORT') ?? '3306';
                $name = Config::get('DB_NAME') ?? 'hairdresser_booking';
                $user = Config::get('DB_USER') ?? 'root';
                $pass = Config::get('DB_PASSWORD') ?? '';
                
                $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $name);
                error_log("Using individual env vars: host=$host, port=$port, db=$name");
            }

            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return self::$instance;
    }
}
