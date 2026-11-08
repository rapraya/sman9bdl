<div class="utility-bar">
    <div class="container utility-inner">
        <span>Portal Informasi Resmi Sekolah</span>
        <div class="utility-links"><a href="/layanan-publik">Layanan Publik</a><a href="/spmb">Informasi SPMB</a><a href="/admin/login">Login Admin</a></div>
    </div>
</div>
<header class="site-header">
    <div class="container header-inner">
        <a class="school-brand" href="/" aria-label="Beranda SMA Negeri 9 Bandar Lampung">
            <?php $logo = setting('logo_path'); ?>
            <?php if ($logo !== ''): ?><img class="school-logo" src="<?= e('/' . ltrim($logo, '/')) ?>" alt="Logo <?= e(setting('school_name', 'SMA Negeri 9 Bandar Lampung')) ?>">
            <?php else: ?><span class="brand-mark" aria-hidden="true">9</span><?php endif; ?>
            <span class="brand-copy"><strong><?= e(setting('school_name', 'SMA Negeri 9 Bandar Lampung')) ?></strong><small><?= e(setting('school_short_name', 'SMALAN')) ?> · Bandar Lampung</small></span>
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation"><span></span><span></span><span></span><span class="sr-only">Buka menu navigasi</span></button>
        <nav class="primary-nav" id="primary-navigation" aria-label="Navigasi utama">
            <?php
            $navItems = [
                'home' => ['Beranda','/'], 'profil' => ['Profil','/profil'], 'akademik' => ['Akademik','/akademik'],
                'kesiswaan' => ['Kesiswaan','/kesiswaan'], 'berita' => ['Berita','/berita'],
                'pengumuman' => ['Pengumuman','/pengumuman'], 'agenda' => ['Agenda','/agenda'],
                'galeri' => ['Galeri','/galeri'], 'prestasi' => ['Prestasi','/prestasi'],
            ];
            foreach ($navItems as $key => [$label, $url]): ?>
                <a href="<?= e($url) ?>" <?= (($activeNav ?? '') === $key) ? 'aria-current="page"' : '' ?>><?= e($label) ?></a>
            <?php endforeach; ?>
            <a class="nav-service" href="/layanan-publik">Layanan</a>
        </nav>
        <form class="header-search" action="/cari" method="get" role="search">
            <label class="sr-only" for="site-search">Cari informasi</label>
            <input id="site-search" name="q" type="search" placeholder="Cari informasi…">
            <button type="submit" aria-label="Cari"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"></circle><path d="m16 16 5 5"></path></svg></button>
        </form>
    </div>
</header>
