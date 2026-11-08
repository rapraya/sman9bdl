Blueprint ini saya susun supaya ketika nanti kamu memulai proyek website baru, AI tidak sekadar membuat halaman PHP yang terlihat bagus, tetapi membangun sebuah aplikasi web yang memiliki:

- Arsitektur folder profesional dan mudah dikembangkan.
- Keamanan berlapis sejak awal pembangunan.
- Standar Clean Code dan pemisahan tanggung jawab.
- Sistem autentikasi, otorisasi, dan manajemen pengguna.
- Dashboard modern dengan pengalaman pengguna premium.
- Komponen UI konsisten di seluruh halaman.
- Operasi data interaktif tanpa reload.
- Sistem pencatatan aktivitas dan pengelolaan data.
- Dokumentasi, migration, seeder, dan pengujian.
- Struktur yang siap dikembangkan menjadi aplikasi berskala besar.

Saya juga akan memasukkan aturan penting agar AI tidak sembarangan mengubah struktur, merusak fitur yang sudah berjalan, atau membuat kode tambal-sulam.

# BLUEPRINT MASTER

# PHP Native Professional Web Application

Security First, Clean Architecture, Premium UI/UX 2027

Visi desain: Modern Monochromatic, Aero Glassmorphism, Soft Glow, aksen Vibrant Blue dan Gold, tipografi bersih, interaksi halus, serta tampilan responsif untuk desktop, tablet, dan smartphone.

## 1. Arsitektur teknologi yang direkomendasikan

Saya merekomendasikan pendekatan PHP Native terstruktur, bukan PHP prosedural yang mencampurkan query database, HTML, dan logika bisnis dalam satu file.

| Komponen        | Teknologi                   |
| --------------- | --------------------------- |
| Backend         | PHP Native 8.x, OOP         |
| Arsitektur      | MVC + Service Layer         |
| Dependency      | Composer                    |
| Autoloading     | PSR-4                       |
| Database        | MySQL / MariaDB             |
| Database Access | PDO + Prepared Statements   |
| Frontend CSS    | Tailwind CSS                |
| Interaksi UI    | Alpine.js                   |
| AJAX            | Fetch API                   |
| Notifikasi      | SweetAlert2                 |
| Tabel           | DataTables                  |
| Grafik          | ApexCharts / Chart.js       |
| Ikon            | Lucide Icons                |
| Routing         | Custom Router               |
| Keamanan        | CSRF, CSP, Session Security |
| Gambar          | GD / Imagick                |
| Ekspor          | CSV, XLSX, PDF, JSON        |
| Logging         | Application Activity Logger |

Prinsip arsitektur:

1. Tidak menggunakan framework backend seperti Laravel atau CodeIgniter.
2. Tetap menggunakan Composer untuk dependency dan autoloading.
3. Seluruh query database menggunakan PDO.
4. Controller tidak boleh menampung seluruh logika bisnis.
5. Business logic ditempatkan di Service.
6. Query kompleks dipisahkan ke Repository apabila dibutuhkan.
7. Validasi dilakukan di sisi server, bukan hanya JavaScript.
8. Semua halaman privat wajib melewati middleware autentikasi dan otorisasi.
9. Seluruh komponen UI menggunakan desain yang konsisten.
10. Struktur dibuat modular agar mudah dikembangkan tanpa harus membangun ulang aplikasi.

## 2. Struktur folder profesional

Berikut struktur dasar yang saya rekomendasikan.

professional-app/
│
├── app/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── DashboardController.php
│   │   ├── UserController.php
│   │   ├── ProfileController.php
│   │   ├── NotificationController.php
│   │   └── SettingsController.php
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Role.php
│   │   └── ActivityLog.php
│   │
│   ├── Services/
│   │   ├── AuthService.php
│   │   ├── UserService.php
│   │   ├── NotificationService.php
│   │   ├── ImageService.php
│   │   └── ExportService.php
│   │
│   ├── Repositories/
│   │   ├── UserRepository.php
│   │   └── ActivityLogRepository.php
│   │
│   ├── Middleware/
│   │   ├── AuthMiddleware.php
│   │   ├── GuestMiddleware.php
│   │   ├── RoleMiddleware.php
│   │   ├── PermissionMiddleware.php
│   │   └── CsrfMiddleware.php
│   │
│   ├── Validators/
│   ├── Helpers/
│   ├── Exceptions/
│   ├── Policies/
│   └── Providers/
│
├── bootstrap/
│   ├── app.php
│   ├── database.php
│   └── security.php
│
├── config/
│   ├── app.php
│   ├── database.php
│   ├── auth.php
│   ├── session.php
│   ├── security.php
│   ├── upload.php
│   └── permissions.php
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── schema/
│
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── assets/
│       ├── css/
│       ├── js/
│       ├── images/
│       └── fonts/
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── components/
│   │   ├── partials/
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── users/
│   │   ├── profile/
│   │   ├── settings/
│   │   └── errors/
│   │
│   ├── css/
│   └── js/
│
├── routes/
│   ├── web.php
│   ├── api.php
│   └── auth.php
│
├── storage/
│   ├── logs/
│   ├── cache/
│   ├── sessions/
│   ├── uploads/
│   └── exports/
│
├── tests/
│   ├── Unit/
│   ├── Feature/
│   └── Security/
│
├── vendor/
│
├── .env
├── .env.example
├── .gitignore
├── composer.json
├── composer.lock
├── phpunit.xml
└── README.md

Standar penting

Folder `app`, `config`, `database`, `resources`, `routes`, `storage`, dan `vendor` tidak boleh bisa diakses langsung melalui browser. Document root hosting diarahkan ke folder `public/`.

## 3. Modul keamanan profesional

| Fitur            | Standar implementasi                                   |
| ---------------- | ------------------------------------------------------ |
| Password         | `password_hash()` dan `password_verify()`              |
| Login            | Validasi kredensial dan pembatasan percobaan           |
| Session          | `HttpOnly`, `Secure`, `SameSite`                       |
| Session ID       | Regenerasi setelah autentikasi                         |
| SQL Injection    | PDO Prepared Statements                                |
| XSS              | Output escaping sesuai konteks                         |
| CSRF             | Token acak, validasi setiap request yang mengubah data |
| Authorization    | Role dan Permission                                    |
| Route            | Middleware autentikasi                                 |
| Upload           | Validasi MIME, ukuran, ekstensi dan nama file          |
| Error            | Exception handler terpusat                             |
| Database         | Least privilege dan transaksi                          |
| Activity Log     | Catatan tindakan pengguna                              |
| Security Headers | CSP, HSTS pada HTTPS, X-Content-Type-Options           |
| Brute Force      | Rate limiting dan penundaan percobaan                  |

### Ketentuan keamanan tambahan

- Jangan pernah menyimpan password dalam bentuk teks biasa.
- Jangan mengandalkan penyembunyian tombol sebagai pengamanan hak akses.
- Validasi kepemilikan data untuk mencegah IDOR.
- Jangan menampilkan stack trace atau kredensial database kepada pengguna.
- Jangan menyimpan token rahasia di repository Git.
- Jangan menggunakan `md5()` atau `sha1()` untuk password.
- Jangan mempercayai data yang berasal dari browser.
- Gunakan HTTPS untuk seluruh halaman autentikasi.
- Jangan mengizinkan file upload dieksekusi sebagai PHP.
- Sediakan pembatasan akses untuk log dan hasil ekspor.

## 4. Sistem autentikasi dan hak akses

Sistem harus mendukung:

- Login dan logout.
- Remember Me dengan token aman jika diperlukan.
- Forgot Password dan Reset Password.
- Pengaturan profil mandiri.
- Ganti password.
- Avatar pengguna.
- Manajemen akun.
- Status akun aktif/nonaktif.
- Riwayat login.
- Pembatasan sesi.
- Multi-role dan permission jika aplikasi membutuhkannya.

Contoh struktur hak akses:

| Role          | Hak akses                                |
| ------------- | ---------------------------------------- |
| Super Admin   | Seluruh konfigurasi dan manajemen sistem |
| Administrator | Pengelolaan operasional                  |
| Operator      | Input dan pengelolaan data sesuai izin   |
| Viewer        | Melihat data yang diizinkan              |

Gunakan pola RBAC (Role-Based Access Control), dengan permission yang granular apabila diperlukan.

Contoh:

users.view
users.create
users.update
users.delete
users.export

settings.view
settings.update

activity_logs.view

## 5. Sistem navigasi dan pengalaman pengguna

Komponen wajib:
1. Smart Sidebar dengan menu berdasarkan permission.
2. Indikator menu aktif berdasarkan URL atau route name.
3. Sidebar collapse dan expand.
4. Breadcrumb dinamis.
5. Topbar dengan judul halaman.
6. Avatar administrator dan dropdown profil.
7. Lonceng notifikasi di pojok kanan atas.
8. Badge jumlah notifikasi belum dibaca.
9. Global Search.
10. Command Palette dengan shortcut `Ctrl + K`.
11. Bottom Navbar khusus mobile.
12. Pengaturan profil mandiri.
13. Pengaturan aplikasi dinamis.
14. Logout dengan konfirmasi.

Command Palette dapat digunakan untuk mencari halaman, menjalankan navigasi, dan menemukan tindakan yang diizinkan berdasarkan hak akses.

## 6. Standar desain Premium UI/UX 2027

Arah desain yang dikunci:

A. Color system

| Elemen     | Warna                       |
| ---------- | --------------------------- |
| Primary    | Deep Navy / Midnight Blue   |
| Secondary  | Royal Blue                  |
| Accent     | Vibrant Electric Blue       |
| Gold       | Champagne Gold              |
| Background | Soft Cool White / Dark Navy |
| Surface    | Translucent Glass           |
| Text       | Slate / White               |
| Success    | Emerald                     |
| Warning    | Amber                       |
| Danger     | Rose                        |

B. Visual language

- Aero Glassmorphism.
- Soft Glow dan subtle gradients.
- Modern Monochromatic.
- Glass cards dengan transparansi terkontrol.
- Border tipis dan radius konsisten.
- Shadow lembut dan bertingkat.
- Gold sebagai aksen, bukan warna dominan.
- Tipografi modern dan mudah dibaca.
- Kontras warna tetap memenuhi kebutuhan aksesibilitas.

C. Komponen UI

Glass Cards

Statistik dan informasi.

Premium Buttons

Primary, secondary, danger, ghost.

Glass Modals

Tambah, edit, hapus, detail.

Toast Notifications

Success, error, warning, info.

D. Micro-interactions

- Hover elevation.
- Soft glow pada elemen interaktif.
- Transisi warna dan shadow.
- Animasi dropdown.
- Modal dengan transisi halus.
- Skeleton loading.
- Button loading state.
- Ripple atau feedback interaksi yang ringan.
- Smooth sidebar transition.
- Toast masuk dan keluar secara halus.

Hindari animasi berlebihan. Hormati `prefers-reduced-motion`.

E. Responsive behavior

- Desktop: sidebar penuh.
- Tablet: sidebar compact.
- Mobile: sidebar menjadi drawer.
- Mobile: bottom navbar untuk navigasi utama.
- Tabel: responsive layout atau horizontal scroll yang terkontrol.
- Modal: menyesuaikan ukuran layar.
- Form: satu kolom pada layar kecil.

## 7. Sistem pengelolaan data modern

Setiap modul CRUD harus menggunakan standar interaksi yang sama.

Fitur wajib:

- Live Search menggunakan Fetch API.
- Filter dinamis.
- Pagination server-side.
- Sorting kolom.
- DataTables interaktif.
- Modal tambah data.
- Modal edit data.
- Modal detail.
- Modal konfirmasi hapus.
- SweetAlert2 untuk notifikasi.
- Loading indicator.
- Empty state.
- Error state.
- Bulk action jika diperlukan.
- Ekspor data.
- Soft Delete jika sesuai kebutuhan bisnis.

### Standar operasi tanpa reload

Gunakan Fetch API dengan format respons JSON yang konsisten.

Contoh:

{
  "success": true,
  "message": "Data berhasil disimpan.",
  "data": {
    "id": 1
  }
}

Ketentuan:

- Semua operasi tetap divalidasi server.
- Gunakan CSRF token pada request yang mengubah data.
- Tampilkan loading state.
- Cegah double submission.
- Tangani kegagalan jaringan.
- Perbarui tabel setelah operasi berhasil.
- Jangan mengirim pesan sukses sebelum server mengonfirmasi keberhasilan.

## 8. Database architecture

Gunakan database migration dan seeder manual yang terstruktur.

Tabel inti yang dapat dijadikan baseline:

| Tabel           | Fungsi                                          |
| --------------- | ----------------------------------------------- |
| users           | Akun pengguna                                   |
| roles           | Daftar role                                     |
| permissions     | Daftar izin                                     |
| role_user       | Relasi pengguna dan role                        |
| permission_role | Relasi permission dan role                      |
| sessions        | Sesi pengguna jika menggunakan database session |
| password_resets | Reset password                                  |
| activity_logs   | Catatan aktivitas                               |
| notifications   | Notifikasi                                      |
| app_settings    | Konfigurasi aplikasi                            |
| media           | Metadata file media                             |
| failed_jobs     | Jika menggunakan sistem antrean                 |

Tabel tersebut merupakan rancangan awal, bukan berarti seluruhnya wajib dibuat pada setiap proyek.

### Standar database

- Primary Key dan Foreign Key.
- Index untuk kolom pencarian.
- Unique constraint.
- Relasi yang jelas.
- Transaction untuk operasi kompleks.
- Soft Delete hanya pada data yang membutuhkan pemulihan.
- Timestamp `created_at` dan `updated_at`.
- Audit trail untuk aktivitas penting.
- Migration harus memiliki prosedur rollback.
- Seeder harus idempotent dan tidak merusak data produksi.

Contoh struktur migration:

database/migrations/
├── 001_create_users_table.php
├── 002_create_roles_table.php
├── 003_create_permissions_table.php
├── 004_create_activity_logs_table.php
├── 005_create_notifications_table.php
└── 006_create_app_settings_table.php

## 9. Penyimpanan dan optimasi gambar

Sistem media harus memiliki:

- Validasi file.
- MIME detection.
- Batas ukuran upload.
- Randomized filename.
- Kompresi gambar.
- Resize otomatis.
- Thumbnail.
- WebP atau AVIF jika didukung.
- Penghapusan file yang tidak digunakan.
- Pemisahan file asli dan hasil optimasi.
- Proteksi directory upload.

Jangan mempercayai ekstensi file saja untuk menentukan jenis gambar.

## 10. Activity Log dan notifikasi

Activity Log mencatat aktivitas penting seperti:

- Login dan logout.
- Penambahan data.
- Perubahan data.
- Penghapusan data.
- Ekspor data.
- Perubahan password.
- Perubahan permission.
- Perubahan pengaturan aplikasi.

Contoh informasi log:

User         : Administrator
Activity     : UPDATE
Module       : Users
Description  : Mengubah data pengguna
IP Address   : Recorded
Timestamp    : Recorded

Jangan mencatat password, token autentikasi, atau data rahasia ke dalam log.

Notification Center

Fitur:

- Notifikasi real-time melalui polling jika dibutuhkan.
- Badge unread.
- Mark as read.
- Mark all as read.
- Riwayat notifikasi.
- Kategori notifikasi.
- Link menuju halaman terkait.
- Pengaturan notifikasi.

## 11. Dashboard dan Data Visualization

Dashboard harus menampilkan informasi yang benar-benar berguna.

Komponen:

- KPI Cards.
- Total data.
- Data aktif/nonaktif.
- Aktivitas terbaru.
- Grafik tren.
- Grafik kategori.
- Ringkasan aktivitas pengguna.
- Notifikasi terbaru.
- Quick Actions.
- Periode data yang dapat difilter.

Gunakan ApexCharts atau Chart.js dengan warna yang konsisten dengan tema aplikasi.

Hindari grafik dekoratif yang tidak memiliki makna bisnis.

## 12. Struktur Friendly URL dan API

Contoh struktur URL:

/login
/logout

/dashboard

/users
/users/create
/users/{id}
/users/{id}/edit

/profile
/settings
/notifications
/activity-logs

Contoh API endpoint:

/api/v1/auth/login
/api/v1/auth/logout

/api/v1/dashboard/statistics

/api/v1/users
/api/v1/users/{id}

/api/v1/notifications
/api/v1/notifications/{id}/read

/api/v1/settings

Standar API:

- Prefix versi `/api/v1`.
- JSON request dan response.
- HTTP status code yang tepat.
- Validasi request.
- Authentication dan authorization.
- Rate limiting.
- CSRF untuk endpoint yang menggunakan autentikasi berbasis cookie.
- Pagination dan filter.
- Error response konsisten.
- Jangan mengembalikan informasi internal server.

## 13. Error handling dan quality assurance

Gunakan exception handler terpusat.

Jenis halaman error:

- 400 Bad Request.
- 401 Unauthorized.
- 403 Forbidden.
- 404 Not Found.
- 419 CSRF Error.
- 422 Validation Error.
- 429 Too Many Requests.
- 500 Internal Server Error.

Standar pengujian:

| Pengujian           | Tujuan                      |
| ------------------- | --------------------------- |
| Unit Test           | Menguji fungsi dan service  |
| Feature Test        | Menguji alur aplikasi       |
| Authentication Test | Memastikan login aman       |
| Authorization Test  | Memastikan hak akses        |
| CSRF Test           | Memastikan proteksi request |
| Validation Test     | Memastikan validasi server  |
| SQL Injection Test  | Memastikan query aman       |
| XSS Test            | Memastikan output di-escape |
| Regression Test     | Mencegah fitur lama rusak   |

Tambahkan pemeriksaan kode menggunakan PHPStan, PHP_CodeSniffer atau PHP-CS-Fixer sesuai kebutuhan.

## 14. Aturan pengembangan proyek

Ini bagian yang sangat penting ketika AI digunakan untuk membangun proyek secara bertahap.

1. Audit seluruh source code sebelum melakukan perubahan.
2. Jangan membuat ulang proyek apabila source sebelumnya masih tersedia.
3. Jangan menghapus fitur yang sudah berjalan tanpa persetujuan.
4. Jangan mengubah struktur database tanpa analisis dampak.
5. Jangan membuat migration duplikat.
6. Jangan menambahkan class CSS baru apabila komponen yang sama sudah tersedia dan dapat digunakan kembali.
7. Jangan menumpuk kode JavaScript, CSS, dan SQL di dalam controller.
8. Jangan membuat file dengan nama seperti `final2`, `new-version`, atau `fix-terbaru`.
9. Jangan memberikan patch parsial yang tidak jelas lokasi pemasangannya.
10. Setiap perubahan harus memiliki tujuan dan ruang lingkup yang jelas.
11. Setiap perubahan harus diuji.
12. Backup sebelum perubahan database atau deployment penting.
13. Pertahankan kompatibilitas antar-modul.
14. Periksa responsive desktop dan mobile.
15. Dokumentasikan perubahan pada README atau changelog.

## 15. Tahapan pembangunan yang direkomendasikan

1. Phase 1 — Project Foundation
   Setup PHP, Composer, autoloading, konfigurasi environment, database, custom router, MVC dan struktur folder.
2. Phase 2 — Security Core
   Authentication, session, password hashing, CSRF, middleware, authorization, validation, exception handler.
3. Phase 3 — Design System
   Tailwind CSS, layout, sidebar, topbar, typography, color tokens, buttons, cards, modals dan responsive navigation.
4. Phase 4 — Core Application
   Dashboard, CRUD, DataTables, Fetch API, SweetAlert2, search dan filter.
5. Phase 5 — Advanced Features
   Notification Center, Activity Log, Command Palette, export, media management, settings dan visualization.
6. Phase 6 — Quality & Security Audit
   Unit test, feature test, security testing, performance, regression dan code review.
7. Phase 7 — Deployment
   Konfigurasi hosting, HTTPS, permission folder, backup, logging, production environment dan dokumentasi.

# 16. MASTER PROMPT untuk ChatGPT, Gemini, dan Claude

Bagian ini adalah instruksi utama yang bisa langsung kamu salin ketika memulai proyek baru.

# MASTER PROMPT

## PROFESSIONAL PHP NATIVE WEB APPLICATION — ENTERPRISE ARCHITECTURE & PREMIUM UI/UX 2027

### A. PERAN AI

Bertindaklah sebagai Senior PHP Native Developer, Software Architect, Database Architect, Cybersecurity Engineer, UI/UX Designer, dan Quality Assurance Engineer.

Bangun aplikasi web profesional menggunakan PHP Native dengan standar Clean Code, Secure Coding, Modular Architecture, dan Premium UI/UX 2027.

Aplikasi harus memiliki arsitektur yang rapi, aman, mudah dipelihara, scalable, responsif, dan siap digunakan pada lingkungan production.

### B. PRINSIP UTAMA

1. Gunakan PHP Native OOP, tanpa framework backend.
2. Gunakan Composer dan PSR-4 Autoloading.
3. Terapkan MVC dan Service Layer.
4. Gunakan PDO Prepared Statements.
5. Terapkan separation of concerns.
6. Pisahkan Controller, Model, Service, Repository, Middleware, Validator, dan View.
7. Gunakan konfigurasi environment melalui `.env`.
8. Jangan mencampurkan business logic dengan tampilan.
9. Gunakan reusable components.
10. Hindari duplikasi kode.
11. Gunakan dependency yang stabil dan sesuai kompatibilitas proyek.
12. Terapkan standar production-ready sejak awal.

### C. BACKEND DAN SECURITY

Implementasikan:

- Login dan logout.
- Password hashing menggunakan password_hash dan password_verify.
- Session security.
- Session ID regeneration.
- Remember Me yang aman jika diperlukan.
- Forgot Password dan Reset Password.
- CSRF protection.
- XSS prevention.
- SQL Injection prevention.
- Input validation dan output escaping.
- Authentication middleware.
- Role-Based Access Control.
- Permission middleware.
- Rate limiting.
- Secure file upload.
- Security headers.
- Error dan exception handling terpusat.
- Activity logging.
- Audit trail.
- Proteksi IDOR.
- HTTPS enforcement di production.
- Secure configuration dan secret management.

Jangan pernah mengandalkan validasi frontend sebagai pengamanan utama.

### D. DATABASE

Gunakan MySQL/MariaDB dengan PDO.

Terapkan:

- Relasi database yang terstruktur.
- Primary key dan foreign key.
- Indexing.
- Unique constraint.
- Database transactions.
- Manual migration.
- Manual seeder.
- Rollback migration.
- Soft delete jika diperlukan.
- Audit timestamps.
- Backup dan restore procedure.

Jangan mengubah struktur database tanpa menganalisis dampak terhadap fitur yang sudah berjalan.

### E. FRONTEND DAN UI/UX

Gunakan:

- Tailwind CSS.
- Alpine.js.
- Fetch API.
- SweetAlert2.
- DataTables.
- ApexCharts atau Chart.js.
- Lucide Icons.

Desain menggunakan konsep Premium Modern 2027:

- Aero Glassmorphism.
- Soft Glow.
- Modern Monochromatic.
- Vibrant Blue Accent.
- Deep Navy.
- Champagne Gold.
- Premium Glass Cards.
- Subtle Gradients.
- Smooth Hover.
- Micro-Interactions.
- Consistent Border Radius.
- Soft Shadows.
- Modern Typography.
- Accessible Color Contrast.

Buat design system terpusat yang mengatur warna, typography, spacing, radius, shadow, button, card, modal, form, badge, toast, tabel, dan navigasi.

Jangan membuat setiap halaman memiliki gaya desain yang berbeda.

### F. DASHBOARD DAN NAVIGASI

Implementasikan:

- Responsive Admin Dashboard.
- Smart Sidebar.
- Active Menu Indicator.
- Collapsible Sidebar.
- Breadcrumb.
- Premium Topbar.
- Admin Avatar dan Profile Dropdown.
- Notification Bell.
- Unread Notification Badge.
- Global Search.
- Command Palette dengan Ctrl + K.
- Quick Actions.
- Mobile Bottom Navigation.
- Mobile Sidebar Drawer.
- Pengaturan Profil Mandiri.
- Pengaturan Aplikasi Dinamis.

Menu harus mengikuti role dan permission pengguna.

### G. DATA MANAGEMENT

Setiap modul pengelolaan data harus memiliki:

- List Data.
- Tambah Data.
- Edit Data.
- Detail Data.
- Hapus Data.
- Konfirmasi Penghapusan.
- Live Search.
- Dynamic Filter.
- Sorting.
- Server-side Pagination.
- DataTables.
- AJAX/Fetch API.
- Loading State.
- Empty State.
- Error State.
- Bulk Action jika diperlukan.
- Multi-format Export.
- Soft Delete jika diperlukan.

Operasi CRUD harus memberikan respons JSON konsisten dan tidak melakukan reload halaman apabila tidak diperlukan.

### H. MEDIA MANAGEMENT

Implementasikan:

- Upload gambar.
- Validasi MIME.
- Validasi ukuran.
- Random filename.
- Image resize.
- Image compression.
- WebP/AVIF.
- Thumbnail.
- Image preview.
- Pengelolaan file.
- Proteksi eksekusi file upload.

### I. NOTIFICATION DAN ACTIVITY LOG

Sediakan Notification Center yang memiliki:

- Notification Bell.
- Unread Counter.
- Mark as Read.
- Mark All as Read.
- Notification History.
- Notification Category.

Activity Log harus mencatat aktivitas penting pengguna, termasuk login, logout, create, update, delete, export, dan perubahan konfigurasi.

Jangan mencatat password, session token, atau secret ke dalam log.

### J. DATA VISUALIZATION

Dashboard menggunakan compact data visualization:

- KPI Cards.
- Line Chart.
- Bar Chart.
- Donut Chart.
- Activity Timeline.
- Statistical Summary.
- Dynamic Date Filter.

Grafik harus merepresentasikan data aktual database, bukan angka dummy ketika aplikasi sudah menggunakan data production.

### K. API ARCHITECTURE

Gunakan struktur:

`/api/v1/`

Terapkan:

- JSON response.
- HTTP status code yang benar.
- Authentication.
- Authorization.
- Validation.
- Rate Limiting.
- Pagination.
- Error Handling.
- API versioning.

### L. QUALITY ASSURANCE

Setiap modul harus melalui:

1. Syntax Validation.
2. Functional Testing.
3. Authentication Testing.
4. Authorization Testing.
5. CSRF Testing.
6. SQL Injection Testing.
7. XSS Testing.
8. Responsive Testing.
9. Performance Review.
10. Regression Testing.

Gunakan PHPStan, PHPUnit, dan PHP-CS-Fixer sesuai kebutuhan.

### M. ATURAN KERJA AI

WAJIB mengikuti ketentuan berikut:

1. Audit source code sebelum mengubah proyek yang sudah ada.
2. Jangan mengarang struktur file yang belum diperiksa.
3. Jangan menghapus fitur yang sudah berjalan.
4. Jangan membuat migration atau class duplikat.
5. Jangan membuat patch asal-asalan.
6. Jangan mengubah file di luar ruang lingkup tanpa alasan teknis.
7. Gunakan reusable components.
8. Gunakan penamaan class, method, function, dan file yang konsisten.
9. Setiap perubahan harus dijelaskan.
10. Setiap perubahan harus memiliki pengujian.
11. Pastikan semua route terlindungi sesuai hak akses.
12. Pastikan UI desktop dan mobile konsisten.
13. Jangan menyimpan konfigurasi rahasia di repository.
14. Lakukan backup sebelum perubahan berisiko.
15. Jangan menyatakan fitur berhasil sebelum benar-benar diuji.

### N. METODE PENGERJAAN

Bangun proyek secara bertahap:

PHASE 1 — Foundation & Architecture.

PHASE 2 — Authentication & Security.

PHASE 3 — Design System & UI Components.

PHASE 4 — Dashboard & Navigation.

PHASE 5 — CRUD & Data Management.

PHASE 6 — Advanced Features.

PHASE 7 — Testing & Security Audit.

PHASE 8 — Production Deployment.

Jangan membangun seluruh aplikasi sekaligus tanpa validasi.

Selesaikan setiap fase, periksa hasilnya, jalankan pengujian, dan pastikan tidak ada regresi sebelum melanjutkan.

### O. TARGET AKHIR

Hasil akhir harus berupa aplikasi PHP Native profesional dengan:

- Clean Architecture.
- Secure Authentication.
- Modular Codebase.
- Professional Database Design.
- Premium Modern 2027 UI.
- Responsive Layout.
- Reusable Components.
- Interactive Data Management.
- Advanced Notification System.
- Dynamic Application Settings.
- Comprehensive Activity Logs.
- Secure API Structure.
- Production-ready Configuration.
- Maintainable Documentation.

**Prioritas tertinggi: SECURITY → CODE QUALITY → DATA INTEGRITY → FUNCTIONALITY → UI/UX → PERFORMANCE.**

Jangan mengorbankan keamanan dan stabilitas demi tampilan visual.

Mulailah dengan melakukan analisis kebutuhan, menyusun arsitektur, membuat struktur folder, dan menentukan urutan implementasi sebelum menulis kode.

# 17. Standar tambahan yang saya sarankan

Ada beberapa keputusan teknis yang sebaiknya kita kunci sebelum proyek pertama dimulai.

| Aspek           | Rekomendasi                       |
| --------------- | --------------------------------- |
| PHP             | Versi stable yang masih didukung  |
| Arsitektur      | Modular MVC                       |
| Routing         | Custom Router dengan middleware   |
| Database        | MySQL / MariaDB                   |
| Frontend        | Tailwind + Alpine.js              |
| JavaScript      | Modular ES Modules                |
| Session         | Database atau secure file session |
| Authentication  | Session-based                     |
| Authorization   | RBAC + Permission                 |
| Testing         | PHPUnit                           |
| Code Quality    | PHPStan                           |
| Version Control | Git                               |
| Deployment      | Shared Hosting / VPS              |

Catatan penting: Tailwind CSS, Alpine.js, DataTables, dan library lainnya perlu dipasang serta dikunci versinya melalui konfigurasi dependency yang jelas. Jangan mengandalkan CDN sebagai satu-satunya sumber aset production.

## 18. Standar hasil visual yang harus dipertahankan

Profile & Settings

Targetnya adalah seluruh halaman terasa sebagai satu produk software profesional, bukan kumpulan template yang berbeda-beda.

## 19. Kesimpulan blueprint

Menurut saya, ada tiga prinsip yang paling penting untuk dijadikan standar permanen setiap proyek PHP Native berikutnya:

1. Security First: keamanan harus dibangun sejak fondasi, bukan ditambahkan setelah aplikasi selesai.
2. Architecture First: struktur folder, database, routing, service, dan komponen harus benar sebelum membangun banyak fitur.
3. Design System First: tampilan, warna, komponen, modal, tombol, notifikasi, dan navigasi harus memiliki standar global.

Dengan blueprint ini, kamu bisa menggunakan satu dokumen acuan yang sama di ChatGPT, Gemini, maupun Claude. Nama proyek, kebutuhan bisnis, modul, dan konfigurasi database tinggal disesuaikan.

## 20. Pengembangan berikutnya

Saya menyarankan kita nantinya membuat satu PHP Native Professional Starter Kit 2027 sebagai fondasi siap pakai, berisi struktur folder, Composer, custom router, autentikasi, security middleware, design system, dashboard, dan modul CRUD pertama.

Dengan begitu, setiap website baru tidak perlu dibangun dari nol. Kita tinggal mengembangkan modul sesuai kebutuhan tanpa mengulang fondasi keamanan dan desain.

---

# 21. Standar khusus website publik SMA Negeri — Modern Institutional Website 2027–2040

Bagian ini merupakan standar tambahan yang WAJIB digunakan apabila proyek yang dibangun adalah **website publik/profil resmi SMA Negeri**, bukan hanya aplikasi internal atau dashboard administrasi. Seluruh standar arsitektur, keamanan, database, pengujian, dan kualitas kode pada blueprint utama tetap berlaku. Untuk tampilan website publik, ketentuan pada bagian ini menjadi acuan utama dan mengesampingkan pola dashboard admin yang tidak sesuai.

## 21.1. Tujuan dan karakter website

Bangun website sekolah sebagai portal informasi pendidikan resmi yang:

- Informatif, kredibel, mudah dipahami, dan mudah dijelajahi oleh masyarakat.
- Memiliki karakter institusi pendidikan negeri: terpercaya, terbuka, tertib, ramah, berwibawa, dan berorientasi pada pelayanan publik.
- Terlihat premium, modern, rapi, dan relevan untuk rentang 2027–2040 tanpa terjebak pada tren visual sesaat.
- Mengutamakan kebutuhan siswa, orang tua/wali, calon peserta didik, alumni, guru/tenaga kependidikan, mitra, media, dan masyarakat.
- Mengutamakan isi dan kemudahan akses informasi, bukan sekadar efek visual atau animasi.

Website publik harus terasa sebagai portal resmi SMA Negeri, bukan template perusahaan, toko online, portal berita umum, atau dashboard aplikasi yang hanya diganti logonya.

## 21.2. Arah desain visual 2027–2040

Gunakan pendekatan **Modern Institutional Editorial Design**: perpaduan identitas pendidikan, editorial layout, fotografi dokumenter berkualitas, hierarki informasi yang kuat, ruang kosong yang cukup, dan komponen digital yang konsisten.

Prinsip visual:

1. **Premium dan berwibawa** — tata letak tertata, komposisi seimbang, detail rapi, dan penggunaan warna yang terkendali.
2. **Modern dan berorientasi masa depan** — tipografi kontemporer, grid fleksibel, komponen responsif, interaksi halus, dan struktur yang mudah dikembangkan.
3. **Informatif dan editorial** — judul berita mudah dipindai, ringkasan informatif, tanggal dan kategori jelas, serta foto kegiatan yang relevan.
4. **Human-centered** — foto asli kegiatan sekolah, warga sekolah, lingkungan, pembelajaran, prestasi, dan fasilitas menjadi elemen visual utama jika tersedia dan diizinkan.
5. **Konsisten dan timeless** — desain tidak bergantung pada tren sesaat sehingga tetap pantas digunakan dalam jangka panjang.

### Sistem warna dan identitas

- Jadikan logo, pedoman identitas, dan warna resmi sekolah sebagai sumber utama penentuan palet.
- Jika belum ada pedoman warna, gunakan palet institusional yang matang, misalnya navy/deep blue, putih, abu-abu netral, serta satu warna aksen yang diambil dari identitas sekolah.
- Warna aksen seperti kuning atau emas digunakan seperlunya untuk penanda, garis, tombol penting, atau elemen identitas; jangan menjadikannya warna dominan tanpa dasar identitas.
- Pastikan kontras teks dan latar memenuhi aksesibilitas.
- Buat design tokens terpusat untuk warna, tipografi, jarak, radius, border, shadow, lebar konten, dan breakpoint.
- Jangan mengubah logo, lambang, nama, atau identitas sekolah tanpa persetujuan dan aset resmi.

### Tipografi dan komposisi

- Gunakan maksimal dua keluarga font utama yang mudah dibaca dan mendukung karakter institusi.
- Bangun hierarki yang jelas antara judul halaman, judul berita, subjudul, ringkasan, isi, metadata, dan keterangan foto.
- Gunakan ukuran teks isi yang nyaman dibaca; hindari teks terlalu kecil, terlalu tipis, atau kontras rendah.
- Gunakan whitespace secara terencana. Jangan memenuhi setiap ruang dengan kartu, garis, ikon, atau ornamen.
- Gunakan radius, shadow, gradient, blur, dan glass effect secara hemat. Glassmorphism bukan gaya dominan website publik.
- Hindari neon berlebihan, glow mencolok, efek 3D yang tidak fungsional, animasi berat, carousel otomatis yang mengganggu, serta ilustrasi generik yang tidak berhubungan dengan sekolah.
- Jangan membuat website terlihat seperti desain anak-anak, template gratis, atau landing page promosi produk.

## 21.3. Struktur informasi dan navigasi utama

Susun sitemap berdasarkan kebutuhan pengguna. Nama menu dapat disesuaikan dengan data dan kebijakan sekolah, tetapi struktur minimal yang perlu dipertimbangkan adalah:

1. **Beranda** — ringkasan identitas, informasi terbaru, akses cepat, dan pintasan layanan.
2. **Profil Sekolah** — sambutan kepala sekolah, sejarah, identitas, visi dan misi, tujuan, struktur organisasi, kepala sekolah, guru dan tenaga kependidikan, serta profil singkat sekolah.
3. **Akademik** — kurikulum, program pembelajaran, kegiatan akademik, kalender akademik, dan informasi pembelajaran yang memang dipublikasikan sekolah.
4. **Kesiswaan** — OSIS, ekstrakurikuler, kegiatan siswa, pembinaan karakter, dan prestasi siswa.
5. **Berita** — berita kegiatan, prestasi, pendidikan, kolaborasi, dan informasi resmi sekolah.
6. **Pengumuman** — informasi resmi yang membutuhkan perhatian siswa, orang tua, alumni, atau masyarakat.
7. **Agenda** — jadwal kegiatan yang akan datang dan arsip kegiatan yang telah berlangsung.
8. **Galeri** — foto dan video kegiatan, fasilitas, lingkungan, serta dokumentasi sekolah.
9. **Layanan Publik/PPID** — informasi publik, prosedur permohonan informasi, formulir atau dokumen unduhan, kanal pengaduan, dan kontak layanan sesuai ketentuan yang berlaku.
10. **SPMB** — informasi penerimaan murid baru sesuai ketentuan dan periode resmi; tampilkan periode aktif dan arsip informasi periode sebelumnya secara jelas.
11. **Kontak** — alamat, peta/lokasi, telepon, email resmi, jam layanan jika tersedia, dan kanal media sosial resmi.

Jangan memunculkan menu kosong atau membuat informasi fiktif hanya untuk memenuhi sitemap. Menu yang belum memiliki konten dapat disembunyikan atau diberi status yang jelas sampai kontennya tersedia.

Navigasi harus ringkas dan mudah dipahami. Gunakan dropdown hanya jika diperlukan. Pada layar kecil, sediakan menu mobile yang jelas, mudah disentuh, dapat diakses dengan keyboard, dan tidak menutupi konten secara tidak wajar.

## 21.4. Standar halaman beranda

Beranda harus menyajikan informasi paling penting dalam urutan yang logis, bukan menumpuk semua modul sekaligus. Komposisi yang direkomendasikan:

1. Header resmi berisi logo, nama sekolah, identitas singkat, dan navigasi utama.
2. Hero section yang memperkenalkan sekolah dengan foto asli atau visual resmi berkualitas. Judul harus menyampaikan identitas atau pesan utama sekolah, bukan slogan generik.
3. Akses cepat ke layanan yang sering dicari, misalnya profil, SPMB, pengumuman, agenda, PPID, dan kontak.
4. Berita terbaru dengan foto, kategori, tanggal, judul, dan ringkasan yang informatif.
5. Pengumuman penting dengan penanda prioritas dan tanggal berlaku bila relevan.
6. Agenda atau kalender kegiatan terdekat.
7. Ringkasan profil, visi-misi, atau program unggulan sekolah.
8. Prestasi dan kegiatan siswa yang benar-benar terverifikasi.
9. Galeri kegiatan atau dokumentasi lingkungan sekolah.
10. Footer lengkap berisi identitas, alamat, kontak, tautan penting, layanan publik, dan media sosial resmi.

Jangan mengisi hero dengan teks terlalu panjang. Hindari slider otomatis sebagai elemen utama; prioritaskan satu visual utama yang kuat atau slider manual yang aksesibel bila benar-benar diperlukan.

## 21.5. Standar berita, pengumuman, dan agenda

### Berita

- Halaman daftar berita mendukung pencarian, kategori, arsip tanggal/tahun, pagination, serta berita unggulan bila diperlukan.
- Kartu berita menampilkan gambar yang proporsional, kategori, tanggal publikasi, judul, dan ringkasan singkat.
- Halaman detail berita menampilkan judul, tanggal, kategori, penulis/redaksi bila tersedia, gambar utama, isi berita, keterangan foto, dan berita terkait.
- Isi berita harus nyaman dibaca, memiliki lebar baris yang wajar, spasi paragraf yang cukup, serta tampilan gambar yang tidak terpotong secara tidak semestinya.
- Sediakan metadata sosial dan tombol berbagi bila sesuai.

### Pengumuman

- Bedakan pengumuman penting, pengumuman umum, dan pengumuman yang sudah berakhir.
- Tampilkan tanggal terbit dan masa berlaku jika tersedia.
- Sediakan detail pengumuman dan lampiran unduhan yang aman.
- Jangan menghapus arsip pengumuman penting hanya karena periodenya selesai; gunakan status arsip.

### Agenda

- Tampilkan tanggal, waktu, lokasi, deskripsi, dan status kegiatan bila datanya tersedia.
- Pisahkan agenda mendatang dari arsip kegiatan.
- Pastikan tanggal dan zona waktu ditampilkan secara konsisten.

## 21.6. CMS dan pengelolaan konten

Website harus dapat dikelola petugas sekolah tanpa mengubah kode untuk pembaruan konten rutin. Sediakan CMS/admin terpisah secara fungsi dari tampilan publik, dengan fitur sesuai kebutuhan:

- Pengelolaan halaman profil dan halaman informatif.
- Pengelolaan berita, kategori, tag, gambar utama, galeri, dan lampiran.
- Pengelolaan pengumuman dan agenda.
- Pengelolaan menu, tautan cepat, footer, kontak, dan media sosial.
- Pengelolaan dokumen/unduhan dan informasi layanan publik.
- Draft, pratinjau, jadwal publikasi bila dibutuhkan, publikasi, arsip, dan status konten.
- Hak akses berbasis peran untuk administrator, editor/redaksi, operator, dan pembaca sesuai kebutuhan nyata sekolah.
- Riwayat perubahan konten untuk mengetahui siapa mengubah apa dan kapan.
- Validasi ukuran, tipe, dimensi, dan keamanan file unggahan.

Jangan membuat fitur CMS yang berlebihan jika tidak dibutuhkan. Alur publikasi harus sederhana dan mudah dipahami oleh operator sekolah.

## 21.7. Aturan mutlak penamaan file, aset, script, CSS, dan komentar

**DILARANG menggunakan label atau nomor versi dalam penamaan file proyek, file aset, script, stylesheet, selector/identifier CSS, maupun komentar kode.** Jangan membuat nama seperti `style-v2.css`, `home-final.js`, `main-new.css`, `script-terbaru.js`, `header-v3.php`, `fix-2028.css`, atau variasi sejenis.

Gunakan nama yang stabil, deskriptif, dan berdasarkan fungsi, misalnya:

- `site.css`
- `layout.css`
- `home.css`
- `navigation.css`
- `news.css`
- `site.js`
- `navigation.js`
- `news.js`
- `header.php`
- `footer.php`
- `news-detail.php`

Ketentuan rinci:

1. Jangan menambahkan `v1`, `v2`, `v3`, `version`, `versi`, `final`, `new`, `latest`, `terbaru`, `fix`, atau nomor tahun sebagai penanda iterasi pada nama file, folder, asset, script, stylesheet, selector CSS, class CSS, ID elemen, maupun identifier JavaScript.
2. Jangan membuat file duplikat untuk menghindari perbaikan file utama. Perbaiki file yang menjadi sumber utama dan gunakan kembali komponen yang sudah ada.
3. Jangan menambahkan query string versi manual seperti `site.css?v=2` atau `app.js?version=3` pada pemanggilan aset.
4. Komentar kode hanya menjelaskan tujuan, alasan teknis, perilaku, atau batasan kode. Dilarang menulis komentar seperti `versi baru`, `update v2`, `fix terbaru`, `revisi 2028`, atau catatan iterasi sejenis.
5. Jangan memasukkan nomor versi ke nama class CSS atau ID, misalnya `.header-v2`, `#hero-final`, atau `.menu-new`.
6. Gunakan nama berdasarkan fungsi dan konteks, bukan berdasarkan urutan revisi.
7. Jangan menghapus metadata versi yang memang diwajibkan oleh ekosistem teknis, seperti versi dependency di `composer.json`, `composer.lock`, atau file lock package manager. Metadata tersebut bukan bagian dari penamaan file atau identitas desain proyek.
8. Jika API versioning memang diperlukan, gunakan konvensi teknis yang konsisten pada endpoint dan dokumentasi API; jangan mengubahnya menjadi label versi pada nama file, CSS, script, atau komentar.

Sebelum menyelesaikan pekerjaan, lakukan pemeriksaan seluruh nama file dan isi kode untuk memastikan tidak ada label iterasi atau versi yang melanggar ketentuan ini.

## 21.8. SEO, aksesibilitas, performa, dan kualitas publik

### SEO teknis

- Setiap halaman memiliki title, meta description, canonical URL, dan heading hierarchy yang benar.
- Gunakan URL yang mudah dibaca dan konsisten.
- Sediakan sitemap XML, robots.txt, Open Graph, dan metadata sosial.
- Gunakan structured data yang relevan dan akurat untuk institusi pendidikan/organisasi jika sesuai.
- Hindari judul, deskripsi, dan data terstruktur yang menyesatkan atau dibuat otomatis tanpa verifikasi.
- Pastikan halaman penting dapat diindeks dan tidak terhalang konfigurasi yang keliru.

### Aksesibilitas

- Gunakan HTML semantik: header, nav, main, article, section, aside, dan footer sesuai konteks.
- Semua fungsi utama dapat digunakan dengan keyboard.
- Sediakan focus state yang jelas, label formulir, alt text gambar yang bermakna, dan struktur heading yang logis.
- Pastikan kontras warna cukup dan ukuran area sentuh nyaman pada perangkat mobile.
- Hormati `prefers-reduced-motion`; jangan membuat animasi sebagai satu-satunya cara menyampaikan informasi.
- Jangan menggunakan warna saja untuk membedakan status atau informasi.

### Performa

- Optimalkan ukuran gambar dan gunakan format modern bila didukung.
- Gunakan ukuran gambar yang sesuai dengan slot tampilannya, lazy loading untuk gambar di bawah fold, serta preload secara selektif untuk aset penting.
- Minimalkan JavaScript dan CSS yang tidak diperlukan.
- Hindari library berat jika fungsi yang dibutuhkan dapat dibuat dengan solusi yang lebih ringan dan terpelihara.
- Pastikan halaman tetap dapat dibaca saat jaringan lambat dan tampilkan status loading/error yang wajar.
- Uji tampilan serta performa pada koneksi dan perangkat kelas menengah, bukan hanya komputer pengembang.

### Responsivitas dan kompatibilitas

Uji sekurang-kurangnya pada lebar layar 360 px, 390 px, 768 px, 1024 px, dan desktop lebar. Pastikan tidak ada horizontal overflow yang tidak disengaja, teks terpotong, menu yang tidak dapat digunakan, tombol terlalu kecil, atau gambar yang merusak tata letak.

## 21.9. Ketentuan konten dan integritas informasi

- Jangan mengarang nama pejabat, guru, data siswa, jumlah rombongan belajar, prestasi, fasilitas, alamat, nomor kontak, jadwal, kebijakan, atau dokumen sekolah.
- Gunakan hanya data dan aset yang diberikan atau sumber resmi yang telah diverifikasi.
- Jika data belum tersedia, gunakan placeholder yang diberi penanda jelas untuk admin dan jangan menampilkannya sebagai fakta pada website publik.
- Gunakan Bahasa Indonesia baku sesuai EYD untuk konten resmi, dengan gaya informatif, lugas, profesional, dan tidak berlebihan.
- Setiap berita dan pengumuman harus memiliki tanggal serta sumber/penanggung jawab sesuai alur redaksi sekolah.
- Lindungi data pribadi peserta didik, orang tua, guru, dan tenaga kependidikan. Jangan menampilkan data pribadi tanpa dasar dan persetujuan yang sesuai.
- Pastikan foto siswa dan warga sekolah digunakan sesuai izin serta kebijakan dokumentasi sekolah.

## 21.10. Aturan kerja AI saat membangun website sekolah

1. Sebelum menulis kode, identifikasi apakah proyek merupakan website publik, CMS/admin, atau keduanya. Pisahkan kebutuhan dan pengalaman pengguna masing-masing.
2. Audit seluruh file, database, route, asset, dan fitur yang telah tersedia sebelum melakukan perubahan pada proyek yang sudah berjalan.
3. Jangan mengganti identitas resmi sekolah atau mengarang isi konten.
4. Susun sitemap, user journey, design system, dan rancangan halaman sebelum mengimplementasikan seluruh halaman.
5. Buat satu sistem komponen bersama untuk header, navigasi, breadcrumb, kartu berita, metadata, tombol, formulir, pagination, notifikasi, dan footer.
6. Jangan menerapkan tampilan dashboard admin ke halaman publik. Halaman publik harus mengutamakan informasi dan keterbacaan; dashboard admin boleh menggunakan pola aplikasi sesuai blueprint utama.
7. Jangan menampilkan komponen hanya sebagai dekorasi. Setiap tombol, menu, pencarian, filter, tautan, formulir, dan pagination harus bekerja sesuai fungsinya.
8. Jangan menggunakan data dummy pada website production. Tandai data contoh dengan jelas selama tahap pengembangan dan hapus atau ganti sebelum peluncuran.
9. Jangan membuat halaman atau menu kosong, tautan mati, gambar yang tidak relevan, teks lorem ipsum, maupun statistik rekaan.
10. Lakukan pengujian fungsional, keamanan, aksesibilitas, SEO, responsivitas, dan performa sebelum menyatakan pekerjaan selesai.
11. Setelah perubahan, periksa konsistensi seluruh halaman dan pastikan tidak ada fitur lama yang rusak.
12. Laporkan file yang dibuat/diubah, fungsi yang diselesaikan, hasil pengujian, dan hal yang masih memerlukan data atau keputusan dari pihak sekolah.

## 21.11. Kriteria penerimaan website

Website hanya dapat dinyatakan siap apabila:

- Identitas sekolah tampil benar dan konsisten.
- Pengunjung dapat menemukan profil, berita, pengumuman, agenda, layanan publik, SPMB, dan kontak dengan mudah.
- Seluruh tautan, menu, tombol, formulir, pencarian, filter, dan pagination berfungsi.
- Admin dapat memperbarui konten utama tanpa mengubah kode.
- Halaman detail konten nyaman dibaca dan menampilkan metadata yang benar.
- Tampilan konsisten pada ponsel, tablet, dan desktop.
- Tidak ada konten rekaan, tautan mati, menu kosong, atau placeholder yang tertinggal di area publik.
- SEO dasar, aksesibilitas, keamanan, dan performa telah diperiksa.
- Tidak ada label versi/iterasi pada nama file, asset, script, CSS, selector, identifier, atau komentar kode.
- Dokumentasi pengelolaan website tersedia untuk operator sekolah.

---

# 22. Tambahan MASTER PROMPT khusus website publik SMA Negeri

Gunakan instruksi ini bersama MASTER PROMPT pada Bagian 16. Jika terdapat perbedaan antara gaya dashboard aplikasi dan website publik sekolah, ikuti standar website publik pada Bagian 21.

**PERAN AI**

Bertindaklah sebagai Senior Web Architect, PHP Developer, UI/UX Designer, Information Architect, SEO Specialist, Accessibility Specialist, Cybersecurity Engineer, dan QA Engineer yang berpengalaman membangun portal resmi institusi pendidikan.

**TUGAS UTAMA**

Rancang dan bangun website publik SMA Negeri yang informatif, profesional, premium, modern, responsif, mudah dikelola, aman, dan relevan untuk standar desain 2027 hingga 2040. Website harus mencerminkan kredibilitas institusi pendidikan negeri dan menjadi pusat informasi resmi bagi siswa, orang tua/wali, calon peserta didik, alumni, guru, mitra, serta masyarakat.

**ARAH VISUAL**

Terapkan Modern Institutional Editorial Design dengan identitas sekolah sebagai dasar. Gunakan tata letak editorial, tipografi yang kuat dan mudah dibaca, whitespace terencana, fotografi kegiatan sekolah yang autentik, navigasi intuitif, komponen konsisten, dan interaksi halus. Buat desain yang timeless dan future-ready; jangan mengejar efek futuristik berlebihan atau tren sesaat. Glassmorphism, glow, gradient, dan animasi hanya boleh digunakan secara terbatas apabila meningkatkan kejelasan atau pengalaman pengguna.

**HALAMAN DAN MODUL**

Bangun halaman Beranda, Profil Sekolah, Akademik, Kesiswaan, Berita, Detail Berita, Pengumuman, Detail Pengumuman, Agenda, Galeri, Layanan Publik/PPID, SPMB, Kontak, serta halaman pendukung sesuai kebutuhan dan ketersediaan data sekolah. Sediakan CMS/admin untuk mengelola konten, media, menu, agenda, pengumuman, dokumen, dan halaman informatif dengan hak akses yang sesuai.

**ATURAN WAJIB**

- Pertahankan teknologi, arsitektur, keamanan, dan standar kualitas pada blueprint utama.
- Bedakan website publik dan dashboard admin dari sisi struktur informasi serta UI/UX.
- Jangan mengarang data sekolah atau menampilkan data contoh sebagai fakta.
- Jangan membuat tautan mati, menu kosong, tombol dekoratif, atau halaman tanpa isi.
- Gunakan komponen reusable dan design system terpusat.
- Pastikan SEO, aksesibilitas, keamanan, responsivitas, dan performa diperiksa.
- Jangan menggunakan label versi atau iterasi pada penamaan file, folder, asset, script, stylesheet, class/ID CSS, identifier JavaScript, maupun komentar kode. Gunakan nama fungsional yang stabil. Pengecualian hanya untuk metadata versi dependency dan kebutuhan versioning teknis yang memang diwajibkan.
- Audit proyek yang sudah ada sebelum mengubahnya. Jangan merusak fitur, database, route, atau konten yang telah berjalan.
- Setiap elemen interaktif wajib berfungsi. Uji hasilnya sebelum menyatakan selesai.

**CARA BEKERJA**

Mulai dengan audit kebutuhan dan source code, lalu susun sitemap, alur pengguna, design system, struktur konten, dan rancangan halaman. Setelah disetujui atau setelah kebutuhan cukup jelas, implementasikan secara bertahap. Di setiap tahap, lakukan pengujian dan pemeriksaan regresi. Pada akhir pekerjaan, berikan ringkasan implementasi, daftar file yang berubah, hasil pengujian, dan daftar data/aset yang masih perlu disediakan sekolah.

**STANDAR HASIL AKHIR**

Website harus tampil seperti portal resmi SMA Negeri yang matang dan terpercaya: identitas sekolah kuat, informasi mudah ditemukan, berita nyaman dibaca, layanan publik mudah diakses, tampilan konsisten di semua perangkat, pengelolaan konten sederhana, dan kode yang aman serta mudah dipelihara. Prioritas pengerjaan adalah **akurasi informasi → keamanan → kemudahan penggunaan → aksesibilitas → performa → kualitas visual**.
