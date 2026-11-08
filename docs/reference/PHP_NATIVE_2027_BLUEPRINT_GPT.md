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