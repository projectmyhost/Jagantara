<?php

class DashboardController extends Controller {
    private Report $reportModel;
    private User $userModel;
    private Organizer $organizerModel;
    private Activity $activityModel;
    private AuditLog $auditLogModel;

    public function __construct() {
        parent::__construct();
        $this->reportModel = new Report();
        $this->userModel = new User();
        $this->organizerModel = new Organizer();
        $this->activityModel = new Activity();
    }

    public function index(): void {
        $this->requireRole(ROLE_ADMIN);

        $reportStats = $this->reportModel->getStats();
        $userStats = $this->userModel->getStats();
        $organizerCount = $this->organizerModel->count(['is_active' => 1]);
        $activityCount = $this->activityModel->count(['deleted_at' => null]);
        $pendingAppsCount = (new OrganizerApplication())->count(['status' => 'pending']);

        $pendingReports = $this->reportModel->query(
            "SELECT r.*, c.name AS category_name, reg.name AS region_name, p.full_name AS reporter_name
             FROM reports r
             LEFT JOIN categories c ON c.id = r.category_id
             LEFT JOIN regions reg ON reg.id = r.region_id
             LEFT JOIN profiles p ON p.user_id = r.user_id
             WHERE r.deleted_at IS NULL AND r.status = 'pending'
             ORDER BY r.created_at DESC LIMIT 5"
        );

        $db = Database::getConnection();
        $stmt = $db->query(
            "SELECT al.*, u.email FROM audit_logs al
             LEFT JOIN users u ON u.id = al.user_id
             ORDER BY al.created_at DESC LIMIT 6"
        );
        $recentAudit = $stmt ? $stmt->fetchAll() : [];

        $this->view('admin.dashboard.index', [
            'pageTitle'        => 'Dashboard Admin - ' . APP_NAME,
            'adminTitle'       => 'Dashboard Utama',
            'reportStats'      => $reportStats,
            'userStats'        => $userStats,
            'organizerCount'   => $organizerCount,
            'activityCount'    => $activityCount,
            'pendingAppsCount' => $pendingAppsCount,
            'pendingReports'   => $pendingReports,
            'recentAudit'      => $recentAudit,
        ], 'admin');
    }
}
