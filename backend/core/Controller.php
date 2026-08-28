<?php
class Controller {
    protected $db;

    public function __construct() {
        $this->db = getDB();
    }

    protected function view($viewPath, $data = []) {
        extract($data);
        $viewFile = VIEW_PATH . '/' . $viewPath . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View not found: {$viewPath}");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if (isset($layout)) {
            $layoutFile = VIEW_PATH . '/layouts/' . $layout . '.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
            }
        } else {
            echo $content;
        }
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function verifyCsrfOrAbort() {
        if (!$this->isPost()) {
            return;
        }
        if (!verify_csrf()) {
            flash('error', 'Invalid session token. Please try again.');
            $this->redirect(url(dashboardPathFor()));
        }
    }

    protected function getInput() {
        $input = json_decode(file_get_contents('php://input'), true);
        return $input ?? $_POST;
    }

    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}
