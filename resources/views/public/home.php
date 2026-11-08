<section class="hero-section">
    <div class="hero-grid-pattern" aria-hidden="true"></div>
    <div class="container hero-inner">
        <div class="hero-copy">
            <p class="eyebrow eyebrow-light"><span></span> PORTAL RESMI SEKOLAH</p>
            <h1><?= e(setting('hero_title', 'SMA Negeri 9 Bandar Lampung')) ?></h1>
            <p class="hero-lead"><?= e(setting('hero_description', setting('school_vision'))) ?></p>
            <div class="hero-actions"><a class="button button-gold" href="/profil">Jelajahi profil sekolah <span aria-hidden="true">↗</span></a><a class="button button-outline-light" href="/berita">Berita sekolah</a></div>
            <div class="hero-footnote"><span class="hero-footnote-line"></span><span>Informasi · Publikasi · Layanan Pendidikan</span></div>
        </div>
        <div class="hero-visual" aria-label="Identitas SMA Negeri 9 Bandar Lampung">
            <?php if (setting('hero_image') !== ''): ?><img class="hero-photo" src="<?= e('/' . ltrim(setting('hero_image'), '/')) ?>" alt="Lingkungan SMA Negeri 9 Bandar Lampung">
            <?php else: ?>
                <div class="hero-architecture" aria-hidden="true"><div class="architecture-sun"></div><div class="architecture-building"><div class="building-roof"></div><div class="building-body"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div><div class="building-base"></div></div><div class="architecture-ground"></div></div>
                <div class="hero-visual-label"><span class="label-number">09</span><span><strong>SMALAN</strong><small>Bandar Lampung · Lampung</small></span></div>
            <?php endif; ?>
            <?php if (preg_match('/^\d{4}$/', trim(setting('founded_year'))) === 1): ?><div class="hero-stamp"><span>SEJAK</span><strong><?= e(setting('founded_year')) ?></strong><small>TAHUN BERDIRI</small></div><?php endif; ?>
            <div class="hero-orbit orbit-one"></div><div class="hero-orbit orbit-two"></div>
        </div>
    </div>
    <div class="hero-bottom-line"><div class="container"><span>01 / INFORMASI SEKOLAH</span><span>PORTAL INFORMASI RESMI SMA NEGERI 9 BANDAR LAMPUNG</span><span>↓</span></div></div>
</section>

<section class="quick-access-section"><div class="container quick-access-grid">
    <div class="quick-intro"><p class="eyebrow">AKSES CEPAT</p><h2>Temukan yang Anda cari.</h2><p>Akses langsung informasi dan layanan sekolah.</p></div>
    <a class="quick-link" href="/pengumuman"><span class="quick-number">01</span><span><strong>Pengumuman</strong><small>Informasi resmi sekolah</small></span><b aria-hidden="true">↗</b></a>
    <a class="quick-link" href="/spmb"><span class="quick-number">02</span><span><strong>Informasi SPMB</strong><small>Penerimaan murid baru</small></span><b aria-hidden="true">↗</b></a>
    <a class="quick-link" href="/layanan-publik"><span class="quick-number">03</span><span><strong>Layanan Publik</strong><small>Informasi dan komunikasi</small></span><b aria-hidden="true">↗</b></a>
    <a class="quick-link" href="/kontak"><span class="quick-number">04</span><span><strong>Hubungi Kami</strong><small>Alamat dan kontak sekolah</small></span><b aria-hidden="true">↗</b></a>
</div></section>

<section class="section-block news-section"><div class="container">
    <div class="section-heading"><div><p class="eyebrow">KABAR SEKOLAH</p><h2>Informasi terbaru dari SMALAN.</h2><p>Publikasi kegiatan, pembelajaran, prestasi, dan informasi resmi sekolah.</p></div><a class="text-link" href="/berita">Semua berita <span aria-hidden="true">↗</span></a></div>
    <?php if ($featured): ?><div class="news-editorial">
        <article class="lead-story">
            <a class="lead-story-image" href="<?= e(content_url('news', $featured['slug'])) ?>">
                <?php if (!empty($featured['image_path'])): ?><img src="<?= e('/' . $featured['image_path']) ?>" alt="<?= e($featured['image_alt'] ?: $featured['title']) ?>" loading="lazy">
                <?php else: ?><div class="lead-placeholder"><span>SMALAN</span><strong>Berita<br>sekolah</strong><i>09</i></div><?php endif; ?>
            </a>
            <div class="lead-story-copy"><div class="story-meta"><span><?= e($featured['category'] ?: 'Berita sekolah') ?></span><time><?= e(format_date($featured['publish_at'] ?: $featured['created_at'])) ?></time></div><h3><a href="<?= e(content_url('news', $featured['slug'])) ?>"><?= e($featured['title']) ?></a></h3><p><?= e(excerpt($featured['excerpt'] ?: $featured['body'], 220)) ?></p><a class="button button-dark" href="<?= e(content_url('news', $featured['slug'])) ?>">Baca berita <span aria-hidden="true">↗</span></a></div>
        </article>
        <div class="side-stories">
            <?php
            $monthLabels = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            foreach (array_slice(array_values(array_filter($news, static fn($n) => (int)$n['id'] !== (int)$featured['id'])), 0, 3) as $item):
                $publishedDate = new DateTimeImmutable((string) ($item['publish_at'] ?: $item['created_at']), new DateTimeZone('Asia/Jakarta'));
            ?>
                <article class="side-story"><div class="side-story-date"><strong><?= e($publishedDate->format('d')) ?></strong><span><?= e($monthLabels[(int) $publishedDate->format('n')]) ?></span></div><div><p class="eyebrow"><?= e($item['category'] ?: 'PUBLIKASI') ?></p><h3><a href="<?= e(content_url('news', $item['slug'])) ?>"><?= e($item['title']) ?></a></h3><p><?= e(excerpt($item['excerpt'] ?: $item['body'], 95)) ?></p></div><a class="side-arrow" href="<?= e(content_url('news', $item['slug'])) ?>" aria-label="Baca <?= e($item['title']) ?>">↗</a></article>
            <?php endforeach; ?>
            <?php if (count($news) <= 1): ?><div class="side-story-empty"><span>02—04</span><p>Berita berikutnya akan muncul di sini setelah diterbitkan.</p></div><?php endif; ?>
        </div>
    </div><?php else: ?>
        <div class="editorial-empty"><div><span class="empty-number">01</span><h3>Ruang publikasi sekolah.</h3><p>Berita dan dokumentasi kegiatan akan tampil di bagian ini setelah tim sekolah menerbitkannya melalui CMS.</p><a class="text-link" href="/pengumuman">Lihat pengumuman sekolah <span aria-hidden="true">↗</span></a></div><div class="editorial-empty-mark" aria-hidden="true">09</div></div>
    <?php endif; ?>
</div></section>

<section class="announcement-band"><div class="container announcement-layout"><div><p class="eyebrow eyebrow-light">PENGUMUMAN RESMI</p><h2>Informasi penting,<br>langsung dari sekolah.</h2><a class="text-link text-link-light" href="/pengumuman">Lihat semua pengumuman <span aria-hidden="true">↗</span></a></div><div class="announcement-list">
    <?php if ($announcements): foreach ($announcements as $item): ?><a class="announcement-row" href="<?= e(content_url('announcement', $item['slug'])) ?>"><span class="announcement-date"><?= e(format_date($item['publish_at'] ?: $item['created_at'])) ?></span><strong><?= e($item['title']) ?></strong><span aria-hidden="true">↗</span></a><?php endforeach; else: ?><div class="announcement-empty">Belum ada pengumuman aktif yang dipublikasikan. Silakan kembali untuk memperoleh informasi resmi terbaru.</div><?php endif; ?>
</div></div></section>

<section class="section-block profile-teaser"><div class="container profile-teaser-grid"><div class="profile-teaser-mark"><span class="mark-outline">09</span><span class="mark-caption">SMA NEGERI<br>9 BANDAR LAMPUNG</span></div><div class="profile-teaser-copy"><p class="eyebrow">MENGENAL SMALAN</p><h2>Mengenal SMA Negeri 9 Bandar Lampung.</h2><p><?= e(setting('school_vision')) ?></p><div class="profile-facts"><?php if (preg_match('/^\d{4}$/', trim(setting('founded_year'))) === 1): ?><div><strong><?= e(setting('founded_year')) ?></strong><span>Tahun berdiri</span></div><?php endif; ?><div><strong>Imtaq</strong><span>Landasan karakter</span></div><div><strong>Iptek</strong><span>Penguatan pengetahuan</span></div></div><a class="button button-dark" href="/profil">Kenali sekolah kami <span aria-hidden="true">↗</span></a></div></div></section>

<section class="section-block soft-section"><div class="container"><div class="section-heading"><div><p class="eyebrow">AGENDA & PRESTASI</p><h2>Aktivitas dan capaian sekolah.</h2><p>Perkembangan kegiatan dan pencapaian yang telah dipublikasikan.</p></div></div><div class="two-column-content"><div class="content-column"><div class="column-heading"><h3>Agenda mendatang</h3><a href="/agenda">Lihat agenda ↗</a></div><?php if ($events): foreach ($events as $item): ?><a class="compact-row" href="<?= e(content_url('event', $item['slug'])) ?>"><span class="compact-date"><?= e($item['event_date'] ? date('d.m', strtotime($item['event_date'])) : '—') ?></span><span><strong><?= e($item['title']) ?></strong><small><?= e($item['event_location'] ?: 'Lokasi belum dicantumkan') ?></small></span><b>↗</b></a><?php endforeach; else: ?><p class="muted-note">Belum ada agenda mendatang yang dipublikasikan.</p><?php endif; ?></div>
<div class="content-column"><div class="column-heading"><h3>Prestasi & apresiasi</h3><a href="/prestasi">Lihat prestasi ↗</a></div><?php if ($achievements): foreach ($achievements as $item): ?><a class="compact-row" href="<?= e(content_url('achievement', $item['slug'])) ?>"><span class="compact-date">✦</span><span><strong><?= e($item['title']) ?></strong><small><?= e(excerpt($item['excerpt'] ?: $item['body'], 70)) ?></small></span><b>↗</b></a><?php endforeach; else: ?><p class="muted-note">Informasi prestasi akan ditampilkan setelah diverifikasi dan diterbitkan oleh sekolah.</p><?php endif; ?></div></div></div></section>

<?php if ($gallery): ?><section class="section-block gallery-preview"><div class="container"><div class="section-heading"><div><p class="eyebrow">CERITA DALAM GAMBAR</p><h2>Potret kegiatan sekolah.</h2></div><a class="text-link" href="/galeri">Kunjungi galeri <span aria-hidden="true">↗</span></a></div><div class="gallery-grid"><?php foreach ($gallery as $photo): ?><a class="gallery-item" href="<?= e('/' . $photo['file_path']) ?>" target="_blank" rel="noopener"><img src="<?= e('/' . $photo['file_path']) ?>" alt="<?= e($photo['alt_text'] ?: $photo['original_name']) ?>" loading="lazy"></a><?php endforeach; ?></div></div></section><?php endif; ?>
