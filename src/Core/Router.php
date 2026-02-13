<?php

declare(strict_types=1);

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $path): bool
    {
        $handler = $this->routes[$method][$path] ?? null;
        if ($handler === null) {
            return false;
        }

        [$class, $action] = $handler;
        $controller = new $class();
        $controller->{$action}();
        return true;
    }
}
