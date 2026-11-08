<?php
$pageIntro = is_array($pageIntro ?? null) ? $pageIntro : [];
$introEyebrow = trim((string) ($pageIntro['eyebrow'] ?? ''));
$introTitle = trim((string) ($pageIntro['title'] ?? ''));
$introDescription = trim((string) ($pageIntro['description'] ?? ''));
$metaValue = trim((string) ($pageIntro['meta_value'] ?? ''));
$metaLabel = trim((string) ($pageIntro['meta_label'] ?? ''));
?>
<section class="page-intro-wrap" aria-label="Pengantar halaman"><div class="container">
    <div class="page-intro-card">
        <div class="page-intro-copy">
            <?php if ($introEyebrow !== ''): ?><p class="eyebrow"><?= e($introEyebrow) ?></p><?php endif; ?>
            <?php if ($introTitle !== ''): ?><h2><?= e($introTitle) ?></h2><?php endif; ?>
            <?php if ($introDescription !== ''): ?><p><?= e($introDescription) ?></p><?php endif; ?>
        </div>
        <?php if ($metaValue !== '' && $metaLabel !== ''): ?>
            <div class="page-intro-meta" aria-label="<?= e($metaLabel . ': ' . $metaValue) ?>"><strong><?= e($metaValue) ?></strong><span><?= e($metaLabel) ?></span></div>
        <?php else: ?>
            <span class="page-intro-mark" aria-hidden="true">09</span>
        <?php endif; ?>
    </div>
</div></section>