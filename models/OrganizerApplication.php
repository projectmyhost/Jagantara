<?php
class OrganizerApplication extends Model {
    protected string $table = 'organizer_applications';

    public function getByUser(int $userId): array {
        return $this->query(
            "SELECT a.*, o.name AS organizer_name FROM organizer_applications a
             LEFT JOIN organizers o ON o.id = a.organizer_id WHERE a.user_id = ? ORDER BY a.created_at DESC",
            [$userId]
        );
    }

    public function hasPending(int $userId, int $organizerId): bool {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM organizer_applications WHERE user_id = ? AND organizer_id = ? AND status = 'pending'"
        );
        $stmt->execute([$userId, $organizerId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function listAdmin(int $organizerId, int $page, int $perPage, string $status = ''): array {
        $where = ['a.organizer_id = ?'];
        $params = [$organizerId];
        if ($status !== '') {
            $where[] = 'a.status = ?';
            $params[] = $status;
        }
        $whereClause = 'WHERE ' . implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM organizer_applications a $whereClause");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT a.*, p.full_name AS applicant_name, u.email AS applicant_email, r.name AS region_name
             FROM organizer_applications a
             LEFT JOIN users u ON u.id = a.user_id
             LEFT JOIN profiles p ON p.user_id = a.user_id
             LEFT JOIN regions r ON r.id = a.region_id
             $whereClause
             ORDER BY a.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([...$params, $perPage, $offset]);

        return [
            'data'         => $stmt->fetchAll(),
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'total_pages'  => (int)ceil($total / $perPage),
        ];
    }
}
