<?php

namespace App\Services;

use App\Core\Database;
use PDO;

final class ContentService
{
    public static function allowedTypes(): array
    {
        return ['news', 'announcement', 'event', 'achievement', 'page'];
    }

    public static function listPublished(string $type, int $limit = 10, int $page = 1, string $query = ''): array
    {
        $type = in_array($type, self::allowedTypes(), true) ? $type : 'news';
        $limit = max(1, min(40, $limit));
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;
        $where = "type = :type AND status = 'published' AND (publish_at IS NULL OR publish_at <= NOW())";
        $params = ['type' => $type];
        if ($query !== '') {
            $where .= ' AND (title LIKE :title_q OR excerpt LIKE :excerpt_q OR body LIKE :body_q OR category LIKE :category_q)';
            $search = '%' . $query . '%';
            $params += ['title_q' => $search, 'excerpt_q' => $search, 'body_q' => $search, 'category_q' => $search];
        }
        $statement = Database::pdo()->prepare("SELECT c.*, m.file_path AS image_path, m.alt_text AS image_alt FROM contents c LEFT JOIN media m ON m.id = c.featured_image_id WHERE {$where} ORDER BY COALESCE(c.publish_at, c.created_at) DESC, c.id DESC LIMIT {$limit} OFFSET {$offset}");
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public static function countPublished(string $type, string $query = ''): int
    {
        $where = "type = :type AND status = 'published' AND (publish_at IS NULL OR publish_at <= NOW())";
        $params = ['type' => $type];
        if ($query !== '') {
            $where .= ' AND (title LIKE :title_q OR excerpt LIKE :excerpt_q OR body LIKE :body_q OR category LIKE :category_q)';
            $search = '%' . $query . '%';
            $params += ['title_q' => $search, 'excerpt_q' => $search, 'body_q' => $search, 'category_q' => $search];
        }
        $statement = Database::pdo()->prepare("SELECT COUNT(*) FROM contents WHERE {$where}");
        $statement->execute($params);
        return (int) $statement->fetchColumn();
    }

    public static function findPublished(string $type, string $slug): ?array
    {
        $statement = Database::pdo()->prepare("SELECT c.*, m.file_path AS image_path, m.alt_text AS image_alt FROM contents c LEFT JOIN media m ON m.id = c.featured_image_id WHERE c.type = :type AND c.slug = :slug AND c.status = 'published' AND (c.publish_at IS NULL OR c.publish_at <= NOW()) LIMIT 1");
        $statement->execute(['type' => $type, 'slug' => $slug]);
        $row = $statement->fetch();
        return $row ?: null;
    }

    public static function featuredNews(int $limit = 4): array
    {
        $limit = max(1, min(10, $limit));
        return Database::pdo()->query("SELECT c.*, m.file_path AS image_path, m.alt_text AS image_alt FROM contents c LEFT JOIN media m ON m.id = c.featured_image_id WHERE c.type = 'news' AND c.status = 'published' AND c.is_featured = 1 AND (c.publish_at IS NULL OR c.publish_at <= NOW()) ORDER BY COALESCE(c.publish_at,c.created_at) DESC LIMIT {$limit}")->fetchAll();
    }

    public static function adminList(string $type, int $page, string $query): array
    {
        $type = in_array($type, self::allowedTypes(), true) ? $type : 'news';
        $limit = 12;
        $offset = (max(1, $page) - 1) * $limit;
        $params = ['type' => $type];
        $where = 'type = :type';
        if ($query !== '') {
            $where .= ' AND (title LIKE :title_q OR slug LIKE :slug_q OR category LIKE :category_q)';
            $search = '%' . $query . '%';
            $params += ['title_q' => $search, 'slug_q' => $search, 'category_q' => $search];
        }
        $stmt = Database::pdo()->prepare("SELECT id, type, title, slug, category, status, publish_at, created_at FROM contents WHERE {$where} ORDER BY updated_at DESC LIMIT {$limit} OFFSET {$offset}");
        $stmt->execute($params);
        $count = Database::pdo()->prepare("SELECT COUNT(*) FROM contents WHERE {$where}");
        $count->execute($params);
        $total = (int) $count->fetchColumn();
        return ['items' => $stmt->fetchAll(), 'total' => $total, 'pages' => max(1, (int) ceil($total / $limit))];
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM contents WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function save(array $input, int $userId, ?int $id = null): int
    {
        $type = (string) ($input['type'] ?? 'news');
        if (!in_array($type, self::allowedTypes(), true)) {
            throw new \InvalidArgumentException('Jenis konten tidak valid.');
        }
        $title = trim((string) ($input['title'] ?? ''));
        $body = trim((string) ($input['body'] ?? ''));
        if ($title === '' || mb_strlen($title) > 220) {
            throw new \InvalidArgumentException('Judul wajib diisi dan maksimal 220 karakter.');
        }
        if ($body === '') {
            throw new \InvalidArgumentException('Isi konten wajib diisi.');
        }
        $slug = slugify(trim((string) ($input['slug'] ?? '')) ?: $title);
        $status = in_array(($input['status'] ?? 'draft'), ['draft', 'published', 'archived'], true) ? $input['status'] : 'draft';
        $publishAt = trim((string) ($input['publish_at'] ?? ''));
        $publishAt = $publishAt !== '' ? str_replace('T', ' ', $publishAt) : null;
        $imageId = (int) ($input['featured_image_id'] ?? 0) ?: null;
        $attachment = trim((string) ($input['attachment_path'] ?? '')) ?: null;
        $data = [
            'type' => $type,
            'title' => $title,
            'slug' => $slug,
            'excerpt' => trim((string) ($input['excerpt'] ?? '')),
            'body' => $body,
            'category' => trim((string) ($input['category'] ?? '')),
            'featured_image_id' => $imageId,
            'attachment_path' => $attachment,
            'status' => $status,
            'is_featured' => !empty($input['is_featured']) ? 1 : 0,
            'priority' => !empty($input['priority']) ? 1 : 0,
            'publish_at' => $publishAt,
            'event_date' => trim((string) ($input['event_date'] ?? '')) ?: null,
            'event_time' => trim((string) ($input['event_time'] ?? '')) ?: null,
            'event_location' => trim((string) ($input['event_location'] ?? '')),
            'seo_title' => trim((string) ($input['seo_title'] ?? '')),
            'seo_description' => trim((string) ($input['seo_description'] ?? '')),
            'updated_by' => $userId,
        ];
        $pdo = Database::pdo();
        if ($id) {
            $data['id'] = $id;
            $data['publish_status'] = $status;
            $statement = $pdo->prepare('UPDATE contents SET type=:type,title=:title,slug=:slug,excerpt=:excerpt,body=:body,category=:category,featured_image_id=:featured_image_id,attachment_path=:attachment_path,status=:status,is_featured=:is_featured,priority=:priority,publish_at=:publish_at,event_date=:event_date,event_time=:event_time,event_location=:event_location,seo_title=:seo_title,seo_description=:seo_description,updated_by=:updated_by,updated_at=NOW(),published_at=IF(:publish_status = \'published\', COALESCE(published_at,NOW()), published_at) WHERE id=:id');
            $statement->execute($data);
            return $id;
        }
        $data['created_by'] = $userId;
        $data['publish_status'] = $status;
        $statement = $pdo->prepare('INSERT INTO contents (type,title,slug,excerpt,body,category,featured_image_id,attachment_path,status,is_featured,priority,publish_at,event_date,event_time,event_location,seo_title,seo_description,created_by,updated_by,published_at,created_at,updated_at) VALUES (:type,:title,:slug,:excerpt,:body,:category,:featured_image_id,:attachment_path,:status,:is_featured,:priority,:publish_at,:event_date,:event_time,:event_location,:seo_title,:seo_description,:created_by,:updated_by,IF(:publish_status = \'published\',NOW(),NULL),NOW(),NOW())');
        $statement->execute($data);
        return (int) $pdo->lastInsertId();
    }

    public static function delete(int $id): void
    {
        $stmt = Database::pdo()->prepare('DELETE FROM contents WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
