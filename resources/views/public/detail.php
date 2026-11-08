<article class="article-page" itemscope itemtype="https://schema.org/Article">
    <?php require BASE_PATH . '/resources/views/partials/page-hero.php'; ?>
    <?php require BASE_PATH . '/resources/views/partials/page-intro-card.php'; ?>
    <div class="container article-layout"><div class="article-main">
        <?php if (!empty($item['image_path'])): ?><figure class="article-cover"><img src="<?= e('/' . $item['image_path']) ?>" alt="<?= e($item['image_alt'] ?: $item['title']) ?>"><figcaption><?= e($item['image_alt'] ?: $item['title']) ?></figcaption></figure><?php endif; ?>
        <?php if (!empty($item['excerpt'])): ?><p class="article-excerpt"><?= e($item['excerpt']) ?></p><?php endif; ?>
        <?php if ($item['type'] === 'event'): ?><div class="event-facts"><span><small>Tanggal</small><strong><?= e(format_date($item['event_date'])) ?></strong></span><?php if ($item['event_time']): ?><span><small>Waktu</small><strong><?= e($item['event_time']) ?></strong></span><?php endif; ?><?php if ($item['event_location']): ?><span><small>Lokasi</small><strong><?= e($item['event_location']) ?></strong></span><?php endif; ?></div><?php endif; ?>
        <div class="article-body" itemprop="articleBody" data-readable-content><?= nl2br(e($item['body'])) ?></div>
        <?php if (!empty($item['attachment_path'])): ?><p><a class="button button-outline-dark" href="<?= e('/' . ltrim($item['attachment_path'], '/')) ?>" target="_blank" rel="noopener">Unduh lampiran ↗</a></p><?php endif; ?>
        <div class="article-actions"><button type="button" class="button button-outline-dark" data-read-page>▶ Baca artikel</button><button type="button" class="button button-outline-dark" data-share-page>Bagikan ↗</button></div>
    </div><aside class="article-aside"><div class="article-aside-card"><p class="eyebrow">PORTAL SEKOLAH</p><h2><?= e(setting('school_short_name', 'SMALAN')) ?></h2><p><?= e(setting('school_vision')) ?></p><a href="/profil" class="text-link">Profil sekolah ↗</a></div><?php if ($related): ?><div class="related-content"><p class="eyebrow">INFORMASI LAINNYA</p><?php foreach ($related as $rel): ?><a href="<?= e(content_url($rel['type'], $rel['slug'])) ?>"><strong><?= e($rel['title']) ?></strong><small><?= e(format_date($rel['publish_at'] ?: $rel['created_at'])) ?></small></a><?php endforeach; ?></div><?php endif; ?></aside></div>
</article>
