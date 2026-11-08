<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;

final class AdminController
{
    public function dashboard(): void
    {
        $user = Auth::requireLogin();
        $pdo = Database::pdo();
        $stats = [];
        foreach (['news','announcement','event','achievement','page'] as $type) {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM contents WHERE type=:type');
            $stmt->execute(['type' => $type]);
            $stats[$type] = (int) $stmt->fetchColumn();
        }
        $mediaCount = (int) $pdo->query('SELECT COUNT(*) FROM media')->fetchColumn();
        $logs = $pdo->query('SELECT a.*,u.name AS user_name FROM activity_logs a LEFT JOIN users u ON u.id=a.user_id ORDER BY a.created_at DESC LIMIT 8')->fetchAll();
        View::admin('admin/dashboard', ['user' => $user, 'stats' => $stats, 'mediaCount' => $mediaCount, 'logs' => $logs, 'pageTitle' => 'Ringkasan website']);
    }

    public function logs(): void
    {
        $user = Auth::requireRole(['super_admin']);
        $logs = Database::pdo()->query('SELECT a.*,u.name AS user_name FROM activity_logs a LEFT JOIN users u ON u.id=a.user_id ORDER BY a.created_at DESC LIMIT 100')->fetchAll();
        View::admin('admin/logs', ['user' => $user, 'logs' => $logs, 'pageTitle' => 'Riwayat aktivitas']);
    }
}
