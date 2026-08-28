<?php
class Router {
    private $routes = [];
    private $notFoundHandler;

    public function get($path, $handler) {
        $this->routes['GET'][$path] = $handler;
        return $this;
    }

    public function post($path, $handler) {
        $this->routes['POST'][$path] = $handler;
        return $this;
    }

    public function any($path, $handler) {
        $this->routes['GET'][$path] = $handler;
        $this->routes['POST'][$path] = $handler;
        return $this;
    }

    public function notFound($handler) {
        $this->notFoundHandler = $handler;
        return $this;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Strip the base path (e.g., /ECMS(rejish))
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $scriptDir = rtrim(dirname($scriptName), '/');
        
        if ($scriptDir && $scriptDir !== '.' && strpos($uri, $scriptDir) === 0) {
            $uri = substr($uri, strlen($scriptDir));
        }
        
        $uri = rtrim($uri, '/') ?: '/';

        // CSRF protection for all POST requests (except login/register which handle it inline)
        if ($method === 'POST' && !in_array($uri, ['/login', '/register'], true)) {
            if (!verify_csrf()) {
                flash('error', 'Invalid session token. Please try again.');
                redirect(url('/login'));
            }
        }

        // Protect authenticated areas (admin/counselor/student dashboards)
        if (preg_match('#^/(admin|counselor|student)(/|$)#', $uri)) {
            if (!isLoggedIn()) {
                redirect(url('/login'));
            }
        }

        // Consolidated role-based access control
        $roleMap = ['admin' => 'admin', 'counselor' => 'counselor', 'student' => 'student'];
        foreach ($roleMap as $prefix => $requiredRole) {
            if (preg_match('#^/' . $prefix . '(/|$)#', $uri)) {
                $user = getUser();
                if (!$user || $user['role'] !== $requiredRole) {
                    flash('error', 'You do not have permission to access that page.');
                    redirect(url('/login'));
                }
                break;
            }
        }

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $handler) {
                $pattern = $this->convertToRegex($route);
                if (preg_match($pattern, $uri, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    return $this->callHandler($handler, $params);
                }
            }
        }

        if ($this->notFoundHandler) {
            return $this->callHandler($this->notFoundHandler, []);
        }

        http_response_code(404);
        require VIEW_PATH . '/errors/404.php';
    }

    private function convertToRegex($route) {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $route);
        return '#^' . $pattern . '$#';
    }

    private function callHandler($handler, $params) {
        if (is_array($handler)) {
            $controller = new $handler[0]();
            $method = $handler[1];
            return call_user_func_array([$controller, $method], $params);
        }

        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }

        if (is_string($handler)) {
            return $this->loadView($handler, $params);
        }
    }

    private function loadView($viewPath, $params = []) {
        extract($params);
        $fullPath = VIEW_PATH . '/' . $viewPath . '.php';
        
        if (file_exists($fullPath)) {
            ob_start();
            require $fullPath;
            return ob_get_clean();
        }

        http_response_code(404);
        echo "View not found: {$viewPath}";
    }
}
