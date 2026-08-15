<?php

class FileUpload {

    private array $errors = [];

    private const BLOCKED_EXTENSIONS = [
        'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'phar', 'phps',
        'asp', 'aspx', 'jsp', 'cgi', 'pl', 'py', 'rb', 'sh', 'bash',
        'exe', 'com', 'bat', 'cmd', 'ps1', 'vbs', 'js', 'jar', 'msi',
        'dll', 'so', 'htaccess', 'htpasswd', 'ini', 'conf', 'config',
    ];

    public function uploadImage(array $file, string $subfolder = 'misc', int $maxSizeBytes = 0): string|false {
        $this->errors = [];

        $settingMb = (int)setting('max_photo_size_mb', (string)MAX_PHOTO_SIZE_MB);
        if ($settingMb < 1) $settingMb = 5;
        $defaultMaxBytes = $settingMb * 1024 * 1024;

        $maxSizeBytes = $maxSizeBytes > 0 ? $maxSizeBytes : $defaultMaxBytes;

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->getUploadError($file['error']);
            return false;
        }

        if ($file['size'] > $maxSizeBytes) {
            $this->errors[] = 'Ukuran file terlalu besar. Maksimal ' . round($maxSizeBytes / 1048576, 1) . ' MB.';
            return false;
        }

        $originalName = $file['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (in_array($ext, self::BLOCKED_EXTENSIONS, true)) {
            (new SecurityLog())->logSuspiciousUpload($originalName, 'Blocked extension: ' . $ext);
            $this->errors[] = 'Tipe file tidak diizinkan.';
            return false;
        }

        if (!in_array($ext, ALLOWED_IMAGE_EXTENSIONS, true)) {
            $this->errors[] = 'Format file tidak didukung. Gunakan JPG, PNG, atau WebP.';
            return false;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, ALLOWED_IMAGE_TYPES, true)) {
            (new SecurityLog())->logSuspiciousUpload($originalName, 'Invalid MIME: ' . $mimeType);
            $this->errors[] = 'File bukan gambar yang valid.';
            return false;
        }

        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            (new SecurityLog())->logSuspiciousUpload($originalName, 'Failed getimagesize');
            $this->errors[] = 'File gambar tidak valid atau rusak.';
            return false;
        }

        $imageMime = $imageInfo['mime'];
        if (!in_array($imageMime, ALLOWED_IMAGE_TYPES, true)) {
            (new SecurityLog())->logSuspiciousUpload($originalName, 'MIME mismatch: ' . $imageMime);
            $this->errors[] = 'Konten file tidak sesuai dengan tipe yang diizinkan.';
            return false;
        }

        $uploadDir = UPLOAD_PATH . '/' . $subfolder;
        $this->ensureSecureDirectory($uploadDir);

        $secureFilename = Security::randomFilename($ext);
        $destPath = $uploadDir . '/' . $secureFilename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            $this->errors[] = 'Gagal menyimpan file. Silakan coba lagi.';
            return false;
        }

        return $subfolder . '/' . $secureFilename;
    }

    public function uploadBase64Image(string $base64String, string $subfolder = 'misc', int $maxSizeBytes = 0): string|false {
        $this->errors = [];

        $settingMb = (int)setting('max_photo_size_mb', (string)MAX_PHOTO_SIZE_MB);
        if ($settingMb < 1) $settingMb = 5;
        $defaultMaxBytes = $settingMb * 1024 * 1024;
        $maxSizeBytes = $maxSizeBytes > 0 ? $maxSizeBytes : $defaultMaxBytes;

        if (preg_match('/^data:(image\/(jpeg|png|webp));base64,(.*)$/is', $base64String, $matches)) {
            $mimeType = strtolower($matches[1]);
            $base64Data = $matches[3];
        } else {
            $this->errors[] = 'Format data gambar tidak valid.';
            return false;
        }

        $binaryData = base64_decode($base64Data, true);
        if ($binaryData === false) {
            $this->errors[] = 'Gagal memproses data gambar.';
            return false;
        }

        if (strlen($binaryData) > $maxSizeBytes) {
            $this->errors[] = 'Ukuran file terlalu besar. Maksimal ' . round($maxSizeBytes / 1048576, 1) . ' MB.';
            return false;
        }

        if (!in_array($mimeType, ALLOWED_IMAGE_TYPES, true)) {
            $this->errors[] = 'Format file tidak didukung. Gunakan JPG, PNG, atau WebP.';
            return false;
        }

        $imageInfo = @getimagesizefromstring($binaryData);
        if ($imageInfo === false || !in_array($imageInfo['mime'], ALLOWED_IMAGE_TYPES, true)) {
            (new SecurityLog())->logSuspiciousUpload('base64_upload', 'Corrupt or invalid MIME base64');
            $this->errors[] = 'File gambar tidak valid atau rusak.';
            return false;
        }

        $extMap = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];
        $ext = $extMap[$imageInfo['mime']] ?? 'jpg';

        $uploadDir = UPLOAD_PATH . '/' . $subfolder;
        $this->ensureSecureDirectory($uploadDir);

        $secureFilename = Security::randomFilename($ext);
        $destPath = $uploadDir . '/' . $secureFilename;

        if (file_put_contents($destPath, $binaryData) === false) {
            $this->errors[] = 'Gagal menyimpan file gambar. Silakan coba lagi.';
            return false;
        }

        return $subfolder . '/' . $secureFilename;
    }

    public function uploadMultipleImages(array $files, string $subfolder = 'reports'): array {
        $results = ['success' => [], 'errors' => []];

        $fileList = $this->reindexFiles($files);

        $maxPhotos = (int)setting('max_report_photos', (string)MAX_REPORT_PHOTOS);
        if ($maxPhotos < 1) $maxPhotos = 10;

        if (count($fileList) > $maxPhotos) {
            $results['errors'][] = 'Maksimal ' . $maxPhotos . ' foto per laporan. File ke-' . ($maxPhotos + 1) . ' dan seterusnya diabaikan.';
            $fileList = array_slice($fileList, 0, $maxPhotos);
        }

        foreach ($fileList as $index => $file) {
            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $path = $this->uploadImage($file, $subfolder);
            if ($path !== false) {
                $results['success'][] = $path;
            } else {
                $results['errors'] = array_merge($results['errors'], $this->getErrors());
            }
        }

        return $results;
    }

    public function deleteFile(string $relativePath): bool {
        if (empty($relativePath)) return false;

        $realBase = realpath(UPLOAD_PATH);
        $fullPath = UPLOAD_PATH . '/' . ltrim($relativePath, '/');
        $realPath = realpath($fullPath);

        if ($realPath === false || !str_starts_with($realPath, $realBase)) {
            logError('FileUpload: path traversal attempt', ['path' => $relativePath]);
            return false;
        }

        if (file_exists($realPath)) {
            return unlink($realPath);
        }
        return false;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    private function ensureSecureDirectory(string $dir): void {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $htaccessPath = UPLOAD_PATH . '/.htaccess';
        if (!file_exists($htaccessPath)) {
            $htaccess = "Options -Indexes\n"
                      . "<FilesMatch \"\.(php|php3|php4|php5|php7|php8|phtml|phar|asp|aspx|cgi|pl|py|sh|exe)$\">\n"
                      . "    Require all denied\n"
                      . "</FilesMatch>\n"
                      . "ForceType application/octet-stream\n"
                      . "<FilesMatch \"\.(jpg|jpeg|png|webp|gif)$\">\n"
                      . "    ForceType image/jpeg\n"
                      . "</FilesMatch>\n";
            file_put_contents($htaccessPath, $htaccess);
        }
    }

    private function reindexFiles(array $files): array {
        $result = [];
        if (!isset($files['name'])) return $result;

        if (is_array($files['name'])) {
            for ($i = 0; $i < count($files['name']); $i++) {
                $result[] = [
                    'name'     => $files['name'][$i],
                    'type'     => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error'    => $files['error'][$i],
                    'size'     => $files['size'][$i],
                ];
            }
        } else {
            $result[] = $files;
        }

        return $result;
    }

    private function getUploadError(int $error): string {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File terlalu besar.',
            UPLOAD_ERR_PARTIAL => 'Upload tidak lengkap. Silakan coba lagi.',
            UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diunggah.',
            UPLOAD_ERR_NO_TMP_DIR => 'Konfigurasi server bermasalah.',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menyimpan file.',
            UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh server.',
            default => 'Terjadi kesalahan saat upload.',
        };
    }
}
