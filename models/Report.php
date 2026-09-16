<?php
class Report extends Model {
    protected string $table = 'reports';
    protected bool $useSoftDelete = true;

    public function listPublic(array $filters = [], int $page = 1, int $perPage = 12): array {
        $where = ['r.deleted_at IS NULL', 'r.is_public = 1'];
        $params = [];

        if (!empty($filters['region_id'])) {
            $where[] = 'r.region_id = ?';
            $params[] = (int)$filters['region_id'];
        }
        if (!empty($filters['category_id'])) {
            $where[] = 'r.category_id = ?';
            $params[] = (int)$filters['category_id'];
        }
        if (!empty($filters['status'])) {
            $where[] = 'r.status = ?';
            $params[] = $filters['status'];
        }
        if (!empty($filters['search'])) {
            $where[] = '(r.title LIKE ? OR r.description LIKE ?)';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['custom_category'])) {
            $where[] = '(r.custom_category LIKE ? OR c.name LIKE ?)';
            $params[] = '%' . $filters['custom_category'] . '%';
            $params[] = '%' . $filters['custom_category'] . '%';
        }

        $whereClause = 'WHERE ' . implode(' AND ', $where);

        $countStmt = $this->db->prepare(
            "SELECT COUNT(*) FROM reports r
             LEFT JOIN categories c ON c.id = r.category_id
             $whereClause"
        );
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare(
            "SELECT r.id, r.title, r.description, r.status, r.created_at,
                    COALESCE(NULLIF(r.custom_category,''), c.name) AS category_name, c.color AS category_color, c.icon AS category_icon,
                    reg.name AS region_name,
                    (SELECT rp.file_path FROM report_photos rp WHERE rp.report_id = r.id AND rp.is_primary = 1 LIMIT 1) AS primary_photo,
                    (SELECT rp2.file_path FROM report_photos rp2 WHERE rp2.report_id = r.id ORDER BY rp2.order ASC LIMIT 1) AS first_photo
             FROM reports r
             LEFT JOIN categories c ON c.id = r.category_id
             LEFT JOIN regions reg ON reg.id = r.region_id
             $whereClause
             ORDER BY r.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([...$params, $perPage, $offset]);

        return [
            'data'         => $stmt->fetchAll(),
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'total_pages'  => (int)ceil($total / $perPage),
            'has_prev'     => $page > 1,
            'has_next'     => $page < ceil($total / $perPage),
            'prev_page'    => max(1, $page - 1),
            'next_page'    => min(ceil($total / $perPage), $page + 1),
        ];
    }

    public function findWithDetails(int $id): ?array {
        return $this->queryOne(
            "SELECT r.*,
                    COALESCE(NULLIF(r.custom_category,''), c.name) AS category_name, c.color AS category_color, c.icon AS category_icon,
                    reg.name AS region_name, reg.parent_id AS region_parent_id,
                    p.username AS reporter_username, p.full_name AS reporter_name
             FROM reports r
             LEFT JOIN categories c ON c.id = r.category_id
             LEFT JOIN regions reg ON reg.id = r.region_id
             LEFT JOIN profiles p ON p.user_id = r.user_id
             WHERE r.id = ? AND r.deleted_at IS NULL",
            [$id]
        );
    }

    public function getByUser(int $userId, int $page = 1, int $perPage = 10): array {
        $offset = ($page - 1) * $perPage;
        $countStmt = $this->db->prepare(
            'SELECT COUNT(*) FROM reports WHERE user_id = ? AND deleted_at IS NULL'
        );
        $countStmt->execute([$userId]);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT r.id, r.title, r.status, r.created_at, r.rejection_reason,
                    COALESCE(NULLIF(r.custom_category,''), c.name) AS category_name, c.color AS category_color,
                    reg.name AS region_name,
                    (SELECT rp.file_path FROM report_photos rp WHERE rp.report_id = r.id ORDER BY rp.order ASC LIMIT 1) AS first_photo
             FROM reports r
             LEFT JOIN categories c ON c.id = r.category_id
             LEFT JOIN regions reg ON reg.id = r.region_id
             WHERE r.user_id = ? AND r.deleted_at IS NULL
             ORDER BY r.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([$userId, $perPage, $offset]);

        return [
            'data'         => $stmt->fetchAll(),
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'total_pages'  => (int)ceil($total / $perPage),
        ];
    }

    public function getRecent(int $limit = 8): array {
        return $this->query(
            "SELECT r.id, r.title, r.status, r.created_at,
                    COALESCE(NULLIF(r.custom_category,''), c.name) AS category_name, c.color AS category_color,
                    reg.name AS region_name,
                    COALESCE(
                        (SELECT rp.file_path FROM report_photos rp WHERE rp.report_id = r.id AND rp.is_primary = 1 LIMIT 1),
                        (SELECT rp2.file_path FROM report_photos rp2 WHERE rp2.report_id = r.id ORDER BY rp2.order ASC LIMIT 1)
                    ) AS primary_photo,
                    (SELECT rp3.file_path FROM report_photos rp3 WHERE rp3.report_id = r.id ORDER BY rp3.order ASC LIMIT 1) AS first_photo,
                    COALESCE(
                        (SELECT rp4.file_path FROM report_photos rp4 WHERE rp4.report_id = r.id AND rp4.is_primary = 1 LIMIT 1),
                        (SELECT rp5.file_path FROM report_photos rp5 WHERE rp5.report_id = r.id ORDER BY rp5.order ASC LIMIT 1)
                    ) AS image_path
             FROM reports r
             LEFT JOIN categories c ON c.id = r.category_id
             LEFT JOIN regions reg ON reg.id = r.region_id
             WHERE r.deleted_at IS NULL AND r.is_public = 1
             ORDER BY r.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }

    public function getForMap(): array {
        return $this->query(
            "SELECT r.id, r.title, r.status, r.latitude, r.longitude,
                    r.location_name,
                    COALESCE(NULLIF(r.custom_category,''), c.name) AS category_name, c.color AS category_color,
                    reg.name AS region_name
             FROM reports r
             LEFT JOIN categories c ON c.id = r.category_id
             LEFT JOIN regions reg ON reg.id = r.region_id
             WHERE r.deleted_at IS NULL AND r.is_public = 1
             AND r.latitude IS NOT NULL AND r.longitude IS NOT NULL",
            []
        );
    }

    public function isOwnedBy(int $reportId, int $userId): bool {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM reports WHERE id = ? AND user_id = ? AND deleted_at IS NULL'
        );
        $stmt->execute([$reportId, $userId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function getStats(): array {
        $stmt = $this->db->query(
            "SELECT
                COUNT(*) AS total,
                SUM(status = 'pending') AS pending,
                SUM(status = 'verified') AS verified,
                SUM(status = 'rejected') AS rejected,
                SUM(status = 'planning') AS planning,
                SUM(status = 'scheduled') AS scheduled,
                SUM(status = 'in_progress') AS in_progress,
                SUM(status = 'completed') AS completed
             FROM reports WHERE deleted_at IS NULL"
        );
        return $stmt->fetch();
    }

    public function listAdmin(array $filters = [], int $page = 1, int $perPage = 20): array {
        $where = ['r.deleted_at IS NULL'];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = 'r.status = ?';
            $params[] = $filters['status'];
        }
        if (!empty($filters['region_id'])) {
            $where[] = 'r.region_id = ?';
            $params[] = (int)$filters['region_id'];
        }
        if (!empty($filters['category_id'])) {
            $where[] = 'r.category_id = ?';
            $params[] = (int)$filters['category_id'];
        }
        if (!empty($filters['search'])) {
            $where[] = '(r.title LIKE ? OR p.full_name LIKE ?)';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        $whereClause = 'WHERE ' . implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $countStmt = $this->db->prepare(
            "SELECT COUNT(*) FROM reports r
             LEFT JOIN profiles p ON p.user_id = r.user_id
             $whereClause"
        );
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT r.id, r.title, r.status, r.created_at,
                    COALESCE(NULLIF(r.custom_category,''), c.name) AS category_name,
                    reg.name AS region_name,
                    p.full_name AS reporter_name,
                    u.email AS reporter_email,
                    (SELECT rp.file_path FROM report_photos rp WHERE rp.report_id = r.id ORDER BY rp.order ASC LIMIT 1) AS first_photo
             FROM reports r
             LEFT JOIN categories c ON c.id = r.category_id
             LEFT JOIN regions reg ON reg.id = r.region_id
             LEFT JOIN users u ON u.id = r.user_id
             LEFT JOIN profiles p ON p.user_id = r.user_id
             $whereClause
             ORDER BY r.created_at DESC
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

    

    public function countActiveByUser(int $userId): int {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM reports
             WHERE user_id = ? AND deleted_at IS NULL AND status != 'completed'"
        );
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }
}
