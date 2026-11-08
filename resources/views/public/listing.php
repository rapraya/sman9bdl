<?php require BASE_PATH . '/resources/views/partials/page-hero.php'; ?>
<?php require BASE_PATH . '/resources/views/partials/page-intro-card.php'; ?>
<section class="section-block"><div class="container"><div class="listing-tools"><form action="<?= e(current_path()) ?>" method="get" role="search"><label class="sr-only" for="listing-search">Cari <?= e(strtolower($title)) ?></label><input id="listing-search" type="search" name="q" value="<?= e($query) ?>" placeholder="Cari judul atau isi informasi…"><button class="button button-dark" type="submit">Cari</button></form><span><?= (int) $total ?> informasi</span></div>
<?php if ($items): ?><div class="story-grid"><?php foreach ($items as $item): ?><?php require BASE_PATH . '/resources/views/partials/content-card.php'; ?><?php endforeach; ?></div>
<?php else: ?><?php require BASE_PATH . '/resources/views/partials/empty-state.php'; ?><?php endif; ?>
<?php if ($pages > 1): ?><nav class="pagination" aria-label="Navigasi halaman"><?php for ($i=1;$i<=$pages;$i++): ?><a href="?q=<?= rawurlencode($query) ?>&page=<?= $i ?>" <?= $i === $page ? 'aria-current="page"' : '' ?>><?= $i ?></a><?php endfor; ?></nav><?php endif; ?>
</div></section>
