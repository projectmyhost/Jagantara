<?php

class CategoryAdminController extends Controller {
    private Category $categoryModel;

    public function __construct() {
        parent::__construct();
        $this->categoryModel = new Category();
    }

    public function index(): void {
        $this->requireRole(ROLE_ADMIN);

        $categories = $this->categoryModel->all('order', 'ASC');

        $this->view('admin.categories.index', [
            'pageTitle'  => 'Manajemen Kategori Lingkungan - ' . APP_NAME,
            'adminTitle' => 'Kategori Lingkungan',
            'categories' => $categories,
        ], 'admin');
    }

    public function create(): void {
        $this->requireRole(ROLE_ADMIN);

        $this->view('admin.categories.create', [
            'pageTitle'  => 'Tambah Kategori - ' . APP_NAME,
            'adminTitle' => 'Tambah Kategori Lingkungan',
        ], 'admin');
    }

    public function store(): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();

        $input = $this->allInput();
        $validator = Validator::make($input);
        $validator->required('name', 'Nama Kategori')
                  ->unique('name', 'categories', 'name', 'Nama Kategori');

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->redirect('admin/categories/create');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $input['name']), '-'));
        $order = !empty($input['order']) ? (int)$input['order'] : 0;
        $isActive = isset($input['is_active']) ? 1 : 0;

        $catId = $this->categoryModel->create([
            'name'        => trim($input['name']),
            'slug'        => $slug,
            'description' => trim((string)($input['description'] ?? '')),
            'color'       => trim((string)($input['color'] ?? '#1D4533')),
            'icon'        => trim((string)($input['icon'] ?? 'trash')),
            'is_active'   => $isActive,
            'order'       => $order,
        ]);

        $this->audit()->log('create_category', 'category', $catId, ['name' => trim($input['name'])]);

        setFlash('success', 'Kategori baru berhasil ditambahkan.');
        $this->redirect('admin/categories');
    }

    public function edit(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $id = (int)$id;

        $category = $this->categoryModel->find($id);
        if (!$category) {
            $this->abort(404, 'Kategori tidak ditemukan.');
        }

        $this->view('admin.categories.edit', [
            'pageTitle'  => 'Edit Kategori: ' . e($category['name']) . ' - ' . APP_NAME,
            'adminTitle' => 'Edit Kategori Lingkungan',
            'category'   => $category,
        ], 'admin');
    }

    public function update(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $category = $this->categoryModel->find($id);
        if (!$category) {
            $this->abort(404, 'Kategori tidak ditemukan.');
        }

        $input = $this->allInput();
        $validator = Validator::make($input);
        $validator->required('name', 'Nama Kategori')
                  ->unique('name', 'categories', 'name', 'Nama Kategori', $id);

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->redirect('admin/categories/' . $id . '/edit');
        }

        $order = !empty($input['order']) ? (int)$input['order'] : 0;
        $isActive = isset($input['is_active']) ? 1 : 0;

        $this->categoryModel->update($id, [
            'name'        => trim($input['name']),
            'description' => trim((string)($input['description'] ?? '')),
            'color'       => trim((string)($input['color'] ?? '#1D4533')),
            'icon'        => trim((string)($input['icon'] ?? 'trash')),
            'is_active'   => $isActive,
            'order'       => $order,
        ]);

        $this->audit()->log('update_category', 'category', $id, ['name' => trim($input['name'])]);

        setFlash('success', 'Kategori berhasil diperbarui.');
        $this->redirect('admin/categories');
    }

    public function delete(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $category = $this->categoryModel->find($id);
        if (!$category) {
            $this->abort(404, 'Kategori tidak ditemukan.');
        }

        $usedCount = (int)$this->categoryModel->queryOne(
            'SELECT COUNT(*) AS total FROM reports WHERE category_id = ?',
            [$id]
        )['total'];

        if ($usedCount > 0) {
            setFlash('error', "Kategori '{$category['name']}' tidak dapat dihapus karena masih terkait dengan {$usedCount} data laporan. Anda dapat menonaktifkannya melalui opsi Edit Kategori jika tidak ingin digunakan lagi.");
            $this->redirect('admin/categories');
        }

        try {
            $this->categoryModel->delete($id);
            $this->audit()->log('delete_category', 'category', $id, ['name' => $category['name']]);
            setFlash('success', 'Kategori berhasil dihapus.');
        } catch (\Throwable $e) {
            error_log('Delete category failed: ' . $e->getMessage());
            setFlash('error', 'Gagal menghapus kategori karena masih terdapat data lain yang terhubung.');
        }

        $this->redirect('admin/categories');
    }
}
