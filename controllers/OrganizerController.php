<?php

class OrganizerController extends Controller {
    private Organizer $organizerModel;
    private OrganizerApplication $appModel;
    private OrganizerMember $memberModel;
    private Profile $profileModel;
    private User $userModel;
    private Region $regionModel;
    private FileUpload $fileUploader;

    public function __construct() {
        parent::__construct();
        $this->organizerModel = new Organizer();
        $this->appModel = new OrganizerApplication();
        $this->memberModel = new OrganizerMember();
        $this->profileModel = new Profile();
        $this->userModel = new User();
        $this->regionModel = new Region();
        $this->fileUploader = new FileUpload();
    }

    public function index(): void {
        $page = currentPage();
        $organizers = $this->organizerModel->getActive($page, 12);

        $this->view('organizer.index', [
            'pageTitle'   => 'Organizer & Komunitas Lingkungan - ' . APP_NAME,
            'organizers'  => $organizers['data'],
            'pagination'  => $organizers,
        ]);
    }

    public function show(string|int $id): void {
        $id = (int)$id;
        $organizer = $this->organizerModel->findWithDetails($id);

        if (!$organizer) {
            $this->abort(404, 'Organizer tidak ditemukan.');
        }

        $members = $this->memberModel->getByOrganizer($id);

        $activities = (new Activity())->query(
            "SELECT * FROM activities WHERE organizer_id = ? AND deleted_at IS NULL ORDER BY scheduled_at DESC LIMIT 6",
            [$id]
        );

        $isMember = false;
        $hasPendingApp = false;

        if (isLoggedIn()) {
            $userId = (int)currentUserId();
            $isMember = $this->memberModel->isMember($userId, $id);
            $hasPendingApp = $this->appModel->hasPending($userId, $id);
        }

        $this->view('organizer.show', [
            'pageTitle'     => e($organizer['name']) . ' - ' . APP_NAME,
            'organizer'     => $organizer,
            'members'       => $members,
            'activities'    => $activities,
            'isMember'      => $isMember,
            'hasPendingApp' => $hasPendingApp,
        ]);
    }

    public function joinForm(string|int $id): void {
        $this->requireAuth();
        $id = (int)$id;
        $userId = (int)currentUserId();

        $organizer = $this->organizerModel->find($id);
        if (!$organizer) {
            $this->abort(404, 'Organizer tidak ditemukan.');
        }

        if (!isOrganizerJoinEnabled()) {
            setFlash('warning', 'Pendaftaran anggota baru ke organizer saat ini sedang dinonaktifkan sementara oleh administrator.');
            $this->redirect('organizers/' . $id);
            return;
        }

        if ($this->memberModel->isMember($userId, $id)) {
            setFlash('info', 'Anda sudah terdaftar sebagai anggota ' . $organizer['name'] . '.');
            $this->redirect('organizers/' . $id);
        }

        if ($this->appModel->hasPending($userId, $id)) {
            setFlash('warning', 'Pendaftaran Anda ke ' . $organizer['name'] . ' sedang menunggu verifikasi admin.');
            $this->redirect('organizers/my-status');
        }

        $userProfile = $this->userModel->findWithProfile($userId);
        $isComplete = !empty($userProfile['is_complete']) && (bool)$userProfile['is_complete'];
        $regions = $this->regionModel->getAll();

        $this->view('organizer.join', [
            'pageTitle'   => 'Gabung ' . e($organizer['name']) . ' - ' . setting('site_name', APP_NAME),
            'organizer'   => $organizer,
            'userProfile' => $userProfile,
            'isComplete'  => $isComplete,
            'regions'     => $regions,
        ]);
    }

    public function joinSubmit(string|int $id): void {
        $this->requireAuth();
        $this->validateCsrf();

        $id = (int)$id;
        $userId = (int)currentUserId();

        $organizer = $this->organizerModel->find($id);
        if (!$organizer) {
            $this->abort(404, 'Organizer tidak ditemukan.');
        }

        if (!isOrganizerJoinEnabled()) {
            setFlash('error', 'Pendaftaran anggota baru ke organizer saat ini sedang dinonaktifkan oleh administrator.');
            $this->redirect('organizers/' . $id);
            return;
        }

        if ($this->memberModel->isMember($userId, $id)) {
            setFlash('info', 'Anda sudah menjadi anggota.');
            $this->redirect('organizers/' . $id);
        }

        if ($this->appModel->hasPending($userId, $id)) {
            setFlash('warning', 'Pendaftaran Anda sedang diproses.');
            $this->redirect('organizers/my-status');
        }

        $userProfile = $this->userModel->findWithProfile($userId);
        $isComplete = !empty($userProfile['is_complete']) && (bool)$userProfile['is_complete'];
        $input = $this->allInput();

        if ($isComplete) {
            $fullName = $userProfile['full_name'];
            $phone = $userProfile['phone'];
            $address = $userProfile['address'];
            $regionId = (int)$userProfile['region_id'];
            $birthDate = $userProfile['birth_date'];
            $gender = $userProfile['gender'];
        } else {

            $validator = Validator::make($input);
            $validator->required('full_name', 'Nama Lengkap')
                      ->required('phone', 'Nomor Handphone')
                      ->phone('phone', 'Nomor Handphone')
                      ->required('address', 'Alamat Lengkap')
                      ->required('region_id', 'Wilayah / Daerah')
                      ->positiveInt('region_id', 'Wilayah / Daerah')
                      ->required('birth_date', 'Tanggal Lahir')
                      ->date('birth_date', 'Tanggal Lahir')
                      ->required('gender', 'Jenis Kelamin')
                      ->inList('gender', ['male', 'female', 'other'], 'Jenis Kelamin');

            if ($validator->fails()) {
                setFlash('error', $validator->getFirstError());
                $this->redirect('organizers/join/' . $id);
            }

            $fullName = trim($input['full_name']);
            $phone = trim($input['phone']);
            $address = trim($input['address']);
            $regionId = (int)$input['region_id'];
            $birthDate = $input['birth_date'];
            $gender = $input['gender'];
        }

        $motivation = trim((string)($input['motivation'] ?? ''));

        $appId = $this->appModel->create([
            'user_id'         => $userId,
            'organizer_id'    => $id,
            'full_name'       => $fullName,
            'phone'           => $phone,
            'address'         => $address,
            'region_id'       => $regionId,
            'birth_date'      => $birthDate,
            'gender'          => $gender,
            'ktp_path'        => null,
            'face_photo_path' => null,
            'motivation'      => $motivation,
            'status'          => 'pending',
        ]);

        $this->audit()->log('join_organizer_application', 'organizer_application', $appId, [
            'organizer_id' => $id,
        ]);

        setFlash('success', 'Formulir pendaftaran berhasil dikirim. Admin akan memverifikasi permohonan Anda.');
        $this->redirect('organizers/my-status');
    }

    public function myStatus(): void {
        $this->requireAuth();
        $userId = (int)currentUserId();

        $applications = $this->appModel->getByUser($userId);

        $memberships = $this->memberModel->query(
            "SELECT om.*, o.name AS organizer_name, o.slug AS organizer_slug, r.name AS region_name
             FROM organizer_members om
             LEFT JOIN organizers o ON o.id = om.organizer_id
             LEFT JOIN regions r ON r.id = o.region_id
             WHERE om.user_id = ? AND om.is_active = 1",
            [$userId]
        );

        $this->view('organizer.status', [
            'pageTitle'    => 'Status Keanggotaan Organizer - ' . APP_NAME,
            'applications' => $applications,
            'memberships'  => $memberships,
        ]);
    }
}
