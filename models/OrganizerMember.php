<?php
class OrganizerMember extends Model {
    protected string $table = 'organizer_members';
    public function isMember(int $userId, int $organizerId): bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM organizer_members WHERE user_id = ? AND organizer_id = ? AND is_active = 1');
        $stmt->execute([$userId, $organizerId]);
        return (int)$stmt->fetchColumn() > 0;
    }
    public function getByOrganizer(int $organizerId): array {
        return $this->query("SELECT om.*, p.full_name, p.avatar_path, u.email FROM organizer_members om LEFT JOIN users u ON u.id = om.user_id LEFT JOIN profiles p ON p.user_id = om.user_id WHERE om.organizer_id = ? AND om.is_active = 1 ORDER BY om.joined_at DESC", [$organizerId]);
    }
}
