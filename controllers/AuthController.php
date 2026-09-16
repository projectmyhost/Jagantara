<?php

class AuthController extends Controller {
    private User $userModel;
    private RateLimit $rateLimiter;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->rateLimiter = new RateLimit();
    }

    public function showLogin(): void {
        if (isLoggedIn()) {
            if (hasRole(ROLE_ADMIN)) {
                $this->redirect('admin/dashboard');
            }
            $this->redirect('');
        }

        if (!headers_sent()) {
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Pragma: no-cache');
        }

        $ip = Security::getClientIp();
        $this->view('auth.login', [
            'pageTitle' => 'Masuk ke Akun - ' . APP_NAME,
        ]);
    }

    public function login(): void {
        if (isLoggedIn()) {
            $this->redirect('');
        }

        $this->validateCsrf();

        $login = trim($this->input('login', ''));
        $password = (string)$this->input('password', '');
        $ip = Security::getClientIp();
        $device = Security::getDeviceFingerprint();

        if (!$this->rateLimiter->checkLogin($ip, $login)) {
            $this->secLog()->logBruteForce($ip, $login);
            setFlash('error', 'Terlalu banyak percobaan login gagal. Silakan coba lagi dalam 15 menit.');
            $this->view('auth.login', ['login' => $login, 'pageTitle' => 'Masuk ke Akun']);
            return;
        }

        $validator = Validator::make([
            'login'    => $login,
            'password' => $password,
        ]);
        $validator->required('login', 'Username / Email / Nama Lengkap');
        $validator->required('password', 'Password');

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->view('auth.login', [
                'login'     => $login,
                'errors'    => $validator->getErrors(),
                'pageTitle' => 'Masuk ke Akun',
            ]);
            return;
        }

        $user = $this->userModel->findByLogin($login);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->rateLimiter->recordLoginAttempt($ip, $login, false);
            $this->audit()->logFailedLogin($login);
            $remaining = $this->rateLimiter->getRemainingLoginAttempts($ip, $login);
            $this->secLog()->logFailedLogin($login, $ip, $remaining);

            $msg = 'Login atau password salah.';
            if ($remaining <= 2 && $remaining > 0) {
                $msg .= ' Sisa percobaan: ' . $remaining . ' kali.';
            }
            setFlash('error', $msg);
            $this->view('auth.login', ['login' => $login, 'pageTitle' => 'Masuk ke Akun']);
            return;
        }

        if ($user['status'] === 'banned') {
            $this->rateLimiter->recordLoginAttempt($ip, $login, false);
            setFlash('error', 'Akun Anda telah di-banned. Hubungi kontak admin untuk informasi selanjutnya.');
            $this->view('auth.login', ['login' => $login, 'pageTitle' => 'Masuk ke Akun']);
            return;
        }

        if ($user['status'] !== 'active') {
            $this->rateLimiter->recordLoginAttempt($ip, $login, false);
            setFlash('error', 'Akun Anda sedang dinonaktifkan atau ditangguhkan. Hubungi kontak admin untuk informasi selanjutnya.');
            $this->view('auth.login', ['login' => $login, 'pageTitle' => 'Masuk ke Akun']);
            return;
        }

        $this->rateLimiter->recordLoginAttempt($ip, $login, true);

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        $this->audit()->logLogin((int)$user['id'], $user['email']);

        setFlash('success', 'Selamat datang kembali di ' . APP_NAME . '.');

        $redirectUrl = $_SESSION['redirect_after_login'] ?? null;
        unset($_SESSION['redirect_after_login']);

        if ($redirectUrl && !str_contains($redirectUrl, 'auth/')) {
            $this->redirect(ltrim($redirectUrl, '/'));
        }

        if ($user['role'] === ROLE_ADMIN) {
            $this->redirect('admin/dashboard');
        }

        $this->redirect('');
    }

    public function showRegister(): void {
        if (isLoggedIn()) {
            $this->redirect('');
        }

        if (!headers_sent()) {
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Pragma: no-cache');
        }

        $this->view('auth.register', [
            'pageTitle'           => 'Daftar Akun Baru - ' . setting('site_name', APP_NAME),
            'registrationEnabled' => isRegistrationEnabled(),
            'values'              => [],
            'errors'              => [],
        ]);
    }

    public function register(): void {
        if (isLoggedIn()) {
            $this->redirect('');
        }

        $this->validateCsrf();

        if (!isRegistrationEnabled()) {
            setFlash('error', 'Pendaftaran akun baru saat ini sedang dinonaktifkan oleh administrator.');
            $this->redirect('auth/login');
            return;
        }

        $ip = Security::getClientIp();
        $device = Security::getDeviceFingerprint();

        if (!$this->rateLimiter->checkRegistration($ip, $device)) {
            $this->secLog()->logSuspiciousRegistration($ip, $device);
            $days = (int)setting('register_rate_window_days', '7');
            $maxAcc = (int)setting('register_rate_limit', '2');
            setFlash('error', "Batas pendaftaran akun untuk perangkat/jaringan ini telah tercapai. Maksimal {$maxAcc} akun per {$days} hari.");
            $this->view('auth.register', [
                'pageTitle'           => 'Daftar Akun Baru',
                'values'              => $this->allInput(),
                'registrationEnabled' => true,
            ]);
            return;
        }

        $input = $this->allInput();

        $validator = Validator::make($input);
        $validator->required('email', 'Email')
                  ->email('email', 'Email')
                  ->unique('email', 'users', 'email', 'Email');

        $validator->required('username', 'Username')
                  ->minLength('username', 3, 'Username')
                  ->maxLength('username', 30, 'Username')
                  ->unique('username', 'profiles', 'username', 'Username');

        $validator->required('full_name', 'Nama Lengkap')
                  ->minLength('full_name', 2, 'Nama Lengkap')
                  ->maxLength('full_name', 100, 'Nama Lengkap');

        $validator->required('password', 'Password')
                  ->minLength('password', 8, 'Password')
                  ->passwordConfirm('password', 'password_confirm');

        $validator->custom('terms', function($val) {
            return !empty($val);
        }, 'Anda harus mencentang dan menyetujui Syarat & Ketentuan untuk mendaftar.');

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->view('auth.register', [
                'pageTitle' => 'Daftar Akun Baru',
                'errors'    => $validator->getErrors(),
                'values'    => $input,
            ]);
            return;
        }

        $db = Database::getConnection();
        try {
            $db->beginTransaction();

            $hash = password_hash($input['password'], PASSWORD_BCRYPT, ['cost' => 12]);
            $stmt = $db->prepare(
                'INSERT INTO users (email, password_hash, role, status, created_at) VALUES (?, ?, ?, ?, NOW())'
            );
            $stmt->execute([$input['email'], $hash, ROLE_USER, 'active']);
            $userId = (int)$db->lastInsertId();

            $profileStmt = $db->prepare(
                'INSERT INTO profiles (user_id, username, full_name, is_complete, created_at) VALUES (?, ?, ?, 0, NOW())'
            );
            $profileStmt->execute([$userId, trim($input['username']), trim($input['full_name'])]);

            $db->commit();

            $this->rateLimiter->recordRegistration($ip, $device, $userId);
            $this->audit()->logRegister($userId);

            session_regenerate_id(true);
            $_SESSION = [];
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_email'] = $input['email'];
            $_SESSION['user_role'] = ROLE_USER;

            setFlash('success', 'Pendaftaran berhasil. Selamat datang di ' . APP_NAME . '. Lengkapi profil Anda untuk pengalaman terbaik.');
            $this->redirect('profile/edit');

        } catch (Exception $e) {
            $db->rollBack();
            logError('Register failed: ' . $e->getMessage());
            setFlash('error', 'Terjadi kesalahan sistem saat mendaftar. Silakan coba lagi.');
            $this->view('auth.register', [
                'pageTitle' => 'Daftar Akun Baru',
                'values'    => $input,
            ]);
        }
    }

    public function checkStatus(): void {
        header('Content-Type: application/json; charset=utf-8');

        if (!isLoggedIn()) {
            echo json_encode(['status' => 'ok']);
            exit;
        }

        $userId = (int)currentUserId();

        try {

            $user = $this->userModel->find($userId);

            if (!$user) {

                echo json_encode([
                    'status'  => 'banned',
                    'banned'  => true,
                    'message' => 'Akun Anda telah dihapus dari sistem. Hubungi kontak admin untuk informasi selanjutnya.'
                ]);
                exit;
            }

            if ($user['status'] === 'banned') {
                echo json_encode([
                    'status'  => 'banned',
                    'banned'  => true,
                    'message' => 'Akun Anda telah di-banned, hubungi kontak admin untuk informasi selanjutnya.'
                ]);
                exit;
            }

            if ($user['status'] !== 'active') {
                echo json_encode([
                    'status'  => 'banned',
                    'banned'  => true,
                    'message' => 'Akun Anda sedang dinonaktifkan. Hubungi kontak admin untuk informasi selanjutnya.'
                ]);
                exit;
            }

            if (!empty($user['role']) && $user['role'] !== ($_SESSION['user_role'] ?? '')) {
                $_SESSION['user_role'] = $user['role'];
            }

            echo json_encode(['status' => 'ok', 'role' => $user['role']]);

        } catch (Exception $e) {
            logError('Check status error: ' . $e->getMessage());
            echo json_encode(['status' => 'ok']);
        }
        exit;
    }

    public function logout(): void {
        if (isLoggedIn()) {
            $this->audit()->logLogout((int)currentUserId());
        }

        $isBannedRedirect = !empty($_GET['banned']) || !empty($_GET['to']) && $_GET['to'] === 'login';

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();

        if (Security::isAjax()) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'ok', 'redirect' => url('auth/login')]);
            exit;
        }

        Security::startSession();
        if ($isBannedRedirect) {
            setFlash('error', 'Akun Anda telah di-banned. Hubungi kontak admin untuk informasi selanjutnya.');
            $this->redirect('auth/login');
        } else {
            setFlash('info', 'Anda telah berhasil keluar.');
            $this->redirect('');
        }
    }

    public function showAdminLoginSecret(): void {
        if (isLoggedIn()) {
            if (hasRole(ROLE_ADMIN)) {
                $this->redirect('admin/dashboard');
            }
            $this->redirect('');
        }

        $this->view('auth.admin_login', [
            'pageTitle' => 'Portal Masuk Administrator - ' . setting('site_name', APP_NAME),
        ]);
    }

    public function loginAdminSecret(): void {
        if (isLoggedIn()) {
            if (hasRole(ROLE_ADMIN)) {
                $this->redirect('admin/dashboard');
            }
            $this->redirect('');
        }

        $this->validateCsrf();

        $email = trim($this->input('email', ''));
        $password = (string)$this->input('password', '');
        $ip = Security::getClientIp();

        if (!$this->rateLimiter->checkLogin($ip, $email)) {
            $this->secLog()->logBruteForce($ip, substr($email, strpos($email, '@') ?: 0));
            setFlash('error', 'Terlalu banyak percobaan login gagal. Silakan coba lagi nanti.');
            $this->view('auth.admin_login', ['email' => $email, 'pageTitle' => 'Portal Masuk Administrator']);
            return;
        }

        $validator = Validator::make([
            'email'    => $email,
            'password' => $password,
        ]);
        $validator->required('email', 'Email')->email('email', 'Email');
        $validator->required('password', 'Password');

        if ($validator->fails()) {
            setFlash('error', $validator->getFirstError());
            $this->view('auth.admin_login', [
                'email'     => $email,
                'errors'    => $validator->getErrors(),
                'pageTitle' => 'Portal Masuk Administrator',
            ]);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->rateLimiter->recordLoginAttempt($ip, $email, false);
            $this->audit()->logFailedLogin($email);
            setFlash('error', 'Email atau password administrator salah.');
            $this->view('auth.admin_login', ['email' => $email, 'pageTitle' => 'Portal Masuk Administrator']);
            return;
        }

        if ($user['role'] !== ROLE_ADMIN) {
            $this->rateLimiter->recordLoginAttempt($ip, $email, false);
            setFlash('error', 'Akses ditolak. Portal ini khusus untuk akun Administrator.');
            $this->view('auth.admin_login', ['email' => $email, 'pageTitle' => 'Portal Masuk Administrator']);
            return;
        }

        if ($user['status'] !== 'active') {
            $this->rateLimiter->recordLoginAttempt($ip, $email, false);
            setFlash('error', 'Akun administrator Anda sedang dinonaktifkan.');
            $this->view('auth.admin_login', ['email' => $email, 'pageTitle' => 'Portal Masuk Administrator']);
            return;
        }

        $this->rateLimiter->recordLoginAttempt($ip, $email, true);
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = ROLE_ADMIN;

        $this->audit()->logLogin((int)$user['id'], $user['email']);
        setFlash('success', 'Selamat datang di Panel Kontrol Administrator.');
        $this->redirect('admin/dashboard');
    }
}

