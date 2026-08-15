<?php

class AuditLog {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function log(
        string $action,
        string $entityType = '',
        ?int $entityId = null,
        array $details = []
    ): void {
        $userId = currentUserId();
        $ip = Security::getClientIp();
        $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500);

        $safeDetails = $this->sanitizeDetails($details);
        $detailsJson = !empty($safeDetails) ? json_encode($safeDetails, JSON_UNESCAPED_UNICODE) : null;

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO audit_logs (user_id, action, entity_type, entity_id, details, ip_address, user_agent, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW())'
            );
            $stmt->execute([$userId, $action, $entityType, $entityId, $detailsJson, $ip, $ua]);
        } catch (\Exception $e) {
            error_log('AuditLog write failed: ' . $e->getMessage());
        }
    }

    private function sanitizeDetails(array $details): array {
        $forbidden = ['password', 'password_hash', 'token', 'csrf_token', 'ktp', 'ktp_path',
                      'encryption_key', 'secret', 'api_key', 'private_key'];
        foreach ($forbidden as $key) {
            unset($details[$key]);
        }
        return $details;
    }

    public function logLogin(int $userId, string $email): void {
        $this->log('login', 'user', $userId, ['email_domain' => substr($email, strpos($email, '@'))]);
    }

    public function logLogout(int $userId): void {
        $this->log('logout', 'user', $userId);
    }

    public function logFailedLogin(string $email): void {
        $this->log('failed_login', 'user', null, ['email_domain' => substr($email, strpos($email, '@'))]);
    }

    public function logRegister(int $userId): void {
        $this->log('register', 'user', $userId);
    }

    public function logCreateReport(int $reportId, string $title): void {
        $this->log('create_report', 'report', $reportId, ['title' => $title]);
    }

    public function logDeleteReport(int $reportId, string $title): void {
        $this->log('delete_report', 'report', $reportId, ['title' => $title]);
    }

    public function logChangeReportStatus(int $reportId, string $oldStatus, string $newStatus): void {
        $this->log('change_report_status', 'report', $reportId, [
            'from' => $oldStatus,
            'to'   => $newStatus,
        ]);
    }

    public function logAdminAction(string $action, string $entityType, int $entityId, array $details = []): void {
        $this->log('admin_' . $action, $entityType, $entityId, $details);
    }

    public function logBannerChange(string $action, int $bannerId, string $title): void {
        $this->log('banner_' . $action, 'banner', $bannerId, ['title' => $title]);
    }

    public function logUserChange(string $action, int $targetUserId, array $changes = []): void {
        $this->log('user_' . $action, 'user', $targetUserId, $changes);
    }

    public function logSecurityEvent(string $event, array $details = []): void {
        $this->log('security_' . $event, 'system', null, $details);
    }
}
