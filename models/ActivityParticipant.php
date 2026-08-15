<?php
class ActivityParticipant extends Model {
    protected string $table = 'activity_participants';
    public function isParticipant(int $activityId, int $userId): bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM activity_participants WHERE activity_id = ? AND user_id = ?');
        $stmt->execute([$activityId, $userId]);
        return (int)$stmt->fetchColumn() > 0;
    }
    public function join(int $activityId, int $userId): bool {
        if ($this->isParticipant($activityId, $userId)) return false;
        $this->create(['activity_id' => $activityId, 'user_id' => $userId, 'status' => 'registered']);
        return true;
    }
    public function leave(int $activityId, int $userId): bool {
        return $this->execute('DELETE FROM activity_participants WHERE activity_id = ? AND user_id = ?', [$activityId, $userId]);
    }
}
