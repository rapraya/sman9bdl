# Catatan Pengembangan Tahap Pertama

## Cakupan

Tahap ini memusatkan pekerjaan pada design system antarmuka administrasi, pengalaman login, dan penyelarasan token warna website publik.

## Sistem warna

| Token | Nilai | Penggunaan |
|---|---|---|
| Deep Navy / Midnight Blue | #09162D | Latar autentikasi dan fondasi sidebar |
| Primary Navy | #142B50 | Identitas utama institusi |
| Royal Blue | #2456A6 | Navigasi aktif dan aksi utama |
| Electric Blue | #2878F0 | Fokus, interaksi, dan aksen digital |
| Champagne Gold | #D8BD83 | Aksen institusional |
| Soft Cool White | #F4F7FB | Latar ruang kerja |
| Glass Surface | rgba(255,255,255,.76) | Permukaan transparan selektif |
| Slate | #52627A | Teks sekunder |
| Emerald | #16865B | Status berhasil |
| Amber | #C18419 | Status perhatian |
| Rose | #D34F67 | Status kesalahan |

## Perubahan

- Menata ulang halaman login menjadi layout institusional dua panel dengan pola geometris yang ringan.
- Menambahkan pengantar sistem, identitas sekolah, indikator akses terlindungi, dan hierarki formulir yang lebih jelas.
- Menambahkan tombol tampil/sembunyikan kata sandi dengan atribut aksesibilitas.
- Memperbarui warna sidebar, navigasi aktif, topbar, panel sambutan, kartu statistik, panel aktivitas, tabel, tombol, dan status.
- Menyelaraskan token warna dasar website publik tanpa mengubah struktur konten atau route.
- Menambahkan responsivitas login untuk layar kecil dan mempertahankan dukungan reduced motion.

## File yang diperbarui

- `resources/views/auth/login.php`
- `public/assets/css/admin.css`
- `public/assets/css/site.css`
- `public/assets/js/admin.js`

## Batas cakupan

Tahap ini belum membangun fitur baru seperti notification center, command palette, global search CMS, granular permission, rich text editor, atau analytics lanjutan. Fitur tersebut tetap menjadi pekerjaan tahap berikutnya. Tidak ada perubahan skema database dan tidak ada perubahan kredensial administrator.

## Pemeriksaan yang dijalankan

- PHP syntax check untuk halaman login, layout admin, dashboard, controller autentikasi, dan core auth.
- JavaScript syntax check untuk `admin.js`.
- Project smoke check.
- Pemeriksaan nama/komentar terhadap pola label versi dan iterasi yang dilarang.

Pengujian visual lintas browser, pengujian login terhadap database MySQL lokal pengguna, dan pengukuran WCAG tetap perlu dilakukan pada lingkungan pengguna.
