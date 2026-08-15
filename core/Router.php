<?php

class Router {
    private array $routes = [];
    private array $middlewares = [];

    public function get(string $path, string $controller, string $action, array $middleware = []): void {
        $this->routes['GET'][$path] = compact('controller', 'action', 'middleware');
    }

    public function post(string $path, string $controller, string $action, array $middleware = []): void {
        $this->routes['POST'][$path] = compact('controller', 'action', 'middleware');
    }

    public function any(string $path, string $controller, string $action, array $middleware = []): void {
        $this->get($path, $controller, $action, $middleware);
        $this->post($path, $controller, $action, $middleware);
    }

    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        $basePath = rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
        if ($basePath && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }
        $path = '/' . trim($path, '/');
        if ($path === '/') $path = '/';

        $route = $this->routes[$method][$path] ?? null;

        if ($route === null) {
            $route = $this->matchParameterized($method, $path);
        }

        if ($route === null) {

            foreach (['GET', 'POST', 'PUT', 'DELETE'] as $m) {
                if (isset($this->routes[$m][$path])) {
                    http_response_code(405);
                    $this->render404('Metode tidak diizinkan.');
                    return;
                }
            }
            http_response_code(404);
            $this->render404();
            return;
        }

        $controllerClass = $route['controller'];
        $action = $route['action'];
        $params = $route['params'] ?? [];

        if (!class_exists($controllerClass)) {
            error_log("Controller not found: $controllerClass");
            http_response_code(500);
            $this->render404('Terjadi kesalahan server.');
            return;
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $action)) {
            error_log("Action not found: $controllerClass::$action");
            http_response_code(404);
            $this->render404();
            return;
        }

        call_user_func_array([$controllerInstance, $action], $params);
    }

    private function matchParameterized(string $method, string $path): ?array {
        $routes = $this->routes[$method] ?? [];
        foreach ($routes as $pattern => $route) {
            if (!str_contains($pattern, '{')) continue;

            $regex = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $path, $matches)) {
                array_shift($matches);
                $route['params'] = $matches;
                return $route;
            }
        }
        return null;
    }

    private function render404(string $message = 'Halaman tidak ditemukan.'): void {

        $viewPath = APP_ROOT . '/views/errors/404.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>404</title></head><body>';
            echo '<h1>404 - ' . e($message) . '</h1>';
            echo '<p><a href="' . BASE_URL . '">Kembali ke Beranda</a></p>';
            echo '</body></html>';
        }
    }
}
