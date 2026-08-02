<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}

if (!defined('BACKEND_PATH')) {
    define('BACKEND_PATH', BASE_PATH . '/backend');
}

if (!defined('FRONTEND_PATH')) {
    define('FRONTEND_PATH', BASE_PATH . '/frontend');
}

if (!defined('VIEW_PATH')) {
    define('VIEW_PATH', BACKEND_PATH . '/views');
}

if (!defined('CONTROLLER_PATH')) {
    define('CONTROLLER_PATH', BACKEND_PATH . '/controllers');
}

if (!defined('MODEL_PATH')) {
    define('MODEL_PATH', BACKEND_PATH . '/models');
}

if (!defined('LIBRARY_PATH')) {
    define('LIBRARY_PATH', BACKEND_PATH . '/libraries');
}

if (!defined('APP_NAME')) {
    define('APP_NAME', 'ECMS');
}

if (!defined('APP_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    define('APP_URL', $protocol . '://' . $host . $path);
}
