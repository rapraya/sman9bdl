<article class="story-card">
    <a class="story-image" href="<?= e(content_url($item['type'], $item['slug'])) ?>">
        <?php if (!empty($item['image_path'])): ?><img src="<?= e('/' . $item['image_path']) ?>" alt="<?= e($item['image_alt'] ?: $item['title']) ?>" loading="lazy">
        <?php else: ?><span class="story-image-placeholder" aria-hidden="true"><span><?= e(mb_substr($item['title'], 0, 1)) ?></span></span><?php endif; ?>
    </a>
    <div class="story-copy">
        <div class="story-meta"><span><?= e($item['category'] ?: ['news'=>'Berita','announcement'=>'Pengumuman','event'=>'Agenda','achievement'=>'Prestasi','page'=>'Informasi'][$item['type']] ?? 'Informasi') ?></span><time datetime="<?= e($item['publish_at'] ?: $item['created_at']) ?>"><?= e(format_date(($item['type'] === 'event' && !empty($item['event_date'])) ? $item['event_date'] : ($item['publish_at'] ?: $item['created_at']))) ?></time></div>
        <h3><a href="<?= e(content_url($item['type'], $item['slug'])) ?>"><?= e($item['title']) ?></a></h3>
        <p><?= e(excerpt($item['excerpt'] ?: $item['body'], 145)) ?></p>
        <a class="text-link" href="<?= e(content_url($item['type'], $item['slug'])) ?>">Baca selengkapnya <span aria-hidden="true">↗</span></a>
    </div>
</article>
