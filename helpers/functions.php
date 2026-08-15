<?php

function e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function redirect(string $path): never {
    $url = str_starts_with($path, 'http') ? $path : BASE_URL . '/' . ltrim($path, '/');
    header('Location: ' . $url);
    exit;
}

function redirectBack(): never {
    $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
    redirect($referer);
}

function currentPath(): string {
    return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
}

function isActivePath(string $path): bool {
    $current = currentPath();
    $base = rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
    $cleanCurrent = str_replace($base, '', $current);
    return $cleanCurrent === '/' . ltrim($path, '/') || str_starts_with($cleanCurrent, '/' . ltrim($path, '/'));
}

function url(string $path = ''): string {
    return BASE_URL . '/' . ltrim($path, '/');
}

function uploadUrl(string $path): string {
    return UPLOAD_URL . '/' . ltrim($path, '/');
}

function formatDate(string $date, string $format = 'd M Y'): string {
    $months = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
    ];

    $timestamp = strtotime($date);
    if (!$timestamp) return '-';

    $day = date('d', $timestamp);
    $month = $months[date('m', $timestamp)] ?? date('M', $timestamp);
    $year = date('Y', $timestamp);
    $time = date('H:i', $timestamp);

    return match ($format) {
        'd M Y'     => "$day $month $year",
        'd M Y H:i' => "$day $month $year $time",
        'relative'  => formatRelativeDate($timestamp),
        default     => date($format, $timestamp),
    };
}

function formatRelativeDate(int $timestamp): string {
    $diff = time() - $timestamp;
    if ($diff < 60) return 'Baru saja';
    if ($diff < 3600) return floor($diff / 60) . ' menit yang lalu';
    if ($diff < 86400) return floor($diff / 3600) . ' jam yang lalu';
    if ($diff < 604800) return floor($diff / 86400) . ' hari yang lalu';
    return formatDate(date('Y-m-d H:i:s', $timestamp));
}

function statusLabel(string $status): string {
    return REPORT_STATUS_LABELS[$status] ?? $status;
}

function reportStatusLabel(string $status): string {
    return REPORT_STATUS_LABELS[$status] ?? $status;
}

function statusColor(string $status): string {
    $colors = REPORT_STATUS_COLORS;
    $color = $colors[$status] ?? 'gray';
    return match ($color) {
        'yellow'  => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'blue'    => 'bg-blue-100 text-blue-800 border-blue-200',
        'red'     => 'bg-red-100 text-red-800 border-red-200',
        'purple'  => 'bg-purple-100 text-purple-800 border-purple-200',
        'indigo'  => 'bg-indigo-100 text-indigo-800 border-indigo-200',
        'orange'  => 'bg-orange-100 text-orange-800 border-orange-200',
        'green'   => 'bg-green-100 text-green-800 border-green-200',
        default   => 'bg-gray-100 text-gray-800 border-gray-200',
    };
}

function truncate(string $text, int $length = 150, string $suffix = '...'): string {
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . $suffix;
}

function setFlash(string $type, string $message): void {
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function getFlash(): array {
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

function paginate(int $total, int $perPage, int $currentPage): array {
    $totalPages = (int)ceil($total / $perPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $perPage;

    return [
        'total'        => $total,
        'per_page'     => $perPage,
        'current_page' => $currentPage,
        'total_pages'  => $totalPages,
        'offset'       => $offset,
        'has_prev'     => $currentPage > 1,
        'has_next'     => $currentPage < $totalPages,
        'prev_page'    => $currentPage - 1,
        'next_page'    => $currentPage + 1,
    ];
}

function currentPage(): int {
    return max(1, (int)($_GET['page'] ?? 1));
}

function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

function currentUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

function currentUser(): ?array {
    if (!isLoggedIn()) return null;
    static $cachedUser = null;
    if ($cachedUser === null) {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            'SELECT u.id, u.email, u.role, u.status, p.username, p.full_name, p.avatar_path, p.is_complete
             FROM users u
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE u.id = ?'
        );
        $stmt->execute([currentUserId()]);
        $cachedUser = $stmt->fetch() ?: null;
    }
    return $cachedUser;
}

function currentUserRole(): string {
    return $_SESSION['user_role'] ?? ROLE_GUEST;
}

function hasRole(string ...$roles): bool {
    return in_array(currentUserRole(), $roles, true);
}

function requireAuth(string $redirect = ''): void {
    if (!isLoggedIn()) {
        if (Security::isAjax()) {
            http_response_code(401);
            echo json_encode(['error' => 'Anda harus login untuk menggunakan fitur ini.', 'require_auth' => true]);
            exit;
        }
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '/';
        setFlash('error', 'Silakan login untuk mengakses halaman ini.');
        redirect('auth/login');
    }
}

function requireRole(string ...$roles): void {
    requireAuth();
    if (!hasRole(...$roles)) {
        (new SecurityLog())->logUnauthorizedAccess($_SERVER['REQUEST_URI'] ?? '', currentUserId());
        if (Security::isAjax()) {
            http_response_code(403);
            echo json_encode(['error' => 'Anda tidak memiliki akses ke fitur ini.']);
            exit;
        }
        setFlash('error', 'Anda tidak memiliki akses ke halaman ini.');
        redirect('');
    }
}

function csrfField(): string {
    return Security::csrfField();
}

function formatFileSize(int $bytes): string {
    if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
    return $bytes . ' B';
}

function jsonResponse(mixed $data, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function logError(string $message, array $context = []): void {
    $logEntry = date('Y-m-d H:i:s') . ' | ' . $message;
    if (!empty($context)) {
        $safe = array_diff_key($context, array_flip(['password', 'token', 'ktp', 'encryption_key']));
        $logEntry .= ' | ' . json_encode($safe);
    }
    error_log($logEntry);
}

function theme(string $key, string $default = '', bool $forceReload = false): string {
    static $themeCache = null;
    if ($themeCache === null || $forceReload) {
        try {
            $db = Database::getConnection();
            $stmt = $db->query('SELECT `key`, `value` FROM theme_settings');
            $themeCache = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        } catch (\Exception $e) {
            $themeCache = [];
        }
    }
    return $themeCache[$key] ?? $default;
}

function clearThemeCache(): void {
    theme('', '', true);
}

function setting(string $key, string $default = '', bool $forceReload = false): string {
    static $settingCache = null;
    if ($settingCache === null || $forceReload) {
        try {
            $db = Database::getConnection();
            $stmt = $db->query('SELECT `key`, `value` FROM system_settings');
            $settingCache = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        } catch (\Exception $e) {
            $settingCache = [];
        }
    }
    return $settingCache[$key] ?? $default;
}

function clearSettingCache(): void {
    setting('', '', true);
}

function isRegistrationEnabled(): bool {
    return setting('registration_enabled', '1') === '1';
}

function isOrganizerJoinEnabled(): bool {
    return setting('organizer_registration_enabled', '1') === '1';
}

function isMaintenanceMode(): bool {
    return setting('maintenance_mode', '0') === '1';
}

