<?php require BASE_PATH . '/resources/views/partials/page-hero.php'; ?>
<?php require BASE_PATH . '/resources/views/partials/page-intro-card.php'; ?>
<section class="section-block">
    <div class="container">
        <?php if ($items): ?>
            <div class="gallery-grid gallery-grid-page">
                <?php foreach ($items as $item): ?>
                    <?php $imageAlt = trim((string) ($item['alt_text'] ?? '')); ?>
                    <a
                        class="gallery-item"
                        href="<?= e('/' . ltrim($item['file_path'], '/')) ?>"
                        target="_blank"
                        rel="noopener"
                        aria-label="<?= e($imageAlt !== '' ? 'Buka foto: ' . $imageAlt : 'Buka foto dokumentasi sekolah') ?>"
                    >
                        <img
                            src="<?= e('/' . ltrim($item['file_path'], '/')) ?>"
                            alt="<?= e($imageAlt) ?>"
                            loading="lazy"
                            decoding="async"
                        >
                        <?php if ($imageAlt !== ''): ?><span><?= e($imageAlt) ?></span><?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <?php require BASE_PATH . '/resources/views/partials/empty-state.php'; ?>
        <?php endif; ?>
    </div>
</section>
