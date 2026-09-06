<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(self)');
}

if (!ob_get_level() && !headers_sent() && extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
    ob_start('ob_gzhandler');
}

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/security.php';
require_once __DIR__ . '/helpers/functions.php';

require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/RateLimit.php';
require_once __DIR__ . '/core/AuditLog.php';
require_once __DIR__ . '/core/SecurityLog.php';
require_once __DIR__ . '/core/FileUpload.php';
require_once __DIR__ . '/core/Validator.php';

require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Profile.php';
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/models/Region.php';
require_once __DIR__ . '/models/Banner.php';
require_once __DIR__ . '/models/Report.php';
require_once __DIR__ . '/models/ReportPhoto.php';
require_once __DIR__ . '/models/ReportStatusHistory.php';
require_once __DIR__ . '/models/Organizer.php';
require_once __DIR__ . '/models/OrganizerApplication.php';
require_once __DIR__ . '/models/OrganizerMember.php';
require_once __DIR__ . '/models/Activity.php';
require_once __DIR__ . '/models/ActivityParticipant.php';
require_once __DIR__ . '/models/ForumPost.php';
require_once __DIR__ . '/models/ForumComment.php';
require_once __DIR__ . '/models/Notification.php';
require_once __DIR__ . '/models/CleanupLocation.php';

require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ReportController.php';
require_once __DIR__ . '/controllers/ProfileController.php';
require_once __DIR__ . '/controllers/OrganizerController.php';
require_once __DIR__ . '/controllers/ActivityController.php';
require_once __DIR__ . '/controllers/ForumController.php';
require_once __DIR__ . '/controllers/MapController.php';

require_once __DIR__ . '/controllers/admin/DashboardController.php';
require_once __DIR__ . '/controllers/admin/BannerController.php';
require_once __DIR__ . '/controllers/admin/ReportAdminController.php';
require_once __DIR__ . '/controllers/admin/UserAdminController.php';
require_once __DIR__ . '/controllers/admin/OrganizerAdminController.php';
require_once __DIR__ . '/controllers/admin/CategoryAdminController.php';
require_once __DIR__ . '/controllers/admin/RegionAdminController.php';
require_once __DIR__ . '/controllers/admin/ActivityAdminController.php';
require_once __DIR__ . '/controllers/admin/ThemeAdminController.php';
require_once __DIR__ . '/controllers/admin/AuditLogAdminController.php';
require_once __DIR__ . '/controllers/admin/SettingsAdminController.php';
require_once __DIR__ . '/controllers/admin/MapManagementAdminController.php';

Security::startSession();

if (isLoggedIn()) {
    $currentPath = currentPath();
    $base = rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
    $cleanPath = '/' . ltrim(str_replace($base, '', $currentPath), '/');

    $allowedPaths = ['/auth/check-status', '/auth/logout'];
    if (!in_array($cleanPath, $allowedPaths, true)) {
        try {
            $userDb = (new User())->find((int)currentUserId());
            if (!$userDb || $userDb['status'] === 'banned' || $userDb['status'] !== 'active') {

                $_SESSION = [];
                if (ini_get('session.use_cookies')) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), '', time() - 42000,
                        $params['path'], $params['domain'],
                        $params['secure'], $params['httponly']
                    );
                }
                session_destroy();

                if (Security::isAjax()) {
                    http_response_code(403);
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode([
                        'status'  => 'banned',
                        'banned'  => true,
                        'message' => 'Akun Anda telah di-banned, hubungi kontak admin untuk informasi selanjutnya.'
                    ]);
                    exit;
                }

                Security::startSession();
                setFlash('error', 'Akun Anda telah di-banned. Hubungi kontak admin untuk informasi selanjutnya.');
                redirect('auth/login');
            }
        } catch (\Throwable $e) {
            logError('Session user status check error: ' . $e->getMessage());
        }
    }
}

if (isMaintenanceMode()) {
    $currentPath = currentPath();
    $base = rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
    $cleanPath = '/' . ltrim(str_replace($base, '', $currentPath), '/');

    $isAllowed = false;
    if (isLoggedIn() && hasRole(ROLE_ADMIN)) {
        $isAllowed = true;
    } elseif (
        str_starts_with($cleanPath, '/adminlogin34821') ||
        str_starts_with($cleanPath, '/auth/logout') ||
        str_starts_with($cleanPath, '/auth/check-status') ||
        str_starts_with($cleanPath, '/public')
    ) {
        $isAllowed = true;
    }

    if (!$isAllowed) {
        http_response_code(503);
        header('Retry-After: 3600');
        require __DIR__ . '/views/errors/maintenance.php';
        exit;
    }
}

$router = new Router();

$router->get('/', 'HomeController', 'index');
$router->get('/map', 'MapController', 'index');
$router->get('/api/map-data', 'MapController', 'getData');
$router->get('/api/cleanup-locations', 'MapController', 'getCleanupLocations');
$router->get('/api/notifications/count', 'MapController', 'getNotificationCount');
$router->get('/api/reverse-geocode', 'MapController', 'reverseGeocode');
$router->post('/api/reverse-geocode', 'MapController', 'reverseGeocode');

$router->get('/adminlogin34821', 'AuthController', 'showAdminLoginSecret');
$router->post('/adminlogin34821', 'AuthController', 'loginAdminSecret');

$router->get('/auth/login', 'AuthController', 'showLogin');
$router->post('/auth/login', 'AuthController', 'login');
$router->get('/auth/register', 'AuthController', 'showRegister');
$router->post('/auth/register', 'AuthController', 'register');
$router->get('/auth/logout', 'AuthController', 'logout');
$router->post('/auth/logout', 'AuthController', 'logout');
$router->get('/auth/check-status', 'AuthController', 'checkStatus');

$router->get('/reports', 'ReportController', 'index');
$router->get('/reports/{id}', 'ReportController', 'show');

$router->get('/organizers', 'OrganizerController', 'index');
$router->get('/organizers/{id}', 'OrganizerController', 'show');

$router->get('/activities', 'ActivityController', 'index');
$router->get('/activities/{id}', 'ActivityController', 'show');
$router->post('/activities/{id}/join', 'ActivityController', 'join');
$router->post('/activities/{id}/leave', 'ActivityController', 'leave');

$router->get('/forum', 'ForumController', 'index');

$router->get('/reports/create', 'ReportController', 'create');
$router->post('/reports/store', 'ReportController', 'store');
$router->get('/reports/status', 'ReportController', 'status');
$router->post('/reports/{id}/delete', 'ReportController', 'delete');

$router->get('/profile', 'ProfileController', 'index');
$router->get('/profile/edit', 'ProfileController', 'edit');
$router->get('/profile/settings', 'ProfileController', 'edit');
$router->get('/settings', 'ProfileController', 'edit');
$router->post('/profile/update', 'ProfileController', 'update');
$router->post('/profile/avatar', 'ProfileController', 'updateAvatar');

$router->get('/organizers/join/{id}', 'OrganizerController', 'joinForm');
$router->post('/organizers/join/{id}', 'OrganizerController', 'joinSubmit');
$router->get('/organizers/my-status', 'OrganizerController', 'myStatus');

$router->get('/admin', 'DashboardController', 'index');
$router->get('/admin/dashboard', 'DashboardController', 'index');

$router->get('/admin/banners', 'BannerAdminController', 'index');
$router->get('/admin/banners/create', 'BannerAdminController', 'create');
$router->post('/admin/banners/store', 'BannerAdminController', 'store');
$router->get('/admin/banners/{id}/edit', 'BannerAdminController', 'edit');
$router->post('/admin/banners/{id}/update', 'BannerAdminController', 'update');
$router->post('/admin/banners/{id}/delete', 'BannerAdminController', 'delete');
$router->post('/admin/banners/{id}/toggle', 'BannerAdminController', 'toggle');

$router->get('/admin/reports', 'ReportAdminController', 'index');
$router->get('/admin/reports/{id}', 'ReportAdminController', 'show');
$router->post('/admin/reports/{id}/status', 'ReportAdminController', 'updateStatus');
$router->post('/admin/reports/{id}/delete', 'ReportAdminController', 'delete');

$router->get('/admin/users', 'UserAdminController', 'index');
$router->get('/admin/users/{id}', 'UserAdminController', 'show');
$router->get('/admin/users/{id}/edit', 'UserAdminController', 'edit');
$router->post('/admin/users/{id}/update', 'UserAdminController', 'update');
$router->post('/admin/users/{id}/delete', 'UserAdminController', 'delete');

$router->get('/admin/organizers', 'OrganizerAdminController', 'index');
$router->get('/admin/organizers/create', 'OrganizerAdminController', 'create');
$router->post('/admin/organizers/store', 'OrganizerAdminController', 'store');
$router->get('/admin/organizers/{id}/edit', 'OrganizerAdminController', 'edit');
$router->post('/admin/organizers/{id}/update', 'OrganizerAdminController', 'update');
$router->post('/admin/organizers/{id}/delete', 'OrganizerAdminController', 'delete');
$router->get('/admin/organizers/{id}/applications', 'OrganizerAdminController', 'applications');
$router->post('/admin/organizers/applications/{id}/approve', 'OrganizerAdminController', 'approveApplication');
$router->post('/admin/organizers/applications/{id}/reject', 'OrganizerAdminController', 'rejectApplication');

$router->get('/admin/categories', 'CategoryAdminController', 'index');
$router->get('/admin/categories/create', 'CategoryAdminController', 'create');
$router->post('/admin/categories/store', 'CategoryAdminController', 'store');
$router->get('/admin/categories/{id}/edit', 'CategoryAdminController', 'edit');
$router->post('/admin/categories/{id}/update', 'CategoryAdminController', 'update');
$router->post('/admin/categories/{id}/delete', 'CategoryAdminController', 'delete');

$router->get('/admin/regions', 'RegionAdminController', 'index');
$router->get('/admin/regions/create', 'RegionAdminController', 'create');
$router->post('/admin/regions/store', 'RegionAdminController', 'store');
$router->get('/admin/regions/{id}/edit', 'RegionAdminController', 'edit');
$router->post('/admin/regions/{id}/update', 'RegionAdminController', 'update');
$router->post('/admin/regions/{id}/delete', 'RegionAdminController', 'delete');

$router->get('/admin/activities', 'ActivityAdminController', 'index');
$router->get('/admin/activities/create', 'ActivityAdminController', 'create');
$router->post('/admin/activities/store', 'ActivityAdminController', 'store');
$router->get('/admin/activities/{id}/edit', 'ActivityAdminController', 'edit');
$router->post('/admin/activities/{id}/update', 'ActivityAdminController', 'update');
$router->post('/admin/activities/{id}/delete', 'ActivityAdminController', 'delete');
$router->get('/admin/activities/{id}/participants', 'ActivityAdminController', 'participants');

$router->get('/admin/theme', 'ThemeAdminController', 'index');
$router->post('/admin/theme/update', 'ThemeAdminController', 'update');
$router->post('/admin/theme/reset', 'ThemeAdminController', 'reset');

$router->get('/admin/audit-logs', 'AuditLogAdminController', 'index');
$router->get('/admin/security-logs', 'AuditLogAdminController', 'securityLogs');

$router->get('/admin/settings', 'SettingsAdminController', 'index');
$router->post('/admin/settings/update', 'SettingsAdminController', 'update');

$router->get('/admin/map-management', 'MapManagementAdminController', 'index');
$router->get('/admin/map-management/create', 'MapManagementAdminController', 'create');
$router->post('/admin/map-management/store', 'MapManagementAdminController', 'store');
$router->get('/admin/map-management/edit/{id}', 'MapManagementAdminController', 'edit');
$router->post('/admin/map-management/update/{id}', 'MapManagementAdminController', 'update');
$router->post('/admin/map-management/delete/{id}', 'MapManagementAdminController', 'delete');
$router->post('/admin/map-management/update-status/{id}', 'MapManagementAdminController', 'updateStatus');
$router->get('/admin/map-management/locations-json', 'MapManagementAdminController', 'getLocationsJson');
$router->post('/admin/map-management/geocode', 'MapManagementAdminController', 'geocode');
$router->post('/admin/map-management/reverse-geocode', 'MapManagementAdminController', 'reverseGeocode');

$router->dispatch();
