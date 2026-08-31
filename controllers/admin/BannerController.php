<?php

class BannerAdminController extends Controller {
    private Banner $bannerModel;
    private FileUpload $fileUploader;

    public function __construct() {
        parent::__construct();
        $this->bannerModel = new Banner();
        $this->fileUploader = new FileUpload();
    }

    public function index(): void {
        $this->requireRole(ROLE_ADMIN);

        $banners = $this->bannerModel->all('order', 'ASC');

        $this->view('admin.banners.index', [
            'pageTitle'  => 'Manajemen Banner - ' . APP_NAME,
            'adminTitle' => 'Banner & Slider Beranda',
            'banners'    => $banners,
        ], 'admin');
    }

    public function create(): void {
        $this->requireRole(ROLE_ADMIN);

        $this->view('admin.banners.create', [
            'pageTitle'  => 'Tambah Banner Baru - ' . APP_NAME,
            'adminTitle' => 'Tambah Banner Baru',
        ], 'admin');
    }

    public function store(): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();

        $input = $this->allInput();

        $validator = Validator::make($input);
        $validator->required('title', 'Judul Banner');

        if (empty($_FILES['image']['name'])) {
            setFlash('error', 'Wajib mengunggah gambar banner.');
            $this->redirect('admin/banners/create');
        }

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->redirect('admin/banners/create');
        }

        $uploaded = $this->fileUploader->uploadImage($_FILES['image'], 'banners');
        if (!$uploaded) {
            setFlash('error', implode(' ', $this->fileUploader->getErrors()));
            $this->redirect('admin/banners/create');
        }

        $order = !empty($input['order']) ? (int)$input['order'] : 0;
        $isActive = isset($input['is_active']) ? 1 : 0;

        $bannerId = $this->bannerModel->create([
            'title'      => trim($input['title']),
            'subtitle'   => !empty($input['subtitle']) ? trim($input['subtitle']) : null,
            'image_path' => $uploaded,
            'link_url'   => !empty($input['link_url']) ? trim($input['link_url']) : null,
            'is_active'  => $isActive,
            'order'      => $order,
        ]);

        $this->audit()->logBannerChange('create', $bannerId, trim($input['title']));

        setFlash('success', 'Banner berhasil ditambahkan.');
        $this->redirect('admin/banners');
    }

    public function edit(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $id = (int)$id;

        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            $this->abort(404, 'Banner tidak ditemukan.');
        }

        $this->view('admin.banners.edit', [
            'pageTitle'  => 'Edit Banner - ' . APP_NAME,
            'adminTitle' => 'Edit Banner',
            'banner'     => $banner,
        ], 'admin');
    }

    public function update(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            $this->abort(404, 'Banner tidak ditemukan.');
        }

        $input = $this->allInput();
        $validator = Validator::make($input);
        $validator->required('title', 'Judul Banner');

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->redirect('admin/banners/' . $id . '/edit');
        }

        $imagePath = $banner['image_path'];
        if (!empty($_FILES['image']['name'])) {
            $uploaded = $this->fileUploader->uploadImage($_FILES['image'], 'banners');
            if ($uploaded) {
                $this->fileUploader->deleteFile($banner['image_path']);
                $imagePath = $uploaded;
            } else {
                setFlash('error', implode(' ', $this->fileUploader->getErrors()));
                $this->redirect('admin/banners/' . $id . '/edit');
            }
        }

        $order = !empty($input['order']) ? (int)$input['order'] : 0;
        $isActive = isset($input['is_active']) ? 1 : 0;

        $this->bannerModel->update($id, [
            'title'      => trim($input['title']),
            'subtitle'   => !empty($input['subtitle']) ? trim($input['subtitle']) : null,
            'image_path' => $imagePath,
            'link_url'   => !empty($input['link_url']) ? trim($input['link_url']) : null,
            'is_active'  => $isActive,
            'order'      => $order,
        ]);

        $this->audit()->logBannerChange('update', $id, trim($input['title']));

        setFlash('success', 'Banner berhasil diperbarui.');
        $this->redirect('admin/banners');
    }

    public function delete(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            $this->abort(404, 'Banner tidak ditemukan.');
        }

        try {
            $this->fileUploader->deleteFile($banner['image_path']);
            $this->bannerModel->delete($id);
            $this->audit()->logBannerChange('delete', $id, $banner['title']);
            setFlash('success', 'Banner berhasil dihapus.');
        } catch (\Throwable $e) {
            error_log('Delete banner failed: ' . $e->getMessage());
            setFlash('error', 'Gagal menghapus banner.');
        }

        $this->redirect('admin/banners');
    }

    public function toggle(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $this->bannerModel->toggle($id);
        $this->audit()->logBannerChange('toggle_status', $id, 'Banner #' . $id);

        setFlash('success', 'Status aktifasi banner berhasil diubah.');
        $this->redirect('admin/banners');
    }
}
