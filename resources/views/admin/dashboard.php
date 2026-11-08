<div class="admin-stat-grid uds-stat-grid">
    <a class="admin-stat uds-stat-card" href="/admin/konten?type=news"><span>BERITA</span><strong><?= (int) $stats['news'] ?></strong><small>Total konten tersimpan</small><b>Kelola <i aria-hidden="true">↗</i></b></a>
    <a class="admin-stat uds-stat-card" href="/admin/konten?type=announcement"><span>PENGUMUMAN</span><strong><?= (int) $stats['announcement'] ?></strong><small>Total konten tersimpan</small><b>Kelola <i aria-hidden="true">↗</i></b></a>
    <a class="admin-stat uds-stat-card" href="/admin/konten?type=event"><span>AGENDA</span><strong><?= (int) $stats['event'] ?></strong><small>Total konten tersimpan</small><b>Kelola <i aria-hidden="true">↗</i></b></a>
    <a class="admin-stat uds-stat-card" href="/admin/media"><span>PUSTAKA MEDIA</span><strong><?= (int) $mediaCount ?></strong><small>Gambar dan dokumentasi</small><b>Kelola <i aria-hidden="true">↗</i></b></a>
</div>

<div class="admin-dashboard-grid uds-dashboard-grid">
    <section class="admin-panel uds-panel">
        <div class="admin-panel-heading"><div><p class="eyebrow">AKTIVITAS TERKINI</p><h2>Riwayat terbaru</h2></div><a href="/admin/aktivitas">Lihat semua ↗</a></div>
        <?php if ($logs): ?>
            <div class="activity-list"><?php foreach ($logs as $log): ?>
                <div class="activity-item"><span class="activity-dot"></span><div><strong><?= e($log['description']) ?></strong><small><?= e($log['user_name'] ?: 'Sistem') ?> · <?= e(format_date($log['created_at'], true)) ?></small></div><span class="activity-action"><?= e($log['action']) ?></span></div>
            <?php endforeach; ?></div>
        <?php else: ?><div class="uds-empty-state"><span aria-hidden="true">◷</span><strong>Belum ada aktivitas</strong><p>Aktivitas pengelolaan CMS akan tercatat di area ini.</p></div><?php endif; ?>
    </section>
    <section class="admin-panel admin-quick-panel uds-panel">
        <div class="admin-panel-heading"><div><p class="eyebrow">AKSES CEPAT</p><h2>Mulai bekerja</h2></div></div>
        <a class="quick-admin-link uds-quick-link" href="/admin/konten/tambah?type=news"><span>＋</span><div><strong>Tulis berita</strong><small>Publikasikan aktivitas sekolah</small></div><b>↗</b></a>
        <a class="quick-admin-link uds-quick-link" href="/admin/konten/tambah?type=announcement"><span>＋</span><div><strong>Buat pengumuman</strong><small>Sampaikan informasi resmi</small></div><b>↗</b></a>
        <a class="quick-admin-link uds-quick-link" href="/admin/media"><span>▧</span><div><strong>Kelola dokumentasi</strong><small>Unggah dan kurasi foto</small></div><b>↗</b></a>
        <?php if (($user['role'] ?? '') === 'super_admin'): ?>
            <a class="quick-admin-link uds-quick-link" href="/admin/pengaturan"><span>⚙</span><div><strong>Identitas sekolah</strong><small>Perbarui profil dan kontak</small></div><b>↗</b></a>
        <?php endif; ?>
    </section>
</div>
<div class="admin-note uds-note"><strong>Pengingat pengelolaan</strong><span>Periksa kembali akurasi nama, tanggal, foto, izin publikasi, dan lampiran sebelum mengubah status konten menjadi Terbit.</span></div>
