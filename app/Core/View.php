<?php

namespace App\Core;

final class View
{
    public static function render(string $view, array $data = []): string
    {
        $file = BASE_PATH . '/resources/views/' . $view . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException('Tampilan tidak ditemukan: ' . $view);
        }
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }

    public static function public(string $view, array $data = []): void
    {
        $content = self::render($view, $data);
        echo self::render('layouts/public', array_merge($data, ['content' => $content]));
    }

    public static function admin(string $view, array $data = []): void
    {
        $content = self::render($view, $data);
        echo self::render('layouts/admin', array_merge($data, ['content' => $content]));
    }
}
