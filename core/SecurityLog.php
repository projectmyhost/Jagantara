<?php

class SecurityLog {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function log(string $action, string $details = '', ?string $ip = null, ?string $deviceFingerprint = null, ?int $userId = null): void {
        $ip = $ip ?? Security::getClientIp();
        $device = $deviceFingerprint ?? Security::getDeviceFingerprint();
        $userId = $userId ?? (isLoggedIn() ? (int)currentUserId() : null);

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO security_logs (user_id, ip_address, device_fingerprint, action, details, created_at)
                 VALUES (?, ?, ?, ?, ?, NOW())'
            );
            $stmt->execute([$userId, $ip, $device, $action, $details]);
        } catch (\Exception $e) {
            error_log('SecurityLog write failed: ' . $e->getMessage());
        }
    }

    public function logBruteForce(string $ip, string $email): void {
        $this->log('brute_force_detected', json_encode([
            'email' => $email,
            'reason' => 'Batas percobaan login terlampaui (Rate limit login)',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '-'
        ]), $ip);
    }

    public function logFailedLogin(string $email, string $ip, int $remainingAttempts): void {
        $this->log('failed_login_attempt', json_encode([
            'email' => $email,
            'remaining_attempts' => $remainingAttempts,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '-'
        ]), $ip);
    }

    public function logSuspiciousRegistration(string $ip, string $device): void {
        $this->log('suspicious_registration', json_encode([
            'reason' => 'Batas registrasi akun per IP / perangkat terlampaui',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '-'
        ]), $ip, $device);
    }

    public function logInvalidCsrf(string $path): void {
        $this->log('invalid_csrf', json_encode([
            'path' => $path,
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'POST',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '-'
        ]));
    }

    public function logUnauthorizedAccess(string $path, ?int $userId = null): void {
        $this->log('unauthorized_access', json_encode([
            'path' => $path,
            'reason' => 'Akses 403 Forbidden / Hak akses tidak mencukupi',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '-'
        ]), null, null, $userId);
    }

    public function logSuspiciousUpload(string $filename, string $reason): void {
        $this->log('suspicious_upload', json_encode([
            'filename' => $filename,
            'reason' => $reason,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '-'
        ]));
    }

    public function logIdorAttempt(string $resource, int $targetId, int $requesterId): void {
        $this->log('idor_attempt', json_encode([
            'resource' => $resource,
            'target_id' => $targetId,
            'requester_id' => $requesterId,
            'reason' => 'Percobaan manipulasi/akses resource milik user lain tanpa izin',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '-'
        ]), null, null, $requesterId);
    }

    public function logRateLimitExceeded(string $actionType, ?int $userId = null): void {
        $this->log('rate_limit_exceeded', json_encode([
            'action_type' => $actionType,
            'path' => $_SERVER['REQUEST_URI'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '-'
        ]), null, null, $userId);
    }
}
