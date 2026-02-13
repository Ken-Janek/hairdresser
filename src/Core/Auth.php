<?php

declare(strict_types=1);

class Auth
{
    public static function requireBasic(string $expectedUser, string $expectedPass): void
    {
        $user = $_SERVER['PHP_AUTH_USER'] ?? '';
        $pass = $_SERVER['PHP_AUTH_PW'] ?? '';

        $ok = $user !== '' && $pass !== ''
            && hash_equals($expectedUser, $user)
            && hash_equals($expectedPass, $pass);

        if ($ok) {
            return;
        }

        header('WWW-Authenticate: Basic realm="Admin"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Unauthorized';
        exit;
    }
}
