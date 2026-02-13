<?php

declare(strict_types=1);

class StylistModel
{
    public static function all(): array
    {
        $pdo = Db::get();
        $stmt = $pdo->query('SELECT id, name FROM stylists ORDER BY id');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Db::get();
        $stmt = $pdo->prepare('SELECT id, name FROM stylists WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }
}
