<?php
class Security {

    public static function startSession(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                   || (int)($_SERVER['SERVER_PORT'] ?? 80) === 443;

        session_name(SESSION_NAME);

        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();

        $fingerprint = self::getSessionFingerprint();
        if (!isset($_SESSION['_fingerprint'])) {
            $_SESSION['_fingerprint'] = $fingerprint;
        }
    }

    public static function generateCsrfToken(): string {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function validateCsrfToken(string $token): bool {
        if (empty($_SESSION['_csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['_csrf_token'], $token);
    }

    public static function getCsrfFromRequest(): string {
        return $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    }

    public static function csrfField(): string {
        $token = self::generateCsrfToken();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function checkCsrf(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = self::getCsrfFromRequest();
            if (!self::validateCsrfToken($token)) {
                http_response_code(403);
                die(json_encode(['error' => 'Invalid security token. Please refresh and try again.']));
            }
        }
    }

    private static function getSessionFingerprint(): string {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $accept = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        return hash('sha256', $ua . '|' . $accept . '|' . SESSION_NAME);
    }

    public static function getDeviceFingerprint(): string {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $acceptLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        $acceptEncoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
        return hash('sha256', $ua . '|' . $accept . '|' . $acceptLang . '|' . $acceptEncoding);
    }

    public static function getClientIp(): string {
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = trim(explode(',', $_SERVER[$header])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public static function e(mixed $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function sanitize(string $input): string {
        return trim(strip_tags($input));
    }

    public static function randomString(int $length = 32): string {
        return bin2hex(random_bytes($length / 2));
    }

    public static function randomFilename(string $extension): string {
        return bin2hex(random_bytes(16)) . '_' . time() . '.' . strtolower($extension);
    }

    public static function isAjax(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
               && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public static function redirect(string $url): never {

        if (!str_starts_with($url, '/') && !str_starts_with($url, BASE_URL)) {
            $url = BASE_URL;
        }
        header('Location: ' . $url);
        exit;
    }
}
