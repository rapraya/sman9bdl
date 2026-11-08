<?php

namespace App\Services;

use App\Core\Database;

final class ChatbotService
{
    public function answer(string $question): array
    {
        $question = trim(mb_strtolower($question));
        $question = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $question) ?? $question;
        $question = preg_replace('/\s+/', ' ', $question) ?? $question;
        if ($question === '' || mb_strlen($question) > 400) {
            return $this->notFound();
        }

        $facts = [
            ['keys' => ['alamat', 'lokasi', 'dimana sekolah', 'letak sekolah'], 'setting' => 'school_address', 'label' => 'Alamat sekolah', 'source' => '/kontak'],
            ['keys' => ['visi', 'misi', 'visi misi'], 'setting' => 'school_vision', 'label' => 'Visi sekolah', 'source' => '/profil'],
            ['keys' => ['kepala sekolah', 'kepsek', 'siapa kepala'], 'setting' => 'headmaster_name', 'label' => 'Kepala sekolah', 'source' => '/profil'],
            ['keys' => ['sejarah', 'berdiri', 'tahun berdiri', 'didirikan'], 'setting' => 'school_history', 'label' => 'Sejarah sekolah', 'source' => '/profil'],
            ['keys' => ['fasilitas', 'sarana prasarana', 'laboratorium', 'perpustakaan'], 'setting' => 'school_facilities', 'label' => 'Fasilitas sekolah', 'source' => '/profil'],
            ['keys' => ['prestasi sekolah', 'penghargaan', 'adiwiyata'], 'setting' => 'school_recognitions', 'label' => 'Pengakuan sekolah', 'source' => '/prestasi'],
            ['keys' => ['email', 'hubungi', 'kontak', 'ppid'], 'setting' => 'ppid_email', 'label' => 'Kontak layanan informasi', 'source' => '/layanan-publik'],
        ];
        foreach ($facts as $fact) {
            foreach ($fact['keys'] as $key) {
                if (str_contains($question, $key)) {
                    $value = trim(setting($fact['setting']));
                    if ($value === '') {
                        return $this->notFound();
                    }
                    return ['answer' => $fact['label'] . ': ' . $value, 'sources' => [['title' => $fact['label'], 'url' => $fact['source']]], 'suggestions' => ['Apa visi sekolah?', 'Di mana alamat sekolah?', 'Apa saja fasilitas sekolah?']];
                }
            }
        }

        if (preg_match('/\b(berita|kabar|informasi terbaru)\b/u', $question)) {
            $items = ContentService::listPublished('news', 3);
            if ($items) {
                $lines = array_map(static fn ($item) => $item['title'] . ' (' . format_date($item['publish_at'] ?: $item['created_at']) . ')', $items);
                return ['answer' => "Berikut berita terbaru yang telah dipublikasikan:\n• " . implode("\n• ", $lines), 'sources' => array_map(static fn ($item) => ['title' => $item['title'], 'url' => content_url('news', $item['slug'])], $items), 'suggestions' => ['Apa pengumuman terbaru?', 'Apa agenda sekolah?']];
            }
        }

        if (preg_match('/\b(pengumuman|pengumuman terbaru)\b/u', $question)) {
            $items = ContentService::listPublished('announcement', 3);
            if ($items) {
                $lines = array_map(static fn ($item) => $item['title'] . ' (' . format_date($item['publish_at'] ?: $item['created_at']) . ')', $items);
                return ['answer' => "Pengumuman yang tersedia:\n• " . implode("\n• ", $lines), 'sources' => array_map(static fn ($item) => ['title' => $item['title'], 'url' => content_url('announcement', $item['slug'])], $items), 'suggestions' => ['Bagaimana melihat berita sekolah?', 'Apa agenda sekolah?']];
            }
        }

        if (preg_match('/\b(agenda|kegiatan|jadwal)\b/u', $question)) {
            $stmt = Database::pdo()->query("SELECT title, slug, event_date, event_time FROM contents WHERE type='event' AND status='published' AND event_date >= CURDATE() AND (publish_at IS NULL OR publish_at <= NOW()) ORDER BY event_date ASC LIMIT 5");
            $items = $stmt->fetchAll();
            if ($items) {
                $lines = array_map(static fn ($item) => $item['title'] . ' — ' . format_date($item['event_date']) . ($item['event_time'] ? ' pukul ' . $item['event_time'] : ''), $items);
                return ['answer' => "Agenda mendatang yang tercatat:\n• " . implode("\n• ", $lines), 'sources' => array_map(static fn ($item) => ['title' => $item['title'], 'url' => content_url('event', $item['slug'])], $items), 'suggestions' => ['Apa berita terbaru?', 'Bagaimana menghubungi sekolah?']];
            }
        }

        $terms = array_values(array_filter(explode(' ', $question), static fn ($term) => mb_strlen($term) > 2));
        if ($terms) {
            $clauses = [];
            $params = [];
            foreach ($terms as $index => $term) {
                $titleKey = ':title' . $index;
                $excerptKey = ':excerpt' . $index;
                $bodyKey = ':body' . $index;
                $categoryKey = ':category' . $index;
                $clauses[] = "(title LIKE {$titleKey} OR excerpt LIKE {$excerptKey} OR body LIKE {$bodyKey} OR category LIKE {$categoryKey})";
                $search = '%' . $term . '%';
                $params[substr($titleKey, 1)] = $search;
                $params[substr($excerptKey, 1)] = $search;
                $params[substr($bodyKey, 1)] = $search;
                $params[substr($categoryKey, 1)] = $search;
            }
            $sql = "SELECT type,title,slug,excerpt,body FROM contents WHERE status='published' AND (publish_at IS NULL OR publish_at <= NOW()) AND (" . implode(' OR ', $clauses) . ') ORDER BY is_featured DESC, COALESCE(publish_at,created_at) DESC LIMIT 5';
            $stmt = Database::pdo()->prepare($sql);
            $stmt->execute($params);
            $items = $stmt->fetchAll();
            if ($items) {
                $best = $items[0];
                $sources = array_map(static fn ($item) => ['title' => $item['title'], 'url' => content_url($item['type'], $item['slug'])], $items);
                return ['answer' => excerpt($best['excerpt'] ?: $best['body'], 420), 'sources' => $sources, 'suggestions' => ['Lihat berita terbaru', 'Lihat pengumuman', 'Hubungi sekolah']];
            }
        }

        return $this->notFound();
    }

    private function notFound(): array
    {
        return ['answer' => 'Maaf, saya belum menemukan informasi tersebut pada data publik website sekolah. Silakan gunakan menu website atau hubungi sekolah melalui kanal kontak resmi yang tersedia.', 'sources' => [], 'suggestions' => ['Di mana alamat sekolah?', 'Apa visi sekolah?', 'Siapa kepala sekolah?']];
    }
}
