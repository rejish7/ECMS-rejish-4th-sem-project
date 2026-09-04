<?php
require_once MODEL_PATH . '/Student.php';
require_once MODEL_PATH . '/Counselor.php';
require_once MODEL_PATH . '/Session.php';
require_once MODEL_PATH . '/User.php';

class DashboardController extends Controller {
    public function index() {
        $studentModel = new Student();
        $counselorModel = new Counselor();
        $sessionModel = new Session();
        $userModel = new User();

        $stats = [
            ['label' => 'Total Students', 'value' => number_format($studentModel->count()), 'icon' => 'students', 'color' => '#0054cb', 'link' => url('/admin/students')],
            ['label' => 'Total Counselors', 'value' => number_format($counselorModel->count()), 'icon' => 'counselors', 'color' => '#0054cb', 'link' => url('/admin/counselors')],
            ['label' => 'Counseling Sessions', 'value' => number_format($sessionModel->count()), 'icon' => 'sessions', 'color' => '#6b7280', 'link' => url('/admin/sessions')],
        ];

        $recentUsers = $userModel->getAll(['limit' => 5]);
        $recentSessions = $sessionModel->getAll(['limit' => 5]);

        $this->view('admin/dashboard', [
            'pageTitle' => 'Dashboard',
            'pageDescription' => 'Admin dashboard overview',
            'currentPage' => 'dashboard',
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentSessions' => $recentSessions,
        ]);
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect(url('/login'));
        }

        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            $this->redirect(url('/login'));
        }

        $this->view('admin/profile', [
            'pageTitle' => 'My Profile',
            'pageDescription' => 'View and edit your profile.',
            'currentPage' => 'profile',
            'user' => $user,
        ]);
    }

    public function updateProfile() {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/profile'));
        }

        if (!isset($_SESSION['user_id'])) {
            $this->redirect(url('/login'));
        }

        $input = $_POST;
        $password = $input['password'] ?? '';
        $currentPassword = $input['current_password'] ?? '';

        $errors = [];
        if (!empty($password)) {
            if (empty($currentPassword)) {
                $errors['current_password'] = 'Please enter your current password.';
            }
            if (strlen($password) < 8) {
                $errors['password'] = 'Password must be at least 8 characters.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $input;
            flash('error', 'Please fix the errors below.');
            $this->redirect(url('/admin/profile'));
        }

        if (!empty($password)) {
            $result = changeUserPassword($_SESSION['user_id'], $currentPassword, $password);
            if ($result['ok']) {
                flash('success', 'Password updated successfully.');
            } else {
                flash('error', $result['error']);
            }
        } else {
            flash('error', 'Please enter a new password.');
        }

        $this->redirect(url('/admin/profile'));
    }

    public function uploadAvatar() {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/profile'));
        }

        if (!isset($_SESSION['user_id'])) {
            $this->redirect(url('/login'));
        }

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            flash('error', 'Please choose an image to upload.');
            $this->redirect(url('/admin/profile'));
        }

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedTypes, true)) {
            flash('error', 'Only JPG, PNG, GIF, and WebP images are allowed.');
            $this->redirect(url('/admin/profile'));
        }

        if ($_FILES['avatar']['size'] > 5 * 1024 * 1024) {
            flash('error', 'Image is too large. Maximum size is 5 MB.');
            $this->redirect(url('/admin/profile'));
        }

        $uploadDir = BASE_PATH . '/uploads/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!empty($_SESSION['user']['avatar'])) {
            $oldPath = BASE_PATH . $_SESSION['user']['avatar'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $fileName = 'avatar_admin_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $filePath)) {
            flash('error', 'Failed to upload image. Please try again.');
            $this->redirect(url('/admin/profile'));
        }

        $avatarUrl = '/uploads/profiles/' . $fileName;
        $db = getDB();
        $stmt = $db->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        $stmt->execute([$avatarUrl, $_SESSION['user_id']]);
        $_SESSION['user']['avatar'] = $avatarUrl;

        flash('success', 'Profile picture updated successfully.');
        $this->redirect(url('/admin/profile'));
    }
}