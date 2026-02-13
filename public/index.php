<?php

declare(strict_types=1);

require __DIR__ . '/../src/Core/Config.php';
Config::load(__DIR__ . '/../.env');

spl_autoload_register(function (string $class): void {
    $paths = [
        __DIR__ . '/../src/Core/' . $class . '.php',
        __DIR__ . '/../src/Controllers/' . $class . '.php',
        __DIR__ . '/../src/Models/' . $class . '.php',
        __DIR__ . '/../src/Services/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

$router = new Router();
$router->get('/', [BookingController::class, 'index']);
$router->post('/book', [BookingController::class, 'store']);
$router->get('/admin', [AdminController::class, 'index']);
$router->post('/admin/cancel', [AdminController::class, 'cancel']);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $handled = $router->dispatch($method, $path);
    if (!$handled) {
        http_response_code(404);
        View::render('notFound', ['title' => 'Page Not Found']);
    }
} catch (Throwable $error) {
    error_log($error->getMessage());
    http_response_code(500);
    View::render('error', [
        'title' => 'Error',
        'message' => 'Something went wrong.'
    ]);
}
