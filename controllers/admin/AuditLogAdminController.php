<?php

class AuditLogAdminController extends Controller {

    public function index(): void {
        $this->requireRole(ROLE_ADMIN);

        $page = currentPage();
        $action = trim((string)$this->query('action', ''));
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;

        $db = Database::getConnection();

        $where = [];
        $params = [];

        if ($action !== '') {
            $where[] = 'al.action LIKE ?';
            $params[] = "%$action%";
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $countStmt = $db->prepare("SELECT COUNT(*) FROM audit_logs al $whereClause");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $db->prepare(
            "SELECT al.*, u.email, p.username, p.full_name
             FROM audit_logs al
             LEFT JOIN users u ON u.id = al.user_id
             LEFT JOIN profiles p ON p.user_id = al.user_id
             $whereClause
             ORDER BY al.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([...$params, ADMIN_ITEMS_PER_PAGE, $offset]);
        $logs = $stmt->fetchAll();

        $pagination = [
            'data'         => $logs,
            'total'        => $total,
            'per_page'     => ADMIN_ITEMS_PER_PAGE,
            'current_page' => $page,
            'total_pages'  => (int)ceil($total / ADMIN_ITEMS_PER_PAGE),
            'has_prev'     => $page > 1,
            'has_next'     => $page < ceil($total / ADMIN_ITEMS_PER_PAGE),
        ];

        $this->view('admin.audit_logs.index', [
            'pageTitle'  => 'Audit Logs Sistem - ' . APP_NAME,
            'adminTitle' => 'Audit Logs Sistem',
            'logs'       => $logs,
            'pagination' => $pagination,
            'action'     => $action,
        ], 'admin');
    }

    public function securityLogs(): void {
        $this->requireRole(ROLE_ADMIN);

        $page   = currentPage();
        $type   = trim((string)$this->query('type', ''));
        $search = trim((string)$this->query('q', ''));
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        $db     = Database::getConnection();

        $where  = [];
        $params = [];

        if ($type !== '' && $type !== 'all') {
            $where[]  = 'sl.action = ?';
            $params[] = $type;
        }

        if ($search !== '') {
            $where[]  = '(sl.ip_address LIKE ? OR sl.details LIKE ? OR u.email LIKE ? OR p.username LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $countStmt = $db->prepare(
            "SELECT COUNT(*) FROM security_logs sl
             LEFT JOIN users u ON u.id = sl.user_id
             LEFT JOIN profiles p ON p.user_id = sl.user_id
             $whereClause"
        );
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $db->prepare(
            "SELECT sl.*, u.email, p.username, p.full_name
             FROM security_logs sl
             LEFT JOIN users u ON u.id = sl.user_id
             LEFT JOIN profiles p ON p.user_id = sl.user_id
             $whereClause
             ORDER BY sl.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([...$params, ADMIN_ITEMS_PER_PAGE, $offset]);
        $logs = $stmt->fetchAll();

        $statTotal = (int)$db->query("SELECT COUNT(*) FROM security_logs")->fetchColumn();
        $statBrute = (int)$db->query("SELECT COUNT(*) FROM security_logs WHERE action IN ('brute_force_detected', 'failed_login_attempt')")->fetchColumn();
        $statIdor  = (int)$db->query("SELECT COUNT(*) FROM security_logs WHERE action = 'idor_attempt'")->fetchColumn();
        $statOther = (int)$db->query("SELECT COUNT(*) FROM security_logs WHERE action IN ('invalid_csrf', 'rate_limit_exceeded', 'unauthorized_access', 'suspicious_upload', 'suspicious_registration')")->fetchColumn();

        $pagination = [
            'data'         => $logs,
            'total'        => $total,
            'per_page'     => ADMIN_ITEMS_PER_PAGE,
            'current_page' => $page,
            'total_pages'  => (int)ceil($total / ADMIN_ITEMS_PER_PAGE),
            'has_prev'     => $page > 1,
            'has_next'     => $page < ceil($total / ADMIN_ITEMS_PER_PAGE),
        ];

        $this->view('admin.audit_logs.security_logs', [
            'pageTitle'  => 'Security Logs - ' . APP_NAME,
            'adminTitle' => 'Security Event & Incident Logs',
            'logs'       => $logs,
            'pagination' => $pagination,
            'type'       => $type,
            'search'     => $search,
            'stats'      => [
                'total' => $statTotal,
                'brute' => $statBrute,
                'idor'  => $statIdor,
                'other' => $statOther,
            ],
        ], 'admin');
    }
}
