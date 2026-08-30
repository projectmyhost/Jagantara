<?php

define('APP_NAME', 'Jagantara');
define('APP_TAGLINE', 'Platform Pelaporan dan Aksi Lingkungan Berbasis Masyarakat');
define('APP_VERSION', '2.0.0');
define('APP_ENV', 'development');

define('APP_ROOT', dirname(__DIR__));
define('PUBLIC_PATH', APP_ROOT . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$basePath = ($scriptName === '/' || $scriptName === '\\') ? '' : rtrim($scriptName, '/\\');
define('BASE_URL', $protocol . '://' . $host . $basePath);
define('PUBLIC_URL', BASE_URL . '/public');
define('UPLOAD_URL', PUBLIC_URL . '/uploads');

define('MAX_REPORT_PHOTOS', 10);
define('MAX_PHOTO_SIZE_MB', 5);
define('MAX_PHOTO_SIZE_BYTES', MAX_PHOTO_SIZE_MB * 1024 * 1024);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);

define('SESSION_NAME', 'jagantara_session');

define('LOGIN_RATE_LIMIT', 5);
define('LOGIN_RATE_WINDOW', 900);
define('REGISTER_RATE_LIMIT', 2);
define('REGISTER_RATE_WINDOW', 604800);
define('REPORT_RATE_LIMIT', 10);
define('REPORT_RATE_WINDOW', 3600);
define('FORUM_RATE_LIMIT', 20);
define('FORUM_RATE_WINDOW', 3600);

define('ROLE_GUEST', 'guest');
define('ROLE_USER', 'user');
define('ROLE_ORGANIZER_MEMBER', 'organizer_member');
define('ROLE_ADMIN', 'admin');

define('STATUS_PENDING', 'pending');
define('STATUS_VERIFIED', 'verified');
define('STATUS_REJECTED', 'rejected');
define('STATUS_PLANNING', 'planning');
define('STATUS_SCHEDULED', 'scheduled');
define('STATUS_IN_PROGRESS', 'in_progress');
define('STATUS_COMPLETED', 'completed');

define('REPORT_STATUS_LABELS', [
    STATUS_PENDING => 'Menunggu Verifikasi',
    STATUS_VERIFIED => 'Diverifikasi',
    STATUS_REJECTED => 'Ditolak',
    STATUS_PLANNING => 'Dalam Perencanaan',
    STATUS_SCHEDULED => 'Dijadwalkan',
    STATUS_IN_PROGRESS => 'Sedang Ditangani',
    STATUS_COMPLETED => 'Selesai Ditangani',
]);

define('REPORT_STATUS_COLORS', [
    STATUS_PENDING => 'yellow',
    STATUS_VERIFIED => 'blue',
    STATUS_REJECTED => 'red',
    STATUS_PLANNING => 'purple',
    STATUS_SCHEDULED => 'indigo',
    STATUS_IN_PROGRESS => 'orange',
    STATUS_COMPLETED => 'green',
]);

define('DEFAULT_REGIONS', ['Jakarta', 'Bogor', 'Depok', 'Tangerang', 'Bekasi']);

define('ITEMS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 20);
