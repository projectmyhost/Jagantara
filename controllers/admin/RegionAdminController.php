<?php

class RegionAdminController extends Controller {
    private Region $regionModel;

    public function __construct() {
        parent::__construct();
        $this->regionModel = new Region();
    }

    public function index(): void {
        $this->requireRole(ROLE_ADMIN);

        $regions = $this->regionModel->getAll();

        $this->view('admin.regions.index', [
            'pageTitle'  => 'Manajemen Wilayah - ' . APP_NAME,
            'adminTitle' => 'Wilayah JABODETABEK & Daerah',
            'regions'    => $regions,
        ], 'admin');
    }

    public function create(): void {
        $this->requireRole(ROLE_ADMIN);
        $parents = $this->regionModel->getParents();

        $this->view('admin.regions.create', [
            'pageTitle'  => 'Tambah Wilayah Baru - ' . APP_NAME,
            'adminTitle' => 'Tambah Wilayah Baru',
            'parents'    => $parents,
        ], 'admin');
    }

    public function store(): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();

        $input = $this->allInput();
        $validator = Validator::make($input);
        $validator->required('name', 'Nama Wilayah');

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->redirect('admin/regions/create');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $input['name']), '-'));
        $parentId = !empty($input['parent_id']) ? (int)$input['parent_id'] : null;
        $order = !empty($input['order']) ? (int)$input['order'] : 0;
        $isActive = isset($input['is_active']) ? 1 : 0;

        $regId = $this->regionModel->create([
            'name'      => trim($input['name']),
            'slug'      => $slug,
            'parent_id' => $parentId,
            'is_active' => $isActive,
            'order'     => $order,
        ]);

        $this->audit()->log('create_region', 'region', $regId, ['name' => trim($input['name'])]);

        setFlash('success', 'Wilayah berhasil ditambahkan.');
        $this->redirect('admin/regions');
    }

    public function edit(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $id = (int)$id;

        $region = $this->regionModel->find($id);
        if (!$region) {
            $this->abort(404, 'Wilayah tidak ditemukan.');
        }

        $parents = $this->regionModel->getParents();

        $this->view('admin.regions.edit', [
            'pageTitle'  => 'Edit Wilayah: ' . e($region['name']) . ' - ' . APP_NAME,
            'adminTitle' => 'Edit Wilayah',
            'region'     => $region,
            'parents'    => $parents,
        ], 'admin');
    }

    public function update(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $region = $this->regionModel->find($id);
        if (!$region) {
            $this->abort(404, 'Wilayah tidak ditemukan.');
        }

        $input = $this->allInput();
        $validator = Validator::make($input);
        $validator->required('name', 'Nama Wilayah');

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->redirect('admin/regions/' . $id . '/edit');
        }

        $parentId = !empty($input['parent_id']) ? (int)$input['parent_id'] : null;
        $order = !empty($input['order']) ? (int)$input['order'] : 0;
        $isActive = isset($input['is_active']) ? 1 : 0;

        $this->regionModel->update($id, [
            'name'      => trim($input['name']),
            'parent_id' => $parentId,
            'is_active' => $isActive,
            'order'     => $order,
        ]);

        $this->audit()->log('update_region', 'region', $id, ['name' => trim($input['name'])]);

        setFlash('success', 'Wilayah berhasil diperbarui.');
        $this->redirect('admin/regions');
    }

    public function delete(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $region = $this->regionModel->find($id);
        if (!$region) {
            $this->abort(404, 'Wilayah tidak ditemukan.');
        }

        $reportCount = (int)$this->regionModel->queryOne(
            'SELECT COUNT(*) AS total FROM reports WHERE region_id = ?',
            [$id]
        )['total'];
        if ($reportCount > 0) {
            setFlash('error', "Wilayah '{$region['name']}' tidak dapat dihapus karena masih terkait dengan {$reportCount} data laporan. Anda dapat menonaktifkannya melalui opsi Edit jika tidak ingin digunakan lagi.");
            $this->redirect('admin/regions');
        }

        $activityCount = (int)$this->regionModel->queryOne(
            'SELECT COUNT(*) AS total FROM activities WHERE region_id = ?',
            [$id]
        )['total'];
        if ($activityCount > 0) {
            setFlash('error', "Wilayah '{$region['name']}' tidak dapat dihapus karena masih terkait dengan {$activityCount} kegiatan.");
            $this->redirect('admin/regions');
        }

        $childCount = (int)$this->regionModel->queryOne(
            'SELECT COUNT(*) AS total FROM regions WHERE parent_id = ?',
            [$id]
        )['total'];
        if ($childCount > 0) {
            setFlash('error', "Wilayah '{$region['name']}' tidak dapat dihapus karena memiliki sub-wilayah terdaftar.");
            $this->redirect('admin/regions');
        }

        try {
            $this->regionModel->delete($id);
            $this->audit()->log('delete_region', 'region', $id, ['name' => $region['name']]);
            setFlash('success', 'Wilayah berhasil dihapus.');
        } catch (\Throwable $e) {
            error_log('Delete region failed: ' . $e->getMessage());
            setFlash('error', 'Gagal menghapus wilayah karena masih terdapat data lain yang terhubung.');
        }

        $this->redirect('admin/regions');
    }
}
