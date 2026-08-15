<?php

abstract class Model {
    protected PDO $db;
    protected string $table = '';
    protected string $primaryKey = 'id';
    protected bool $useSoftDelete = false;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function find(int $id): ?array {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?";
        if ($this->useSoftDelete) {
            $sql .= ' AND deleted_at IS NULL';
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array {
        return $this->find($id);
    }

    public function findBy(string $column, mixed $value): ?array {
        $sql = "SELECT * FROM `{$this->table}` WHERE `$column` = ?";
        if ($this->useSoftDelete) {
            $sql .= ' AND deleted_at IS NULL';
        }
        $sql .= ' LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetch() ?: null;
    }

    public function all(string $orderBy = '', string $direction = 'ASC'): array {
        $sql = "SELECT * FROM `{$this->table}`";
        if ($this->useSoftDelete) {
            $sql .= ' WHERE deleted_at IS NULL';
        }
        if ($orderBy) {
            $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
            $sql .= " ORDER BY `$orderBy` $direction";
        }
        return $this->db->query($sql)->fetchAll();
    }

    public function count(array $where = []): int {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        $conditions = [];
        $params = [];

        if ($this->useSoftDelete) {
            $conditions[] = 'deleted_at IS NULL';
        }

        foreach ($where as $column => $value) {
            $conditions[] = "`$column` = ?";
            $params[] = $value;
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function create(array $data): int {
        $columns = implode('`, `', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO `{$this->table}` (`$columns`) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        if (empty($data)) return false;
        $sets = implode(' = ?, ', array_map(fn($col) => "`$col`", array_keys($data))) . ' = ?';
        $sql = "UPDATE `{$this->table}` SET $sets WHERE `{$this->primaryKey}` = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([...array_values($data), $id]);
    }

    public function delete(int $id): bool {
        if ($this->useSoftDelete) {
            return $this->update($id, ['deleted_at' => date('Y-m-d H:i:s')]);
        }
        $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?");
        return $stmt->execute([$id]);
    }

    public function restore(int $id): bool {
        if (!$this->useSoftDelete) return false;
        $stmt = $this->db->prepare("UPDATE `{$this->table}` SET deleted_at = NULL WHERE `{$this->primaryKey}` = ?");
        return $stmt->execute([$id]);
    }

    public function paginate(int $page, int $perPage, string $where = '', array $params = [], string $orderBy = 'created_at DESC'): array {
        $offset = ($page - 1) * $perPage;
        $baseWhere = '';

        if ($this->useSoftDelete) {
            $baseWhere = 'deleted_at IS NULL';
        }

        $whereClause = '';
        if ($baseWhere && $where) {
            $whereClause = "WHERE $baseWhere AND ($where)";
        } elseif ($baseWhere) {
            $whereClause = "WHERE $baseWhere";
        } elseif ($where) {
            $whereClause = "WHERE $where";
        }

        $countSql = "SELECT COUNT(*) FROM `{$this->table}` $whereClause";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();

        $sql = "SELECT * FROM `{$this->table}` $whereClause ORDER BY $orderBy LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([...$params, $perPage, $offset]);
        $records = $stmt->fetchAll();

        return [
            'data'         => $records,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'total_pages'  => (int)ceil($total / $perPage),
            'has_prev'     => $page > 1,
            'has_next'     => $page < ceil($total / $perPage),
        ];
    }

    public function query(string $sql, array $params = []): array {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function queryOne(string $sql, array $params = []): ?array {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ?: null;
    }

    public function execute(string $sql, array $params = []): bool {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function lastInsertId(): int {
        return (int)$this->db->lastInsertId();
    }

    public function beginTransaction(): void {
        $this->db->beginTransaction();
    }

    public function commit(): void {
        $this->db->commit();
    }

    public function rollback(): void {
        $this->db->rollBack();
    }
}
