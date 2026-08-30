<?php

class ActivityController extends Controller {
    private Activity $activityModel;
    private ActivityParticipant $participantModel;
    private Region $regionModel;

    public function __construct() {
        parent::__construct();
        $this->activityModel = new Activity();
        $this->participantModel = new ActivityParticipant();
        $this->regionModel = new Region();
    }

    public function index(): void {
        $page = currentPage();
        $regionId = (int)$this->query('region_id', 0);
        $status = (string)$this->query('status', '');

        $filters = [
            'region_id' => $regionId,
            'status'    => $status,
        ];

        $activitiesData = $this->activityModel->listPublic($filters, $page, ITEMS_PER_PAGE);
        $regions = $this->regionModel->getAll();

        $this->view('activities.index', [
            'pageTitle'   => 'Kegiatan Lingkungan & Kerja Bakti - ' . APP_NAME,
            'activities'  => $activitiesData['data'],
            'pagination'  => $activitiesData,
            'regions'     => $regions,
            'filters'     => $filters,
        ]);
    }

    public function show(string|int $id): void {
        $id = (int)$id;
        $activity = $this->activityModel->findWithDetails($id);

        if (!$activity) {
            $this->abort(404, 'Kegiatan tidak ditemukan.');
        }

        $participants = $this->participantModel->query(
            "SELECT ap.*, p.full_name, p.username, p.avatar_path
             FROM activity_participants ap
             LEFT JOIN users u ON u.id = ap.user_id
             LEFT JOIN profiles p ON p.user_id = ap.user_id
             WHERE ap.activity_id = ?
             ORDER BY ap.joined_at DESC",
            [$id]
        );

        $isJoined = false;
        if (isLoggedIn()) {
            $isJoined = $this->participantModel->isParticipant($id, (int)currentUserId());
        }

        $this->view('activities.show', [
            'pageTitle'    => e($activity['title']) . ' - ' . APP_NAME,
            'activity'     => $activity,
            'participants' => $participants,
            'isJoined'     => $isJoined,
        ]);
    }

    public function join(string|int $id): void {
        $this->requireAuth();
        $this->validateCsrf();

        $id = (int)$id;
        $userId = (int)currentUserId();

        $activity = $this->activityModel->find($id);
        if (!$activity || !in_array($activity['status'], ['published', 'ongoing'], true)) {
            setFlash('error', 'Kegiatan tidak dapat diikuti saat ini.');
            $this->redirect('activities/' . $id);
        }

        if (!empty($activity['max_participants'])) {
            $currentCount = (int)$this->participantModel->queryOne(
                'SELECT COUNT(*) as cnt FROM activity_participants WHERE activity_id = ?',
                [$id]
            )['cnt'];

            if ($currentCount >= $activity['max_participants']) {
                setFlash('error', 'Kuota peserta kegiatan ini sudah penuh.');
                $this->redirect('activities/' . $id);
            }
        }

        if ($this->participantModel->join($id, $userId)) {
            $this->audit()->log('join_activity', 'activity', $id);
            setFlash('success', 'Anda berhasil mendaftar sebagai peserta kegiatan ini.');
        } else {
            setFlash('info', 'Anda sudah terdaftar dalam kegiatan ini.');
        }

        $this->redirect('activities/' . $id);
    }

    public function leave(string|int $id): void {
        $this->requireAuth();
        $this->validateCsrf();

        $id = (int)$id;
        $userId = (int)currentUserId();

        $this->participantModel->leave($id, $userId);
        $this->audit()->log('leave_activity', 'activity', $id);

        setFlash('info', 'Anda telah membatalkan keikutsertaan dalam kegiatan ini.');
        $this->redirect('activities/' . $id);
    }
}
