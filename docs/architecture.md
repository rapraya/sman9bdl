# Rancangan Portal Informasi SMAN 9 Bandar Lampung

## Karakter dan prinsip

Website publik menggunakan **Modern Institutional Editorial Design**: deep navy, putih, abu-abu netral, aksen champagne gold, fotografi autentik yang dikelola sekolah, tipografi editorial, whitespace, dan interaksi yang tidak berlebihan. CMS memiliki desain ruang kerja yang terpisah.

Prioritas: akurasi informasi, keamanan, kemudahan penggunaan, aksesibilitas, performa, lalu kualitas visual.

## Sitemap publik

- Beranda
- Profil
- Akademik
- Kesiswaan
- Berita → Detail berita
- Pengumuman → Detail pengumuman
- Agenda → Detail agenda
- Prestasi → Detail prestasi
- Galeri
- Layanan Publik / PPID
- SPMB
- Kontak
- Pencarian
- Kebijakan Privasi

Menu publik diarahkan ke rute yang aktif. Konten yang belum tersedia ditampilkan sebagai status belum dipublikasikan, bukan data contoh.

## Modul CMS

- Dashboard dan aktivitas terakhir.
- Konten: berita, pengumuman, agenda, prestasi, halaman informasi.
- Pustaka media: upload, teks alternatif, status galeri, pemakaian gambar utama.
- Identitas dan pengaturan sekolah.
- Pengguna dengan empat peran awal.
- Audit log.

## Alur pengunjung

1. Beranda → ringkasan identitas, akses cepat, berita, pengumuman, agenda, prestasi, galeri.
2. Pengunjung membuka detail konten → membaca isi dan metadata → memanfaatkan fitur baca otomatis atau berbagi.
3. Pengunjung menggunakan pencarian global atau chatbot untuk menemukan informasi publik.
4. Pengunjung memeriksa profil, layanan publik, SPMB, atau kontak.

## Arsitektur

PHP Native OOP dengan custom router, MVC ringan, service layer, PDO prepared statements, MySQL/MariaDB, HTML semantik, CSS lokal, JavaScript modular, dan Fetch API untuk chatbot. Document root diarahkan ke `public/`.

## Model data inti

- `users`: akun CMS, peran, status aktif, waktu login.
- `settings`: identitas dan konfigurasi sekolah dalam pasangan key/value.
- `contents`: konten bertipe news, announcement, event, achievement, page.
- `media`: metadata gambar, teks alternatif, flag galeri, pengunggah.
- `activity_logs`: jejak aktivitas pengguna.
- `login_attempts`: pembatasan percobaan login.

## Batas chatbot

Chatbot melakukan retrieval dari setting dan konten publik dengan normalisasi teks, pencocokan sinonim, dan LIKE query terparameterisasi. Tidak mengakses akun admin atau konten draft. Tidak memakai API Key dan tidak mengklaim sebagai LLM generatif.

## Keamanan

Password hashing, regenerasi session ID, cookie HttpOnly/SameSite, CSRF token, validasi server, output escaping, PDO prepared statements, rate limit login/chat, validasi MIME/ukuran upload, pembatasan file upload, CSP/security headers, dan audit trail.
