<?php

class ActivityAdminController extends Controller {
    private Activity $activityModel;
    private ActivityParticipant $participantModel;
    private Organizer $organizerModel;
    private Region $regionModel;

    public function __construct() {
        parent::__construct();
        $this->activityModel = new Activity();
        $this->participantModel = new ActivityParticipant();
        $this->organizerModel = new Organizer();
        $this->regionModel = new Region();
    }

    public function index(): void {
        $this->requireRole(ROLE_ADMIN);

        $activities = $this->activityModel->query(
            "SELECT a.*, o.name AS organizer_name, r.name AS region_name,
                    (SELECT COUNT(*) FROM activity_participants ap WHERE ap.activity_id = a.id) AS participant_count
             FROM activities a
             LEFT JOIN organizers o ON o.id = a.organizer_id
             LEFT JOIN regions r ON r.id = a.region_id
             WHERE a.deleted_at IS NULL
             ORDER BY a.scheduled_at DESC"
        );

        $this->view('admin.activities.index', [
            'pageTitle'  => 'Manajemen Kegiatan - ' . APP_NAME,
            'adminTitle' => 'Kegiatan & Kerja Bakti',
            'activities' => $activities,
        ], 'admin');
    }

    public function create(): void {
        $this->requireRole(ROLE_ADMIN);

        $organizers = $this->organizerModel->all('name', 'ASC');
        $regions = $this->regionModel->getAll();

        $this->view('admin.activities.create', [
            'pageTitle'  => 'Tambah Kegiatan Baru - ' . APP_NAME,
            'adminTitle' => 'Tambah Kegiatan Lingkungan',
            'organizers' => $organizers,
            'regions'    => $regions,
        ], 'admin');
    }

    public function store(): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();

        $input = $this->allInput();
        $validator = Validator::make($input);
        $validator->required('title', 'Judul Kegiatan')
                  ->required('organizer_id', 'Organizer')
                  ->required('region_id', 'Wilayah')
                  ->required('scheduled_at', 'Jadwal Kegiatan')
                  ->required('description', 'Deskripsi Kegiatan');

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->redirect('admin/activities/create');
        }

        $actId = $this->activityModel->create([
            'title'            => trim($input['title']),
            'organizer_id'     => (int)$input['organizer_id'],
            'region_id'        => (int)$input['region_id'],
            'location_name'    => !empty($input['location_name']) ? trim($input['location_name']) : null,
            'address'          => !empty($input['address']) ? trim($input['address']) : null,
            'scheduled_at'     => $input['scheduled_at'],
            'description'      => trim($input['description']),
            'status'           => $input['status'] ?? 'published',
            'max_participants' => !empty($input['max_participants']) ? (int)$input['max_participants'] : null,
        ]);

        $this->audit()->log('create_activity', 'activity', $actId, ['title' => trim($input['title'])]);

        setFlash('success', 'Kegiatan baru berhasil dibuat.');
        $this->redirect('admin/activities');
    }

    public function edit(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $id = (int)$id;

        $activity = $this->activityModel->find($id);
        if (!$activity) {
            $this->abort(404, 'Kegiatan tidak ditemukan.');
        }

        $organizers = $this->organizerModel->all('name', 'ASC');
        $regions = $this->regionModel->getAll();

        $this->view('admin.activities.edit', [
            'pageTitle'  => 'Edit Kegiatan - ' . APP_NAME,
            'adminTitle' => 'Edit Kegiatan: ' . e($activity['title']),
            'activity'   => $activity,
            'organizers' => $organizers,
            'regions'    => $regions,
        ], 'admin');
    }

    public function update(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $activity = $this->activityModel->find($id);
        if (!$activity) {
            $this->abort(404, 'Kegiatan tidak ditemukan.');
        }

        $input = $this->allInput();
        $validator = Validator::make($input);
        $validator->required('title', 'Judul Kegiatan')
                  ->required('organizer_id', 'Organizer')
                  ->required('region_id', 'Wilayah')
                  ->required('scheduled_at', 'Jadwal Kegiatan')
                  ->required('description', 'Deskripsi Kegiatan');

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->redirect('admin/activities/' . $id . '/edit');
        }

        $this->activityModel->update($id, [
            'title'            => trim($input['title']),
            'organizer_id'     => (int)$input['organizer_id'],
            'region_id'        => (int)$input['region_id'],
            'location_name'    => !empty($input['location_name']) ? trim($input['location_name']) : null,
            'address'          => !empty($input['address']) ? trim($input['address']) : null,
            'scheduled_at'     => $input['scheduled_at'],
            'description'      => trim($input['description']),
            'status'           => $input['status'] ?? 'published',
            'max_participants' => !empty($input['max_participants']) ? (int)$input['max_participants'] : null,
        ]);

        $this->audit()->log('update_activity', 'activity', $id, ['title' => trim($input['title'])]);

        setFlash('success', 'Kegiatan berhasil diperbarui.');
        $this->redirect('admin/activities');
    }

    public function delete(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $activity = $this->activityModel->find($id);
        if (!$activity) {
            $this->abort(404, 'Kegiatan tidak ditemukan.');
        }

        try {
            $this->activityModel->delete($id);
            $this->audit()->log('delete_activity', 'activity', $id, ['title' => $activity['title']]);
            setFlash('success', 'Kegiatan berhasil dihapus.');
        } catch (\Throwable $e) {
            error_log('Delete activity failed: ' . $e->getMessage());
            setFlash('error', 'Gagal menghapus kegiatan karena masih terdapat data terkait.');
        }

        $this->redirect('admin/activities');
    }

    public function participants(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $id = (int)$id;

        $activity = $this->activityModel->findWithDetails($id);
        if (!$activity) {
            $this->abort(404, 'Kegiatan tidak ditemukan.');
        }

        $participants = $this->participantModel->query(
            "SELECT ap.*, p.full_name, p.phone, u.email
             FROM activity_participants ap
             LEFT JOIN users u ON u.id = ap.user_id
             LEFT JOIN profiles p ON p.user_id = ap.user_id
             WHERE ap.activity_id = ?
             ORDER BY ap.joined_at DESC",
            [$id]
        );

        $this->view('admin.activities.participants', [
            'pageTitle'    => 'Daftar Peserta: ' . e($activity['title']) . ' - ' . APP_NAME,
            'adminTitle'   => 'Peserta Kegiatan: ' . e($activity['title']),
            'activity'     => $activity,
            'participants' => $participants,
        ], 'admin');
    }
}
