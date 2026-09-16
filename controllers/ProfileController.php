<?php

class ProfileController extends Controller {
    private Profile $profileModel;
    private User $userModel;
    private Region $regionModel;
    private FileUpload $fileUploader;

    public function __construct() {
        parent::__construct();
        $this->profileModel = new Profile();
        $this->userModel = new User();
        $this->regionModel = new Region();
        $this->fileUploader = new FileUpload();
    }

    public function index(): void {
        $this->requireAuth();
        $userId = (int)currentUserId();

        $user = $this->userModel->findWithProfile($userId);
        $regions = $this->regionModel->getAll();

        $reportsCount = (new Report())->count(['user_id' => $userId, 'deleted_at' => null]);

        $this->view('profile.index', [
            'pageTitle'       => 'Profil Saya - ' . APP_NAME,
            'user'            => $user,
            'regions'         => $regions,
            'reportsCount'    => $reportsCount,
        ]);
    }

    public function edit(): void {
        $this->requireAuth();
        $userId = (int)currentUserId();

        $user = $this->userModel->findWithProfile($userId);
        $regions = $this->regionModel->getAll();

        $this->view('profile.edit', [
            'pageTitle' => 'Edit Profil - ' . APP_NAME,
            'user'      => $user,
            'regions'   => $regions,
        ]);
    }

    public function update(): void {
        $this->requireAuth();
        $this->validateCsrf();

        $userId = (int)currentUserId();
        $input = $this->allInput();

        $validator = Validator::make($input);
        $validator->required('full_name', 'Nama Lengkap')
                  ->minLength('full_name', 2, 'Nama Lengkap')
                  ->maxLength('full_name', 100, 'Nama Lengkap')
                  ->required('username', 'Username')
                  ->minLength('username', 3, 'Username')
                  ->maxLength('username', 30, 'Username');

        $existingProfile = $this->profileModel->findByUserId($userId);
        $profileId = $existingProfile['id'] ?? null;
        $validator->unique('username', 'profiles', 'username', 'Username', $profileId);

        if (!empty($input['phone'])) {
            $validator->phone('phone', 'Nomor Handphone');
        }
        if (!empty($input['birth_date'])) {
            $validator->date('birth_date', 'Tanggal Lahir');
        }
        if (!empty($input['gender'])) {
            $validator->inList('gender', ['male', 'female', 'other'], 'Jenis Kelamin');
        }

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->redirect('profile/edit');
        }

        $avatarPath = $existingProfile['avatar_path'] ?? null;
        if (!empty($input['avatar_cropped']) && str_starts_with($input['avatar_cropped'], 'data:image/')) {
            $uploaded = $this->fileUploader->uploadBase64Image($input['avatar_cropped'], 'profiles');
            if ($uploaded) {
                if ($avatarPath) {
                    $this->fileUploader->deleteFile($avatarPath);
                }
                $avatarPath = $uploaded;
            } else {
                setFlash('error', implode(' ', $this->fileUploader->getErrors()));
                $this->redirect('profile/edit');
            }
        } elseif (!empty($_FILES['avatar']['name'])) {
            $uploaded = $this->fileUploader->uploadImage($_FILES['avatar'], 'profiles');
            if ($uploaded) {
                if ($avatarPath) {
                    $this->fileUploader->deleteFile($avatarPath);
                }
                $avatarPath = $uploaded;
            } else {
                setFlash('error', implode(' ', $this->fileUploader->getErrors()));
                $this->redirect('profile/edit');
            }
        }

        $profileData = [
            'username'    => trim($input['username']),
            'full_name'   => trim($input['full_name']),
            'phone'       => !empty($input['phone']) ? trim($input['phone']) : null,
            'address'     => !empty($input['address']) ? trim($input['address']) : null,
            'region_id'   => !empty($input['region_id']) ? (int)$input['region_id'] : null,
            'birth_date'  => !empty($input['birth_date']) ? $input['birth_date'] : null,
            'gender'      => !empty($input['gender']) ? $input['gender'] : null,
            'avatar_path' => $avatarPath,
        ];

        $isComplete = $this->profileModel->checkCompleteness($profileData);
        $profileData['is_complete'] = $isComplete ? 1 : 0;

        $this->profileModel->upsert($userId, $profileData);
        $this->audit()->log('update_profile', 'user', $userId, ['username' => $profileData['username']]);

        setFlash('success', 'Profil berhasil diperbarui.');
        $this->redirect('profile');
    }

    public function updateAvatar(): void {
        $this->requireAuth();
        $this->validateCsrf();

        $userId = (int)currentUserId();
        $input = $this->allInput();
        $existingProfile = $this->profileModel->findByUserId($userId);
        $uploaded = false;

        if (!empty($input['avatar_cropped']) && str_starts_with($input['avatar_cropped'], 'data:image/')) {
            $uploaded = $this->fileUploader->uploadBase64Image($input['avatar_cropped'], 'profiles');
        } elseif (!empty($_FILES['avatar']['name'])) {
            $uploaded = $this->fileUploader->uploadImage($_FILES['avatar'], 'profiles');
        } else {
            setFlash('error', 'Pilih file foto terlebih dahulu.');
            $this->redirect('profile/edit');
        }

        if ($uploaded) {
            if (!empty($existingProfile['avatar_path'])) {
                $this->fileUploader->deleteFile($existingProfile['avatar_path']);
            }
            $this->profileModel->upsert($userId, ['avatar_path' => $uploaded]);
            setFlash('success', 'Foto profil berhasil diperbarui.');
        } else {
            setFlash('error', implode(' ', $this->fileUploader->getErrors()));
        }

        $this->redirect('profile/edit');
    }
}
