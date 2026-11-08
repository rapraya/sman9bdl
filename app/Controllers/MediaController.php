<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\View;
use App\Services\ActivityLogger;
use App\Services\UploadService;

final class MediaController
{
    public function index(): void
    {
        $user = Auth::requireRole(['super_admin','editor','operator','viewer']);
        $items = Database::pdo()->query('SELECT m.*,u.name AS uploader FROM media m LEFT JOIN users u ON u.id=m.uploaded_by ORDER BY m.created_at DESC LIMIT 180')->fetchAll();
        View::admin('admin/media', ['user' => $user, 'pageTitle' => 'Pustaka media', 'items' => $items, 'canEdit' => $user['role'] !== 'viewer']);
    }

    public function upload(): void
    {
        $user = Auth::requireRole(['super_admin','editor','operator']);
        Csrf::enforce();
        try {
            $id = UploadService::image($_FILES['image'] ?? [], (int) $user['id'], (string) ($_POST['alt_text'] ?? ''), !empty($_POST['is_gallery']));
            ActivityLogger::record('UPLOAD', 'media', $id, 'Mengunggah gambar ke pustaka media');
            flash('success', 'Gambar berhasil diunggah.');
        } catch (\Throwable $exception) {
            flash('error', $exception->getMessage());
        }
        redirect('/admin/media');
    }

    public function delete(string $id): void
    {
        Auth::requireRole(['super_admin','editor']);
        Csrf::enforce();
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT * FROM media WHERE id=:id');
        $stmt->execute(['id' => (int) $id]);
        $media = $stmt->fetch();
        if ($media) {
            $used = $pdo->prepare('SELECT COUNT(*) FROM contents WHERE featured_image_id=:id');
            $used->execute(['id' => $id]);
            $backgroundSetting = $pdo->prepare('SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1');
            $backgroundSetting->execute(['key' => 'login_backgrounds']);
            $backgroundRows = json_decode((string) ($backgroundSetting->fetchColumn() ?: '[]'), true);
            $isLoginBackground = is_array($backgroundRows) && (bool) array_filter($backgroundRows, static fn ($item): bool => is_array($item) && (int) ($item['id'] ?? 0) === (int) $id);
            if ((int) $used->fetchColumn() > 0) {
                flash('error', 'Gambar masih digunakan sebagai gambar utama konten. Lepaskan dari konten terlebih dahulu.');
            } elseif ($isLoginBackground) {
                flash('error', 'Gambar masih digunakan sebagai background login administrator. Keluarkan dari rotasi melalui Identitas & Pengaturan Website terlebih dahulu.');
            } else {
                $path = BASE_PATH . '/public/' . $media['file_path'];
                $pdo->prepare('DELETE FROM media WHERE id=:id')->execute(['id' => $id]);
                if (is_file($path)) {
                    @unlink($path);
                }
                ActivityLogger::record('DELETE', 'media', (int) $id, 'Menghapus media ' . $media['original_name']);
                flash('success', 'Media berhasil dihapus.');
            }
        }
        redirect('/admin/media');
    }

    public function toggleGallery(string $id): void
    {
        Auth::requireRole(['super_admin','editor','operator']);
        Csrf::enforce();
        $stmt = Database::pdo()->prepare('UPDATE media SET is_gallery = IF(is_gallery=1,0,1) WHERE id=:id');
        $stmt->execute(['id' => (int) $id]);
        flash('success', 'Status galeri diperbarui.');
        redirect('/admin/media');
    }
}
