<?php

class UserAdminController extends Controller {
    private User $userModel;
    private Profile $profileModel;
    private Region $regionModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->profileModel = new Profile();
        $this->regionModel = new Region();
    }

    public function index(): void {
        $this->requireRole(ROLE_ADMIN);

        $page = currentPage();
        $search = trim((string)$this->query('q', ''));
        $role = (string)$this->query('role', '');

        $usersData = $this->userModel->listWithProfiles($page, ADMIN_ITEMS_PER_PAGE, $search, $role);

        $this->view('admin.users.index', [
            'pageTitle'  => 'Manajemen Pengguna - ' . APP_NAME,
            'adminTitle' => 'Manajemen Pengguna',
            'users'      => $usersData['data'],
            'pagination' => $usersData,
            'search'     => $search,
            'role'       => $role,
        ], 'admin');
    }

    public function show(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $id = (int)$id;

        $user = $this->userModel->findWithProfile($id);
        if (!$user) {
            $this->abort(404, 'Pengguna tidak ditemukan.');
        }

        $reports = (new Report())->query(
            "SELECT r.*, c.name AS category_name FROM reports r LEFT JOIN categories c ON c.id = r.category_id WHERE r.user_id = ? AND r.deleted_at IS NULL ORDER BY r.created_at DESC",
            [$id]
        );

        $memberships = (new OrganizerMember())->query(
            "SELECT om.*, o.name AS organizer_name FROM organizer_members om LEFT JOIN organizers o ON o.id = om.organizer_id WHERE om.user_id = ?",
            [$id]
        );

        $this->view('admin.users.show', [
            'pageTitle'   => 'Detail Pengguna: ' . e($user['email']) . ' - ' . APP_NAME,
            'adminTitle'  => 'Detail Pengguna #' . $id,
            'user'        => $user,
            'reports'     => $reports,
            'memberships' => $memberships,
        ], 'admin');
    }

    public function edit(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $id = (int)$id;

        $user = $this->userModel->findWithProfile($id);
        if (!$user) {
            $this->abort(404, 'Pengguna tidak ditemukan.');
        }

        $regions = $this->regionModel->getAll();

        $this->view('admin.users.edit', [
            'pageTitle'  => 'Edit Pengguna #' . $id . ' - ' . APP_NAME,
            'adminTitle' => 'Edit Pengguna #' . $id,
            'user'       => $user,
            'regions'    => $regions,
        ], 'admin');
    }

    public function update(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $user = $this->userModel->find($id);
        if (!$user) {
            $this->abort(404, 'Pengguna tidak ditemukan.');
        }

        $input = $this->allInput();

        $role = $input['role'] ?? ROLE_USER;
        $status = $input['status'] ?? 'active';

        $allowedRoles = [ROLE_USER, ROLE_ORGANIZER_MEMBER, ROLE_ADMIN];
        $allowedStatuses = ['active', 'suspended', 'banned'];

        if (!in_array($role, $allowedRoles, true) || !in_array($status, $allowedStatuses, true)) {
            setFlash('error', 'Role atau status tidak valid.');
            $this->redirect('admin/users/' . $id . '/edit');
        }

        if ($id === (int)currentUserId() && ($role !== ROLE_ADMIN || $status !== 'active')) {
            setFlash('error', 'Anda tidak dapat mengubah role atau status akun Anda sendiri.');
            $this->redirect('admin/users/' . $id . '/edit');
        }

        $this->userModel->update($id, [
            'role'   => $role,
            'status' => $status,
        ]);

        $this->audit()->logUserChange('update_role_status', $id, ['role' => $role, 'status' => $status]);

        setFlash('success', 'Data pengguna berhasil diperbarui.');
        $this->redirect('admin/users/' . $id);
    }

    public function delete(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        if ($id === (int)currentUserId()) {
            setFlash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            $this->redirect('admin/users');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            $this->abort(404, 'Pengguna tidak ditemukan.');
        }

        try {
            $this->userModel->delete($id);
            $this->audit()->logUserChange('delete', $id, ['email' => $user['email']]);
            setFlash('success', 'Pengguna berhasil dihapus.');
        } catch (\Throwable $e) {
            error_log('Delete user failed: ' . $e->getMessage());
            setFlash('error', 'Gagal menghapus pengguna karena masih terdapat data yang terhubung.');
        }

        $this->redirect('admin/users');
    }
}
