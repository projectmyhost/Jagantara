<?php

class OrganizerAdminController extends Controller {
    private Organizer $organizerModel;
    private OrganizerApplication $appModel;
    private OrganizerMember $memberModel;
    private Region $regionModel;
    private User $userModel;
    private FileUpload $fileUploader;

    public function __construct() {
        parent::__construct();
        $this->organizerModel = new Organizer();
        $this->appModel = new OrganizerApplication();
        $this->memberModel = new OrganizerMember();
        $this->regionModel = new Region();
        $this->userModel = new User();
        $this->fileUploader = new FileUpload();
    }

    public function index(): void {
        $this->requireRole(ROLE_ADMIN);

        $organizers = $this->organizerModel->query(
            "SELECT o.*, r.name AS region_name,
                    (SELECT COUNT(*) FROM organizer_members om WHERE om.organizer_id = o.id AND om.is_active = 1) AS member_count,
                    (SELECT COUNT(*) FROM organizer_applications oa WHERE oa.organizer_id = o.id AND oa.status = 'pending') AS pending_apps
             FROM organizers o
             LEFT JOIN regions r ON r.id = o.region_id
             ORDER BY o.name ASC"
        );

        $this->view('admin.organizers.index', [
            'pageTitle'  => 'Manajemen Organizer - ' . APP_NAME,
            'adminTitle' => 'Organizer & Komunitas Lingkungan',
            'organizers' => $organizers,
        ], 'admin');
    }

    public function create(): void {
        $this->requireRole(ROLE_ADMIN);
        $regions = $this->regionModel->getAll();

        $this->view('admin.organizers.create', [
            'pageTitle'  => 'Tambah Organizer Baru - ' . APP_NAME,
            'adminTitle' => 'Tambah Organizer Baru',
            'regions'    => $regions,
        ], 'admin');
    }

    public function store(): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();

        $input = $this->allInput();

        $validator = Validator::make($input);
        $validator->required('name', 'Nama Organizer')
                  ->minLength('name', 3, 'Nama Organizer')
                  ->required('description', 'Deskripsi Organizer');

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->redirect('admin/organizers/create');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $input['name']), '-'));

        $logoPath = null;
        if (!empty($_FILES['logo']['name'])) {
            $logoPath = $this->fileUploader->uploadImage($_FILES['logo'], 'organizers');
        }

        $organizerId = $this->organizerModel->create([
            'name'          => trim($input['name']),
            'slug'          => $slug,
            'description'   => trim($input['description']),
            'region_id'     => !empty($input['region_id']) ? (int)$input['region_id'] : null,
            'logo_path'     => $logoPath,
            'contact_email' => !empty($input['contact_email']) ? trim($input['contact_email']) : null,
            'contact_phone' => !empty($input['contact_phone']) ? trim($input['contact_phone']) : null,
            'is_active'     => isset($input['is_active']) ? 1 : 0,
        ]);

        $this->audit()->log('create_organizer', 'organizer', $organizerId, ['name' => trim($input['name'])]);

        setFlash('success', 'Organizer berhasil ditambahkan.');
        $this->redirect('admin/organizers');
    }

    public function edit(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $id = (int)$id;

        $organizer = $this->organizerModel->find($id);
        if (!$organizer) {
            $this->abort(404, 'Organizer tidak ditemukan.');
        }

        $regions = $this->regionModel->getAll();

        $this->view('admin.organizers.edit', [
            'pageTitle'  => 'Edit Organizer - ' . APP_NAME,
            'adminTitle' => 'Edit Organizer: ' . e($organizer['name']),
            'organizer'  => $organizer,
            'regions'    => $regions,
        ], 'admin');
    }

    public function update(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $organizer = $this->organizerModel->find($id);
        if (!$organizer) {
            $this->abort(404, 'Organizer tidak ditemukan.');
        }

        $input = $this->allInput();
        $validator = Validator::make($input);
        $validator->required('name', 'Nama Organizer')->required('description', 'Deskripsi Organizer');

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->redirect('admin/organizers/' . $id . '/edit');
        }

        $logoPath = $organizer['logo_path'];
        if (!empty($_FILES['logo']['name'])) {
            $uploaded = $this->fileUploader->uploadImage($_FILES['logo'], 'organizers');
            if ($uploaded) {
                if ($logoPath) $this->fileUploader->deleteFile($logoPath);
                $logoPath = $uploaded;
            }
        }

        $this->organizerModel->update($id, [
            'name'          => trim($input['name']),
            'description'   => trim($input['description']),
            'region_id'     => !empty($input['region_id']) ? (int)$input['region_id'] : null,
            'logo_path'     => $logoPath,
            'contact_email' => !empty($input['contact_email']) ? trim($input['contact_email']) : null,
            'contact_phone' => !empty($input['contact_phone']) ? trim($input['contact_phone']) : null,
            'is_active'     => isset($input['is_active']) ? 1 : 0,
        ]);

        $this->audit()->log('update_organizer', 'organizer', $id, ['name' => trim($input['name'])]);

        setFlash('success', 'Data organizer berhasil diperbarui.');
        $this->redirect('admin/organizers');
    }

    public function delete(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $organizer = $this->organizerModel->find($id);
        if (!$organizer) {
            $this->abort(404, 'Organizer tidak ditemukan.');
        }

        try {
            $this->organizerModel->delete($id);
            $this->audit()->log('delete_organizer', 'organizer', $id, ['name' => $organizer['name']]);
            setFlash('success', 'Organizer berhasil dihapus.');
        } catch (\Throwable $e) {
            error_log('Delete organizer failed: ' . $e->getMessage());
            setFlash('error', 'Gagal menghapus organizer karena masih terdapat data terkait.');
        }

        $this->redirect('admin/organizers');
    }

    public function applications(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $id = (int)$id;

        $organizer = $this->organizerModel->find($id);
        if (!$organizer) {
            $this->abort(404, 'Organizer tidak ditemukan.');
        }

        $page = currentPage();
        $status = (string)$this->query('status', '');

        $appsData = $this->appModel->listAdmin($id, $page, ADMIN_ITEMS_PER_PAGE, $status);

        $this->view('admin.organizers.applications', [
            'pageTitle'  => 'Permohonan Anggota: ' . e($organizer['name']) . ' - ' . APP_NAME,
            'adminTitle' => 'Verifikasi Permohonan Anggota: ' . e($organizer['name']),
            'organizer'  => $organizer,
            'apps'       => $appsData['data'],
            'pagination' => $appsData,
            'status'     => $status,
        ], 'admin');
    }

    public function approveApplication(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $app = $this->appModel->find($id);
        if (!$app) {
            $this->abort(404, 'Permohonan tidak ditemukan.');
        }

        $db = Database::getConnection();
        try {
            $db->beginTransaction();

            $this->appModel->update($id, [
                'status'      => 'approved',
                'reviewed_by' => (int)currentUserId(),
                'reviewed_at' => date('Y-m-d H:i:s'),
            ]);

            $stmt = $db->prepare(
                'INSERT INTO organizer_members (user_id, organizer_id, role, joined_at, is_active)
                 VALUES (?, ?, ?, NOW(), 1)
                 ON DUPLICATE KEY UPDATE is_active = 1'
            );
            $stmt->execute([$app['user_id'], $app['organizer_id'], 'member']);

            $user = $this->userModel->find($app['user_id']);
            if ($user && $user['role'] === ROLE_USER) {
                $this->userModel->update($app['user_id'], ['role' => ROLE_ORGANIZER_MEMBER]);
            }

            $db->commit();

            $this->audit()->log('approve_organizer_application', 'organizer_application', $id, [
                'user_id'      => $app['user_id'],
                'organizer_id' => $app['organizer_id'],
            ]);

            setFlash('success', 'Permohonan anggota telah disetujui.');
        } catch (Exception $e) {
            $db->rollBack();
            logError('Approve application failed: ' . $e->getMessage());
            setFlash('error', 'Gagal memproses persetujuan permohonan.');
        }

        $this->redirect('admin/organizers/' . $app['organizer_id'] . '/applications');
    }

    public function rejectApplication(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $app = $this->appModel->find($id);
        if (!$app) {
            $this->abort(404, 'Permohonan tidak ditemukan.');
        }

        $reason = trim((string)$this->input('rejection_reason', ''));
        if (empty($reason)) {
            setFlash('error', 'Wajib mencantumkan alasan penolakan.');
            $this->redirect('admin/organizers/' . $app['organizer_id'] . '/applications');
        }

        $this->appModel->update($id, [
            'status'           => 'rejected',
            'rejection_reason' => $reason,
            'reviewed_by'      => (int)currentUserId(),
            'reviewed_at'      => date('Y-m-d H:i:s'),
        ]);

        $this->audit()->log('reject_organizer_application', 'organizer_application', $id, [
            'user_id' => $app['user_id'],
        ]);

        setFlash('success', 'Permohonan anggota telah ditolak.');
        $this->redirect('admin/organizers/' . $app['organizer_id'] . '/applications');
    }
}
