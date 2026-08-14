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
        $this->sessionModel->create($data);

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