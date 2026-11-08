PENGATURAN LOGIN ADMINISTRATOR DAN TEMA BACKGROUND — SMALAN

Ruang lingkup:
- Login administrator menggunakan satu login-panel di tengah background, tanpa login-aside atau panel informasi samping.
- Identitas sekolah (logo, nama, visi, dan misi) ditampilkan dari pengaturan website.
- Ambient lighting, floating orb, entrance animation, hover response, brand interaction, input focus glow, button shine/elevation, password toggle, serta prefers-reduced-motion tetap dipertahankan.
- Ornamen sparkle yang sebelumnya berada di atas form login dihapus karena mengganggu fokus input.
- Admin Super Admin dapat mengelola background login pada Admin > Identitas & Pengaturan Website.
- Setiap gambar dapat menggunakan salah satu dari 22 tema preset atau Custom Palette dengan warna accent, champagne gold, dan base/background.
- Nama sekolah mengikuti accent warna tema aktif, sementara label identitas dan Administrator Portal memakai Champagne Gold.
- Gambar dipilih secara acak pada setiap pembukaan login; bila ada lebih dari satu, sistem berusaha menghindari gambar yang sama dengan request login sebelumnya pada sesi tersebut.
- Pesan error login otomatis menghilang setelah tiga detik dengan animasi singkat.
- Custom palette disimpan bersama metadata background di tabel settings. Tidak diperlukan perubahan schema database.

Cara mengelola:
1. Login sebagai super_admin.
2. Buka Admin > Identitas & Pengaturan Website.
3. Pada bagian Background login admin, aktifkan rotasi.
4. Unggah satu atau beberapa gambar.
5. Pilih salah satu tema preset atau Custom Palette untuk masing-masing gambar.
6. Jika menggunakan Custom Palette, atur warna aksen, Champagne Gold, dan warna dasar.
7. Simpan pengaturan.
8. Buka /admin/login, lalu refresh atau buka tab baru untuk melihat rotasi background dan tema.

22 tema preset dan 1 pilihan Custom Palette:
- Midnight Blue & Champagne
- Royal Blue & Gold
- Electric Blue & Silver
- Emerald & Champagne
- Violet & Champagne
- Graphite & Electric Blue
- Deep Navy & Champagne Gold
- Ocean Teal & Pearl
- Sapphire Blue & Platinum
- Arctic Blue & Silver
- Cobalt Blue & Gold
- Lagoon Teal & Ivory
- Jade Emerald & Champagne
- Forest Green & Sand
- Amethyst Violet & Silver
- Plum Orchid & Rose Gold
- Ruby Red & Blush Gold
- Rose Quartz & Pearl
- Copper Ember & Champagne
- Bronze Heritage & Ivory
- Obsidian Steel & Ice
- Silver Graphite & Blue
- Custom Palette

Keamanan dan perilaku:
- Pengaturan hanya dapat diubah oleh super_admin dan seluruh perubahan melewati CSRF.
- Warna custom divalidasi sebagai HEX enam digit sebelum disimpan.
- CSS tema custom dikeluarkan oleh endpoint same-origin /login-theme.css, dengan nilai warna yang telah disanitasi. Tidak perlu inline style atau melonggarkan Content Security Policy.
- File disimpan dalam pustaka media dengan nama acak dan MIME divalidasi server-side.
- Menghapus background dari rotasi tidak menghapus file media; file tetap bisa diatur dari Pustaka Media setelah tidak dipakai.
- Header no-store pada halaman login dipertahankan supaya pilihan acak tidak tertahan cache browser.

Optimasi gambar:
- File asli diterima hingga 20 MB per gambar.
- Background dapat diunggah maksimal lima gambar per pengiriman.
- Server mengoptimasi gambar dengan target hasil simpan di bawah 1 MB dan batas keras 5 MB.
- Aktifkan ekstensi PHP GD; panduan tersedia pada docs/upload-limits.md.

Pengujian integrasi upload dan penyimpanan tetap perlu dilakukan pada instalasi lokal/hosting karena ketersediaan GD dan database mengikuti konfigurasi server tujuan.
