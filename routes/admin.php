<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\ContentController;
use App\Controllers\MediaController;
use App\Controllers\SettingsController;
use App\Controllers\UserController;

$auth = new AuthController();
$admin = new AdminController();
$content = new ContentController();
$settings = new SettingsController();
$media = new MediaController();
$users = new UserController();

$router->get('/admin/login', [$auth, 'login']);
$router->get('/login-theme.css', [$auth, 'themeCss']);
$router->post('/admin/login', [$auth, 'authenticate']);
$router->post('/admin/logout', [$auth, 'logout']);
$router->get('/admin', [$admin, 'dashboard']);
$router->get('/admin/aktivitas', [$admin, 'logs']);
$router->get('/admin/konten', [$content, 'index']);
$router->get('/admin/konten/tambah', [$content, 'create']);
$router->post('/admin/konten/simpan', [$content, 'save']);
$router->get('/admin/konten/{id}/edit', [$content, 'edit']);
$router->post('/admin/konten/{id}/hapus', [$content, 'delete']);
$router->get('/admin/pengaturan', [$settings, 'index']);
$router->post('/admin/pengaturan', [$settings, 'save']);
$router->get('/admin/media', [$media, 'index']);
$router->post('/admin/media/unggah', [$media, 'upload']);
$router->post('/admin/media/{id}/hapus', [$media, 'delete']);
$router->post('/admin/media/{id}/galeri', [$media, 'toggleGallery']);
$router->get('/admin/pengguna', [$users, 'index']);
$router->post('/admin/pengguna', [$users, 'save']);
$router->post('/admin/pengguna/{id}/status', [$users, 'toggle']);
