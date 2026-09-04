<?php
require_once MODEL_PATH . '/User.php';

class UserController extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

    public function index() {
        $filters = [
            'search' => $_GET['search'] ?? '',
            'role' => $_GET['role'] ?? '',
            'status' => $_GET['status'] ?? '',
            'limit' => 10,
            'offset' => max(0, ((int)($_GET['page'] ?? 1) - 1) * 10),
        ];

        $users = $this->userModel->getAll($filters);
        $total = $this->userModel->count($filters);
        $stats = $this->userModel->getStats();

        $this->view('admin/users/index', [
            'pageTitle' => 'User Management',
            'pageDescription' => 'Manage all system users across roles.',
            'currentPage' => 'users',
            'users' => $users,
            'total' => $total,
            'stats' => $stats,
            'filters' => $filters,
        ]);
    }

    public function create() {
        $this->view('admin/users/create', [
            'pageTitle' => 'Add User',
            'pageDescription' => 'Create a new user account.',
            'currentPage' => 'users',
        ]);
    }

    public function store() {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/users'));
        }

        $data = $this->sanitize($this->getInput());

        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'Full name is required.';
        }
        if (empty($data['email'])) {
            $errors['email'] = 'Email address is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }
        $allowedRoles = ['admin', 'counselor', 'student'];
        if (empty($data['role']) || !in_array($data['role'], $allowedRoles, true)) {
            $errors['role'] = 'Please select a valid role.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $_SESSION['old'] = $data;
            $this->redirect(url('/admin/users/create'));
        }

        $this->userModel->create($data);

        flash('success', 'User created successfully.');
        $this->redirect(url('/admin/users'));
    }

    public function show($id) {
        $user = $this->userModel->getById($id);
        if (!$user) {
            $this->redirect(url('/admin/users'));
        }

        $this->view('admin/users/show', [
            'pageTitle' => 'User Details',
            'pageDescription' => 'View user profile.',
            'currentPage' => 'users',
            'user' => $user,
        ]);
    }

    public function edit($id) {
        $user = $this->userModel->getById($id);
        if (!$user) {
            $this->redirect(url('/admin/users'));
        }

        $this->view('admin/users/edit', [
            'pageTitle' => 'Edit User',
            'pageDescription' => 'Update user account.',
            'currentPage' => 'users',
            'user' => $user,
        ]);
    }

    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/users'));
        }

        $data = $this->sanitize($this->getInput());

        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'Full name is required.';
        }
        if (empty($data['email'])) {
            $errors['email'] = 'Email address is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }
        $allowedRoles = ['admin', 'counselor', 'student'];
        if (!empty($data['role']) && !in_array($data['role'], $allowedRoles, true)) {
            $errors['role'] = 'Please select a valid role.';
        }
        $allowedStatuses = ['active', 'inactive'];
        if (!empty($data['status']) && !in_array($data['status'], $allowedStatuses, true)) {
            $errors['status'] = 'Please select a valid status.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $_SESSION['old'] = $data;
            $this->redirect(url('/admin/users/' . $id . '/edit'));
        }

        $this->userModel->update($id, $data);

        flash('success', 'User updated successfully.');
        $this->redirect(url('/admin/users/' . $id));
    }

    public function destroy($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/users'));
        }

        $this->userModel->delete($id);
        flash('success', 'User deleted successfully.');
        $this->redirect(url('/admin/users'));
    }
}