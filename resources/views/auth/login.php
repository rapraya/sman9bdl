<?php
$theme = in_array($loginTheme ?? 'midnight', ['midnight', 'royal', 'electric', 'emerald', 'violet', 'graphite', 'champagne', 'ocean', 'sapphire', 'arctic', 'cobalt', 'lagoon', 'jade', 'forest', 'amethyst', 'plum', 'ruby', 'rose', 'copper', 'bronze', 'obsidian', 'silver', 'custom'], true) ? $loginTheme : 'midnight';
$backgroundPath = $loginBackground['path'] ?? '';
$themeColors = ['midnight' => '#09162d', 'royal' => '#10264a', 'electric' => '#071b35', 'emerald' => '#082a2a', 'violet' => '#17152f', 'graphite' => '#111d2c', 'champagne' => '#1d1d24', 'ocean' => '#071f35', 'sapphire' => '#0b1738', 'arctic' => '#10283d', 'cobalt' => '#081b45', 'lagoon' => '#082c36', 'jade' => '#092b2a', 'forest' => '#122d27', 'amethyst' => '#21183d', 'plum' => '#2a1635', 'ruby' => '#311321', 'rose' => '#32182c', 'copper' => '#2e201b', 'bronze' => '#2a2117', 'obsidian' => '#111923', 'silver' => '#1c2938', 'custom' => '#09162d'];
$themeCssUrl = '/login-theme.css';
if ($theme === 'custom' && !empty($loginBackground['id'])) {
    $themeCssUrl .= '?background=' . (int) $loginBackground['id'];
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="theme-color" content="<?= e($themeColors[$theme]) ?>">
    <title>Login Administrator | <?= e($schoolName ?? 'SMA Negeri 9 Bandar Lampung') ?></title>
    <link rel="icon" href="/assets/images/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="stylesheet" href="<?= e($themeCssUrl) ?>">
    <script src="/assets/js/admin.js" defer></script>
</head>
<body class="login-body login-theme-<?= e($theme) ?>">
<div class="login-background" aria-hidden="true">
    <?php if ($backgroundPath !== ''): ?>
        <img class="login-background-image" src="/<?= e($backgroundPath) ?>" alt="" fetchpriority="high">
    <?php endif; ?>
    <span class="login-background-overlay"></span>
</div>
<div class="login-ambient" aria-hidden="true">
    <span class="login-ambient-orb login-ambient-orb-one"></span>
    <span class="login-ambient-orb login-ambient-orb-two"></span>
    <span class="login-ambient-orb login-ambient-orb-three"></span>
</div>
<a class="login-skip" href="#login-form">Lewati ke formulir login</a>
<main class="login-stage">
    <section class="login-layout" aria-label="Login administrator">
        <div class="login-panel">
            <header class="login-brand-row">
                <a class="login-brand" href="/" aria-label="Kembali ke website publik">
                    <span class="login-brand-mark" aria-hidden="true">
                        <?php if (!empty($schoolLogo)): ?>
                            <img src="/<?= e($schoolLogo) ?>" alt="">
                        <?php else: ?>
                            <span>9</span>
                        <?php endif; ?>
                    </span>
                    <span class="login-brand-name">
                        <strong><?= e($schoolName ?? 'SMA Negeri 9 Bandar Lampung') ?></strong>
                        <?php if (!empty($schoolVision) || !empty($schoolMission)): ?>
                            <small>
                                <?php if (!empty($schoolVision)): ?><span class="login-identity-label"><?= e($schoolVision) ?></span><?php endif; ?>
                                <?php if (!empty($schoolVision) && !empty($schoolMission)): ?><span class="login-identity-separator"> · </span><?php endif; ?>
                                <?php if (!empty($schoolMission)): ?><span class="login-identity-label"><?= e($schoolMission) ?></span><?php endif; ?>
                            </small>
                        <?php endif; ?>
                    </span>
                </a>
            </header>
            <div class="login-card-heading">
                <p class="login-eyebrow">ADMINISTRATOR PORTAL</p>
                <h1>Selamat datang kembali.</h1>
                <p>Masuk untuk mengelola website sekolah.</p>
            </div>
            <?php if (!empty($installed)): ?><div class="admin-alert alert-success login-alert" role="status"><span class="login-alert-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="m5 12.5 4.2 4.2L19 7"/></svg></span><span>Instalasi database selesai. Silakan masuk dengan akun administrator.</span></div><?php endif; ?>
            <?php if (!empty($error)): ?><div class="admin-alert alert-error login-alert" role="alert" aria-live="assertive" aria-atomic="true" data-auto-dismiss="3000"><span class="login-alert-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M12 8v5m0 3.5h.01M10.3 3.9 2.8 17a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3l-7.5-13.1a2 2 0 0 0-3.4 0Z"/></svg></span><span><?= e($error) ?></span></div><?php endif; ?>
            <form method="post" action="/admin/login" class="login-form" id="login-form">
                <?= csrf_field() ?>
                <div class="login-field">
                    <label class="visually-hidden" for="login-identifier">Nama Pengguna atau Email</label>
                    <div class="login-input-wrap">
                        <span class="login-input-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="3.6"/><path d="M4.8 20c.4-3.4 3.2-5.4 7.2-5.4s6.8 2 7.2 5.4" stroke-linecap="round"/></svg></span>
                        <input id="login-identifier" type="text" name="identifier" autocomplete="username" placeholder="Nama Pengguna / Email" aria-label="Nama Pengguna atau Email" required autofocus maxlength="190">
                    </div>
                </div>
                <div class="login-field">
                    <label class="visually-hidden" for="login-password">Kata Sandi</label>
                    <div class="login-input-wrap">
                        <span class="login-input-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><rect x="4" y="10" width="16" height="11" rx="2.2"/><path d="M8 10V7a4 4 0 0 1 8 0v3" stroke-linecap="round"/><path d="M12 14.5v2.5" stroke-linecap="round"/></svg></span>
                        <input id="login-password" type="password" name="password" autocomplete="current-password" placeholder="Kata Sandi" aria-label="Kata Sandi" required>
                        <button type="button" class="password-toggle" data-password-toggle="login-password" aria-label="Tampilkan kata sandi" aria-pressed="false">
                            <svg class="password-eye-open" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2.2 12s3.5-7 9.8-7 9.8 7 9.8 7-3.5 7-9.8 7-9.8-7-9.8-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="password-eye-closed" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 3 18 18M10.6 5.2A10.8 10.8 0 0 1 12 5c6.3 0 9.8 7 9.8 7a18 18 0 0 1-3.1 4.1M6.2 6.3C3.6 8 2.2 12 2.2 12s3.5 7 9.8 7c1.4 0 2.7-.3 3.8-.9"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="button button-primary button-wide login-submit"><span>Masuk ke dashboard</span><span aria-hidden="true">↗</span></button>
            </form>
            <a class="login-back" href="/">← Kembali ke website publik</a>
            <footer class="login-panel-footer"><span>© <?= date('Y') ?> <?= e($schoolName ?? 'SMA Negeri 9 Bandar Lampung') ?></span><span>ADMINISTRATOR</span></footer>
        </div>
    </section>
</main>
</body>
</html>
