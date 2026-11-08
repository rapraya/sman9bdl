<?php

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, callable|array $handler): void
    {
        $this->routes[] = compact('method', 'path', 'handler');
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }
            $keys = [];
            $pattern = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', static function (array $match) use (&$keys): string {
                $keys[] = $match[1];
                return '([^/]+)';
            }, $route['path']);
            if (!preg_match('#^' . $pattern . '$#', $path, $matches)) {
                continue;
            }
            array_shift($matches);
            $params = [];
            foreach ($keys as $index => $key) {
                $params[$key] = rawurldecode($matches[$index] ?? '');
            }
            call_user_func_array($route['handler'], $params);
            return;
        }
        http_response_code(404);
        echo View::render('errors/404', ['title' => 'Halaman tidak ditemukan']);
    }
}
