<?php

class Category extends Model {
    protected string $table = 'categories';
    public function getActive(): array {
        return $this->query('SELECT * FROM categories WHERE is_active = 1 ORDER BY `order` ASC, name ASC', []);
    }
    public function findBySlug(string $slug): ?array { return $this->findBy('slug', $slug); }
}
