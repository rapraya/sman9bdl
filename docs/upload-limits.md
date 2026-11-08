# Pengaturan batas unggah dan optimasi gambar

## Batas file

- File gambar asli yang dikirim ke server: maksimal **20 MB per gambar**.
- Background login: maksimal lima gambar per satu kali pengiriman.
- Target hasil setelah optimasi: **di bawah 1 MB per gambar**.
- Batas keras ukuran file hasil simpan: **5 MB**.

Dengan demikian, gambar asli yang berukuran lebih dari 5 MB tetap dapat diterima (hingga 20 MB), diproses di server, lalu disimpan dalam format terkompresi. Aplikasi tidak menyimpan file mentah yang besar sebagai media publik.

## Kompresi otomatis

Kompresi dilakukan di server menggunakan ekstensi PHP GD. Jika dukungan WebP tersedia, JPG, PNG, dan WebP akan dikonversi ke WebP dengan tetap menjaga transparansi. Jika WebP tidak tersedia, JPEG akan dikompresi sebagai JPEG dan PNG transparan diproses sebagai PNG. Proses mengatur dimensi dan kualitas secara bertahap hingga ukuran file berada di bawah 1 MB. Jika target tidak dapat dicapai, unggahan ditolak dengan pesan yang dapat ditindaklanjuti.

Aktifkan ekstensi GD pada konfigurasi PHP yang digunakan untuk menjalankan website:

```ini
extension=gd
```

Pada XAMPP, buka `php.ini` melalui tombol Config di samping Apache, pastikan baris `extension=gd` aktif (tidak diawali titik koma), simpan, lalu restart Apache. Untuk server lokal dari `run-local.bat`, aktifkan GD pada `php.ini` milik `php.exe` yang ditemukan oleh file BAT, kemudian jalankan ulang server.

## Server lokal melalui `run-local.bat`

File BAT menjalankan PHP built-in server dengan `upload_max_filesize=20M`, `post_max_size=128M`, dan `max_file_uploads=10`. Hentikan server lalu jalankan kembali BAT setelah pembaruan.

## Hosting PHP-FPM/FastCGI

File `public/.user.ini` mengatur batas yang sama. Sebagian hosting menerapkan perubahan setelah jeda cache konfigurasi PHP atau setelah PHP-FPM dimuat ulang.

## XAMPP Apache dengan mod_php

Jika website dijalankan melalui Apache XAMPP, buka `php.ini` dari menu Config pada Apache, lalu sesuaikan minimal:

```ini
upload_max_filesize=20M
post_max_size=128M
max_file_uploads=10
max_execution_time=120
max_input_time=120
extension=gd
```

Simpan dan restart Apache. `post_max_size` harus lebih besar daripada total file yang dikirim dalam satu formulir.

## Validasi keamanan

Aplikasi memeriksa kode error PHP, ukuran file asli, MIME melalui `finfo`, signature/dimensi gambar, dan batas resolusi 16 megapiksel. Nama file hasil simpan dibuat acak. File hasil kompresi dicatat pada tabel media dengan ukuran aktualnya. Jika proses penyimpanan metadata database gagal, file hasil kompresi dihapus kembali.
