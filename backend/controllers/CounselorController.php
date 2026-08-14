<?php
require_once MODEL_PATH . '/Counselor.php';
require_once MODEL_PATH . '/Student.php';
require_once MODEL_PATH . '/Session.php';
require_once MODEL_PATH . '/Inquiry.php';
require_once MODEL_PATH . '/Document.php';

class CounselorController extends Controller {
    private $counselorModel;
    private $studentModel;
    private $sessionModel;
    private $inquiryModel;
    private $documentModel;

    public function __construct() {
        parent::__construct();
        $this->counselorModel = new Counselor();
        $this->studentModel = new Student();
        $this->sessionModel = new Session();
        $this->inquiryModel = new Inquiry();
        $this->documentModel = new Document();
    }

    public function index() {
        $filters = [
            'search' => $_GET['search'] ?? '',
            'specialization' => $_GET['specialization'] ?? '',
            'status' => $_GET['status'] ?? '',
            'limit' => 10,
            'offset' => max(0, ((int)($_GET['page'] ?? 1) - 1) * 10),
        ];

        $counselors = $this->counselorModel->getAll($filters);
        $total = $this->counselorModel->count($filters);

        $this->view('admin/counselors/index', [
            'pageTitle' => 'Counselors Management',
            'pageDescription' => 'Manage educational counselors, their specializations, and availability.',
            'currentPage' => 'counselors',
            'counselors' => $counselors,
            'total' => $total,
            'filters' => $filters,
        ]);
    }

    public function create() {
        $this->view('admin/counselors/create', [
            'pageTitle' => 'Add Counselor',
            'pageDescription' => 'Register a new counselor.',
            'currentPage' => 'counselors',
        ]);
    }

    public function store() {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/counselors'));
        }

        $data = $this->sanitize($this->getInput());
        $this->counselorModel->create($data);

        flash('success', 'Counselor added successfully.');
        $this->redirect(url('/admin/counselors'));
    }

    public function show($id) {
        $counselor = $this->counselorModel->getById($id);
        if (!$counselor) {
            $this->redirect(url('/admin/counselors'));
        }

        $this->view('admin/counselors/show', [
            'pageTitle' => 'Counselor Details',
            'pageDescription' => 'View counselor profile.',
            'currentPage' => 'counselors',
            'counselor' => $counselor,
        ]);
    }

    public function edit($id) {
        $counselor = $this->counselorModel->getById($id);
        if (!$counselor) {
            $this->redirect(url('/admin/counselors'));
        }

        $this->view('admin/counselors/edit', [
            'pageTitle' => 'Edit Counselor',
            'pageDescription' => 'Update counselor profile.',
            'currentPage' => 'counselors',
            'counselor' => $counselor,
        ]);
    }

    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/counselors'));
        }

        $data = $this->sanitize($this->getInput());
        $this->counselorModel->update($id, $data);

        flash('success', 'Counselor updated successfully.');
        $this->redirect(url('/admin/counselors/' . $id));
    }

    public function destroy($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/counselors'));
        }

        $this->counselorModel->delete($id);
        flash('success', 'Counselor deleted successfully.');
        $this->redirect(url('/admin/counselors'));
    }

    private function currentCounselor() {
        $email = $_SESSION['user']['email'] ?? '';
        return $email !== '' ? $this->counselorModel->getByEmail($email) : null;
    }

    public function documents() {
        $counselor = $this->currentCounselor();
        $documents = $counselor ? $this->documentModel->getByCounselorId($counselor['id']) : [];

        $this->view('counselor/documents', [
            'pageTitle' => 'Student Documents',
            'pageDescription' => 'Documents for your assigned students.',
            'currentPage' => 'documents',
            'counselor' => $counselor,
            'documents' => $documents,
            'students' => $counselor ? $this->studentModel->getAll(['counselor_id' => $counselor['id']]) : [],
        ]);
    }

    public function assignCreate() {
        $counselor = $this->currentCounselor();
        if (!$counselor) {
            $this->redirect(url('/counselor/documents'));
        }

        $students = $this->studentModel->getAll(['counselor_id' => $counselor['id']]);

        $this->view('counselor/documents-assign', [
            'pageTitle' => 'Assign Required Document',
            'pageDescription' => 'Require a document from one of your students.',
            'currentPage' => 'documents',
            'students' => $students,
            'preselectedStudent' => (int)($_GET['student_id'] ?? 0),
        ]);
    }

    public function assignStore() {
        if (!$this->isPost()) {
            $this->redirect(url('/counselor/documents'));
        }

        $counselor = $this->currentCounselor();
        if (!$counselor) {
            $this->redirect(url('/counselor/documents'));
        }

        $data = $this->sanitize($this->getInput());

        if (empty($data['student_id']) || empty($data['name']) || !in_array($data['category'], ['education', 'visa'], true)) {
            flash('error', 'Please select a student, a document name, and a category.');
            $this->redirect(url('/counselor/documents/assign'));
        }

        $this->documentModel->assign([
            'student_id' => $data['student_id'],
            'name' => $data['name'],
            'category' => $data['category'],
            'assigned_by' => $_SESSION['user_id'] ?? null,
        ]);

        flash('success', 'Required document assigned to the student.');
        $this->redirect(url('/counselor/documents'));
    }

    public function dashboard() {
        $user = $_SESSION['user'] ?? null;
        $email = $user['email'] ?? '';

        $counselor = $email !== '' ? $this->counselorModel->getByEmail($email) : null;

        $students = $counselor ? $this->studentModel->getAll(['counselor_id' => $counselor['id']]) : [];
        $sessions = $counselor ? $this->sessionModel->getByCounselorId($counselor['id']) : [];
        $inquiries = $counselor ? $this->inquiryModel->getByCounselorId($counselor['id']) : [];

        $pendingInquiries = array_filter($inquiries, function ($i) {
            return in_array($i['status'] ?? '', ['new', 'assigned', 'in-progress']);
        });

        $stats = [
            ['label' => 'Assigned Students', 'value' => count($students), 'icon' => 'students'],
            ['label' => 'Sessions', 'value' => count($sessions), 'icon' => 'sessions'],
            ['label' => 'Pending Inquiries', 'value' => count($pendingInquiries), 'icon' => 'inquiries'],
            ['label' => 'Total Inquiries', 'value' => count($inquiries), 'icon' => 'inquiries'],
        ];

        $this->view('counselor/dashboard', [
            'pageTitle' => 'Counselor Dashboard',
            'pageDescription' => 'Your counseling overview.',
            'currentPage' => 'dashboard',
            'counselor' => $counselor,
            'students' => $students,
            'sessions' => $sessions,
            'inquiries' => $inquiries,
            'stats' => $stats,
        ]);
    }
}