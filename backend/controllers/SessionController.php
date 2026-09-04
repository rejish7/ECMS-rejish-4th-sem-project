<?php
require_once MODEL_PATH . '/Session.php';
require_once MODEL_PATH . '/Student.php';
require_once MODEL_PATH . '/Counselor.php';

class SessionController extends Controller {
    private $sessionModel;
    private $studentModel;
    private $counselorModel;

    public function __construct() {
        parent::__construct();
        $this->sessionModel = new Session();
        $this->studentModel = new Student();
        $this->counselorModel = new Counselor();
    }

    public function index() {
        $filters = [
            'search' => $_GET['search'] ?? '',
            'status' => $_GET['status'] ?? '',
            'counselor_id' => $_GET['counselor_id'] ?? '',
            'limit' => 10,
            'offset' => max(0, ((int)($_GET['page'] ?? 1) - 1) * 10),
        ];

        $sessions = $this->sessionModel->getAll($filters);
        $total = $this->sessionModel->count($filters);
        $stats = $this->sessionModel->getStats();
        $counselors = $this->counselorModel->getAvailable();

        $this->view('admin/sessions/index', [
            'pageTitle' => 'Counseling Sessions',
            'pageDescription' => 'Manage and track counseling sessions.',
            'currentPage' => 'sessions',
            'sessions' => $sessions,
            'total' => $total,
            'stats' => $stats,
            'counselors' => $counselors,
            'filters' => $filters,
        ]);
    }

    public function create() {
        $students = $this->studentModel->getAll();
        $counselors = $this->counselorModel->getAvailable();

        $this->view('admin/sessions/create', [
            'pageTitle' => 'Schedule Session',
            'pageDescription' => 'Schedule a new counseling session.',
            'currentPage' => 'sessions',
            'students' => $students,
            'counselors' => $counselors,
        ]);
    }

    public function store() {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/sessions'));
        }

        $data = $this->sanitize($this->getInput());

        $errors = [];
        if (empty($data['student_id'])) {
            $errors['student_id'] = 'Student is required.';
        }
        if (empty($data['counselor_id'])) {
            $errors['counselor_id'] = 'Counselor is required.';
        }
        if (empty($data['datetime'])) {
            $errors['datetime'] = 'Date & Time is required.';
        }
        if (!in_array($data['mode'] ?? '', ['In-Person', 'Video Call'], true)) {
            $errors['mode'] = 'Please select a valid session mode.';
        }
        if (empty($data['subject'])) {
            $errors['subject'] = 'Subject / Purpose is required.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            flash('error', 'Please fix the errors below.');
            $this->redirect(url('/admin/sessions/create'));
        }

        if (empty($data['session_id'])) {
            $data['session_id'] = 'SES-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        if (!in_array($data['mode'] ?? '', ['In-Person', 'Video Call'], true)) {
            $data['mode'] = 'In-Person';
        }

        $this->sessionModel->create($data);

        $student = $this->studentModel->getById($data['student_id']);
        $counselor = $this->counselorModel->getById($data['counselor_id']);
        if ($student) {
            createNotificationByEmail($student['email'], 'New Session Scheduled', 'A counseling session has been scheduled for you on ' . date('M d, Y g:i A', strtotime($data['datetime'])) . '.', '/student/sessions');
        }
        if ($counselor) {
            createNotificationByEmail($counselor['email'], 'New Session Assigned', 'A counseling session has been assigned to you on ' . date('M d, Y g:i A', strtotime($data['datetime'])) . '.', '/counselor/sessions');
        }

        flash('success', 'Session scheduled successfully.');
        $this->redirect(url('/admin/sessions'));
    }

    public function show($id) {
        $session = $this->sessionModel->getById($id);
        if (!$session) {
            $this->redirect(url('/admin/sessions'));
        }

        $this->view('admin/sessions/show', [
            'pageTitle' => 'Session Details',
            'pageDescription' => 'View session details.',
            'currentPage' => 'sessions',
            'session' => $session,
        ]);
    }

    public function edit($id) {
        $session = $this->sessionModel->getById($id);
        if (!$session) {
            $this->redirect(url('/admin/sessions'));
        }

        $students = $this->studentModel->getAll();
        $counselors = $this->counselorModel->getAvailable();

        $this->view('admin/sessions/edit', [
            'pageTitle' => 'Edit Session',
            'pageDescription' => 'Update session details.',
            'currentPage' => 'sessions',
            'session' => $session,
            'students' => $students,
            'counselors' => $counselors,
        ]);
    }

    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/sessions'));
        }

        $data = $this->sanitize($this->getInput());

        $errors = [];
        if (!empty($data['mode']) && !in_array($data['mode'], ['In-Person', 'Video Call'], true)) {
            $errors['mode'] = 'Please select a valid session mode.';
        }
        if (!empty($data['status'])) {
            $allowedStatuses = ['scheduled', 'completed', 'cancelled', 'in-progress'];
            if (!in_array($data['status'], $allowedStatuses, true)) {
                $errors['status'] = 'Please select a valid status.';
            }
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $this->redirect(url('/admin/sessions/' . $id . '/edit'));
        }

        $this->sessionModel->update($id, $data);

        flash('success', 'Session updated successfully.');
        $this->redirect(url('/admin/sessions/' . $id));
    }

    public function destroy($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/sessions'));
        }

        $this->sessionModel->delete($id);
        flash('success', 'Session deleted successfully.');
        $this->redirect(url('/admin/sessions'));
    }
}