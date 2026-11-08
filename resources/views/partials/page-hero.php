<?php
$pageHero = is_array($pageHero ?? null) ? $pageHero : [];
$breadcrumbs = is_array($pageHero['breadcrumbs'] ?? null) ? $pageHero['breadcrumbs'] : [];
$iconName = (string) ($pageHero['icon'] ?? 'school');
$heroIcons = [
'school'=>'<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><path d="M8 25 32 11l24 14v27H8V25Z" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M20 52V36h24v16M25 24h14M25 30h14M15 52V31m34 21V31" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/><path d="M27 52V42h10v10" stroke="currentColor" stroke-width="2.4"/></svg>',
'book'=>'<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><path d="M8 15.5c9-4 17-3 24 2v36c-7-5-15-6-24-2v-36Zm48 0c-9-4-17-3-24 2v36c7-5 15-6 24-2v-36Z" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M15 24c5-1 9 0 13 2m-13 7c5-1 9 0 13 2m21-11c-5-1-9 0-13 2m13 7c-5-1-9 0-13 2" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>',
'users'=>'<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><circle cx="24" cy="21" r="9" stroke="currentColor" stroke-width="2.4"/><path d="M7 51v-5c0-8 7-14 17-14s17 6 17 14v5H7Z" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M43 13c6 1 9 5 9 10s-3 8-8 9m4 5c6 2 9 6 9 12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>',
'news'=>'<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><path d="M13 10h38v44H13V10Z" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M20 19h24v13H20V19Zm0 20h24M20 46h16M38 38h6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/><path d="M8 17v32" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>',
'calendar'=>'<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><rect x="9" y="14" width="46" height="42" rx="5" stroke="currentColor" stroke-width="2.4"/><path d="M20 8v12m24-12v12M9 25h46" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/><path d="M20 34h7m10 0h7M20 44h7m10 0h7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>',
'trophy'=>'<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><path d="M19 10h26v13c0 11-5 18-13 18s-13-7-13-18V10Z" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M19 17H9v5c0 8 5 12 13 12m23-17h10v5c0 8-5 12-13 12M32 41v10m-12 4h24M24 55v-4h16v4" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>',
'image'=>'<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><rect x="8" y="12" width="48" height="40" rx="5" stroke="currentColor" stroke-width="2.4"/><circle cx="22" cy="25" r="4" stroke="currentColor" stroke-width="2.4"/><path d="m12 46 13-12 9 8 8-8 10 12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>',
'search'=>'<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><circle cx="28" cy="28" r="17" stroke="currentColor" stroke-width="2.8"/><path d="m41 41 13 13" stroke="currentColor" stroke-width="2.8" stroke-linecap="round"/></svg>',
'megaphone'=>'<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><path d="m10 28 35-13v34L10 36V28Z" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M45 23h8a5 5 0 0 1 0 10h-8M16 38l5 15h10l-7-12M54 18l4-4m-4 27 4 4" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>',
'shield'=>'<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><path d="M32 8 51 15v15c0 13-8 21-19 27-11-6-19-14-19-27V15L32 8Z" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="m23 32 6 6 13-14" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
'phone'=>'<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><path d="M20 9h24a5 5 0 0 1 5 5v36a5 5 0 0 1-5 5H20a5 5 0 0 1-5-5V14a5 5 0 0 1 5-5Z" stroke="currentColor" stroke-width="2.4"/><path d="M25 16h14m-14 27h14m-7 6h.1" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>',
'file'=>'<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><path d="M17 8h20l12 12v36H17V8Z" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M37 8v13h12M24 32h18M24 41h18M24 50h12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>'
];
$iconMarkup = $heroIcons[$iconName] ?? $heroIcons['school'];
$heroEyebrow = trim((string) ($pageHero['eyebrow'] ?? ''));
$heroTitle = trim((string) ($pageHero['title'] ?? ''));
$heroSubtitle = trim((string) ($pageHero['subtitle'] ?? ''));
$heroWatermark = trim((string) ($pageHero['watermark'] ?? ''));
$heroCaption = trim((string) ($pageHero['caption'] ?? setting('school_short_name', 'SMALAN')));
?>
<section class="page-hero page-hero-system" aria-labelledby="page-hero-title">
    <div class="page-hero-grid" aria-hidden="true"></div>
    <div class="page-hero-orbit page-hero-orbit-one" aria-hidden="true"></div>
    <div class="page-hero-orbit page-hero-orbit-two" aria-hidden="true"></div>
    <div class="container page-hero-layout">
        <div class="page-hero-copy">
            <?php if ($breadcrumbs !== []): ?>
                <nav class="page-breadcrumb" aria-label="Breadcrumb"><ol>
                    <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
                        <?php $label = trim((string) ($breadcrumb['label'] ?? '')); $url = trim((string) ($breadcrumb['url'] ?? '')); $isLast = $index === array_key_last($breadcrumbs); ?>
                        <?php if ($label !== ''): ?><li><?php if (!$isLast && $url !== ''): ?><a href="<?= e($url) ?>"><?= e($label) ?></a><?php else: ?><span aria-current="page"><?= e($label) ?></span><?php endif; ?></li><?php endif; ?>
                    <?php endforeach; ?>
                </ol></nav>
            <?php endif; ?>
            <?php if ($heroEyebrow !== ''): ?><p class="eyebrow eyebrow-light page-hero-eyebrow"><span aria-hidden="true"></span><?= e($heroEyebrow) ?></p><?php endif; ?>
            <?php if ($heroTitle !== ''): ?><h1 id="page-hero-title"><?= e($heroTitle) ?></h1><?php endif; ?>
            <?php if ($heroSubtitle !== ''): ?><p class="page-hero-subtitle"><?= e($heroSubtitle) ?></p><?php endif; ?>
        </div>
        <div class="page-hero-art" aria-hidden="true">
            <?php if ($heroWatermark !== ''): ?><span class="page-hero-watermark"><?= e(mb_strtoupper($heroWatermark)) ?></span><?php endif; ?>
            <span class="page-hero-icon"><?= $iconMarkup ?></span>
            <?php if ($heroCaption !== ''): ?><span class="page-hero-caption"><?= e($heroCaption) ?></span><?php endif; ?>
        </div>
    </div>
</section>