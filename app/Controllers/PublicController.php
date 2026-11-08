<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;
use App\Services\ContentService;

final class PublicController
{
    private function base(array $data = []): array
    {
        return array_merge([
            'schoolName' => setting('school_name', 'SMA Negeri 9 Bandar Lampung'),
            'schoolShortName' => setting('school_short_name', 'SMALAN'),
            'description' => setting('site_description', 'Portal informasi resmi SMA Negeri 9 Bandar Lampung.'),
            'canonical' => $this->absoluteUrl(current_path()),
            'activeNav' => '',
        ], $data);
    }

    private function absoluteUrl(string $path): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = preg_replace('/[^a-zA-Z0-9.\-:]/', '', (string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
        return $scheme . '://' . $host . $path;
    }

    public function home(): void
    {
        $news = ContentService::listPublished('news', 5);
        $announcements = ContentService::listPublished('announcement', 4);
        $eventQuery = Database::pdo()->query("SELECT c.*, m.file_path AS image_path, m.alt_text AS image_alt FROM contents c LEFT JOIN media m ON m.id = c.featured_image_id WHERE c.type='event' AND c.status='published' AND c.event_date >= CURDATE() AND (c.publish_at IS NULL OR c.publish_at <= NOW()) ORDER BY c.event_date ASC, c.event_time ASC LIMIT 4");
        $events = $eventQuery->fetchAll();
        $achievements = ContentService::listPublished('achievement', 3);
        $gallery = Database::pdo()->query('SELECT id,file_path,alt_text,original_name FROM media WHERE is_gallery=1 ORDER BY created_at DESC LIMIT 6')->fetchAll();
        $featured = ContentService::featuredNews(1);
        View::public('public/home', $this->base([
            'title' => setting('hero_title', 'SMA Negeri 9 Bandar Lampung'),
            'metaDescription' => setting('hero_description', setting('site_description')),
            'activeNav' => 'home',
            'news' => $news,
            'announcements' => $announcements,
            'events' => $events,
            'achievements' => $achievements,
            'gallery' => $gallery,
            'featured' => $featured[0] ?? ($news[0] ?? null),
        ]));
    }

    public function profile(): void
    {
        View::public('public/profile', $this->base([
            'title' => 'Profil Sekolah', 'activeNav' => 'profil',
            'metaDescription' => 'Profil, sejarah, visi, misi, dan identitas SMA Negeri 9 Bandar Lampung.',
        ]));
    }

    public function informationPage(string $slug): void
    {
        $map = [
            'akademik' => ['Akademik', 'Informasi program pembelajaran, kegiatan akademik, dan informasi pendidikan sekolah.'],
            'kesiswaan' => ['Kesiswaan', 'Informasi kegiatan peserta didik, organisasi, pengembangan karakter, dan ekstrakurikuler.'],
            'layanan-publik' => ['Layanan Publik / PPID', 'Informasi pelayanan publik dan kanal komunikasi resmi sekolah.'],
            'spmb' => ['SPMB', 'Informasi Sistem Penerimaan Murid Baru SMA Negeri 9 Bandar Lampung.'],
            'kontak' => ['Kontak', 'Alamat dan kanal komunikasi SMA Negeri 9 Bandar Lampung.'],
            'kebijakan-privasi' => ['Kebijakan Privasi', 'Informasi mengenai penggunaan dan perlindungan data saat mengakses website sekolah.'],
        ];
        if (!isset($map[$slug])) {
            http_response_code(404);
            View::public('errors/404', $this->base(['title' => 'Halaman tidak ditemukan']));
            return;
        }
        [$title, $description] = $map[$slug];
        $page = null;
        $stmt = Database::pdo()->prepare("SELECT * FROM contents WHERE type='page' AND slug=:slug AND status='published' AND (publish_at IS NULL OR publish_at <= NOW()) LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $page = $stmt->fetch() ?: null;
        View::public('public/information', $this->base([
            'title' => $page['title'] ?? $title,
            'metaDescription' => $page['seo_description'] ?? $description,
            'activeNav' => $slug,
            'slug' => $slug,
            'page' => $page,
        ]));
    }

    public function listing(string $type): void
    {
        $names = [
            'news' => ['Berita Sekolah', 'berita'],
            'announcement' => ['Pengumuman', 'pengumuman'],
            'event' => ['Agenda Sekolah', 'agenda'],
            'achievement' => ['Prestasi', 'prestasi'],
        ];
        if (!isset($names[$type])) {
            http_response_code(404);
            View::public('errors/404', $this->base(['title' => 'Halaman tidak ditemukan']));
            return;
        }
        [$title, $nav] = $names[$type];
        $query = trim((string) ($_GET['q'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $items = ContentService::listPublished($type, 12, $page, $query);
        $total = ContentService::countPublished($type, $query);
        View::public('public/listing', $this->base([
            'title' => $title, 'activeNav' => $nav, 'type' => $type,
            'items' => $items, 'query' => $query, 'page' => $page,
            'pages' => max(1, (int) ceil($total / 12)), 'total' => $total,
            'metaDescription' => 'Informasi ' . strtolower($title) . ' SMA Negeri 9 Bandar Lampung.',
        ]));
    }

    public function detail(string $type, string $slug): void
    {
        $item = ContentService::findPublished($type, $slug);
        if (!$item) {
            http_response_code(404);
            View::public('errors/404', $this->base(['title' => 'Konten tidak ditemukan']));
            return;
        }
        $related = ContentService::listPublished($type, 3, 1);
        $related = array_values(array_filter($related, static fn ($row) => (int) $row['id'] !== (int) $item['id']));
        $active = ['news' => 'berita', 'announcement' => 'pengumuman', 'event' => 'agenda', 'achievement' => 'prestasi'][$type] ?? '';
        View::public('public/detail', $this->base([
            'title' => $item['seo_title'] ?: $item['title'],
            'metaDescription' => $item['seo_description'] ?: excerpt($item['excerpt'] ?: $item['body'], 155),
            'canonical' => $this->absoluteUrl(content_url($type, $slug)),
            'activeNav' => $active,
            'item' => $item,
            'related' => $related,
        ]));
    }

    public function gallery(): void
    {
        $items = Database::pdo()->query('SELECT * FROM media WHERE is_gallery=1 ORDER BY created_at DESC')->fetchAll();
        View::public('public/gallery', $this->base(['title' => 'Galeri Sekolah', 'activeNav' => 'galeri', 'items' => $items]));
    }


    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = preg_replace('/[^a-zA-Z0-9.\-:]/', '', (string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
        echo "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /install\nSitemap: {$scheme}://{$host}/sitemap.xml\n";
    }

    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        header('Cache-Control: public, max-age=900');
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = preg_replace('/[^a-zA-Z0-9.\\-:]/', '', (string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
        $base = $scheme . '://' . $host;
        $urls = ['/', '/profil', '/akademik', '/kesiswaan', '/berita', '/pengumuman', '/agenda', '/prestasi', '/galeri', '/layanan-publik', '/spmb', '/kontak'];
        $items = Database::pdo()->query("SELECT type,slug FROM contents WHERE status='published' AND (publish_at IS NULL OR publish_at <= NOW())")->fetchAll();
        foreach ($items as $item) {
            $urls[] = content_url($item['type'], $item['slug']);
        }
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach (array_unique($urls) as $url) {
            echo '<url><loc>' . htmlspecialchars($base . $url, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</loc></url>';
        }
        echo '</urlset>';
    }

    public function search(): void
    {
        $query = trim((string) ($_GET['q'] ?? ''));
        $results = [];
        if ($query !== '' && mb_strlen($query) <= 100) {
            $stmt = Database::pdo()->prepare("SELECT type,title,slug,excerpt,publish_at,created_at FROM contents WHERE status='published' AND (publish_at IS NULL OR publish_at <= NOW()) AND (title LIKE :title_q OR excerpt LIKE :excerpt_q OR body LIKE :body_q) ORDER BY COALESCE(publish_at,created_at) DESC LIMIT 30");
            $search = '%' . $query . '%';
            $stmt->execute(['title_q' => $search, 'excerpt_q' => $search, 'body_q' => $search]);
            $results = $stmt->fetchAll();
        }
        View::public('public/search', $this->base(['title' => 'Pencarian', 'query' => $query, 'results' => $results]));
    }
}
