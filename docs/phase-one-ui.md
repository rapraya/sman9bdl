# Tahap Pertama — Design System dan Authentication

## Sistem warna

| Token | Nilai | Penggunaan |
|---|---|---|
| Primary / Midnight Navy | `#081A33` | Sidebar, latar identitas, struktur utama |
| Secondary / Royal Blue | `#2457A6` | Navigasi sekunder dan elemen pendukung |
| Accent / Electric Blue | `#2878F0` | Aksi utama, tautan aktif, fokus interaksi |
| Champagne Gold | `#D8BD82` | Aksen identitas dan detail premium |
| Background | `#F3F6FA` | Latar area kerja terang |
| Surface | `#FFFFFF` | Panel dan kartu |
| Surface Glass | `rgba(255,255,255,.84)` | Header transparan dengan blur secara terbatas |
| Text | `#172B45` | Teks utama |
| Muted | `#718198` | Metadata dan teks sekunder |
| Success | `#13845B` | Status berhasil |
| Warning | `#C58A20` | Status perhatian |
| Danger | `#C64D61` | Status kesalahan/destruktif |

Mode gelap disiapkan sebagai latar identitas dan elemen navigasi. Latar konten CMS tetap terang untuk menjaga keterbacaan dan efisiensi kerja. Efek glass digunakan terbatas pada topbar/login, bukan seluruh permukaan.

## Cakupan implementasi

- Penyelarasan token warna pada website publik dan CMS.
- Penyegaran halaman login administrator.
- Label dan petunjuk formulir login yang lebih jelas.
- Tombol tampil/sembunyikan kata sandi dengan atribut aksesibilitas.
- Penyegaran sidebar, topbar, panel dashboard, tombol, tabel, form, status, dan notifikasi.
- Fokus keyboard yang terlihat dan dukungan `prefers-reduced-motion`.
- Penamaan file, selector, dan identifier tetap fungsional tanpa label revisi/versi.

## File utama yang diperbarui

- `resources/views/auth/login.php`
- `public/assets/css/admin.css`
- `public/assets/css/site.css`
- `public/assets/js/admin.js`

## Pengujian yang dilakukan

- PHP lint: 54 file tanpa kesalahan sintaks.
- JavaScript syntax check untuk `admin.js`.
- Smoke test struktur proyek.

Pengujian visual lintas browser, perangkat, dan resolusi tetap perlu dilakukan di komputer pengembang setelah paket diterapkan.
