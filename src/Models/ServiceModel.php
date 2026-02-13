<?php

declare(strict_types=1);

class ServiceModel
{
    public static function all(): array
    {
        $pdo = Db::get();
        $stmt = $pdo->query('SELECT id, name, price_cents, duration_minutes FROM services ORDER BY id');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Db::get();
        $stmt = $pdo->prepare('SELECT id, name, price_cents, duration_minutes FROM services WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }
}
