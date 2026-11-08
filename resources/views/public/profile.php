<?php
$schoolName = trim(setting('school_name', 'SMA Negeri 9 Bandar Lampung'));
$schoolShortName = trim(setting('school_short_name', 'SMALAN'));
$schoolAddress = trim(setting('school_address'));
$foundedYear = trim(setting('founded_year'));
$headmasterName = trim(setting('headmaster_name'));
$schoolVision = trim(setting('school_vision'));
$schoolMission = trim(setting('school_mission'));
$schoolHistory = trim(setting('school_history'));
$schoolFacilities = trim(setting('school_facilities'));
$schoolRecognitions = trim(setting('school_recognitions'));
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow eyebrow-light">PROFIL SEKOLAH</p>
        <h1>Mengenal <?= e($schoolName) ?>.</h1>
        <p>Identitas dan informasi resmi sekolah.</p>
    </div>
</section>

<section class="section-block">
    <div class="container profile-page-grid">
        <aside class="profile-side">
            <span class="profile-side-mark" aria-hidden="true">09</span>
            <strong><?= e($schoolShortName !== '' ? $schoolShortName : 'SMALAN') ?></strong>
            <span>Bandar Lampung · Lampung</span>
            <a href="/kontak">Informasi kontak <span aria-hidden="true">↗</span></a>
        </aside>

        <div class="profile-main">
            <p class="eyebrow">IDENTITAS SEKOLAH</p>
            <h2><?= e($schoolName) ?></h2>
            <?php if ($schoolVision !== ''): ?>
                <p class="lead-paragraph"><?= e($schoolVision) ?></p>
            <?php endif; ?>

            <div class="fact-table">
                <div><span>Nama sekolah</span><strong><?= e($schoolName) ?></strong></div>
                <?php if ($schoolShortName !== ''): ?>
                    <div><span>Nama singkat</span><strong><?= e($schoolShortName) ?></strong></div>
                <?php endif; ?>
                <?php if (preg_match('/^\d{4}$/', $foundedYear) === 1): ?>
                    <div><span>Tahun berdiri</span><strong><?= e($foundedYear) ?></strong></div>
                <?php endif; ?>
                <?php if ($schoolAddress !== ''): ?>
                    <div><span>Alamat</span><strong><?= e($schoolAddress) ?></strong></div>
                <?php endif; ?>
                <?php if ($headmasterName !== ''): ?>
                    <div><span>Kepala sekolah</span><strong><?= e($headmasterName) ?></strong></div>
                <?php endif; ?>
            </div>

            <?php if ($schoolHistory !== ''): ?>
                <section class="profile-detail">
                    <p class="eyebrow">SEJARAH SINGKAT</p>
                    <h3>Perjalanan sekolah</h3>
                    <p><?= nl2br(e($schoolHistory)) ?></p>
                </section>
            <?php endif; ?>

            <?php if ($schoolVision !== ''): ?>
                <section class="profile-detail">
                    <p class="eyebrow">VISI SEKOLAH</p>
                    <h3>Arah dan cita-cita pendidikan</h3>
                    <blockquote><?= e($schoolVision) ?></blockquote>
                </section>
            <?php endif; ?>

            <?php if ($schoolMission !== ''): ?>
                <section class="profile-detail">
                    <p class="eyebrow">MISI SEKOLAH</p>
                    <h3>Komitmen pelaksanaan visi</h3>
                    <p><?= nl2br(e($schoolMission)) ?></p>
                </section>
            <?php endif; ?>

            <?php if ($schoolFacilities !== ''): ?>
                <section class="profile-detail">
                    <p class="eyebrow">FASILITAS</p>
                    <h3>Lingkungan dan sarana pembelajaran</h3>
                    <p><?= nl2br(e($schoolFacilities)) ?></p>
                </section>
            <?php endif; ?>

            <?php if ($schoolRecognitions !== ''): ?>
                <section class="profile-detail">
                    <p class="eyebrow">PENGAKUAN KELEMBAGAAN</p>
                    <h3>Pengakuan dan pencapaian sekolah</h3>
                    <p><?= nl2br(e($schoolRecognitions)) ?></p>
                </section>
            <?php endif; ?>
        </div>
    </div>
</section>
