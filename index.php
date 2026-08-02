<?php
session_start();

define('BASE_PATH', __DIR__);
define('BACKEND_PATH', BASE_PATH . '/backend');
define('FRONTEND_PATH', BASE_PATH . '/frontend');
define('VIEW_PATH', BACKEND_PATH . '/views');
define('CONTROLLER_PATH', BACKEND_PATH . '/controllers');
define('MODEL_PATH', BACKEND_PATH . '/models');
define('LIBRARY_PATH', BACKEND_PATH . '/libraries');

require_once BACKEND_PATH . '/core/Router.php';
require_once BACKEND_PATH . '/core/Controller.php';
require_once BACKEND_PATH . '/core/helpers.php';
require_once BACKEND_PATH . '/config/database.php';

$router = new Router();

$router->get('/', function() {
    redirect(url('/login'));
});

$router->get('/login', function() {
    require VIEW_PATH . '/auth/login.php';
});

$router->post('/login', function() {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->execute([$email, md5($password)]);
    $user = $stmt->fetch();
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user;
        
        switch ($user['role']) {
            case 'admin':
                redirect(url('/admin/dashboard'));
                break;
            // TODO: Create counselor dashboard view
            // case 'counselor':
            //     redirect(url('/counselor/dashboard'));
            //     break;
            // TODO: Create student dashboard view
            // case 'student':
            //     redirect(url('/student/dashboard'));
            //     break;
            default:
                redirect(url('/admin/dashboard'));
        }
    } else {
        $_SESSION['error'] = 'Invalid email or password';
        redirect(url('/login'));
    }
});

$router->get('/logout', function() {
    session_destroy();
    redirect(url('/login'));
});

$router->get('/register', function() {
    require VIEW_PATH . '/auth/student-registration.php';
});

$router->get('/admin/dashboard', function() {
    require VIEW_PATH . '/admin/dashboard.php';
});

$router->get('/admin/users', function() {
    require VIEW_PATH . '/admin/user-management.php';
});

// TODO: Create these views
// $router->get('/counselor/dashboard', function() {
//     require VIEW_PATH . '/counselor/dashboard.php';
// });

// $router->get('/student/dashboard', function() {
//     require VIEW_PATH . '/student/dashboard.php';
// });

$router->notFound(function() {
    http_response_code(404);
    require VIEW_PATH . '/errors/404.php';
});

$router->dispatch();
