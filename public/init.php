<?php

declare(strict_types=1);

require __DIR__ . '/../src/Core/Config.php';
Config::load(__DIR__ . '/../.env');

try {
    $pdo = new PDO(
        'mysql:host=' . (Config::get('DB_HOST') ?? 'localhost') . 
        ';charset=utf8mb4',
        Config::get('DB_USER') ?? 'root',
        Config::get('DB_PASSWORD') ?? '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Loo andmebaas
    $dbName = Config::get('DB_NAME') ?? 'hairdresser_booking';
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName");
    $pdo->exec("USE $dbName");

    // Loo tabelid
    $sql = file_get_contents(__DIR__ . '/../db/schema.sql');
    $pdo->exec($sql);

    echo "✅ Database initialized successfully\n";
} catch (Exception $e) {
    echo "⚠️ Database check completed: " . $e->getMessage() . "\n";
}
