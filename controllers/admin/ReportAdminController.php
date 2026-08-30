<?php

class ReportAdminController extends Controller {
    private Report $reportModel;
    private ReportPhoto $photoModel;
    private ReportStatusHistory $historyModel;
    private Category $categoryModel;
    private Region $regionModel;
    private Notification $notifModel;

    public function __construct() {
        parent::__construct();
        $this->reportModel = new Report();
        $this->photoModel = new ReportPhoto();
        $this->historyModel = new ReportStatusHistory();
        $this->categoryModel = new Category();
        $this->regionModel = new Region();
        $this->notifModel = new Notification();
    }

    public function index(): void {
        $this->requireRole(ROLE_ADMIN);

        $page = currentPage();
        $status = (string)$this->query('status', '');
        $regionId = (int)$this->query('region_id', 0);
        $categoryId = (int)$this->query('category_id', 0);
        $search = trim((string)$this->query('q', ''));

        $filters = [
            'status'      => $status,
            'region_id'   => $regionId,
            'category_id' => $categoryId,
            'search'      => $search,
        ];

        $reportsData = $this->reportModel->listAdmin($filters, $page, ADMIN_ITEMS_PER_PAGE);
        $categories = $this->categoryModel->getActive();
        $regions = $this->regionModel->getAll();

        $this->view('admin.reports.index', [
            'pageTitle'   => 'Kelola Laporan Masyarakat - ' . APP_NAME,
            'adminTitle'  => 'Manajemen Laporan Masyarakat',
            'reports'     => $reportsData['data'],
            'pagination'  => $reportsData,
            'categories'  => $categories,
            'regions'     => $regions,
            'filters'     => $filters,
        ], 'admin');
    }

    public function show(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $id = (int)$id;

        $report = $this->reportModel->queryOne(
            "SELECT r.*, c.name AS category_name, reg.name AS region_name,
                    p.full_name AS reporter_name, p.username AS reporter_username,
                    p.phone AS reporter_phone, u.email AS reporter_email
             FROM reports r
             LEFT JOIN categories c ON c.id = r.category_id
             LEFT JOIN regions reg ON reg.id = r.region_id
             LEFT JOIN users u ON u.id = r.user_id
             LEFT JOIN profiles p ON p.user_id = r.user_id
             WHERE r.id = ? AND r.deleted_at IS NULL",
            [$id]
        );

        if (!$report) {
            $this->abort(404, 'Laporan tidak ditemukan.');
        }

        $photos = $this->photoModel->getByReport($id);
        $statusHistory = $this->historyModel->getByReport($id);

        $this->view('admin.reports.show', [
            'pageTitle'     => 'Detail Laporan #' . $id . ' - ' . APP_NAME,
            'adminTitle'    => 'Verifikasi & Status Laporan #' . $id,
            'report'        => $report,
            'photos'        => $photos,
            'statusHistory' => $statusHistory,
        ], 'admin');
    }

    public function updateStatus(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $report = $this->reportModel->find($id);
        if (!$report) {
            $this->abort(404, 'Laporan tidak ditemukan.');
        }

        $newStatus = (string)$this->input('status', '');
        $notes = trim((string)$this->input('notes', ''));
        $rejectionReason = trim((string)$this->input('rejection_reason', ''));

        $allowedStatuses = array_keys(REPORT_STATUS_LABELS);
        if (!in_array($newStatus, $allowedStatuses, true)) {
            setFlash('error', 'Status laporan tidak valid.');
            $this->redirect('admin/reports/' . $id);
        }

        if ($newStatus === STATUS_REJECTED && empty($rejectionReason)) {
            setFlash('error', 'Wajib memberikan alasan penolakan untuk laporan yang ditolak.');
            $this->redirect('admin/reports/' . $id);
        }

        $updateData = [
            'status' => $newStatus,
        ];
        if ($newStatus === STATUS_REJECTED) {
            $updateData['rejection_reason'] = $rejectionReason;
        }

        $this->reportModel->update($id, $updateData);

        $historyNotes = ($newStatus === STATUS_REJECTED) ? $rejectionReason : $notes;
        $this->historyModel->addEntry($id, $newStatus, $historyNotes, (int)currentUserId());

        $this->audit()->logChangeReportStatus($id, $report['status'], $newStatus);

        $reportOwnerId = (int)($report['user_id'] ?? 0);
        if ($reportOwnerId > 0 && $newStatus !== $report['status']) {
            $statusLabelText = statusLabel($newStatus);
            $notifTitle = 'Status laporan diperbarui: ' . $statusLabelText;
            $notifMsg   = 'Laporan "' . mb_substr($report['title'], 0, 80) . '" kini berstatus: ' . $statusLabelText . '.';
            $this->notifModel->createFor($reportOwnerId, 'report_status', $notifTitle, $notifMsg, url('reports/status'));
        }

        setFlash('success', 'Status laporan berhasil diperbarui menjadi: ' . statusLabel($newStatus));
        $this->redirect('admin/reports/' . $id);
    }

    public function delete(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $report = $this->reportModel->find($id);
        if (!$report) {
            $this->abort(404, 'Laporan tidak ditemukan.');
        }

        $this->reportModel->delete($id);
        $this->audit()->logDeleteReport($id, $report['title']);

        setFlash('success', 'Laporan berhasil dihapus (soft delete).');
        $this->redirect('admin/reports');
    }
}
