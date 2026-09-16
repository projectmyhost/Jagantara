<?php
class User extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email): ?array {
        return $this->findBy('email', $email);
    }

    public function findByLogin(string $login): ?array {
        return $this->queryOne(
            'SELECT u.*, p.username, p.full_name FROM users u
             LEFT JOIN profiles p ON p.user_id = u.id
             WHERE u.email = ? OR p.username = ? OR p.full_name = ?
             LIMIT 1',
            [$login, $login, $login]
        );
    }

    public function findWithProfile(int $id): ?array {
        return $this->queryOne(
            'SELECT u.*, p.username, p.full_name, p.phone, p.address, p.region_id,
                    p.birth_date, p.gender, p.avatar_path, p.is_complete,
                    r.name AS region_name
             FROM users u
             LEFT JOIN profiles p ON p.user_id = u.id
             LEFT JOIN regions r ON r.id = p.region_id
             WHERE u.id = ?',
            [$id]
        );
    }

    public function createUser(string $email, string $password, string $role = 'user'): int {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        return $this->create([
            'email'         => $email,
            'password_hash' => $hash,
            'role'          => $role,
            'status'        => 'active',
        ]);
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public function isActive(array $user): bool {
        return $user['status'] === 'active';
    }

    public function listWithProfiles(int $page, int $perPage, string $search = '', string $role = ''): array {
        $where = [];
        $params = [];

        if ($search !== '') {
            $where[] = '(u.email LIKE ? OR p.full_name LIKE ? OR p.username LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if ($role !== '') {
            $where[] = 'u.role = ?';
            $params[] = $role;
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $countStmt = $this->db->prepare(
            "SELECT COUNT(*) FROM users u LEFT JOIN profiles p ON p.user_id = u.id $whereClause"
        );
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare(
            "SELECT u.*, p.username, p.full_name, p.avatar_path, r.name AS region_name
             FROM users u
             LEFT JOIN profiles p ON p.user_id = u.id
             LEFT JOIN regions r ON r.id = p.region_id
             $whereClause
             ORDER BY u.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([...$params, $perPage, $offset]);

        return [
            'data'        => $stmt->fetchAll(),
            'total'       => $total,
            'per_page'    => $perPage,
            'current_page'=> $page,
            'total_pages' => (int)ceil($total / $perPage),
        ];
    }

    public function getStats(): array {
        $stmt = $this->db->query(
            "SELECT
                COUNT(*) AS total,
                SUM(role = 'user') AS users,
                SUM(role = 'organizer_member') AS organizer_members,
                SUM(role = 'admin') AS admins,
                SUM(status = 'active') AS active,
                SUM(status = 'suspended') AS suspended
             FROM users"
        );
        return $stmt->fetch();
    }
}
