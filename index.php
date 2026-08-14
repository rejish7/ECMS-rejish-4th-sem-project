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

// Load controllers
require_once CONTROLLER_PATH . '/DashboardController.php';
require_once CONTROLLER_PATH . '/StudentController.php';
require_once CONTROLLER_PATH . '/CounselorController.php';
require_once CONTROLLER_PATH . '/SessionController.php';
require_once CONTROLLER_PATH . '/DocumentController.php';
require_once CONTROLLER_PATH . '/UserController.php';
require_once CONTROLLER_PATH . '/InquiryController.php';
require_once CONTROLLER_PATH . '/CatalogController.php';

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

$router->post('/register', function() {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $education_level = $_POST['education_level'] ?? '';

    if (empty($name) || empty($email) || empty($education_level)) {
        $_SESSION['error'] = 'Please fill in all required fields.';
        redirect(url('/register'));
    }

    $db = getDB();

    $stmt = $db->prepare("SELECT id FROM students WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = 'An account with this email already exists.';
        redirect(url('/register'));
    }

    $student_id = 'STU-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    $password = md5('password123');

    $stmt = $db->prepare(
        "INSERT INTO students (student_id, name, email, education_level, status, created_at)
         VALUES (?, ?, ?, ?, 'active', NOW())"
    );
    $stmt->execute([$student_id, $name, $email, $education_level]);

    $_SESSION['success'] = 'Account created successfully! Your Student ID is ' . $student_id . '. You can now sign in.';
    redirect(url('/login'));
});

// Dashboard
$router->get('/admin/dashboard', ['DashboardController', 'index']);

// Profile
$router->get('/admin/profile', ['DashboardController', 'profile']);
$router->post('/admin/profile/update', ['DashboardController', 'updateProfile']);

// Students CRUD
$router->get('/admin/students', ['StudentController', 'index']);
$router->get('/admin/students/create', ['StudentController', 'create']);
$router->post('/admin/students/store', ['StudentController', 'store']);
$router->get('/admin/students/{id}', ['StudentController', 'show']);
$router->get('/admin/students/{id}/edit', ['StudentController', 'edit']);
$router->post('/admin/students/{id}/update', ['StudentController', 'update']);
$router->post('/admin/students/{id}/delete', ['StudentController', 'destroy']);

// Counselors CRUD
$router->get('/admin/counselors', ['CounselorController', 'index']);
$router->get('/admin/counselors/create', ['CounselorController', 'create']);
$router->post('/admin/counselors/store', ['CounselorController', 'store']);
$router->get('/admin/counselors/{id}', ['CounselorController', 'show']);
$router->get('/admin/counselors/{id}/edit', ['CounselorController', 'edit']);
$router->post('/admin/counselors/{id}/update', ['CounselorController', 'update']);
$router->post('/admin/counselors/{id}/delete', ['CounselorController', 'destroy']);

// Sessions CRUD
$router->get('/admin/sessions', ['SessionController', 'index']);
$router->get('/admin/sessions/create', ['SessionController', 'create']);
$router->post('/admin/sessions/store', ['SessionController', 'store']);
$router->get('/admin/sessions/{id}', ['SessionController', 'show']);
$router->get('/admin/sessions/{id}/edit', ['SessionController', 'edit']);
$router->post('/admin/sessions/{id}/update', ['SessionController', 'update']);
$router->post('/admin/sessions/{id}/delete', ['SessionController', 'destroy']);

// Documents
$router->get('/admin/documents', ['DocumentController', 'index']);
$router->get('/admin/documents/review-queue', ['DocumentController', 'reviewQueue']);
$router->post('/admin/documents/{id}/review', ['DocumentController', 'review']);
$router->get('/admin/documents/student/{id}', ['DocumentController', 'studentDocs']);
$router->get('/admin/documents/create', ['DocumentController', 'create']);
$router->post('/admin/documents/store', ['DocumentController', 'store']);
$router->get('/admin/documents/{id}', ['DocumentController', 'show']);
$router->get('/admin/documents/{id}/edit', ['DocumentController', 'edit']);
$router->post('/admin/documents/{id}/update', ['DocumentController', 'update']);
$router->post('/admin/documents/{id}/delete', ['DocumentController', 'destroy']);

// Inquiries
$router->get('/admin/inquiries', ['InquiryController', 'index']);
$router->get('/admin/inquiries/{id}', ['InquiryController', 'show']);
$router->post('/admin/inquiries/{id}/assign', ['InquiryController', 'assign']);
$router->post('/admin/inquiries/{id}/auto-assign', ['InquiryController', 'autoAssign']);
$router->post('/admin/inquiries/{id}/delete', ['InquiryController', 'destroy']);

// College & Course Catalog
$router->get('/admin/catalog', ['CatalogController', 'index']);
$router->get('/admin/catalog/college/create', ['CatalogController', 'collegeCreate']);
$router->post('/admin/catalog/college/store', ['CatalogController', 'collegeStore']);
$router->get('/admin/catalog/college/{id}/edit', ['CatalogController', 'collegeEdit']);
$router->post('/admin/catalog/college/{id}/update', ['CatalogController', 'collegeUpdate']);
$router->post('/admin/catalog/college/{id}/delete', ['CatalogController', 'collegeDelete']);
$router->get('/admin/catalog/course/create', ['CatalogController', 'courseCreate']);
$router->post('/admin/catalog/course/store', ['CatalogController', 'courseStore']);
$router->get('/admin/catalog/course/{id}/edit', ['CatalogController', 'courseEdit']);
$router->post('/admin/catalog/course/{id}/update', ['CatalogController', 'courseUpdate']);
$router->post('/admin/catalog/course/{id}/delete', ['CatalogController', 'courseDelete']);

// Users CRUD
$router->get('/admin/users', ['UserController', 'index']);
$router->get('/admin/users/create', ['UserController', 'create']);
$router->post('/admin/users/store', ['UserController', 'store']);
$router->get('/admin/users/{id}', ['UserController', 'show']);
$router->get('/admin/users/{id}/edit', ['UserController', 'edit']);
$router->post('/admin/users/{id}/update', ['UserController', 'update']);
$router->post('/admin/users/{id}/delete', ['UserController', 'destroy']);

$router->notFound(function() {
    http_response_code(404);
    require VIEW_PATH . '/errors/404.php';
});

$router->dispatch();
