<?php

declare(strict_types=1);

class Auth
{
    public static function requireBasic(string $expectedUser, string $expectedPass): void
    {
        [$user, $pass] = self::getBasicCredentials();

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

    private static function getBasicCredentials(): array
    {
        $user = $_SERVER['PHP_AUTH_USER'] ?? '';
        $pass = $_SERVER['PHP_AUTH_PW'] ?? '';

        if ($user !== '' || $pass !== '') {
            return [$user, $pass];
        }

        $authorization = $_SERVER['HTTP_AUTHORIZATION']
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
            ?? '';

        if (stripos($authorization, 'Basic ') !== 0) {
            return ['', ''];
        }

        $decoded = base64_decode(substr($authorization, 6), true);
        if ($decoded === false || strpos($decoded, ':') === false) {
            return ['', ''];
        }

        return explode(':', $decoded, 2);
    }
}
