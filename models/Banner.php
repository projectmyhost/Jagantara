<?php
class Banner extends Model {
    protected string $table = 'banners';
    public function getActive(): array {
        return $this->query('SELECT * FROM banners WHERE is_active = 1 ORDER BY `order` ASC, id ASC', []);
    }
    public function toggle(int $id): void {
        $banner = $this->find($id);
        if ($banner) { $this->update($id, ['is_active' => $banner['is_active'] ? 0 : 1]); }
    }
}
