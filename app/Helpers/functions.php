<?php

use App\Core\Csrf;
use App\Core\Database;

function e(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path, int $status = 302): never
{
    header('Location: ' . $path, true, $status);
    exit;
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(Csrf::token()) . '">';
}

function setting(string $key, string $fallback = ''): string
{
    static $settings = null;
    if ($settings === null) {
        $settings = [];
        try {
            $rows = Database::pdo()->query('SELECT setting_key, setting_value FROM settings')->fetchAll();
            foreach ($rows as $row) {
                $settings[$row['setting_key']] = (string) $row['setting_value'];
            }
        } catch (Throwable) {
            $settings = [];
        }
    }
    return (string) ($settings[$key] ?? $fallback);
}

function slugify(string $value): string
{
    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    return trim($value, '-') ?: 'konten';
}

function format_date(?string $value, bool $withTime = false): string
{
    if (!$value) {
        return 'Tanggal belum ditentukan';
    }
    try {
        $date = new DateTimeImmutable($value, new DateTimeZone('Asia/Jakarta'));
    } catch (Throwable) {
        return e($value);
    }
    $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
    $result = $date->format('j') . ' ' . $months[(int) $date->format('n')] . ' ' . $date->format('Y');
    return $withTime ? $result . ' · ' . $date->format('H:i') . ' WIB' : $result;
}

function content_url(string $type, string $slug): string
{
    $prefix = [
        'news' => 'berita',
        'announcement' => 'pengumuman',
        'event' => 'agenda',
        'achievement' => 'prestasi',
        'page' => 'halaman',
    ][$type] ?? 'halaman';
    return '/' . $prefix . '/' . rawurlencode($slug);
}

function excerpt(string $text, int $length = 150): string
{
    $plain = trim(preg_replace('/\s+/', ' ', strip_tags($text)) ?? '');
    if (mb_strlen($plain) <= $length) {
        return $plain;
    }
    return rtrim(mb_substr($plain, 0, $length - 1)) . '…';
}

function flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['_flash'][$key] = $value;
        return null;
    }
    $message = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $message;
}

function current_path(): string
{
    return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
}

function safe_return_url(?string $url, string $fallback = '/admin'): string
{
    if (!$url || !str_starts_with($url, '/') || str_starts_with($url, '//')) {
        return $fallback;
    }
    return $url;
}
