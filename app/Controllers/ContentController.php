<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\View;
use App\Services\ActivityLogger;
use App\Services\ContentService;

final class ContentController
{
    private const TITLES = [
        'news' => 'Berita',
        'announcement' => 'Pengumuman',
        'event' => 'Agenda',
        'achievement' => 'Prestasi',
        'page' => 'Halaman informasi',
    ];

    public function index(): void
    {
        $user = Auth::requireRole(['super_admin','editor','operator','viewer']);
        $type = (string) ($_GET['type'] ?? 'news');
        $type = in_array($type, ContentService::allowedTypes(), true) ? $type : 'news';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $query = trim((string) ($_GET['q'] ?? ''));
        $data = ContentService::adminList($type, $page, $query);
        View::admin('admin/content-list', [
            'user' => $user, 'pageTitle' => self::TITLES[$type], 'type' => $type,
            'items' => $data['items'], 'total' => $data['total'], 'pages' => max(1, (int) ceil($data['total'] / 12)),
            'page' => $page, 'query' => $query, 'canEdit' => $user['role'] !== 'viewer',
        ]);
    }

    public function create(): void
    {
        $user = Auth::requireRole(['super_admin','editor','operator']);
        $type = (string) ($_GET['type'] ?? 'news');
        $type = in_array($type, ContentService::allowedTypes(), true) ? $type : 'news';
        $this->form($user, $type, null);
    }

    public function edit(string $id): void
    {
        $user = Auth::requireRole(['super_admin','editor','operator']);
        $item = ContentService::find((int) $id);
        if (!$item) {
            flash('error', 'Konten tidak ditemukan.');
            redirect('/admin/konten');
        }
        $this->form($user, $item['type'], $item);
    }

    private function form(array $user, string $type, ?array $item): void
    {
        $media = Database::pdo()->query('SELECT id,file_path,alt_text,original_name FROM media ORDER BY created_at DESC LIMIT 150')->fetchAll();
        View::admin('admin/content-form', [
            'user' => $user, 'pageTitle' => ($item ? 'Ubah ' : 'Tambah ') . (self::TITLES[$type] ?? 'Konten'),
            'type' => $type, 'item' => $item, 'media' => $media,
        ]);
    }

    public function save(): void
    {
        $user = Auth::requireRole(['super_admin','editor','operator']);
        Csrf::enforce();
        try {
            $id = (int) ($_POST['id'] ?? 0) ?: null;
            $savedId = ContentService::save($_POST, (int) $user['id'], $id);
            ActivityLogger::record($id ? 'UPDATE' : 'CREATE', 'contents', $savedId, ($id ? 'Mengubah ' : 'Menambahkan ') . (self::TITLES[$_POST['type'] ?? 'news'] ?? 'konten'));
            flash('success', 'Konten berhasil disimpan.');
            redirect('/admin/konten?type=' . rawurlencode((string) ($_POST['type'] ?? 'news')));
        } catch (\Throwable $exception) {
            flash('error', $exception->getMessage());
            $id = (int) ($_POST['id'] ?? 0);
            redirect($id ? '/admin/konten/' . $id . '/edit' : '/admin/konten/tambah?type=' . rawurlencode((string) ($_POST['type'] ?? 'news')));
        }
    }

    public function delete(string $id): void
    {
        $user = Auth::requireRole(['super_admin','editor']);
        Csrf::enforce();
        $item = ContentService::find((int) $id);
        if ($item) {
            ContentService::delete((int) $id);
            ActivityLogger::record('DELETE', 'contents', (int) $id, 'Menghapus konten: ' . $item['title']);
            flash('success', 'Konten berhasil dihapus.');
        }
        redirect('/admin/konten?type=' . rawurlencode((string) ($item['type'] ?? 'news')));
    }
}
