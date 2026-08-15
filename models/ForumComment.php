<?php
class ForumComment extends Model {
    protected string $table = 'forum_comments';
    protected bool $useSoftDelete = true;
    public function getByPost(int $postId): array {
        return $this->query("SELECT fc.*, p.username, p.full_name, p.avatar_path, u.id AS author_id FROM forum_comments fc LEFT JOIN users u ON u.id = fc.user_id LEFT JOIN profiles p ON p.user_id = fc.user_id WHERE fc.post_id = ? AND fc.deleted_at IS NULL ORDER BY fc.created_at ASC", [$postId]);
    }
    public function isOwnedBy(int $commentId, int $userId): bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM forum_comments WHERE id = ? AND user_id = ? AND deleted_at IS NULL');
        $stmt->execute([$commentId, $userId]);
        return (int)$stmt->fetchColumn() > 0;
    }
}
