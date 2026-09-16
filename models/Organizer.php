<?php
class Organizer extends Model {
    protected string $table = 'organizers';

    public function getActive(int $page = 1, int $perPage = 12): array {
        $offset = ($page - 1) * $perPage;
        $countStmt = $this->db->query('SELECT COUNT(*) FROM organizers WHERE is_active = 1');
        $total = (int)$countStmt->fetchColumn();
        $stmt = $this->db->prepare(
            "SELECT o.*, r.name AS region_name,
                    (SELECT COUNT(*) FROM organizer_members om WHERE om.organizer_id = o.id AND om.is_active = 1) AS member_count,
                    (SELECT COUNT(*) FROM activities a WHERE a.organizer_id = o.id AND a.deleted_at IS NULL) AS activity_count
             FROM organizers o
             LEFT JOIN regions r ON r.id = o.region_id
             WHERE o.is_active = 1
             ORDER BY o.name ASC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([$perPage, $offset]);
        return ['data' => $stmt->fetchAll(), 'total' => $total, 'per_page' => $perPage, 'current_page' => $page, 'total_pages' => (int)ceil($total / $perPage)];
    }

    public function findWithDetails(int $id): ?array {
        return $this->queryOne(
            "SELECT o.*, r.name AS region_name,
                    (SELECT COUNT(*) FROM organizer_members om WHERE om.organizer_id = o.id AND om.is_active = 1) AS member_count
             FROM organizers o LEFT JOIN regions r ON r.id = o.region_id
             WHERE o.id = ?",
            [$id]
        );
    }

    public function findBySlug(string $slug): ?array {
        return $this->queryOne(
            "SELECT o.*, r.name AS region_name FROM organizers o
             LEFT JOIN regions r ON r.id = o.region_id WHERE o.slug = ?",
            [$slug]
        );
    }
}
