<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\View;
use App\Services\ActivityLogger;

final class UserController
{
    public function index(): void
    {
        $user = Auth::requireRole(['super_admin']);
        $users = Database::pdo()->query('SELECT id,name,email,role,is_active,last_login_at,created_at FROM users ORDER BY created_at DESC')->fetchAll();
        View::admin('admin/users', ['user' => $user, 'pageTitle' => 'Pengguna CMS', 'users' => $users]);
    }

    public function save(): void
    {
        $actor = Auth::requireRole(['super_admin']);
        Csrf::enforce();
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $role = (string) ($_POST['role'] ?? 'operator');
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12 || !in_array($role, ['super_admin','editor','operator','viewer'], true)) {
            flash('error', 'Periksa nama, email, role, dan kata sandi minimal 12 karakter.');
            redirect('/admin/pengguna');
        }
        try {
            $stmt = Database::pdo()->prepare('INSERT INTO users (name,email,password_hash,role,is_active,created_at,updated_at) VALUES (:name,:email,:password,:role,1,NOW(),NOW())');
            $stmt->execute(['name' => $name, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT), 'role' => $role]);
            $id = (int) Database::pdo()->lastInsertId();
            ActivityLogger::record('CREATE', 'users', $id, 'Membuat akun CMS: ' . $email);
            flash('success', 'Pengguna berhasil ditambahkan.');
        } catch (\PDOException) {
            flash('error', 'Email sudah terdaftar atau akun tidak dapat dibuat.');
        }
        redirect('/admin/pengguna');
    }

    public function toggle(string $id): void
    {
        $actor = Auth::requireRole(['super_admin']);
        Csrf::enforce();
        if ((int) $id === (int) $actor['id']) {
            flash('error', 'Akun yang sedang digunakan tidak dapat dinonaktifkan.');
            redirect('/admin/pengguna');
        }
        Database::pdo()->prepare('UPDATE users SET is_active=IF(is_active=1,0,1),updated_at=NOW() WHERE id=:id')->execute(['id' => (int) $id]);
        ActivityLogger::record('UPDATE', 'users', (int) $id, 'Mengubah status akun pengguna');
        flash('success', 'Status pengguna diperbarui.');
        redirect('/admin/pengguna');
    }
}
