<?php

namespace App\Services;

use App\Core\Database;

final class UploadService
{
    private const MAX_SOURCE_SIZE = 20 * 1024 * 1024;
    private const TARGET_SIZE = 950 * 1024;
    private const MAX_STORED_SIZE = 5 * 1024 * 1024;
    private const MAX_PIXELS = 16_000_000;

    public static function image(array $file, int $userId, string $alt = '', bool $gallery = false): int
    {
        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error !== UPLOAD_ERR_OK) {
            $messages = [
                UPLOAD_ERR_INI_SIZE => 'File melebihi batas upload PHP. Atur upload_max_filesize minimal 20M dan post_max_size minimal 24M.',
                UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi batas formulir unggahan.',
                UPLOAD_ERR_PARTIAL => 'Unggahan tidak selesai. Periksa koneksi lalu coba kembali.',
                UPLOAD_ERR_NO_FILE => 'Pilih file gambar yang akan diunggah.',
                UPLOAD_ERR_NO_TMP_DIR => 'Folder sementara PHP tidak tersedia.',
                UPLOAD_ERR_CANT_WRITE => 'Server gagal menulis file ke penyimpanan.',
                UPLOAD_ERR_EXTENSION => 'Unggahan dihentikan oleh ekstensi PHP.',
            ];
            throw new \RuntimeException($messages[$error] ?? 'File gagal diunggah. Kode kesalahan: ' . $error);
        }

        $sourceSize = (int) ($file['size'] ?? 0);
        if ($sourceSize < 1 || $sourceSize > self::MAX_SOURCE_SIZE) {
            throw new \RuntimeException('Ukuran gambar asli maksimal 20 MB per file. Gambar akan dikompres otomatis saat disimpan.');
        }

        $tmp = (string) ($file['tmp_name'] ?? '');
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            throw new \RuntimeException('File unggahan tidak valid atau sudah tidak tersedia. Silakan pilih ulang file.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmp);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            throw new \RuntimeException('Format gambar harus JPG, PNG, atau WebP yang valid.');
        }

        $imageInfo = @getimagesize($tmp);
        if (!$imageInfo || empty($imageInfo[0]) || empty($imageInfo[1])) {
            throw new \RuntimeException('File tidak berisi gambar yang valid.');
        }
        $width = (int) $imageInfo[0];
        $height = (int) $imageInfo[1];
        if ($width * $height > self::MAX_PIXELS) {
            throw new \RuntimeException('Resolusi gambar terlalu besar untuk diproses dengan aman. Maksimal 16 megapiksel.');
        }

        $directory = BASE_PATH . '/public/uploads';
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new \RuntimeException('Folder unggahan tidak dapat dibuat.');
        }

        $optimized = self::optimize($tmp, $mime, $width, $height, $directory);
        $filename = bin2hex(random_bytes(18)) . '.' . $optimized['extension'];
        $destination = $directory . '/' . $filename;
        if (!rename($optimized['path'], $destination)) {
            @unlink($optimized['path']);
            throw new \RuntimeException('Gambar hasil kompresi gagal disimpan pada server.');
        }

        $path = 'uploads/' . $filename;
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('INSERT INTO media (file_name, original_name, mime_type, file_size, file_path, alt_text, is_gallery, uploaded_by, created_at) VALUES (:file_name,:original_name,:mime_type,:file_size,:file_path,:alt_text,:is_gallery,:uploaded_by,NOW())');
            $stmt->execute([
                'file_name' => $filename,
                'original_name' => substr((string) ($file['name'] ?? $filename), 0, 190),
                'mime_type' => $optimized['mime'],
                'file_size' => (int) filesize($destination),
                'file_path' => $path,
                'alt_text' => trim($alt),
                'is_gallery' => $gallery ? 1 : 0,
                'uploaded_by' => $userId,
            ]);
        } catch (\Throwable $exception) {
            @unlink($destination);
            throw $exception;
        }

        return (int) $pdo->lastInsertId();
    }

    private static function optimize(string $sourcePath, string $sourceMime, int $sourceWidth, int $sourceHeight, string $directory): array
    {
        $temporaryPath = tempnam($directory, '.optimized-');
        if ($temporaryPath === false) {
            throw new \RuntimeException('Server tidak dapat menyiapkan file sementara untuk kompresi.');
        }

        if (!function_exists('imagecreatefromstring') || !function_exists('imagecreatetruecolor')) {
            if ((int) filesize($sourcePath) <= self::TARGET_SIZE) {
                $extension = $sourceMime === 'image/jpeg' ? 'jpg' : ($sourceMime === 'image/png' ? 'png' : 'webp');
                if (!copy($sourcePath, $temporaryPath)) {
                    @unlink($temporaryPath);
                    throw new \RuntimeException('Gambar gagal disalin ke penyimpanan.');
                }
                return ['path' => $temporaryPath, 'extension' => $extension, 'mime' => $sourceMime];
            }
            @unlink($temporaryPath);
            throw new \RuntimeException('Kompresi otomatis membutuhkan ekstensi PHP GD. Aktifkan extension=gd pada php.ini, lalu mulai ulang server.');
        }

        $bytes = @file_get_contents($sourcePath);
        $source = $bytes !== false ? @imagecreatefromstring($bytes) : false;
        unset($bytes);
        if (!$source) {
            @unlink($temporaryPath);
            throw new \RuntimeException('Gambar tidak dapat dibuka oleh mesin kompresi.');
        }

        $hasWebp = function_exists('imagewebp');
        $preserveAlpha = $sourceMime !== 'image/jpeg';
        $maxWidth = 2400;
        $maxHeight = 1800;
        $scale = min(1, $maxWidth / $sourceWidth, $maxHeight / $sourceHeight);
        $width = max(1, (int) round($sourceWidth * $scale));
        $height = max(1, (int) round($sourceHeight * $scale));
        $qualityLevels = $hasWebp ? [84, 78, 72, 66, 60, 54, 48, 42, 36, 30] : [86, 80, 74, 68, 62, 56, 50, 44, 38, 32];
        $success = false;
        $outputMime = '';
        $extension = '';

        try {
            for ($resizeAttempt = 0; $resizeAttempt < 12 && !$success; $resizeAttempt++) {
                $canvas = imagecreatetruecolor($width, $height);
                if (!$canvas) {
                    throw new \RuntimeException('Memori server tidak cukup untuk memproses gambar ini.');
                }

                if ($preserveAlpha) {
                    imagealphablending($canvas, false);
                    imagesavealpha($canvas, true);
                    $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
                    imagefilledrectangle($canvas, 0, 0, $width, $height, $transparent);
                } else {
                    $background = imagecolorallocate($canvas, 255, 255, 255);
                    imagefilledrectangle($canvas, 0, 0, $width, $height, $background);
                }

                imagecopyresampled($canvas, $source, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

                if ($hasWebp) {
                    foreach ($qualityLevels as $quality) {
                        if (@imagewebp($canvas, $temporaryPath, $quality)) {
                            clearstatcache(true, $temporaryPath);
                            if (is_file($temporaryPath) && filesize($temporaryPath) <= self::TARGET_SIZE) {
                                $success = true;
                                $outputMime = 'image/webp';
                                $extension = 'webp';
                                break;
                            }
                        }
                    }
                } elseif ($preserveAlpha) {
                    if (@imagepng($canvas, $temporaryPath, 9)) {
                        clearstatcache(true, $temporaryPath);
                        if (is_file($temporaryPath) && filesize($temporaryPath) <= self::TARGET_SIZE) {
                            $success = true;
                            $outputMime = 'image/png';
                            $extension = 'png';
                        }
                    }
                } else {
                    foreach ($qualityLevels as $quality) {
                        if (@imagejpeg($canvas, $temporaryPath, $quality)) {
                            clearstatcache(true, $temporaryPath);
                            if (is_file($temporaryPath) && filesize($temporaryPath) <= self::TARGET_SIZE) {
                                $success = true;
                                $outputMime = 'image/jpeg';
                                $extension = 'jpg';
                                break;
                            }
                        }
                    }
                }

                imagedestroy($canvas);
                if (!$success) {
                    $width = max(1, (int) floor($width * 0.82));
                    $height = max(1, (int) floor($height * 0.82));
                }
            }
        } catch (\Throwable $exception) {
            imagedestroy($source);
            @unlink($temporaryPath);
            throw $exception;
        }

        imagedestroy($source);
        clearstatcache(true, $temporaryPath);
        $finalSize = is_file($temporaryPath) ? (int) filesize($temporaryPath) : 0;
        if (!$success || $finalSize < 1 || $finalSize > self::TARGET_SIZE) {
            @unlink($temporaryPath);
            throw new \RuntimeException('Gambar belum dapat dikompres hingga di bawah 1 MB. Coba gunakan resolusi atau detail gambar yang lebih rendah.');
        }
        if ($finalSize > self::MAX_STORED_SIZE) {
            @unlink($temporaryPath);
            throw new \RuntimeException('Ukuran gambar hasil kompresi melebihi batas penyimpanan 5 MB.');
        }

        return ['path' => $temporaryPath, 'extension' => $extension, 'mime' => $outputMime];
    }
}
