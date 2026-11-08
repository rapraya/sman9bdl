<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Instalasi Portal SMA Negeri 9 Bandar Lampung</title>
    <link rel="stylesheet" href="/assets/css/site.css">
</head>
<body class="install-body">
<main class="install-shell">
    <div class="install-brand"><span class="brand-mark">9</span><div><strong>SMA Negeri 9 Bandar Lampung</strong><span>Penyiapan portal informasi sekolah</span></div></div>
    <section class="install-card">
        <p class="eyebrow">PENYIAPAN SISTEM</p>
        <h1>Hubungkan website dengan database.</h1>
        <p class="muted">Buat database MySQL/MariaDB terlebih dahulu melalui phpMyAdmin atau panel hosting. Installer akan membuat tabel dan akun administrator awal.</p>
        <?php if (!empty($error)): ?><div class="alert alert-error" role="alert"><?= e($error) ?></div><?php endif; ?>
        <form method="post" action="/install/process" class="form-stack" id="install-form">
            <?= csrf_field() ?>
            <h2>Koneksi database</h2>
            <div class="form-grid">
                <label>Host database<input name="db_host" value="127.0.0.1" required></label>
                <label>Port<input name="db_port" type="number" value="3306" required></label>
                <label>Nama database<input name="db_name" placeholder="sman9bdl" required></label>
                <label>Username database<input name="db_user" value="root" required></label>
                <label class="field-wide">Password database<input name="db_pass" type="password" autocomplete="new-password"></label>
            </div>
            <h2>Akun administrator utama</h2>
            <div class="form-grid">
                <label>Nama administrator<input name="admin_name" placeholder="Administrator Website" required></label>
                <label>Email login<input name="admin_email" type="email" placeholder="admin@sekolah.sch.id" required></label>
                <label class="field-wide">Kata sandi<input name="admin_password" type="password" minlength="12" autocomplete="new-password" required><small>Minimal 12 karakter. Gunakan kata sandi unik dan simpan dengan aman.</small></label>
            </div>
            <button class="button button-primary button-wide" type="submit" id="install-submit">Pasang website dan buat administrator</button>
            <p class="install-progress" id="install-progress" role="status" aria-live="polite" hidden>Permintaan instalasi sedang diproses. Jangan tutup halaman ini. Proses koneksi database dan pembuatan tabel mungkin memerlukan beberapa saat.</p>
        </form>
        <p class="install-footnote">Installer hanya berjalan sebelum konfigurasi lokal dibuat. Setelah instalasi, halaman ini tidak dapat digunakan kembali.</p>
    </section>
</main>
<script src="/assets/js/install.js" defer></script>
</body>
</html>
