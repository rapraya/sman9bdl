# Portal Website SMA Negeri 9 Bandar Lampung

Portal informasi sekolah dengan CMS berbasis PHP Native OOP, MySQL/MariaDB, PDO, dan custom routing. Implementasi mengacu pada tiga dokumen blueprint yang disertakan di `docs/reference/`.

## Isi paket

- Website publik: beranda editorial, profil, akademik, kesiswaan, berita, pengumuman, agenda, prestasi, galeri, layanan publik/PPID, SPMB, kontak, pencarian, dan kebijakan privasi.
- CMS/admin: dashboard, pengelolaan berita/pengumuman/agenda/prestasi/halaman informasi, pustaka media, identitas sekolah, pengguna, peran akses, dan riwayat aktivitas.
- Chatbot informasi sekolah tanpa API Key: pencarian kata kunci dan pencocokan informasi pada database publik. Chatbot bukan model AI generatif dan tidak akan mengarang jawaban.
- Fitur baca otomatis menggunakan Web Speech API pada browser yang mendukungnya.
- Toolbar aksesibilitas: skala teks, kontras tinggi, grayscale, mode gelap/terang, garis bawah tautan, sorot fokus, kurangi animasi, dan reset.
- SEO dasar, canonical, Open Graph, sitemap dan robots.txt dinamis, microdata Schema.org, HTML semantik, proteksi CSRF, PDO prepared statements, session hardening, pembatasan login, validasi unggahan, dan activity log.

## Kebutuhan server

- PHP 8.1 atau lebih baru.
- Ekstensi PHP: `pdo`, `pdo_mysql`, `fileinfo`, `mbstring`, dan `iconv`.
- MySQL 8.0+ atau MariaDB yang mendukung InnoDB, `utf8mb4`, dan foreign key.
- Apache dengan `mod_rewrite` untuk hosting Apache. Untuk pengembangan lokal, PHP built-in server sudah disertakan melalui file BAT.

Tidak ada library frontend eksternal yang wajib diunduh. Aset CSS dan JavaScript utama disimpan lokal dalam proyek.

## Menjalankan di Windows

1. Ekstrak seluruh isi ZIP ke folder `D:\Project\sman9bdl`.
2. Pastikan PHP CLI tersedia. XAMPP dapat digunakan; file `run-local.bat` akan mencari PHP pada PATH dan beberapa lokasi instalasi XAMPP umum.
3. Jalankan MySQL/MariaDB dari XAMPP Control Panel atau layanan database yang digunakan.
4. Buat database kosong, misalnya `sman9bdl`, melalui phpMyAdmin. Gunakan collation `utf8mb4_unicode_ci` bila diminta.
5. Klik dua kali `run-local.bat`.
6. Browser akan membuka `http://127.0.0.1:8099/install`. Lengkapi host, port, nama database, akun database, nama administrator, email, dan kata sandi awal.
7. Setelah instalasi berhasil, login melalui `http://127.0.0.1:8099/admin/login`.

Jika browser tidak terbuka otomatis, buka alamat di atas secara manual. Jika port 8099 sedang digunakan, hentikan aplikasi lain yang memakai port tersebut atau ubah port pada file BAT dan alamat yang digunakan.

### Pengaturan database XAMPP yang lazim

| Kolom | Nilai lokal yang lazim |
|---|---|
| Host | `127.0.0.1` |
| Port | `3306` |
| Nama database | `sman9bdl` |
| Username | `root` |
| Password | Kosong pada instalasi XAMPP default; gunakan sesuai konfigurasi lokal Anda |

Jangan gunakan akun `root` tanpa password pada server production. Buat database dan akun database khusus dengan hak minimum yang diperlukan.

## Akun administrator

Akun administrator pertama dibuat sendiri saat instalasi. Tidak ada username/password bawaan yang ditanam di source code. Kata sandi minimal 12 karakter dan disimpan dengan `password_hash()`.

Peran awal CMS:

- **Super Admin**: seluruh konfigurasi, pengguna, konten, media, dan audit log.
- **Editor**: membuat, memperbarui, menerbitkan, dan menghapus konten/media.
- **Operator**: membuat dan memperbarui konten serta mengelola media.
- **Viewer**: membaca data CMS tanpa mengubahnya.

## Mengelola konten

Setelah login, gunakan menu di sidebar. Berita, pengumuman, agenda, prestasi, dan halaman informasi menggunakan satu pola editor. Konten berstatus Draft tidak muncul di website publik. Ubah status menjadi Terbit setelah konten diperiksa. Waktu publikasi dapat diisi untuk penjadwalan.

Editor konten saat ini menggunakan teks biasa agar input aman dan sederhana. Paragraf ditampilkan dengan pemisahan baris otomatis. Untuk foto:

1. Masuk ke **Pustaka Media**.
2. Unggah foto JPG/PNG/WebP dengan ukuran file asli maksimal 20 MB. Sistem mengoptimasi gambar di server dengan target hasil simpan di bawah 1 MB; ekstensi PHP GD harus aktif.
3. Isi teks alternatif yang menjelaskan isi foto.
4. Pilih apakah foto akan tampil di galeri publik.
5. Pilih media tersebut sebagai gambar utama pada formulir konten.

Gunakan foto dan dokumen yang telah diverifikasi serta memperoleh izin publikasi dari pihak sekolah/warga sekolah terkait.

## Pengaturan profil sekolah

Menu **Identitas & Pengaturan** berisi identitas yang ditampilkan pada website, misalnya alamat, visi, nama kepala sekolah, fasilitas, pengakuan kelembagaan, kontak, tautan media sosial, logo, dan gambar utama. Nilai awal yang telah diisi bersumber dari informasi sekolah yang sebelumnya diberikan untuk proyek ini. Periksa kembali seluruh informasi sebelum website dipublikasikan.

Untuk logo dan foto utama, unggah lebih dahulu ke Pustaka Media lalu isikan jalur file relatif, misalnya `uploads/nama-file.webp`. Kosongkan logo untuk menggunakan tanda identitas monogram bawaan; kosongkan foto utama agar hero menggunakan ilustrasi geometris.

## Chatbot tanpa API Key

Chatbot mengakses setting profil dan konten yang berstatus Terbit. Mesin dasar menggunakan normalisasi teks, pencocokan kata kunci/sinonim, dan pencarian `LIKE` terparameterisasi di MySQL. Sumber informasi ditampilkan sebagai tautan ke halaman publik.

Chatbot tidak menggunakan OpenAI, Gemini, Claude, ResponsiveVoice, atau layanan AI eksternal; tidak memerlukan API Key atau biaya token. Karena pendekatannya retrieval/rule-based, chatbot tidak memiliki kemampuan penalaran bebas seperti model bahasa generatif. Jika data tidak ditemukan, bot menyampaikan bahwa informasi belum tersedia.

## Baca otomatis dan aksesibilitas

Pembacaan otomatis menggunakan Web Speech API (`SpeechSynthesis`) yang disediakan browser/perangkat. Dukungan suara bahasa Indonesia bergantung pada suara yang terpasang pada sistem operasi dan browser. Fitur tidak otomatis berjalan saat halaman dibuka; pengunjung harus menekan tombol **Baca artikel**.

Screen-reader compatibility dibangun melalui landmark HTML, struktur heading, label, fokus keyboard, status ARIA, dan teks alternatif gambar. Pengujian manual dengan NVDA, VoiceOver, dan TalkBack tetap perlu dilakukan pada perangkat target sebelum menyatakan kepatuhan WCAG 2.2 AA.

## Publikasi ke hosting

1. Siapkan hosting yang mendukung PHP 8.1+ dan MySQL/MariaDB.
2. Buat database dan user database khusus. Catat host, nama database, username, dan password.
3. Upload seluruh proyek ke server melalui File Manager/FTP.
4. **Atur document root domain ke folder `public/`**. Jangan arahkan domain ke folder utama proyek jika bisa dihindari.
5. Pastikan folder `config/` dapat ditulis sementara saat proses instalasi agar installer dapat membuat `config/local.php`. Setelah instalasi, batasi izin tulis file konfigurasi.
6. Pastikan `public/uploads/` dapat ditulisi oleh PHP dan file eksekusi dinonaktifkan.
7. Buka domain `/install`, selesaikan instalasi, lalu login ke `/admin/login`.
8. Aktifkan HTTPS. Sesuaikan konfigurasi backup, email, DNS, dan kebijakan privasi sesuai infrastruktur sekolah.
9. Setelah instalasi, hapus izin tulis folder `config/` bila tidak lagi diperlukan. Jangan menghapus `config/local.php`.

Bila hosting tidak memungkinkan document root diarahkan ke `public/`, minta bantuan administrator hosting untuk mengatur virtual host atau memindahkan isi `public/` ke document root secara terencana. Jangan menyalin folder `app`, `config`, `database`, `resources`, `routes`, `storage`, atau `vendor` ke direktori web publik.

## Backup dan pemeliharaan

- Backup database secara berkala menggunakan `mysqldump` atau fasilitas backup hosting.
- Backup file `public/uploads/` dan `config/local.php` secara aman.
- Simpan kredensial database di luar repository dan batasi izin akses.
- Periksa `storage/logs/` bila membuat integrasi logging tambahan. Error PHP standar juga dikirim ke log server.
- Jangan menghapus gambar yang masih digunakan oleh konten.
- Pastikan PHP, MySQL/MariaDB, dan server web tetap mendapat pembaruan keamanan.

## Struktur direktori

```text
app/                Controller, Core, Service, Helpers
bootstrap/          Bootstrap aplikasi dan keamanan
config/             Konfigurasi aplikasi dan konfigurasi database lokal
 database/          Skema database
public/             Document root, aset, dan unggahan publik
resources/views/    Layout dan tampilan
routes/             Pemetaan URL
storage/            Direktori privat untuk log/cache/session/export
 tests/              Pemeriksaan dasar proyek
docs/reference/     Blueprint master dan referensi teknis
```

## Batasan yang perlu diketahui

- Paket ini adalah fondasi portal sekolah dan CMS fungsional. Sebelum tayang, sekolah masih perlu meninjau konten, mengunggah logo/foto resmi, mengisi kontak yang belum tersedia, dan memeriksa informasi kebijakan/layanan berdasarkan dokumen resmi.
- Form kontak publik, email transaksional, kalender interaktif penuh, unggah dokumen PDF melalui CMS, editor WYSIWYG, dan sistem permohonan informasi PPID belum disediakan sebagai alur transaksi lengkap.
- Belum dilakukan pengujian pengguna dengan NVDA/VoiceOver/TalkBack maupun pengukuran Core Web Vitals pada hosting produksi. Jangan menganggap paket otomatis tersertifikasi WCAG atau production-hardened tanpa pengujian infrastruktur.
- Sitemap dibuat dinamis di `/sitemap.xml` dan `robots.txt` menggunakan host yang sedang diakses. Setelah domain dan HTTPS produksi aktif, uji kembali canonical URL, sitemap, dan robots melalui domain sebenarnya.

## Blueprint referensi

Tiga dokumen sumber disimpan apa adanya di `docs/reference/`. Implementasi menyesuaikan arsitektur PHP Native OOP, keamanan, CMS, chatbot database tanpa API Key, aksesibilitas, aturan penamaan tanpa label versi, serta karakter website publik institusi pendidikan dalam dokumen tersebut.
