<div class="accessibility-dock" aria-label="Pengaturan aksesibilitas">
    <button class="accessibility-toggle" type="button" aria-expanded="false" aria-controls="accessibility-panel">
        <span aria-hidden="true">Aa</span>
        <span class="sr-only">Buka pengaturan aksesibilitas</span>
    </button>

    <section class="accessibility-panel" id="accessibility-panel" hidden aria-label="Pengaturan tampilan dan pembacaan">
        <div class="panel-heading">
            <strong>Aksesibilitas</strong>
            <button type="button" class="icon-button" data-accessibility-close aria-label="Tutup pengaturan">&times;</button>
        </div>

        <div class="accessibility-actions">
            <button type="button" data-text-step="-1">A− <span>Perkecil teks</span></button>
            <button type="button" data-text-step="1">A+ <span>Perbesar teks</span></button>
            <button type="button" data-text-reset>Reset Teks <span>Ukuran normal</span></button>
            <button type="button" data-accessibility="contrast">Kontras Tinggi <span>Warna kontras</span></button>
            <button type="button" data-accessibility="grayscale">Skala Abu <span>Hitam putih</span></button>
            <button type="button" data-accessibility="underline">Garis Bawah <span>Tautan teks</span></button>
            <button type="button" data-accessibility="focus">Sorot Fokus <span>Batas aktif</span></button>
            <button type="button" data-accessibility="motion">Kurangi Animasi <span>Matikan efek</span></button>
            <button type="button" data-theme="light">Mode Terang <span>Tampilan cerah</span></button>
            <button type="button" data-theme="dark">Mode Gelap <span>Tampilan malam</span></button>
            <button type="button" data-accessibility-reset class="accessibility-reset">Reset Semua Pengaturan</button>
        </div>

        <p class="small-note">Pengaturan disimpan pada perangkat dan dapat direset kapan saja.</p>
    </section>
</div>

<div class="reading-bar" data-reading-bar hidden>
    <div class="reading-title">
        <strong>Baca Halaman</strong>
        <span data-reading-status role="status">Siap dibacakan</span>
    </div>
    <div class="reading-controls">
        <button type="button" data-speech-action="play">Putar</button>
        <button type="button" data-speech-action="pause">Jeda</button>
        <button type="button" data-speech-action="resume">Lanjutkan</button>
        <button type="button" data-speech-action="stop">Hentikan</button>
        <label class="reading-rate">Kecepatan
            <select data-speech-rate class="reading-rate-select">
                <option value="0.8">Lambat</option>
                <option value="1" selected>Normal</option>
                <option value="1.2">Cepat</option>
            </select>
        </label>
    </div>
</div>