<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?= e($pageTitle ?? 'Dashboard') ?> | Admin SMALAN</title>
    <link rel="icon" href="/assets/images/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="stylesheet" href="/assets/css/admin-experience.css">
    <script src="/assets/js/admin.js" defer></script>
    <script src="/assets/js/admin-experience.js" defer></script>
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="admin-sidebar" id="admin-sidebar">
        <a href="/admin" class="admin-brand"><span class="brand-mark">9</span><span><strong>SMALAN</strong><small>Content workspace</small></span></a>
        <div class="sidebar-caption">WORKSPACE</div>
        <nav class="admin-nav" aria-label="Navigasi administrasi">
            <a href="/admin" class="<?= current_path() === '/admin' ? 'active' : '' ?>"><span>⌂</span>Dashboard</a>
            <div class="sidebar-caption">PUBLIKASI</div>
            <a href="/admin/konten?type=news"><span>▤</span>Berita</a>
            <a href="/admin/konten?type=announcement"><span>◷</span>Pengumuman</a>
            <a href="/admin/konten?type=event"><span>▦</span>Agenda</a>
            <a href="/admin/konten?type=achievement"><span>✦</span>Prestasi</a>
            <a href="/admin/konten?type=page"><span>▧</span>Halaman informasi</a>
            <a href="/admin/media"><span>▧</span>Pustaka media</a>
            <?php if (($user['role'] ?? '') === 'super_admin'): ?><div class="sidebar-caption">PENGELOLAAN</div><a href="/admin/pengaturan"><span>⚙</span>Identitas & pengaturan</a><a href="/admin/pengguna"><span>♙</span>Pengguna CMS</a><a href="/admin/aktivitas"><span>≋</span>Riwayat aktivitas</a><?php endif; ?>
        </nav>
        <div class="sidebar-bottom"><a href="/" target="_blank" rel="noopener"><span>↗</span>Lihat website</a><div class="sidebar-user"><span class="user-avatar"><?= e(mb_strtoupper(mb_substr($user['name'] ?? 'A', 0, 1))) ?></span><span><strong><?= e($user['name'] ?? 'Administrator') ?></strong><small><?= e(str_replace('_', ' ', $user['role'] ?? '')) ?></small></span></div><form method="post" action="/admin/logout"><?= csrf_field() ?><button class="logout-button" type="submit">Keluar dari CMS ↗</button></form></div>
    </aside>
    <div class="admin-main-wrap">
        <header class="admin-topbar"><button type="button" class="admin-menu-toggle" data-admin-menu aria-label="Buka navigasi">☰</button><div><span class="admin-breadcrumb">SMALAN / ADMIN</span><h1><?= e($pageTitle ?? 'Dashboard') ?></h1></div><div class="admin-topbar-right"><span class="admin-status"><i></i> Sistem aktif</span><a href="/" target="_blank" rel="noopener">Website publik ↗</a></div></header>
        <main class="admin-content" id="main-content">
            <?php if ($message = flash('success')): ?><div class="admin-alert alert-success" role="status"><?= e($message) ?></div><?php endif; ?>
            <?php if ($message = flash('error')): ?><div class="admin-alert alert-error" role="alert"><?= e($message) ?></div><?php endif; ?>
            <?= $content ?? '' ?>
        </main>
        <footer class="admin-footer"><span>SMALAN Content Workspace</span><span>Portal informasi SMA Negeri 9 Bandar Lampung</span></footer>
    </div>
</div>
</body>
</html>
