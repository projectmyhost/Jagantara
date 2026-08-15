<?php
class Activity extends Model {
    protected string $table = 'activities';
    protected bool $useSoftDelete = true;

    public function listPublic(array $filters = [], int $page = 1, int $perPage = 12): array {
        $where = ["a.deleted_at IS NULL", "a.status IN ('published','ongoing','completed')"];
        $params = [];
        if (!empty($filters['region_id'])) { $where[] = 'a.region_id = ?'; $params[] = (int)$filters['region_id']; }
        if (!empty($filters['status'])) { $where[] = 'a.status = ?'; $params[] = $filters['status']; }
        $whereClause = 'WHERE ' . implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM activities a $whereClause");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();
        $stmt = $this->db->prepare(
            "SELECT a.*, o.name AS organizer_name, r.name AS region_name,
                    (SELECT COUNT(*) FROM activity_participants ap WHERE ap.activity_id = a.id) AS participant_count
             FROM activities a
             LEFT JOIN organizers o ON o.id = a.organizer_id
             LEFT JOIN regions r ON r.id = a.region_id
             $whereClause ORDER BY a.scheduled_at ASC LIMIT ? OFFSET ?"
        );
        $stmt->execute([...$params, $perPage, $offset]);
        return ['data' => $stmt->fetchAll(), 'total' => $total, 'per_page' => $perPage, 'current_page' => $page, 'total_pages' => (int)ceil($total / $perPage)];
    }

    public function findWithDetails(int $id): ?array {
        return $this->queryOne(
            "SELECT a.*, o.name AS organizer_name, r.name AS region_name,
                    (SELECT COUNT(*) FROM activity_participants ap WHERE ap.activity_id = a.id) AS participant_count
             FROM activities a LEFT JOIN organizers o ON o.id = a.organizer_id LEFT JOIN regions r ON r.id = a.region_id
             WHERE a.id = ? AND a.deleted_at IS NULL",
            [$id]
        );
    }

    public function getRecent(int $limit = 3): array {
        return $this->query(
            "SELECT a.*, o.name AS organizer_name, r.name AS region_name
             FROM activities a LEFT JOIN organizers o ON o.id = a.organizer_id LEFT JOIN regions r ON r.id = a.region_id
             WHERE a.deleted_at IS NULL AND a.status = 'published' AND a.scheduled_at >= NOW()
             ORDER BY a.scheduled_at ASC LIMIT ?",
            [$limit]
        );
    }
}
