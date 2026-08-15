<?php
class Notification extends Model {
    protected string $table = 'notifications';
    public function getForUser(int $userId, int $limit = 20): array {
        return $this->query('SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?', [$userId, $limit]);
    }
    public function getUnreadCount(int $userId): int {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0');
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }
    public function markAllRead(int $userId): void {
        $this->execute('UPDATE notifications SET is_read = 1 WHERE user_id = ?', [$userId]);
    }
    public function createFor(int $userId, string $type, string $title, string $message, string $link = ''): void {
        $this->create(['user_id' => $userId, 'type' => $type, 'title' => $title, 'message' => $message, 'link' => $link, 'is_read' => 0]);
    }
}
