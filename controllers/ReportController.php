<?php

class ReportController extends Controller {
    private Report $reportModel;
    private ReportPhoto $photoModel;
    private Category $categoryModel;
    private Region $regionModel;
    private ReportStatusHistory $historyModel;
    private RateLimit $rateLimiter;
    private FileUpload $fileUploader;
    private Notification $notifModel;

    public function __construct() {
        parent::__construct();
        $this->reportModel = new Report();
        $this->photoModel = new ReportPhoto();
        $this->categoryModel = new Category();
        $this->regionModel = new Region();
        $this->historyModel = new ReportStatusHistory();
        $this->rateLimiter = new RateLimit();
        $this->fileUploader = new FileUpload();
        $this->notifModel = new Notification();
    }

    public function index(): void {
        $page = currentPage();
        $regionId = (int)$this->query('region_id', 0);
        $categoryId = (int)$this->query('category', 0);
        $status = (string)$this->query('status', '');
        $search = trim((string)$this->query('q', ''));
        $customCategory = trim((string)$this->query('custom_category', ''));

        $filters = [
            'region_id'       => $regionId,
            'category_id'     => $categoryId,
            'status'          => $status,
            'search'          => $search,
            'custom_category' => $customCategory,
        ];

        $perPage = (int)setting('reports_per_page', (string)ITEMS_PER_PAGE);
        if ($perPage < 1) $perPage = 12;

        $reportsData = $this->reportModel->listPublic($filters, $page, $perPage);
        $categories = $this->categoryModel->getActive();
        $regions = $this->regionModel->getAll();

        $this->view('reports.index', [
            'pageTitle'   => 'Laporan Masyarakat - ' . setting('site_name', APP_NAME),
            'reports'     => $reportsData['data'],
            'pagination'  => $reportsData,
            'categories'  => $categories,
            'regions'     => $regions,
            'filters'     => $filters,
        ]);
    }

    public function show(string|int $id): void {
        $id = (int)$id;
        $report = $this->reportModel->findWithDetails($id);

        if (!$report) {
            $this->abort(404, 'Laporan tidak ditemukan.');
        }

        $photos = $this->photoModel->getByReport($id);

        $statusHistory = $this->historyModel->getByReport($id);

        $this->view('reports.show', [
            'pageTitle'     => e($report['title']) . ' - ' . APP_NAME,
            'report'        => $report,
            'photos'        => $photos,
            'statusHistory' => $statusHistory,
        ]);
    }

    public function create(): void {
        $this->requireAuth();

        $categories = $this->categoryModel->getActive();
        $regions = $this->regionModel->getAll();

        $this->view('reports.create', [
            'pageTitle'  => 'Buat Laporan Lingkungan - ' . APP_NAME,
            'categories' => $categories,
            'regions'    => $regions,
        ]);
    }

    public function store(): void {
        $this->requireAuth();
        $this->validateCsrf();

        $userId = (int)currentUserId();

        if (!$this->rateLimiter->checkReport($userId)) {
            $this->secLog()->logRateLimitExceeded('create_report', $userId);
            setFlash('error', 'Batas pengiriman laporan tercapai (maksimal 10 laporan per jam). Silakan coba lagi nanti.');
            $this->redirect('reports/create');
        }

        $input = $this->allInput();

        $validator = Validator::make($input);
        $validator->required('title', 'Judul Laporan')
                  ->minLength('title', 5, 'Judul Laporan')
                  ->maxLength('title', 200, 'Judul Laporan')
                  ->required('category_id', 'Kategori')
                  ->positiveInt('category_id', 'Kategori')
                  ->required('region_id', 'Wilayah / Daerah')
                  ->positiveInt('region_id', 'Wilayah / Daerah')
                  ->required('location_name', 'Nama Patokan Lokasi')
                  ->required('address', 'Alamat Lengkap Lokasi')
                  ->required('description', 'Deskripsi Permasalahan')
                  ->minLength('description', 20, 'Deskripsi Permasalahan');

        $lat = !empty($input['latitude']) ? (float)$input['latitude'] : null;
        $lng = !empty($input['longitude']) ? (float)$input['longitude'] : null;

        $categoryId = (int)($input['category_id'] ?? 0);
        $customCategory = null;
        $othersCategory = $this->categoryModel->findBySlug('lainnya');
        $othersCategoryId = $othersCategory ? (int)$othersCategory['id'] : 0;
        if ($othersCategoryId && $categoryId === $othersCategoryId) {
            $customCategory = trim((string)($input['custom_category'] ?? ''));
            if ($customCategory === '') {
                setFlash('error', 'Silakan tulis jenis kategori lainnya.');
                $this->view('reports.create', [
                    'pageTitle'  => 'Buat Laporan Lingkungan',
                    'categories' => $this->categoryModel->getActive(),
                    'regions'    => $this->regionModel->getAll(),
                    'values'     => $input,
                    'errors'     => ['custom_category' => 'Wajib diisi saat memilih kategori Lainnya.'],
                ]);
                return;
            }
        }

        $hasPhotos = !empty($_FILES['photos']['name'][0]);
        if (!$hasPhotos) {
            setFlash('error', 'Wajib mengunggah minimal 1 foto bukti kondisi lingkungan.');
            $this->view('reports.create', [
                'pageTitle'  => 'Buat Laporan Lingkungan',
                'categories' => $this->categoryModel->getActive(),
                'regions'    => $this->regionModel->getAll(),
                'values'     => $input,
                'errors'     => ['photos' => 'Minimal 1 foto bukti wajib diunggah.'],
            ]);
            return;
        }

        $maxPhotos = (int)setting('max_report_photos', (string)MAX_REPORT_PHOTOS);
        if ($maxPhotos < 1) $maxPhotos = 10;
        $uploadedCount = is_array($_FILES['photos']['name']) ? count($_FILES['photos']['name']) : 1;
        if ($uploadedCount > $maxPhotos) {
            setFlash('error', 'Maksimal ' . $maxPhotos . ' foto yang diperbolehkan. Foto ke-' . ($maxPhotos + 1) . ' dan seterusnya ditolak.');
            $this->view('reports.create', [
                'pageTitle'  => 'Buat Laporan Lingkungan',
                'categories' => $this->categoryModel->getActive(),
                'regions'    => $this->regionModel->getAll(),
                'values'     => $input,
                'errors'     => ['photos' => 'Maksimal ' . $maxPhotos . ' foto.'],
            ]);
            return;
        }

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->view('reports.create', [
                'pageTitle'  => 'Buat Laporan Lingkungan',
                'categories' => $this->categoryModel->getActive(),
                'regions'    => $this->regionModel->getAll(),
                'values'     => $input,
                'errors'     => $validator->getErrors(),
            ]);
            return;
        }

        $uploadResult = $this->fileUploader->uploadMultipleImages($_FILES['photos'], 'reports');

        if (empty($uploadResult['success'])) {
            $errorMsg = !empty($uploadResult['errors']) ? implode(' ', $uploadResult['errors']) : 'Gagal mengunggah foto bukti.';
            setFlash('error', $errorMsg);
            $this->view('reports.create', [
                'pageTitle'  => 'Buat Laporan Lingkungan',
                'categories' => $this->categoryModel->getActive(),
                'regions'    => $this->regionModel->getAll(),
                'values'     => $input,
            ]);
            return;
        }

        $db = Database::getConnection();
        try {
            $db->beginTransaction();

            $reportId = $this->reportModel->create([
                'user_id'       => $userId,
                'title'         => trim($input['title']),
                'category_id'   => $categoryId,
                'custom_category' => $customCategory,
                'description'   => trim($input['description']),
                'region_id'     => (int)$input['region_id'],
                'location_name' => trim($input['location_name']),
                'address'       => trim($input['address']),
                'latitude'      => $lat,
                'longitude'     => $lng,
                'status'        => STATUS_PENDING,
                'is_public'     => 1,
            ]);

            $this->photoModel->addPhotos($reportId, $uploadResult['success']);

            $this->historyModel->addEntry($reportId, STATUS_PENDING, 'Laporan dibuat oleh pelapor.', $userId);

            $db->commit();

            $this->audit()->logCreateReport($reportId, trim($input['title']));

            setFlash('success', 'Laporan berhasil dikirim dan sedang menunggu verifikasi admin.');
            $this->redirect('reports/status');

        } catch (Exception $e) {
            $db->rollBack();

            foreach ($uploadResult['success'] as $filePath) {
                $this->fileUploader->deleteFile($filePath);
            }
            logError('Store report failed: ' . $e->getMessage());
            setFlash('error', 'Terjadi kesalahan sistem saat menyimpan laporan.');
            $this->redirect('reports/create');
        }
    }

    public function status(): void {
        $this->requireAuth();

        $userId = (int)currentUserId();
        $page = currentPage();

        $this->notifModel->markAllRead($userId);

        $userReports = $this->reportModel->getByUser($userId, $page, 10);

        $this->view('reports.status', [
            'pageTitle'  => 'Status Laporan Saya - ' . APP_NAME,
            'reports'    => $userReports['data'],
            'pagination' => $userReports,
        ]);
    }

    public function delete(string|int $id): void {
        $this->requireAuth();
        $this->validateCsrf();

        $id = (int)$id;
        $userId = (int)currentUserId();

        if (!$this->reportModel->isOwnedBy($id, $userId) && !hasRole(ROLE_ADMIN)) {
            $this->secLog()->logIdorAttempt('report_delete', $id, $userId);
            setFlash('error', 'Anda tidak memiliki akses untuk menghapus laporan ini.');
            $this->redirect('reports/status');
        }

        $report = $this->reportModel->find($id);
        if (!$report) {
            $this->abort(404, 'Laporan tidak ditemukan.');
        }

        if (!in_array($report['status'], [STATUS_PENDING, STATUS_REJECTED], true) && !hasRole(ROLE_ADMIN)) {
            setFlash('error', 'Laporan yang sedang diproses atau sudah diverifikasi tidak dapat dihapus secara mandiri.');
            $this->redirect('reports/status');
        }

        $this->reportModel->delete($id);
        $this->audit()->logDeleteReport($id, $report['title']);

        setFlash('success', 'Laporan berhasil dihapus.');
        $this->redirect('reports/status');
    }
}
