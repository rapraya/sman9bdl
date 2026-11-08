<footer class="site-footer" itemscope itemtype="https://schema.org/EducationalOrganization">
    <div class="container footer-main">
        <div class="footer-school">
            <a class="school-brand footer-brand" href="/"><span class="brand-mark">9</span><span class="brand-copy"><strong itemprop="name"><?= e(setting('school_name', 'SMA Negeri 9 Bandar Lampung')) ?></strong><small>Portal Informasi Pendidikan</small></span></a>
            <p><?= e(setting('footer_note', 'Portal informasi pendidikan dan layanan publik sekolah.')) ?></p>
        </div>
        <div><h2>Jelajahi</h2><a href="/profil">Profil sekolah</a><a href="/berita">Berita</a><a href="/pengumuman">Pengumuman</a><a href="/agenda">Agenda</a></div>
        <div><h2>Layanan</h2><a href="/layanan-publik">Layanan publik / PPID</a><a href="/spmb">Informasi SPMB</a><a href="/galeri">Galeri</a><a href="/kebijakan-privasi">Kebijakan privasi</a></div>
        <div class="footer-contact"><h2>Hubungi Sekolah</h2><p itemprop="address"><?= e(setting('school_address')) ?></p>
            <?php if (setting('contact_email') !== ''): ?><a itemprop="email" href="mailto:<?= e(setting('contact_email')) ?>"><?= e(setting('contact_email')) ?></a><?php endif; ?>
            <?php if (setting('contact_phone') !== ''): ?><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', setting('contact_phone'))) ?>"><?= e(setting('contact_phone')) ?></a><?php endif; ?>
            <?php if (setting('ppid_email') !== ''): ?><a href="mailto:<?= e(setting('ppid_email')) ?>">Email layanan informasi</a><?php endif; ?>
            <?php if (setting('school_website') !== ''): ?><a href="<?= e(setting('school_website')) ?>" target="_blank" rel="noopener noreferrer">Website sekolah ↗</a><?php endif; ?>
            <?php foreach (['Instagram' => 'social_instagram', 'Facebook' => 'social_facebook', 'YouTube' => 'social_youtube'] as $label => $key): ?><?php if (setting($key) !== ''): ?><span><?= e($label) ?>: <?= e(setting($key)) ?></span><?php endif; ?><?php endforeach; ?>
        </div>
    </div>
    <div class="footer-bottom"><div class="container footer-bottom-inner"><span>© <?= date('Y') ?> <?= e(setting('school_name', 'SMA Negeri 9 Bandar Lampung')) ?>.</span><span>Informasi resmi sekolah dalam satu portal digital.</span></div></div>
</footer>
