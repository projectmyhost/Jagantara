<?php

class RateLimit {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    private function getLoginWindow(): int {
        $mins = (int)setting('login_rate_window_minutes', '15');
        return ($mins > 0 ? $mins : 15) * 60;
    }

    private function getLoginLimit(): int {
        $limit = (int)setting('login_rate_limit', (string)LOGIN_RATE_LIMIT);
        return $limit > 0 ? $limit : 5;
    }

    private function getRegisterWindow(): int {
        $days = (int)setting('register_rate_window_days', '7');
        return ($days > 0 ? $days : 7) * 86400;
    }

    private function getRegisterLimit(): int {
        $limit = (int)setting('register_rate_limit', (string)REGISTER_RATE_LIMIT);
        return $limit > 0 ? $limit : 2;
    }

    public function checkLogin(string $ip, string $email): bool {
        $window = time() - $this->getLoginWindow();
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM login_attempts
             WHERE (ip_address = ? OR email = ?)
             AND success = 0
             AND created_at > FROM_UNIXTIME(?)'
        );
        $stmt->execute([$ip, $email, $window]);
        $count = (int)$stmt->fetchColumn();
        return $count < $this->getLoginLimit();
    }

    public function recordLoginAttempt(string $ip, string $email, bool $success): void {
        $stmt = $this->db->prepare(
            'INSERT INTO login_attempts (ip_address, email, success, created_at)
             VALUES (?, ?, ?, NOW())'
        );
        $stmt->execute([$ip, $email, $success ? 1 : 0]);
    }

    public function getRemainingLoginAttempts(string $ip, string $email): int {
        $window = time() - $this->getLoginWindow();
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM login_attempts
             WHERE (ip_address = ? OR email = ?)
             AND success = 0
             AND created_at > FROM_UNIXTIME(?)'
        );
        $stmt->execute([$ip, $email, $window]);
        $count = (int)$stmt->fetchColumn();
        return max(0, $this->getLoginLimit() - $count);
    }

    public function checkRegistration(string $ip, string $deviceFingerprint): bool {
        $window = time() - $this->getRegisterWindow();
        $stmt = $this->db->prepare(
            'SELECT COUNT(DISTINCT user_id) FROM registration_attempts
             WHERE ip_address = ?
             AND device_fingerprint = ?
             AND created_at > FROM_UNIXTIME(?)'
        );
        $stmt->execute([$ip, $deviceFingerprint, $window]);
        $count = (int)$stmt->fetchColumn();
        return $count < $this->getRegisterLimit();
    }

    public function recordRegistration(string $ip, string $deviceFingerprint, int $userId): void {
        $stmt = $this->db->prepare(
            'INSERT INTO registration_attempts (ip_address, device_fingerprint, user_id, created_at)
             VALUES (?, ?, ?, NOW())'
        );
        $stmt->execute([$ip, $deviceFingerprint, $userId]);
    }

    public function checkReport(int $userId): bool {
        $window = time() - REPORT_RATE_WINDOW;
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM reports
             WHERE user_id = ?
             AND created_at > FROM_UNIXTIME(?)'
        );
        $stmt->execute([$userId, $window]);
        $count = (int)$stmt->fetchColumn();
        return $count < REPORT_RATE_LIMIT;
    }

    public function checkForum(int $userId): bool {
        $window = time() - FORUM_RATE_WINDOW;
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM forum_posts
             WHERE user_id = ?
             AND created_at > FROM_UNIXTIME(?)'
        );
        $stmt->execute([$userId, $window]);
        $count = (int)$stmt->fetchColumn();
        return $count < FORUM_RATE_LIMIT;
    }

    public function checkComment(int $userId): bool {
        $window = time() - FORUM_RATE_WINDOW;
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM forum_comments
             WHERE user_id = ?
             AND created_at > FROM_UNIXTIME(?)'
        );
        $stmt->execute([$userId, $window]);
        $count = (int)$stmt->fetchColumn();
        return $count < FORUM_RATE_LIMIT;
    }

    public function checkAdminLogin(string $ip): bool {
        $window = time() - $this->getLoginWindow();
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM login_attempts
             WHERE ip_address = ?
             AND success = 0
             AND created_at > FROM_UNIXTIME(?)'
        );
        $stmt->execute([$ip, $window]);
        $count = (int)$stmt->fetchColumn();
        return $count < $this->getLoginLimit();
    }

    public function cleanup(): void {
        $oldWindow = time() - max($this->getRegisterWindow(), $this->getLoginWindow(), REPORT_RATE_WINDOW);
        $this->db->prepare('DELETE FROM login_attempts WHERE created_at < FROM_UNIXTIME(?)')->execute([$oldWindow]);

    }
}
