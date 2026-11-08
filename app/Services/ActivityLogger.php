<?php

namespace App\Services;

use App\Core\Database;

final class ActivityLogger
{
    public static function record(string $action, string $entity, ?int $entityId, string $description): void
    {
        try {
            $userId = (int) ($_SESSION['user_id'] ?? 0) ?: null;
            $statement = Database::pdo()->prepare('INSERT INTO activity_logs (user_id, action, entity_type, entity_id, description, ip_address, user_agent, created_at) VALUES (:user_id, :action, :entity_type, :entity_id, :description, :ip, :agent, NOW())');
            $statement->execute([
                'user_id' => $userId,
                'action' => $action,
                'entity_type' => $entity,
                'entity_id' => $entityId,
                'description' => $description,
                'ip' => substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45),
                'agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
            ]);
        } catch (\Throwable $exception) {
            error_log('Activity log: ' . $exception->getMessage());
        }
    }
}
