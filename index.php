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
require_once MODEL_PATH . '/Notification.php';
require_once MODEL_PATH . '/PasswordReset.php';

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

// Forgot Password - Show form
$router->get('/forgot-password', function() {
    require VIEW_PATH . '/auth/forgot-password.php';
});

// Forgot Password - Send reset link (for demo: show token on screen)
$router->post('/forgot-password', function() {
    if (!verify_csrf()) {
        $_SESSION['error'] = 'Invalid session token. Please try again.';
        redirect(url('/forgot-password'));
    }

    $email = trim($_POST['email'] ?? '');
    $errors = [];

    if ($email === '') {
        $errors['email'] = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if ($errors) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = ['email' => $email];
        redirect(url('/forgot-password'));
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        // Don't reveal if email exists - show same message
        $_SESSION['success'] = 'If an account with that email exists, a password reset link has been sent.';
        unset($_SESSION['errors'], $_SESSION['old']);
        redirect(url('/forgot-password'));
    }

    $passwordReset = new PasswordReset();
    $passwordReset->deleteByEmail($email);
    $token = $passwordReset->createToken($email);

    $_SESSION['success'] = 'If an account with that email exists, a password reset link has been sent.';
    unset($_SESSION['errors'], $_SESSION['old']);
    redirect(url('/reset-password?token=' . $token));
});

// Reset Password - Show form
$router->get('/reset-password', function() {
    $token = $_GET['token'] ?? '';
    require VIEW_PATH . '/auth/reset-password.php';
});

// Reset Password - Process new password
$router->post('/reset-password', function() {
    if (!verify_csrf()) {
        $_SESSION['error'] = 'Invalid session token. Please try again.';
        redirect(url('/login'));
    }

    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirmation'] ?? '';
    $errors = [];

    if ($token === '') {
        $errors['token'] = 'Invalid reset token.';
    }

    if ($password === '') {
        $errors['password'] = 'New password is required.';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters.';
    }

    if ($password !== $passwordConfirm) {
        $errors['password_confirmation'] = 'Passwords do not match.';
    }

    if ($errors) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = ['password' => '', 'password_confirmation' => ''];
        redirect(url('/reset-password?token=' . $token));
    }

    $passwordReset = new PasswordReset();
    $record = $passwordReset->getToken($token);

    if (!$record) {
        $_SESSION['error'] = 'Invalid or expired reset token.';
        redirect(url('/login'));
    }

    if ($passwordReset->isExpired($record['created_at'])) {
        $passwordReset->deleteToken($token);
        $_SESSION['error'] = 'Reset token has expired. Please request a new one.';
        redirect(url('/forgot-password'));
    }

    $passwordReset->updatePassword($record['email'], $password);
    $passwordReset->deleteToken($token);

    $_SESSION['success'] = 'Your password has been reset successfully. You can now log in.';
    unset($_SESSION['errors'], $_SESSION['old']);
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
$router->get('/student/catalog', ['StudentController', 'catalog']);
$router->post('/student/profile/update', ['StudentController', 'updateProfile']);
$router->post('/student/profile/password', ['StudentController', 'updatePassword']);
$router->post('/student/profile/avatar', ['StudentController', 'uploadAvatar']);

// Counselor portal
$router->get('/counselor/dashboard', ['CounselorController', 'dashboard']);
$router->get('/counselor/students', ['CounselorController', 'students']);
$router->get('/counselor/students/{id}', ['CounselorController', 'studentShow']);
$router->get('/counselor/sessions', ['CounselorController', 'sessions']);
$router->get('/counselor/sessions/create', ['CounselorController', 'sessionCreate']);
$router->post('/counselor/sessions/store', ['CounselorController', 'sessionStore']);
$router->post('/counselor/sessions/{id}/status', ['CounselorController', 'sessionStatus']);
$router->get('/counselor/documents', ['CounselorController', 'documents']);
$router->get('/counselor/documents/review-queue', ['CounselorController', 'reviewQueue']);
$router->get('/counselor/documents/assign', ['CounselorController', 'assignCreate']);
$router->post('/counselor/documents/assign/store', ['CounselorController', 'assignStore']);
$router->post('/counselor/documents/{id}/review', ['CounselorController', 'documentReview']);
$router->get('/counselor/inquiries', ['CounselorController', 'inquiries']);
$router->get('/counselor/profile', ['CounselorController', 'profile']);
$router->get('/counselor/catalog', ['CounselorController', 'catalog']);
$router->post('/counselor/profile/password', ['CounselorController', 'updatePassword']);
$router->post('/counselor/profile/avatar', ['CounselorController', 'uploadAvatar']);

// Profile
$router->get('/admin/profile', ['DashboardController', 'profile']);
$router->post('/admin/profile/update', ['DashboardController', 'updateProfile']);
$router->post('/admin/profile/avatar', ['DashboardController', 'uploadAvatar']);

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

// Sessions Calendar API
$router->get('/api/sessions', function() {
    if (!isLoggedIn()) {
        redirect(url('/login'));
    }
    $month = (int)($_GET['month'] ?? date('m'));
    $year = (int)($_GET['year'] ?? date('Y'));
    $db = getDB();
    $user = getUser();

    $sql = "SELECT s.id, s.session_id, s.datetime, s.status, s.mode,
                   st.name AS student_name, c.name AS counselor_name
            FROM sessions s
            LEFT JOIN students st ON s.student_id = st.id
            LEFT JOIN counselors c ON s.counselor_id = c.id
            WHERE MONTH(s.datetime) = ? AND YEAR(s.datetime) = ?";

    $params = [$month, $year];

    if ($user['role'] === 'counselor') {
        $counselorModel = new Counselor();
        $counselor = $counselorModel->getByEmail($user['email']);
        if ($counselor) {
            $sql .= " AND s.counselor_id = ?";
            $params[] = $counselor['id'];
        }
    } elseif ($user['role'] === 'student') {
        $studentModel = new Student();
        $student = $studentModel->getByEmail($user['email']);
        if ($student) {
            $sql .= " AND s.student_id = ?";
            $params[] = $student['id'];
        }
    }

    $sql .= " ORDER BY s.datetime ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $sessions = $stmt->fetchAll();

    header('Content-Type: application/json');
    echo json_encode($sessions);
    exit;
});

// Notifications API
$router->get('/notifications', function() {
    if (!isLoggedIn()) {
        redirect(url('/login'));
    }
    require_once MODEL_PATH . '/Notification.php';
    $model = new Notification();
    $userId = $_SESSION['user_id'];
    $notifications = $model->getByUserId($userId, 20);
    $unreadCount = $model->getUnreadCount($userId);
    header('Content-Type: application/json');
    echo json_encode(['notifications' => $notifications, 'unread_count' => $unreadCount]);
    exit;
});

$router->post('/notifications/read', function() {
    if (!isLoggedIn()) {
        redirect(url('/login'));
    }
    require_once MODEL_PATH . '/Notification.php';
    $model = new Notification();
    $userId = $_SESSION['user_id'];
    $id = $_POST['id'] ?? null;
    if ($id) {
        $model->markAsRead((int)$id, $userId);
    }
    header('Content-Type: application/json');
    echo json_encode(['ok' => true]);
    exit;
});

$router->post('/notifications/read-all', function() {
    if (!isLoggedIn()) {
        redirect(url('/login'));
    }
    require_once MODEL_PATH . '/Notification.php';
    $model = new Notification();
    $model->markAllAsRead($_SESSION['user_id']);
    header('Content-Type: application/json');
    echo json_encode(['ok' => true]);
    exit;
});

$router->notFound(function() {
    http_response_code(404);
    require VIEW_PATH . '/errors/404.php';
});

$router->dispatch();
