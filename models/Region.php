<?php
class Region extends Model {
    protected string $table = 'regions';
    public function getParents(): array {
        return $this->query('SELECT * FROM regions WHERE parent_id IS NULL AND is_active = 1 ORDER BY `order` ASC, name ASC', []);
    }
    public function getChildren(int $parentId): array {
        return $this->query('SELECT * FROM regions WHERE parent_id = ? AND is_active = 1 ORDER BY `order` ASC, name ASC', [$parentId]);
    }
    public function getAll(): array {
        return $this->query('SELECT r.*, p.name AS parent_name FROM regions r LEFT JOIN regions p ON p.id = r.parent_id WHERE r.is_active = 1 ORDER BY r.parent_id ASC, r.order ASC, r.name ASC', []);
    }
    public function findBySlug(string $slug): ?array { return $this->findBy('slug', $slug); }
    public function listAdmin(int $page, int $perPage): array {
        return $this->paginate($page, $perPage, '', [], 'parent_id ASC, `order` ASC, name ASC');
    }
}
