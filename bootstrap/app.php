<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

$appConfig = require BASE_PATH . '/config/app.php';
date_default_timezone_set($appConfig['timezone'] ?? 'Asia/Jakarta');

$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_httponly', '1');
session_name($appConfig['session_name'] ?? 'SMALAN_PORTAL');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
]);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
if ($secure) {
    header('Strict-Transport-Security: max-age=31536000');
}
header("Content-Security-Policy: default-src 'self'; img-src 'self' data: blob:; style-src 'self'; script-src 'self'; font-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self'");

if (is_file(BASE_PATH . '/vendor/autoload.php')) {
    require BASE_PATH . '/vendor/autoload.php';
} else {
    spl_autoload_register(static function (string $class): void {
        $prefix = 'App\\';
        if (!str_starts_with($class, $prefix)) {
            return;
        }
        $relative = substr($class, strlen($prefix));
        $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relative) . '.php';
        if (is_file($file)) {
            require $file;
        }
    });
}

require BASE_PATH . '/app/Helpers/functions.php';

$localConfig = require BASE_PATH . '/config/database.php';
$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$isInstallRoute = ($uriPath === '/install' || $uriPath === '/install/process');

if (!$localConfig && !$isInstallRoute) {
    header('Location: /install', true, 302);
    exit;
}

if ($localConfig) {
    try {
        App\Core\Database::connect($localConfig);
    } catch (Throwable $exception) {
        if (!$isInstallRoute) {
            http_response_code(503);
            echo 'Koneksi database belum tersedia. Periksa konfigurasi pada config/local.php atau jalankan installer.';
            exit;
        }
    }
}

set_exception_handler(static function (Throwable $exception): void {
    $debug = (bool) (require BASE_PATH . '/config/app.php')['debug'];
    error_log((string) $exception);
    http_response_code(500);
    if ($debug) {
        echo '<pre>' . htmlspecialchars((string) $exception, ENT_QUOTES, 'UTF-8') . '</pre>';
    } else {
        echo '<!doctype html><html lang="id"><meta charset="utf-8"><title>Gangguan layanan</title><body style="font-family:Arial,sans-serif;padding:3rem;color:#18243a"><h1>Maaf, layanan sedang mengalami gangguan.</h1><p>Silakan muat ulang halaman beberapa saat lagi.</p><a href="/">Kembali ke beranda</a></body></html>';
    }
});
