<?php

declare(strict_types=1);

class View
{
    public static function render(string $template, array $data = []): void
    {
        $viewPath = __DIR__ . '/../Views/' . $template . '.php';
        if (!file_exists($viewPath)) {
            throw new RuntimeException('View not found: ' . $template);
        }

        $escape = static function (string $value): string {
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        };

        extract($data, EXTR_SKIP);

        require __DIR__ . '/../Views/partials/header.php';
        require $viewPath;
        require __DIR__ . '/../Views/partials/footer.php';
    }
}
