<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\View;
use App\Services\ActivityLogger;
use App\Services\UploadService;

final class SettingsController
{
    private const FIELDS = [
        'school_name' => 'Nama resmi sekolah',
        'school_short_name' => 'Nama singkat',
        'school_address' => 'Alamat sekolah',
        'founded_year' => 'Tahun berdiri',
        'school_vision' => 'Visi sekolah',
        'school_mission' => 'Misi sekolah',
        'school_history' => 'Sejarah singkat',
        'headmaster_name' => 'Kepala sekolah',
        'school_facilities' => 'Fasilitas sekolah',
        'school_recognitions' => 'Pengakuan/prestasi kelembagaan',
        'hero_title' => 'Judul utama beranda',
        'hero_description' => 'Deskripsi hero',
        'hero_image' => 'Foto utama beranda',
        'logo_path' => 'Logo sekolah',
        'site_description' => 'Deskripsi website',
        'footer_note' => 'Catatan footer',
        'contact_email' => 'Email umum sekolah',
        'contact_phone' => 'Telepon sekolah',
        'ppid_email' => 'Email layanan informasi/PPID',
        'school_website' => 'Alamat website resmi',
        'social_instagram' => 'Instagram',
        'social_facebook' => 'Facebook',
        'social_youtube' => 'YouTube',
    ];

    private const LOGIN_THEMES = [
        'midnight' => 'Midnight Blue & Champagne',
        'royal' => 'Royal Blue & Gold',
        'electric' => 'Electric Blue & Silver',
        'emerald' => 'Emerald & Champagne',
        'violet' => 'Violet & Champagne',
        'graphite' => 'Graphite & Electric Blue',
        'champagne' => 'Deep Navy & Champagne Gold',
        'ocean' => 'Ocean Teal & Pearl',
        'sapphire' => 'Sapphire Blue & Platinum',
        'arctic' => 'Arctic Blue & Silver',
        'cobalt' => 'Cobalt Blue & Gold',
        'lagoon' => 'Lagoon Teal & Ivory',
        'jade' => 'Jade Emerald & Champagne',
        'forest' => 'Forest Green & Sand',
        'amethyst' => 'Amethyst Violet & Silver',
        'plum' => 'Plum Orchid & Rose Gold',
        'ruby' => 'Ruby Red & Blush Gold',
        'rose' => 'Rose Quartz & Pearl',
        'copper' => 'Copper Ember & Champagne',
        'bronze' => 'Bronze Heritage & Ivory',
        'obsidian' => 'Obsidian Steel & Ice',
        'silver' => 'Silver Graphite & Blue',
        'custom' => 'Custom Palette',
    ];

    private const DEFAULT_LOGIN_COLORS = [
        'accent' => '#2878F0',
        'gold' => '#D8BD83',
        'base' => '#09162D',
    ];

    public function index(): void
    {
        $user = Auth::requireRole(['super_admin']);
        $settings = [];
        foreach (Database::pdo()->query('SELECT setting_key,setting_value FROM settings')->fetchAll() as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        $decoded = json_decode($settings['login_backgrounds'] ?? '[]', true);
        $loginBackgrounds = is_array($decoded) ? array_values(array_filter($decoded, static function ($item): bool {
            return is_array($item)
                && isset($item['id'], $item['path'], $item['label'], $item['theme'])
                && preg_match('~^uploads/[a-f0-9]{36}\.(jpg|png|webp)$~i', (string) $item['path']) === 1
                && isset(self::LOGIN_THEMES[$item['theme']]);
        })) : [];
        foreach ($loginBackgrounds as &$background) {
            $background['colors'] = self::normalizeLoginColors($background['colors'] ?? []);
        }
        unset($background);

        View::admin('admin/settings', [
            'user' => $user,
            'pageTitle' => 'Identitas dan pengaturan website',
            'settings' => $settings,
            'fields' => self::FIELDS,
            'loginBackgrounds' => $loginBackgrounds,
            'loginThemes' => self::LOGIN_THEMES,
        ]);
    }

    public function save(): void
    {
        $user = Auth::requireRole(['super_admin']);
        Csrf::enforce();
        $stmt = Database::pdo()->prepare('INSERT INTO settings (setting_key,setting_value,updated_at) VALUES (:k,:v,NOW()) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value),updated_at=NOW()');
        foreach (self::FIELDS as $key => $label) {
            $value = trim((string) ($_POST[$key] ?? ''));
            if (str_contains($key, 'email') && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                flash('error', 'Format ' . $label . ' tidak valid.');
                redirect('/admin/pengaturan');
            }
            if ($key === 'school_website' && $value !== '') {
                $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));
                if (!filter_var($value, FILTER_VALIDATE_URL) || !in_array($scheme, ['http', 'https'], true)) {
                    flash('error', 'Alamat website resmi harus berupa URL yang diawali http:// atau https://.');
                    redirect('/admin/pengaturan');
                }
            }
            if (in_array($key, ['hero_image', 'logo_path'], true) && $value !== '' && !preg_match('~^uploads/[a-f0-9]{36}\.(jpg|png|webp)$~i', $value)) {
                flash('error', 'Pilih jalur logo atau foto utama dari file yang telah diunggah melalui Pustaka Media.');
                redirect('/admin/pengaturan');
            }
            $stmt->execute(['k' => $key, 'v' => $value]);
        }
        $pdo = Database::pdo();
        $backgroundStmt = $pdo->prepare('SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1');
        $backgroundStmt->execute(['key' => 'login_backgrounds']);
        $current = json_decode((string) ($backgroundStmt->fetchColumn() ?: '[]'), true);
        $backgrounds = is_array($current) ? $current : [];

        $removeIds = array_values(array_unique(array_filter(array_map('intval', (array) ($_POST['remove_login_backgrounds'] ?? [])), static fn (int $id): bool => $id > 0)));
        if ($removeIds !== []) {
            $backgrounds = array_values(array_filter($backgrounds, static fn ($item): bool => is_array($item) && !in_array((int) ($item['id'] ?? 0), $removeIds, true)));
        }

        $themeUpdates = (array) ($_POST['login_background_themes'] ?? []);
        $colorUpdates = (array) ($_POST['login_background_colors'] ?? []);
        foreach ($backgrounds as &$background) {
            if (!is_array($background)) {
                continue;
            }
            $backgroundId = (string) ($background['id'] ?? '');
            $updatedTheme = (string) ($themeUpdates[$backgroundId] ?? $background['theme'] ?? 'midnight');
            if (isset(self::LOGIN_THEMES[$updatedTheme])) {
                $background['theme'] = $updatedTheme;
            }
            if ($updatedTheme === 'custom') {
                $background['colors'] = self::normalizeLoginColors($colorUpdates[$backgroundId] ?? $background['colors'] ?? []);
            }
        }
        unset($background);

        $theme = (string) ($_POST['login_background_theme'] ?? 'midnight');
        if (!isset(self::LOGIN_THEMES[$theme])) {
            flash('error', 'Tema background login tidak valid.');
            redirect('/admin/pengaturan');
        }

        $newColors = self::normalizeLoginColors($_POST['login_new_colors'] ?? []);
        $uploadErrors = [];
        $files = $_FILES['login_background_files'] ?? null;
        if (is_array($files) && isset($files['name']) && is_array($files['name'])) {
            $count = count($files['name']);
            if ($count > 5) {
                $uploadErrors[] = 'Maksimal 5 background dapat diunggah dalam satu kali pengiriman.';
                $count = 5;
            }
            for ($i = 0; $i < $count; $i++) {
                $errorCode = (int) ($files['error'][$i] ?? UPLOAD_ERR_NO_FILE);
                if ($errorCode === UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                $file = [
                    'name' => $files['name'][$i] ?? '',
                    'type' => $files['type'][$i] ?? '',
                    'tmp_name' => $files['tmp_name'][$i] ?? '',
                    'error' => $errorCode,
                    'size' => $files['size'][$i] ?? 0,
                ];
                try {
                    $mediaId = UploadService::image($file, (int) $user['id'], 'Background login administrator', false);
                    $mediaQuery = $pdo->prepare('SELECT file_path, original_name FROM media WHERE id = :id LIMIT 1');
                    $mediaQuery->execute(['id' => $mediaId]);
                    $media = $mediaQuery->fetch();
                    if (!$media || !preg_match('~^uploads/[a-f0-9]{36}\.(jpg|png|webp)$~i', (string) $media['file_path'])) {
                        throw new \RuntimeException('Metadata gambar background gagal disimpan.');
                    }
                    $backgrounds[] = [
                        'id' => $mediaId,
                        'path' => $media['file_path'],
                        'label' => substr((string) $media['original_name'], 0, 160),
                        'theme' => $theme,
                        'colors' => $theme === 'custom' ? $newColors : null,
                    ];
                } catch (\Throwable $exception) {
                    $uploadErrors[] = (string) ($files['name'][$i] ?? 'Gambar') . ': ' . $exception->getMessage();
                }
            }
        }

        $upsert = $pdo->prepare('INSERT INTO settings (setting_key,setting_value,updated_at) VALUES (:key,:value,NOW()) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value),updated_at=NOW()');
        $upsert->execute(['key' => 'login_backgrounds', 'value' => json_encode(array_values($backgrounds), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)]);
        $upsert->execute(['key' => 'login_background_enabled', 'value' => isset($_POST['login_background_enabled']) ? '1' : '0']);

        ActivityLogger::record('UPDATE', 'settings', null, 'Memperbarui identitas website dan tema login administrator');
        if ($uploadErrors !== []) {
            flash('error', 'Pengaturan tersimpan, tetapi beberapa background tidak dapat diunggah: ' . implode(' | ', array_slice($uploadErrors, 0, 3)));
        } else {
            flash('success', 'Pengaturan website dan tampilan login berhasil diperbarui.');
        }
        redirect('/admin/pengaturan');
    }

    private static function normalizeLoginColors(mixed $colors): array
    {
        $colors = is_array($colors) ? $colors : [];
        $normalized = [];
        foreach (self::DEFAULT_LOGIN_COLORS as $key => $default) {
            $value = strtoupper(trim((string) ($colors[$key] ?? $default)));
            $normalized[$key] = preg_match('/^#[0-9A-F]{6}$/', $value) === 1 ? $value : $default;
        }
        return $normalized;
    }
}
