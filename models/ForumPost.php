<?php
class ForumPost extends Model {
    protected string $table = 'forum_posts';
    protected bool $useSoftDelete = true;

    public function listPublic(array $filters = [], int $page = 1, int $perPage = 15): array {
        $where = ['fp.deleted_at IS NULL'];
        $params = [];
        if (!empty($filters['category_id'])) { $where[] = 'fp.category_id = ?'; $params[] = (int)$filters['category_id']; }
        if (!empty($filters['search'])) { $where[] = '(fp.title LIKE ? OR fp.content LIKE ?)'; $params[] = '%' . $filters['search'] . '%'; $params[] = '%' . $filters['search'] . '%'; }
        $whereClause = 'WHERE ' . implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM forum_posts fp $whereClause");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();
        $stmt = $this->db->prepare(
            "SELECT fp.id, fp.title, fp.content, fp.view_count, fp.created_at,
                    p.username, p.full_name, p.avatar_path,
                    c.name AS category_name, c.color AS category_color,
                    (SELECT COUNT(*) FROM forum_comments fc WHERE fc.post_id = fp.id AND fc.deleted_at IS NULL) AS comment_count
             FROM forum_posts fp LEFT JOIN users u ON u.id = fp.user_id LEFT JOIN profiles p ON p.user_id = fp.user_id LEFT JOIN categories c ON c.id = fp.category_id
             $whereClause ORDER BY fp.created_at DESC LIMIT ? OFFSET ?"
        );
        $stmt->execute([...$params, $perPage, $offset]);
        return ['data' => $stmt->fetchAll(), 'total' => $total, 'per_page' => $perPage, 'current_page' => $page, 'total_pages' => (int)ceil($total / $perPage)];
    }

    public function findWithDetails(int $id): ?array {
        $post = $this->queryOne(
            "SELECT fp.*, p.username, p.full_name, p.avatar_path, u.id AS author_id, c.name AS category_name
             FROM forum_posts fp LEFT JOIN users u ON u.id = fp.user_id LEFT JOIN profiles p ON p.user_id = fp.user_id LEFT JOIN categories c ON c.id = fp.category_id
             WHERE fp.id = ? AND fp.deleted_at IS NULL",
            [$id]
        );
        if ($post) { $this->execute('UPDATE forum_posts SET view_count = view_count + 1 WHERE id = ?', [$id]); }
        return $post;
    }

    public function isOwnedBy(int $postId, int $userId): bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM forum_posts WHERE id = ? AND user_id = ? AND deleted_at IS NULL');
        $stmt->execute([$postId, $userId]);
        return (int)$stmt->fetchColumn() > 0;
    }
}
