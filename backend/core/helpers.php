<?php
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function asset($path) {
    return url('/frontend/assets/' . ltrim($path, '/'));
}

function url($path = '') {
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    return $base . '/' . ltrim($path, '/');
}

function redirect($url) {
    header("Location: {$url}");
    exit;
}

function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function old($key, $default = '') {
    return $_SESSION['old'][$key] ?? $default;
}

function flash($key, $message = null) {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
    } else {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }
}

function dd($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;
}

function csrf_token() {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf() {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['_csrf'] ?? '', $_POST['csrf_token'])) {
        return false;
    }
    return true;
}

function setRememberCookie($user) {
    $signature = hash('sha256', $user['id'] . $user['password']);
    $value = json_encode(['uid' => $user['id'], 'sig' => $signature]);
    setcookie('ecms_remember', $value, time() + (30 * 24 * 60 * 60), '/');
}

function clearRememberCookie() {
    setcookie('ecms_remember', '', time() - 3600, '/');
}

function restoreRememberedUser() {
    if (empty($_COOKIE['ecms_remember'])) {
        return false;
    }

    $data = json_decode($_COOKIE['ecms_remember'], true);
    if (!is_array($data) || empty($data['uid']) || empty($data['sig'])) {
        return false;
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$data['uid']]);
    $user = $stmt->fetch();

    if (!$user || !hash_equals(hash('sha256', $user['id'] . $user['password']), $data['sig'])) {
        return false;
    }

    session_regenerate_id(true);
    $authUser = $user;
    unset($authUser['password']);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user'] = $authUser;
    return true;
}

function authenticate() {
    if (!isLoggedIn()) {
        redirect(url('/login'));
    }
}

function isLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        return true;
    }
    return restoreRememberedUser();
}

function getUser() {
    return $_SESSION['user'] ?? null;
}

function hasRole($role) {
    $user = getUser();
    return $user && $user['role'] === $role;
}

function requireRole($role) {
    authenticate();
    $user = getUser();
    if (!$user || $user['role'] !== $role) {
        $_SESSION['error'] = 'You do not have permission to access that page.';
        redirect(url('/login'));
    }
}

function dashboardPathFor($role = null) {
    $role = $role ?? (getUser()['role'] ?? 'admin');
    switch ($role) {
        case 'counselor':
            return '/counselor/dashboard';
        case 'student':
            return '/student/dashboard';
        case 'admin':
        default:
            return '/admin/dashboard';
    }
}

function redirectToDashboard($role = null) {
    redirect(url(dashboardPathFor($role)));
}
