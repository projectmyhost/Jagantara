<?php

abstract class Controller {
    protected ?AuditLog $audit = null;
    protected ?SecurityLog $secLog = null;

    public function __construct() {

    }

    protected function view(string $view, array $data = [], string $layout = 'main'): void {

        extract($data);

        $csrfToken = Security::generateCsrfToken();

        $flashMessages = getFlash();

        $currentUser = $this->getCurrentUser();

        ob_start();
        $viewPath = APP_ROOT . '/views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewPath)) {
            ob_end_clean();
            $this->abort(404, "View not found: $view");
        }
        require $viewPath;
        $content = ob_get_clean();

        $layoutPath = APP_ROOT . '/views/layouts/' . $layout . '.php';
        if (!file_exists($layoutPath)) {
            echo $content;
            return;
        }
        require $layoutPath;
    }

    protected function viewPartial(string $view, array $data = []): void {
        extract($data);
        $viewPath = APP_ROOT . '/views/' . str_replace('.', '/', $view) . '.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        }
    }

    protected function getCurrentUser(): ?array {
        if (!isLoggedIn()) return null;
        static $user = null;
        if ($user === null) {
            $db = Database::getConnection();
            $stmt = $db->prepare(
                'SELECT u.id, u.email, u.role, u.status, p.username, p.full_name, p.avatar_path, p.is_complete
                 FROM users u
                 LEFT JOIN profiles p ON p.user_id = u.id
                 WHERE u.id = ?'
            );
            $stmt->execute([currentUserId()]);
            $user = $stmt->fetch() ?: null;
        }
        return $user;
    }

    protected function json(mixed $data, int $status = 200): never {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    protected function redirect(string $path): never {
        redirect($path);
    }

    protected function back(): never {
        redirectBack();
    }

    protected function abort(int $code = 404, string $message = ''): never {
        if ($code === 403) {
            $this->secLog()->logUnauthorizedAccess($_SERVER['REQUEST_URI'] ?? '', currentUserId());
        }
        http_response_code($code);
        $messages = [
            400 => 'Permintaan tidak valid.',
            403 => 'Anda tidak memiliki akses ke halaman ini.',
            404 => 'Halaman tidak ditemukan.',
            405 => 'Metode tidak diizinkan.',
            500 => 'Terjadi kesalahan server.',
        ];
        $msg = $message ?: ($messages[$code] ?? 'Terjadi kesalahan.');
        $this->view('errors.error', ['code' => $code, 'message' => $msg], 'main');
        exit;
    }

    protected function validateCsrf(): void {
        $token = Security::getCsrfFromRequest();
        if (!Security::validateCsrfToken($token)) {
            (new SecurityLog())->logInvalidCsrf($_SERVER['REQUEST_URI'] ?? '');
            if (Security::isAjax()) {
                $this->json(['error' => 'Token keamanan tidak valid. Refresh halaman dan coba lagi.'], 403);
            }
            setFlash('error', 'Token keamanan tidak valid. Silakan coba lagi.');
            $this->back();
        }
    }

    protected function requireAuth(): void {
        requireAuth();
    }

    protected function requireRole(string ...$roles): void {
        requireRole(...$roles);
    }

    protected function isPost(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function input(string $key, mixed $default = ''): mixed {
        return $_POST[$key] ?? $default;
    }

    protected function query(string $key, mixed $default = ''): mixed {
        return $_GET[$key] ?? $default;
    }

    protected function allInput(): array {
        return $_POST;
    }

    protected function audit(): AuditLog {
        if ($this->audit === null) {
            $this->audit = new AuditLog();
        }
        return $this->audit;
    }

    protected function secLog(): SecurityLog {
        if ($this->secLog === null) {
            $this->secLog = new SecurityLog();
        }
        return $this->secLog;
    }
}
