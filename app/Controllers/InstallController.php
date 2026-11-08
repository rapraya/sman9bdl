<?php

namespace App\Controllers;

use App\Core\Csrf;
use PDO;
use Throwable;

final class InstallController
{
    public function index(): void
    {
        if (is_file(BASE_PATH . '/config/local.php')) {
            redirect('/admin/login');
        }
        $error = flash('error');
        echo \App\Core\View::render('install', ['error' => $error]);
    }

    public function process(): void
    {
        if (is_file(BASE_PATH . '/config/local.php')) {
            redirect('/admin/login');
        }
        if (!Csrf::verify($_POST['_token'] ?? null)) {
            flash('error', 'Sesi instalasi tidak valid. Muat ulang halaman.');
            redirect('/install');
        }

        @set_time_limit(45);
        ini_set('max_execution_time', '45');

        $host = trim((string) ($_POST['db_host'] ?? '127.0.0.1'));
        $port = (int) ($_POST['db_port'] ?? 3306);
        $name = trim((string) ($_POST['db_name'] ?? ''));
        $user = trim((string) ($_POST['db_user'] ?? ''));
        $pass = (string) ($_POST['db_pass'] ?? '');
        $adminName = trim((string) ($_POST['admin_name'] ?? ''));
        $adminEmail = strtolower(trim((string) ($_POST['admin_email'] ?? '')));
        $adminPassword = (string) ($_POST['admin_password'] ?? '');

        if ($host === '' || !preg_match('/^[a-zA-Z0-9][a-zA-Z0-9.-]*$/', $host)
            || $name === '' || !preg_match('/^[a-zA-Z0-9_]+$/', $name)
            || $port < 1 || $port > 65535) {
            flash('error', 'Host, port, atau nama database tidak valid. Gunakan nama database yang sudah dibuat terlebih dahulu.');
            redirect('/install');
        }
        if ($adminName === '' || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL) || strlen($adminPassword) < 12) {
            flash('error', 'Lengkapi nama, email valid, dan kata sandi administrator minimal 12 karakter.');
            redirect('/install');
        }
        if (!extension_loaded('pdo_mysql')) {
            flash('error', 'Ekstensi pdo_mysql belum aktif pada PHP yang menjalankan server. Aktifkan extension=pdo_mysql pada php.ini, lalu mulai ulang server lokal.');
            redirect('/install');
        }
        if (!is_dir(BASE_PATH . '/config') || !is_writable(BASE_PATH . '/config')) {
            flash('error', 'Folder config tidak dapat ditulisi PHP. Periksa izin folder proyek, lalu coba kembali.');
            redirect('/install');
        }

        $pdo = null;
        $stage = 'Memulai instalasi';
        $logPath = BASE_PATH . '/storage/logs/installer.log';
        $writeLog = static function (string $message) use ($logPath): void {
            $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
            @file_put_contents($logPath, $line, FILE_APPEND | LOCK_EX);
        };

        try {
            $writeLog('Memulai instalasi. Host=' . $host . '; port=' . $port . '; database=' . $name . '; db_user=' . $user);
            $stage = 'Menghubungkan ke MySQL';
            $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 8,
            ]);
            $pdo->query('SELECT 1')->fetchColumn();
            $writeLog('Koneksi database berhasil.');

            $stage = 'Membaca struktur tabel';
            $schemaPath = BASE_PATH . '/database/schema.sql';
            $schema = is_file($schemaPath) ? file_get_contents($schemaPath) : false;
            if ($schema === false || trim($schema) === '') {
                throw new \RuntimeException('File database/schema.sql tidak ditemukan atau kosong.');
            }
            $statements = preg_split('/;\s*(?:\r?\n|$)/', $schema) ?: [];
            $executed = 0;
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if ($statement === '') {
                    continue;
                }
                $pdo->exec($statement);
                $executed++;
                $writeLog('Pembuatan tabel dijalankan (' . $executed . ').');
            }
            if ($executed < 6) {
                throw new \RuntimeException('Struktur database tidak lengkap. Hanya ' . $executed . ' perintah tabel yang diproses.');
            }
            $writeLog('Struktur database selesai.');

            $stage = 'Mengisi identitas awal sekolah';
            $profile = [
                'school_name' => 'SMA Negeri 9 Bandar Lampung',
                'school_short_name' => 'SMALAN',
                'school_address' => 'Jl. Panglima Polim No. 18, Tanjung Karang Barat, Kota Bandar Lampung, Lampung',
                'school_vision' => 'Terdepan dalam Imtaq dan Iptek, dengan lingkungan asri dan berwawasan global.',
                'school_history' => 'SMA Negeri 9 Bandar Lampung berdiri pada tahun 1976.',
                'founded_year' => '1976',
                'headmaster_name' => 'Dra. Hj. Hayati Nufus, M.Pd.',
                'school_facilities' => 'Ruang kelas ber-AC dan LCD; laboratorium; perpustakaan berakreditasi A; Masjid As-Syifa; dan taman literasi.',
                'school_recognitions' => 'Adiwiyata Mandiri; sekolah hijau/asri; sekolah rujukan; sekolah sehat nasional; dan sekolah ramah anak.',
                'ppid_email' => 'sman9bdl@gmail.com',
                'contact_email' => '',
                'contact_phone' => '',
                'school_website' => '',
                'social_instagram' => '',
                'social_facebook' => '',
                'social_youtube' => '',
                'hero_title' => 'SMA Negeri 9 Bandar Lampung',
                'hero_description' => 'Terdepan dalam Imtaq dan Iptek, dengan lingkungan asri dan berwawasan global.',
                'hero_image' => '',
                'logo_path' => '',
                'site_description' => 'Portal informasi resmi SMA Negeri 9 Bandar Lampung.',
                'footer_note' => 'Portal informasi pendidikan dan layanan publik SMA Negeri 9 Bandar Lampung.',
            ];
            $insertSetting = $pdo->prepare('INSERT INTO settings (setting_key, setting_value, updated_at) VALUES (:k,:v,NOW()) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value), updated_at=NOW()');
            foreach ($profile as $key => $value) {
                $insertSetting->execute(['k' => $key, 'v' => $value]);
            }
            $writeLog('Identitas awal sekolah berhasil disimpan.');

            $stage = 'Membuat akun administrator';
            $hash = password_hash($adminPassword, PASSWORD_DEFAULT);
            if ($hash === false) {
                throw new \RuntimeException('PHP gagal memproses kata sandi administrator.');
            }
            $insertUser = $pdo->prepare("INSERT INTO users (name,email,password_hash,role,is_active,created_at,updated_at) VALUES (:name,:email,:password,'super_admin',1,NOW(),NOW()) ON DUPLICATE KEY UPDATE name=VALUES(name), password_hash=VALUES(password_hash), role='super_admin', is_active=1, updated_at=NOW()");
            $insertUser->execute(['name' => $adminName, 'email' => $adminEmail, 'password' => $hash]);
            $writeLog('Akun administrator berhasil dibuat atau diperbarui.');

            $stage = 'Menyimpan konfigurasi aplikasi';
            $configData = [
                'host' => $host,
                'port' => $port,
                'name' => $name,
                'user' => $user,
                'pass' => $pass,
                'charset' => 'utf8mb4',
            ];
            $config = "<?php\n\ndeclare(strict_types=1);\n\nreturn " . var_export($configData, true) . ";\n";
            $configPath = BASE_PATH . '/config/local.php';
            $temporaryPath = $configPath . '.tmp';
            if (file_put_contents($temporaryPath, $config, LOCK_EX) === false) {
                throw new \RuntimeException('PHP gagal menulis konfigurasi sementara. Periksa izin folder config.');
            }
            if (!rename($temporaryPath, $configPath)) {
                @unlink($temporaryPath);
                throw new \RuntimeException('Konfigurasi database gagal dipasang. Periksa izin tulis folder config.');
            }
            @chmod($configPath, 0640);
            $writeLog('Konfigurasi tersimpan. Instalasi selesai.');
            redirect('/admin/login?installed=1');
        } catch (Throwable $exception) {
            $writeLog('GAGAL pada tahap "' . $stage . '": ' . get_class($exception) . ' - ' . $exception->getMessage());
            error_log('Installer [' . $stage . ']: ' . $exception->getMessage());
            $message = 'Instalasi berhenti pada tahap: ' . $stage . '. ' . $exception->getMessage() . ' Periksa storage/logs/installer.log untuk detail teknis.';
            flash('error', $message);
            redirect('/install');
        }
    }
}
