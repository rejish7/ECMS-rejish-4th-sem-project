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

        $password = $_POST['password'] ?? '';
        if (!empty($password)) {
            $db = getDB();
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([md5($password), $_SESSION['user_id']]);
            flash('success', 'Password updated successfully.');
        } else {
            flash('error', 'Please enter a new password.');
        }

        $this->redirect(url('/admin/profile'));
    }
}