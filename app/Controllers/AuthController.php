<?php

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\Database;
use App\Core\Auth;
use App\Services\ActivityLogger;

final class AuthController
{
    public function login(): void
    {
        if (Auth::check()) {
            redirect('/admin');
        }
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        $settings = [];
        foreach (Database::pdo()->query('SELECT setting_key, setting_value FROM settings WHERE setting_key IN (\'login_backgrounds\', \'login_background_enabled\', \'school_name\', \'school_vision\', \'school_mission\', \'logo_path\')')->fetchAll() as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        $loginBackground = null;
        if (($settings['login_background_enabled'] ?? '0') === '1') {
            $decoded = json_decode($settings['login_backgrounds'] ?? '[]', true);
            $backgrounds = is_array($decoded) ? array_values(array_filter($decoded, static function ($item): bool {
                return is_array($item)
                    && isset($item['id'], $item['path'], $item['theme'])
                    && preg_match('~^uploads/[a-f0-9]{36}\.(jpg|png|webp)$~i', (string) $item['path']) === 1
                    && in_array($item['theme'], ['midnight', 'royal', 'electric', 'emerald', 'violet', 'graphite', 'champagne', 'ocean', 'sapphire', 'arctic', 'cobalt', 'lagoon', 'jade', 'forest', 'amethyst', 'plum', 'ruby', 'rose', 'copper', 'bronze', 'obsidian', 'silver', 'custom'], true);
            })) : [];

            if ($backgrounds !== []) {
                $previousId = (string) ($_SESSION['login_background_last_id'] ?? '');
                $candidates = count($backgrounds) > 1
                    ? array_values(array_filter($backgrounds, static fn (array $item): bool => (string) $item['id'] !== $previousId))
                    : $backgrounds;
                if ($candidates === []) {
                    $candidates = $backgrounds;
                }
                $loginBackground = $candidates[random_int(0, count($candidates) - 1)];
                $_SESSION['login_background_last_id'] = (string) $loginBackground['id'];
            }
        }

        if (($loginBackground['theme'] ?? '') === 'custom') {
            $_SESSION['login_theme_colors'] = self::normalizeLoginColors($loginBackground['colors'] ?? []);
        } else {
            unset($_SESSION['login_theme_colors']);
        }

        $schoolName = trim((string) ($settings['school_name'] ?? '')) ?: 'SMA Negeri 9 Bandar Lampung';
        $schoolVision = trim((string) ($settings['school_vision'] ?? ''));
        $schoolMission = trim((string) ($settings['school_mission'] ?? ''));
        $schoolLogo = (string) ($settings['logo_path'] ?? '');
        if ($schoolLogo !== '' && preg_match('~^uploads/[a-f0-9]{36}\.(jpg|png|webp)$~i', $schoolLogo) !== 1) {
            $schoolLogo = '';
        }

        echo \App\Core\View::render('auth/login', [
            'error' => flash('error'),
            'installed' => isset($_GET['installed']),
            'loginBackground' => $loginBackground,
            'loginTheme' => $loginBackground['theme'] ?? 'midnight',
            'schoolName' => $schoolName,
            'schoolVision' => $schoolVision,
            'schoolMission' => $schoolMission,
            'schoolLogo' => $schoolLogo,
        ]);
    }

    public function themeCss(): void
    {
        header('Content-Type: text/css; charset=UTF-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('X-Content-Type-Options: nosniff');

        $colors = self::normalizeLoginColors($_SESSION['login_theme_colors'] ?? []);
        $backgroundId = filter_var($_GET['background'] ?? null, FILTER_VALIDATE_INT);
        if (is_int($backgroundId) && $backgroundId > 0) {
            $statement = Database::pdo()->prepare('SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1');
            $statement->execute(['key' => 'login_backgrounds']);
            $storedBackgrounds = json_decode((string) ($statement->fetchColumn() ?: '[]'), true);
            if (is_array($storedBackgrounds)) {
                foreach ($storedBackgrounds as $storedBackground) {
                    if (is_array($storedBackground)
                        && (int) ($storedBackground['id'] ?? 0) === $backgroundId
                        && ($storedBackground['theme'] ?? '') === 'custom') {
                        $colors = self::normalizeLoginColors($storedBackground['colors'] ?? []);
                        break;
                    }
                }
            }
        }
        $accentRgb = self::hexToRgb($colors['accent']);
        $goldRgb = self::hexToRgb($colors['gold']);
        $baseRgb = self::hexToRgb($colors['base']);

        echo '.login-theme-custom{'
            . '--login-accent:' . $colors['accent'] . ';'
            . '--login-accent-rgb:' . $accentRgb . ';'
            . '--login-gold:' . $colors['gold'] . ';'
            . '--login-gold-rgb:' . $goldRgb . ';'
            . '--login-brand-color:' . $colors['accent'] . ';'
            . '--login-base:' . $colors['base'] . ';'
            . '--login-base-rgb:' . $baseRgb . ';'
            . '--login-overlay:rgba(' . $baseRgb . ',.82);'
            . '--login-panel-start:rgba(' . $baseRgb . ',.91);'
            . '--login-panel-end:rgba(' . $baseRgb . ',.80);'
            . '}';
        echo '.login-theme-custom .login-background{background:radial-gradient(circle at 18% 12%,rgba(' . $accentRgb . ',.35),transparent 37%),linear-gradient(135deg,' . $colors['base'] . ',' . $colors['base'] . ' 60%,rgba(' . $baseRgb . ',.94))}';
        echo '.login-theme-custom .login-background-overlay{background:linear-gradient(120deg,rgba(' . $baseRgb . ',.84),rgba(' . $baseRgb . ',.58)),radial-gradient(circle at 15% 10%,rgba(' . $accentRgb . ',.34),transparent 38%),radial-gradient(circle at 90% 90%,rgba(' . $goldRgb . ',.18),transparent 31%)}';
        echo '.login-theme-custom .login-panel{background:linear-gradient(145deg,var(--login-panel-start),var(--login-panel-end))}';
        echo '.login-theme-custom .login-panel:after{background:linear-gradient(135deg,rgba(' . $accentRgb . ',.14),transparent 38%,rgba(' . $goldRgb . ',.09))}';
        echo '.login-theme-custom .login-submit{background-color:rgba(var(--login-accent-rgb),.15)!important;background-image:none!important;border:1px solid rgba(var(--login-accent-rgb),.55)!important;box-shadow:inset 0 1px 0 rgba(255,255,255,.22),inset 0 -1px 0 rgba(var(--login-base-rgb),.24),0 8px 22px rgba(var(--login-accent-rgb),.14),0 0 0 1px rgba(255,255,255,.035);-webkit-backdrop-filter:blur(18px) saturate(150%);backdrop-filter:blur(18px) saturate(150%)}';
        echo '.login-theme-custom .login-submit:hover{background-color:rgba(var(--login-accent-rgb),.29)!important;background-image:none!important;border-color:rgba(var(--login-accent-rgb),.92)!important;box-shadow:inset 0 1px 0 rgba(255,255,255,.32),inset 0 0 18px rgba(var(--login-accent-rgb),.12),0 13px 29px rgba(var(--login-accent-rgb),.27),0 0 0 1px rgba(var(--login-accent-rgb),.17)}';
        exit;
    }

    private static function normalizeLoginColors(mixed $colors): array
    {
        $defaults = ['accent' => '#2878F0', 'gold' => '#D8BD83', 'base' => '#09162D'];
        $colors = is_array($colors) ? $colors : [];
        $result = [];
        foreach ($defaults as $key => $default) {
            $value = strtoupper(trim((string) ($colors[$key] ?? $default)));
            $result[$key] = preg_match('/^#[0-9A-F]{6}$/', $value) === 1 ? $value : $default;
        }
        return $result;
    }

    private static function hexToRgb(string $hex): string
    {
        return hexdec(substr($hex, 1, 2)) . ',' . hexdec(substr($hex, 3, 2)) . ',' . hexdec(substr($hex, 5, 2));
    }

    public function authenticate(): void
    {
        Csrf::enforce();
        $identifier = trim((string) ($_POST['identifier'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $ip = substr((string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'), 0, 45);

        if ($identifier === '' || strlen($identifier) > 190 || $password === '') {
            flash('error', 'Nama pengguna/email atau kata sandi tidak sesuai.');
            redirect('/admin/login');
        }

        $attempts = Database::pdo()->prepare('SELECT COUNT(*) FROM login_attempts WHERE email = :email AND ip_address = :ip AND attempted_at >= (NOW() - INTERVAL 15 MINUTE)');
        $attempts->execute(['email' => strtolower($identifier), 'ip' => $ip]);
        if ((int) $attempts->fetchColumn() >= 8) {
            flash('error', 'Terlalu banyak percobaan login. Silakan coba kembali setelah 15 menit.');
            redirect('/admin/login');
        }

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $stmt = Database::pdo()->prepare('SELECT id,name,email,password_hash,role,is_active FROM users WHERE email = :identifier LIMIT 1');
            $stmt->execute(['identifier' => strtolower($identifier)]);
            $user = $stmt->fetch();
        } else {
            $stmt = Database::pdo()->prepare('SELECT id,name,email,password_hash,role,is_active FROM users WHERE name = :identifier LIMIT 2');
            $stmt->execute(['identifier' => $identifier]);
            $matches = $stmt->fetchAll();
            $user = count($matches) === 1 ? $matches[0] : false;
        }
        if (!$user || (int) $user['is_active'] !== 1 || !password_verify($password, $user['password_hash'])) {
            $insert = Database::pdo()->prepare('INSERT INTO login_attempts (email,ip_address,attempted_at) VALUES (:email,:ip,NOW())');
            $insert->execute(['email' => strtolower($identifier), 'ip' => $ip]);
            flash('error', 'Nama pengguna/email atau kata sandi tidak sesuai.');
            redirect('/admin/login');
        }

        Database::pdo()->prepare('DELETE FROM login_attempts WHERE email = :email AND ip_address = :ip')->execute(['email' => strtolower($identifier), 'ip' => $ip]);
        Database::pdo()->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id')->execute(['id' => $user['id']]);
        Auth::login((int) $user['id']);
        ActivityLogger::record('LOGIN', 'users', (int) $user['id'], 'Administrator masuk ke CMS');
        redirect('/admin');
    }

    public function logout(): void
    {
        Csrf::enforce();
        $user = Auth::user();
        if ($user) {
            ActivityLogger::record('LOGOUT', 'users', (int) $user['id'], 'Administrator keluar dari CMS');
        }
        Auth::logout();
        redirect('/admin/login');
    }
}
