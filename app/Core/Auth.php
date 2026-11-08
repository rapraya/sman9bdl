<?php

namespace App\Core;

use App\Core\Database;

final class Auth
{
    public static function user(): ?array
    {
        $id = (int) ($_SESSION['user_id'] ?? 0);
        if ($id < 1) {
            return null;
        }
        $statement = Database::pdo()->prepare('SELECT id, name, email, role, is_active FROM users WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $user = $statement->fetch();
        if (!$user || (int) $user['is_active'] !== 1) {
            unset($_SESSION['user_id']);
            return null;
        }
        return $user;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function requireLogin(): array
    {
        $user = self::user();
        if (!$user) {
            redirect('/admin/login');
        }
        return $user;
    }

    public static function requireRole(array $roles): array
    {
        $user = self::requireLogin();
        if (!in_array($user['role'], $roles, true)) {
            http_response_code(403);
            echo View::render('errors/403', ['title' => 'Akses tidak diizinkan']);
            exit;
        }
        return $user;
    }

    public static function login(int $id): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $id;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
    }
}
