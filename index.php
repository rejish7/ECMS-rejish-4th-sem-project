<?php
session_start();

define('BASE_PATH', __DIR__);
define('BACKEND_PATH', BASE_PATH . '/backend');
define('FRONTEND_PATH', BASE_PATH . '/frontend');
define('VIEW_PATH', BACKEND_PATH . '/views');
define('CONTROLLER_PATH', BACKEND_PATH . '/controllers');
define('MODEL_PATH', BACKEND_PATH . '/models');
define('LIBRARY_PATH', BACKEND_PATH . '/libraries');

require_once BACKEND_PATH . '/config/constants.php';
require_once BACKEND_PATH . '/core/Router.php';
require_once BACKEND_PATH . '/core/Controller.php';
require_once BACKEND_PATH . '/core/helpers.php';
require_once BACKEND_PATH . '/config/database.php';
require_once LIBRARY_PATH . '/Mailer.php';

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
    if (isLoggedIn()) {
        redirectToDashboard();
    }
    require VIEW_PATH . '/auth/login.php';
});

$router->post('/login', function() {
    if (!verify_csrf()) {
        $_SESSION['error'] = 'Invalid session token. Please try again.';
        redirect(url('/login'));
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = !empty($_POST['remember']);

    $errors = [];
    if ($email === '') {
        $errors[] = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if ($errors) {
        $_SESSION['error'] = implode(' ', $errors);
        $_SESSION['old'] = ['email' => $email];
        redirect(url('/login'));
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = 'Invalid email or password.';
        redirect(url('/login'));
    }

    if ($user['status'] !== 'active') {
        $_SESSION['error'] = 'Your account has been deactivated. Please contact an administrator.';
        redirect(url('/login'));
    }

    session_regenerate_id(true);

    $authUser = $user;
    unset($authUser['password']);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user'] = $authUser;
    unset($_SESSION['old']);

    if ($remember) {
        setRememberCookie($user);
    }

    redirectToDashboard($user['role']);
});

$router->get('/logout', function() {
    clearRememberCookie();
    session_destroy();
    redirect(url('/login'));
});

$router->get('/register', function() {
    unset($_SESSION['errors']);
    require VIEW_PATH . '/auth/student-registration.php';
});

$router->post('/register', function() {
    if (!verify_csrf()) {
        $_SESSION['error'] = 'Invalid session token. Please try again.';
        redirect(url('/register'));
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $education_level = $_POST['education_level'] ?? '';
    $qualification = $_POST['qualification'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirmation = $_POST['password_confirmation'] ?? '';

    $errors = [];
    if ($name === '') {
        $errors['name'] = 'Please enter your full name.';
    }
    if ($email === '') {
        $errors['email'] = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if ($phone !== '' && !preg_match('/^\d{10}$/', $phone)) {
        $errors['phone'] = 'Phone number must be exactly 10 digits.';
    }
    $allowedLevels = ['High School', 'Undergraduate', 'Postgraduate'];
    if ($education_level === '' || !in_array($education_level, $allowedLevels, true)) {
        $errors['education_level'] = 'Please select a desired study level.';
    }
    if ($password === '') {
        $errors['password'] = 'Please choose a password.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    } elseif ($password !== $password_confirmation) {
        $errors['password'] = 'The password confirmation does not match.';
    }

    if ($errors) {
        $_SESSION['error'] = 'Please correct the highlighted fields below.';
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'education_level' => $education_level,
            'qualification' => $qualification,
            'destination' => $destination,
        ];
        redirect(url('/register'));
    }

    $db = getDB();

    foreach (['students' => 'email', 'users' => 'email'] as $table => $column) {
        $stmt = $db->prepare("SELECT id FROM {$table} WHERE {$column} = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = 'An account with this email already exists.';
            $_SESSION['errors'] = ['email' => 'An account with this email already exists.'];
            $_SESSION['old'] = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'education_level' => $education_level,
                'qualification' => $qualification,
                'destination' => $destination,
            ];
            redirect(url('/register'));
        }
    }

    $student_id = 'STU-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

    $db->prepare(
        "INSERT INTO students (student_id, name, email, education_level, status, created_at)
         VALUES (?, ?, ?, ?, 'active', NOW())"
    )->execute([$student_id, $name, $email, $education_level]);

    $db->prepare(
        "INSERT INTO users (user_id, name, email, password, role, status, created_at)
         VALUES (?, ?, ?, ?, 'student', 'active', NOW())"
    )->execute([$student_id, $name, $email, password_hash($password, PASSWORD_DEFAULT)]);

    unset($_SESSION['old']);
    unset($_SESSION['errors']);
    $_SESSION['success'] = 'Account created successfully! Your Student ID is ' . $student_id . '. You can now sign in.';
    redirect(url('/login'));
});

// Dashboard
$router->get('/admin/dashboard', ['DashboardController', 'index']);

// Student portal
$router->get('/student/dashboard', ['StudentController', 'dashboard']);
$router->get('/student/sessions', ['StudentController', 'sessions']);
$router->get('/student/documents', ['StudentController', 'documents']);
$router->post('/student/documents/{id}/submit', ['StudentController', 'submitDocument']);
$router->get('/student/inquiries', ['StudentController', 'inquiries']);
$router->post('/student/inquiries/store', ['StudentController', 'storeInquiry']);
$router->get('/student/profile', ['StudentController', 'profile']);
$router->post('/student/profile/update', ['StudentController', 'updateProfile']);
$router->post('/student/profile/password', ['StudentController', 'updatePassword']);

// Counselor portal
$router->get('/counselor/dashboard', ['CounselorController', 'dashboard']);
$router->get('/counselor/students', ['CounselorController', 'students']);
$router->get('/counselor/students/{id}', ['CounselorController', 'studentShow']);
$router->get('/counselor/sessions', ['CounselorController', 'sessions']);
$router->get('/counselor/sessions/create', ['CounselorController', 'sessionCreate']);
$router->post('/counselor/sessions/store', ['CounselorController', 'sessionStore']);
$router->post('/counselor/sessions/{id}/status', ['CounselorController', 'sessionStatus']);
$router->get('/counselor/documents', ['CounselorController', 'documents']);
$router->get('/counselor/documents/assign', ['CounselorController', 'assignCreate']);
$router->post('/counselor/documents/assign/store', ['CounselorController', 'assignStore']);
$router->post('/counselor/documents/{id}/review', ['CounselorController', 'documentReview']);
$router->get('/counselor/inquiries', ['CounselorController', 'inquiries']);
$router->get('/counselor/profile', ['CounselorController', 'profile']);
$router->post('/counselor/profile/password', ['CounselorController', 'updatePassword']);

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
$router->get('/admin/documents/student/{student_id}', ['DocumentController', 'studentDocs']);
$router->get('/admin/documents/create', ['DocumentController', 'create']);
$router->post('/admin/documents/store', ['DocumentController', 'store']);
$router->get('/admin/documents/assign', ['DocumentController', 'assignCreate']);
$router->post('/admin/documents/assign/store', ['DocumentController', 'assignStore']);
$router->get('/admin/documents/{id}', ['DocumentController', 'show']);
$router->get('/admin/documents/{id}/edit', ['DocumentController', 'edit']);
$router->post('/admin/documents/{id}/update', ['DocumentController', 'update']);
$router->post('/admin/documents/{id}/delete', ['DocumentController', 'destroy']);

// Inquiries
$router->get('/admin/inquiries', ['InquiryController', 'index']);
$router->get('/admin/inquiries/{id}', ['InquiryController', 'show']);
$router->post('/admin/inquiries/{id}/assign', ['InquiryController', 'assign']);
$router->post('/admin/inquiries/{id}/auto-assign', ['InquiryController', 'autoAssign']);
$router->post('/admin/inquiries/{id}/close', ['InquiryController', 'close']);
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
