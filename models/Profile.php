<?php
class Profile extends Model {
    protected string $table = 'profiles';

    public function findByUserId(int $userId): ?array {
        return $this->findBy('user_id', $userId);
    }

    public function upsert(int $userId, array $data): void {
        $existing = $this->findByUserId($userId);
        if ($existing) {
            $this->update($existing['id'], $data);
        } else {
            $data['user_id'] = $userId;
            $this->create($data);
        }
    }

    public function isComplete(int $userId): bool {
        $profile = $this->findByUserId($userId);
        if (!$profile) return false;
        return (bool)$profile['is_complete'];
    }

    public function checkCompleteness(array $profile): bool {
        $required = ['full_name', 'phone', 'address', 'region_id', 'birth_date', 'gender'];
        foreach ($required as $field) {
            if (empty($profile[$field])) return false;
        }
        return true;
    }

    public function markComplete(int $userId, bool $complete = true): void {
        $profile = $this->findByUserId($userId);
        if ($profile) {
            $this->update($profile['id'], ['is_complete' => $complete ? 1 : 0]);
        }
    }
}
