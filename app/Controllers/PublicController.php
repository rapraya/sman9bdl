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

    private function pagePresentation(string $key, array $heroDefaults, array $introDefaults, array $breadcrumbs = [], array $introMeta = []): array
    {
        $hero = [];
        foreach (['eyebrow', 'title', 'subtitle', 'icon', 'watermark'] as $field) {
            $hero[$field] = trim(setting('page_hero_' . $key . '_' . $field, (string) ($heroDefaults[$field] ?? '')));
        }
        $hero['breadcrumbs'] = $breadcrumbs;
        $hero['caption'] = setting('school_short_name', 'SMALAN');
        $intro = [];
        foreach (['eyebrow', 'title', 'description', 'meta_value', 'meta_label'] as $field) {
            $intro[$field] = trim(setting('page_intro_' . $key . '_' . $field, (string) ($introDefaults[$field] ?? '')));
        }
        return ['pageHero' => $hero, 'pageIntro' => array_merge($intro, $introMeta)];
    }

    private function homeHeroStats(): array
    {
        $definitions = [
            ['key' => 'rombel', 'setting' => 'school_rombel_count', 'label' => 'Rombel', 'symbol' => '▦', 'numeric' => true],
            ['key' => 'students', 'setting' => 'school_students_count', 'label' => 'Peserta Didik', 'symbol' => '♙', 'numeric' => true],
            // Guru and PTK are connected to verified personnel records in PHASE 4.
            ['key' => 'accreditation', 'setting' => 'school_accreditation', 'label' => 'Akreditasi', 'symbol' => 'A', 'numeric' => false],
            ['key' => 'npsn', 'setting' => 'school_npsn', 'label' => 'NPSN', 'symbol' => '#', 'numeric' => false],
        ];
        $stats = [];
        foreach ($definitions as $definition) {
            $value = trim(setting($definition['setting']));
            if ($value === '') continue;
            if ($definition['numeric']) {
                if (!ctype_digit($value)) continue;
                $value = number_format((int) $value, 0, ',', '.');
            }
            $stats[] = ['key' => $definition['key'], 'label' => $definition['label'], 'symbol' => $definition['symbol'], 'value' => $value];
        }
        return $stats;
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
            'homeHeroStats' => $this->homeHeroStats(),
        ]));
    }

    public function profile(): void
    {
        $schoolName = trim(setting('school_name', 'SMA Negeri 9 Bandar Lampung'));
        $presentation = $this->pagePresentation('profil',
            ['eyebrow'=>'PROFIL SEKOLAH','title'=>'Profil Sekolah','subtitle'=>'Kenali identitas, sejarah, visi, misi, dan lingkungan SMA Negeri 9 Bandar Lampung.','icon'=>'school','watermark'=>'PROFIL'],
            ['eyebrow'=>'SEKILAS SMALAN','title'=>$schoolName,'description'=>'Informasi resmi sekolah, identitas, dan layanan utama yang dikelola melalui website sekolah.'],
            [['label'=>'Beranda','url'=>'/'],['label'=>'Profil','url'=>'']]
        );
        View::public('public/profile', array_merge($this->base([
            'title'=>'Profil Sekolah','activeNav'=>'profil',
            'metaDescription'=>'Profil, sejarah, visi, misi, dan identitas SMA Negeri 9 Bandar Lampung.',
        ]), $presentation));
    }

    public function informationPage(string $slug): void
    {
        $map = [
            'akademik'=>['Akademik','Informasi program pembelajaran, kegiatan akademik, dan informasi pendidikan sekolah.'],
            'kesiswaan'=>['Kesiswaan','Informasi kegiatan peserta didik, organisasi, pengembangan karakter, dan ekstrakurikuler.'],
            'layanan-publik'=>['Layanan Publik / PPID','Informasi pelayanan publik dan kanal komunikasi resmi sekolah.'],
            'spmb'=>['SPMB','Informasi Sistem Penerimaan Murid Baru SMA Negeri 9 Bandar Lampung.'],
            'kontak'=>['Kontak','Alamat dan kanal komunikasi SMA Negeri 9 Bandar Lampung.'],
            'kebijakan-privasi'=>['Kebijakan Privasi','Informasi mengenai penggunaan dan perlindungan data saat mengakses website sekolah.'],
        ];
        $visuals = [
            'akademik'=>['INFORMASI AKADEMIK','book','AKADEMIK','Informasi pembelajaran dan program akademik','Kanal informasi akademik sekolah.'],
            'kesiswaan'=>['INFORMASI KESISWAAN','users','KESISWAAN','Pengembangan dan kegiatan peserta didik','Informasi kegiatan dan layanan kesiswaan.'],
            'layanan-publik'=>['LAYANAN INFORMASI','file','LAYANAN','Akses informasi dan layanan publik sekolah','Kanal layanan informasi resmi sekolah.'],
            'spmb'=>['PENERIMAAN MURID BARU','school','SPMB','Informasi penerimaan murid baru','Informasi SPMB mengikuti ketentuan dan periode resmi.'],
            'kontak'=>['HUBUNGI SEKOLAH','phone','KONTAK','Kanal komunikasi resmi sekolah','Informasi alamat dan komunikasi sekolah.'],
            'kebijakan-privasi'=>['INFORMASI WEBSITE','shield','PRIVASI','Perlindungan informasi pengunjung website','Ketentuan privasi saat mengakses website sekolah.'],
        ];
        if (!isset($map[$slug])) {
            http_response_code(404);
            View::public('errors/404', $this->base(['title'=>'Halaman tidak ditemukan']));
            return;
        }
        [$title,$description] = $map[$slug];
        $stmt = Database::pdo()->prepare("SELECT * FROM contents WHERE type='page' AND slug=:slug AND status='published' AND (publish_at IS NULL OR publish_at <= NOW()) LIMIT 1");
        $stmt->execute(['slug'=>$slug]);
        $page = $stmt->fetch() ?: null;
        [$eyebrow,$icon,$watermark,$introTitle,$introDescription] = $visuals[$slug];
        $presentation = $this->pagePresentation($slug,
            ['eyebrow'=>$eyebrow,'title'=>$page['title'] ?? $title,'subtitle'=>excerpt((string) ($page['excerpt'] ?? $description),170),'icon'=>$icon,'watermark'=>$watermark],
            ['eyebrow'=>'INFORMASI SEKOLAH','title'=>$introTitle,'description'=>$page && trim((string) $page['excerpt']) !== '' ? (string) $page['excerpt'] : $introDescription],
            [['label'=>'Beranda','url'=>'/'],['label'=>$title,'url'=>'']]
        );
        View::public('public/information', array_merge($this->base([
            'title'=>$page['title'] ?? $title,
            'metaDescription'=>trim((string) ($page['seo_description'] ?? '')) !== '' ? $page['seo_description'] : $description,
            'activeNav'=>$slug,'slug'=>$slug,'page'=>$page,
        ]), $presentation));
    }

    public function listing(string $type): void
    {
        $names = [
            'news'=>['Berita Sekolah','berita','Berita','KANAL INFORMASI SEKOLAH','news','BERITA','Ikuti kabar terbaru dari sekolah.','Informasi, kegiatan, dan publikasi resmi dari lingkungan sekolah.'],
            'announcement'=>['Pengumuman','pengumuman','Pengumuman','INFORMASI RESMI','megaphone','PENGUMUMAN','Informasi penting dari sekolah.','Pengumuman yang telah diterbitkan melalui pengelolaan resmi website.'],
            'event'=>['Agenda Sekolah','agenda','Agenda','AGENDA SEKOLAH','calendar','AGENDA','Jadwal dan kegiatan sekolah.','Agenda kegiatan yang dipublikasikan oleh sekolah.'],
            'achievement'=>['Prestasi','prestasi','Prestasi','CAPAIAN SEKOLAH','trophy','PRESTASI','Apresiasi atas capaian sekolah.','Informasi prestasi yang telah diverifikasi dan diterbitkan.'],
        ];
        if (!isset($names[$type])) {
            http_response_code(404);
            View::public('errors/404', $this->base(['title'=>'Halaman tidak ditemukan']));
            return;
        }
        [$title,$nav,$heroTitle,$eyebrow,$icon,$watermark,$introTitle,$introDescription] = $names[$type];
        $query = trim((string) ($_GET['q'] ?? ''));
        $page = max(1,(int) ($_GET['page'] ?? 1));
        $items = ContentService::listPublished($type,12,$page,$query);
        $total = ContentService::countPublished($type,$query);
        $presentation = $this->pagePresentation($nav,
            ['eyebrow'=>$eyebrow,'title'=>$heroTitle,'subtitle'=>'Temukan ' . strtolower($title) . ' yang dipublikasikan secara resmi oleh SMA Negeri 9 Bandar Lampung.','icon'=>$icon,'watermark'=>$watermark],
            ['eyebrow'=>strtoupper($title) . ' SMALAN','title'=>$introTitle,'description'=>$introDescription],
            [['label'=>'Beranda','url'=>'/'],['label'=>'Publikasi','url'=>'/berita'],['label'=>$heroTitle,'url'=>'']],
            ['meta_value'=>number_format($total,0,',','.'),'meta_label'=>strtolower($title) . ' tersedia']
        );
        View::public('public/listing', array_merge($this->base([
            'title'=>$title,'activeNav'=>$nav,'type'=>$type,'items'=>$items,'query'=>$query,'page'=>$page,
            'pages'=>max(1,(int) ceil($total/12)),'total'=>$total,
            'metaDescription'=>'Informasi ' . strtolower($title) . ' SMA Negeri 9 Bandar Lampung.',
        ]), $presentation));
    }

    public function detail(string $type, string $slug): void
    {
        $item = ContentService::findPublished($type,$slug);
        if (!$item) {
            http_response_code(404);
            View::public('errors/404',$this->base(['title'=>'Konten tidak ditemukan']));
            return;
        }
        $related = ContentService::listPublished($type,3,1);
        $related = array_values(array_filter($related,static fn($row)=>(int)$row['id'] !== (int)$item['id']));
        $section = [
            'news'=>['Berita','/berita','news','BERITA'],
            'announcement'=>['Pengumuman','/pengumuman','megaphone','PENGUMUMAN'],
            'event'=>['Agenda','/agenda','calendar','AGENDA'],
            'achievement'=>['Prestasi','/prestasi','trophy','PRESTASI'],
            'page'=>['Informasi','/profil','file','INFORMASI'],
        ][$type] ?? ['Informasi','/','file','INFORMASI'];
        [$sectionLabel,$sectionUrl,$icon,$watermark] = $section;
        $publishedDate = format_date($item['publish_at'] ?: $item['created_at']);
        $introDescription = 'Dipublikasikan pada ' . $publishedDate . '.';
        if ($type === 'event' && !empty($item['event_date'])) {
            $introDescription .= ' Jadwal kegiatan: ' . format_date($item['event_date']);
            if (!empty($item['event_time'])) $introDescription .= ' pukul ' . $item['event_time'];
            if (!empty($item['event_location'])) $introDescription .= ', berlokasi di ' . $item['event_location'];
            $introDescription .= '.';
        }
        $presentation = $this->pagePresentation('detail',
            ['eyebrow'=>trim((string) ($item['category'] ?? '')) !== '' ? $item['category'] : strtoupper($sectionLabel),
             'title'=>$item['title'],'subtitle'=>excerpt((string) ($item['excerpt'] ?: $item['body']),170),'icon'=>$icon,'watermark'=>$watermark],
            ['eyebrow'=>'RINGKASAN PUBLIKASI','title'=>'Informasi resmi sekolah','description'=>$introDescription],
            [['label'=>'Beranda','url'=>'/'],['label'=>$sectionLabel,'url'=>$sectionUrl],['label'=>$item['title'],'url'=>'']],
            ['meta_value'=>$publishedDate,'meta_label'=>'tanggal publikasi']
        );
        $active = ['news'=>'berita','announcement'=>'pengumuman','event'=>'agenda','achievement'=>'prestasi'][$type] ?? '';
        View::public('public/detail',array_merge($this->base([
            'title'=>$item['seo_title'] ?: $item['title'],
            'metaDescription'=>$item['seo_description'] ?: excerpt($item['excerpt'] ?: $item['body'],155),
            'canonical'=>$this->absoluteUrl(content_url($type,$slug)),
            'activeNav'=>$active,'item'=>$item,'related'=>$related,
        ]),$presentation));
    }

    public function gallery(): void
    {
        $items = Database::pdo()->query('SELECT * FROM media WHERE is_gallery=1 ORDER BY created_at DESC')->fetchAll();
        $presentation = $this->pagePresentation('galeri',
            ['eyebrow'=>'DOKUMENTASI SEKOLAH','title'=>'Galeri Sekolah','subtitle'=>'Potret kegiatan dan lingkungan sekolah yang dipublikasikan secara resmi.','icon'=>'image','watermark'=>'GALERI'],
            ['eyebrow'=>'CERITA DALAM GAMBAR','title'=>'Dokumentasi kegiatan sekolah.','description'=>'Kumpulan foto yang dikelola dan diterbitkan melalui Pustaka Media website sekolah.'],
            [['label'=>'Beranda','url'=>'/'],['label'=>'Galeri','url'=>'']],
            ['meta_value'=>number_format(count($items),0,',','.'),'meta_label'=>'foto tersedia']
        );
        View::public('public/gallery',array_merge($this->base([
            'title'=>'Galeri Sekolah','activeNav'=>'galeri','items'=>$items,
        ]),$presentation));
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
        $urls = ['/', '/profil', '/akademik', '/kesiswaan', '/berita', '/pengumuman', '/agenda', '/prestasi', '/galeri', '/layanan-publik', '/spmb', '/kontak', '/kebijakan-privasi'];
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
            $stmt->execute(['title_q'=>$search,'excerpt_q'=>$search,'body_q'=>$search]);
            $results = $stmt->fetchAll();
        }
        $presentation = $this->pagePresentation('pencarian',
            ['eyebrow'=>'PENCARIAN WEBSITE','title'=>'Temukan informasi.','subtitle'=>'Gunakan kata kunci untuk menemukan informasi resmi SMA Negeri 9 Bandar Lampung.','icon'=>'search','watermark'=>'CARI'],
            ['eyebrow'=>'PUSAT INFORMASI','title'=>$query !== '' ? 'Hasil pencarian informasi.' : 'Cari informasi sekolah.',
             'description'=>$query !== '' ? 'Hasil pencarian ditampilkan dari konten website yang telah dipublikasikan.' : 'Masukkan kata kunci untuk mencari berita, pengumuman, agenda, dan halaman informasi sekolah.'],
            [['label'=>'Beranda','url'=>'/'],['label'=>'Pencarian','url'=>'']],
            $query !== '' ? ['meta_value'=>number_format(count($results),0,',','.'),'meta_label'=>'hasil ditemukan'] : []
        );
        View::public('public/search',array_merge($this->base([
            'title'=>'Pencarian','query'=>$query,'results'=>$results,
        ]),$presentation));
    }
}
