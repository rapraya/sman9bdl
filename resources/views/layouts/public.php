<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#102b50">
    <meta name="description" content="<?= e($metaDescription ?? $description ?? 'Portal informasi resmi SMA Negeri 9 Bandar Lampung.') ?>">
    <link rel="canonical" href="<?= e($canonical ?? '') ?>">
    <meta property="og:type" content="<?= !empty($item) ? 'article' : 'website' ?>">
    <meta property="og:title" content="<?= e(($title ?? 'Beranda') . ' | ' . ($schoolName ?? 'SMA Negeri 9 Bandar Lampung')) ?>">
    <meta property="og:description" content="<?= e($metaDescription ?? $description ?? '') ?>">
    <meta property="og:url" content="<?= e($canonical ?? '') ?>">
    <?php if (!empty($item['image_path'])): ?><meta property="og:image" content="<?= e(((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? '') . '/' . $item['image_path']) ?>"><?php endif; ?>
    <title><?= e($title ?? 'Beranda') ?> | <?= e($schoolName ?? 'SMA Negeri 9 Bandar Lampung') ?></title>
    <link rel="icon" href="/assets/images/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/assets/css/site.css">
    <link rel="stylesheet" href="/assets/css/accessibility.css">
    <script src="/assets/js/site.js" defer></script>
    <script src="/assets/js/accessibility.js" defer></script>
    <script src="/assets/js/chatbot.js" defer></script>
</head>
<body>
<a class="skip-link" href="#main-content">Lewati ke konten utama</a>
<?php require BASE_PATH . '/resources/views/partials/public-header.php'; ?>
<main id="main-content" tabindex="-1">
    <?= $content ?? '' ?>
</main>
<?php require BASE_PATH . '/resources/views/partials/public-footer.php'; ?>
<?php require BASE_PATH . '/resources/views/partials/accessibility-toolbar.php'; ?>
<?php require BASE_PATH . '/resources/views/partials/chatbot.php'; ?>
</body>
</html>
