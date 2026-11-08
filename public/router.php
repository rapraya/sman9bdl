<?php

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$requested = __DIR__ . $path;
if ($path !== '/' && is_file($requested)) {
    return false;
}
require __DIR__ . '/index.php';
