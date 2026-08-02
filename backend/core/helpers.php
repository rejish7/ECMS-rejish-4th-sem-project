<?php
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function asset($path) {
    return 'frontend/assets/' . ltrim($path, '/');
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

function authenticate() {
    if (!isset($_SESSION['user_id'])) {
        redirect(url('/login'));
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUser() {
    return $_SESSION['user'] ?? null;
}

function hasRole($role) {
    $user = getUser();
    return $user && $user['role'] === $role;
}
