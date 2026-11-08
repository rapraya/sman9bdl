<?php

use App\Controllers\ChatbotController;
use App\Controllers\InstallController;
use App\Controllers\PublicController;
use App\Core\Router;

$install = new InstallController();
$public = new PublicController();
$chatbot = new ChatbotController();

$router->get('/install', [$install, 'index']);
$router->post('/install/process', [$install, 'process']);

$router->get('/', [$public, 'home']);
$router->get('/profil', [$public, 'profile']);
$router->get('/akademik', static fn () => $public->informationPage('akademik'));
$router->get('/kesiswaan', static fn () => $public->informationPage('kesiswaan'));
$router->get('/layanan-publik', static fn () => $public->informationPage('layanan-publik'));
$router->get('/spmb', static fn () => $public->informationPage('spmb'));
$router->get('/kontak', static fn () => $public->informationPage('kontak'));
$router->get('/kebijakan-privasi', static fn () => $public->informationPage('kebijakan-privasi'));
$router->get('/berita', static fn () => $public->listing('news'));
$router->get('/pengumuman', static fn () => $public->listing('announcement'));
$router->get('/agenda', static fn () => $public->listing('event'));
$router->get('/prestasi', static fn () => $public->listing('achievement'));
$router->get('/galeri', [$public, 'gallery']);
$router->get('/cari', [$public, 'search']);
$router->get('/sitemap.xml', [$public, 'sitemap']);
$router->get('/robots.txt', [$public, 'robots']);
$router->get('/berita/{slug}', static fn (string $slug) => $public->detail('news', $slug));
$router->get('/pengumuman/{slug}', static fn (string $slug) => $public->detail('announcement', $slug));
$router->get('/agenda/{slug}', static fn (string $slug) => $public->detail('event', $slug));
$router->get('/prestasi/{slug}', static fn (string $slug) => $public->detail('achievement', $slug));
$router->get('/halaman/{slug}', static fn (string $slug) => $public->detail('page', $slug));
$router->post('/api/chatbot', [$chatbot, 'answer']);
