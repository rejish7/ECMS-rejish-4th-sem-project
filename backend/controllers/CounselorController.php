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

        try {
            $id = $this->counselorModel->create($data);
        } catch (PDOException $e) {
            flash('error', 'Could not add counselor. Check that the email is unique.');
            $this->redirect(url('/admin/counselors'));
        }

        $password = $data['password'] ?? '';
        if (strlen($password) < 8) {
            $password = generateTempPassword();
        }

        $result = createLoginAccount('counselor', 'USR-CNS-' . str_pad((string)$id, 4, '0', STR_PAD_LEFT), $data['name'], $data['email'], $password);

        if ($result['created']) {
            $msg = 'Counselor added successfully. ';
            $msg .= $result['emailed']
                ? 'Login credentials emailed to ' . $data['email'] . '.'
                : 'A login account was created but the email could not be sent. Temporary password: ' . $result['password'];
        } else {
            $msg = 'Counselor added successfully, but a login account already exists for ' . $data['email'] . '.';
        }

        flash('success', $msg);
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
        $documents = $counselor ? $this->documentModel->getByCounselorId($counselor['id']) : [];

        $now = date('Y-m-d H:i:s');
        $upcomingSessions = array_filter($sessions, function ($s) use ($now) {
            return in_array($s['status'] ?? '', ['scheduled', 'in-progress'])
                && (($s['datetime'] ?? '') === '' || $s['datetime'] >= $now);
        });
        usort($upcomingSessions, function ($a, $b) {
            return strcmp($a['datetime'] ?? '', $b['datetime'] ?? '');
        });

        $pendingInquiries = array_filter($inquiries, function ($i) {
            return in_array($i['status'] ?? '', ['new', 'assigned', 'in-progress']);
        });

        $activeStudents = array_filter($students, function ($st) {
            return ($st['status'] ?? 'active') === 'active';
        });

        $pendingDocuments = array_filter($documents, function ($d) {
            return in_array($d['status'] ?? '', ['assigned', 'pending', 'resubmit']);
        });

        $stats = [
            ['label' => 'Assigned Students', 'value' => count($activeStudents), 'icon' => 'students', 'link' => '/counselor/dashboard#students'],
            ['label' => 'Upcoming Sessions', 'value' => count($upcomingSessions), 'icon' => 'appointments', 'link' => '/counselor/sessions'],
            ['label' => 'Pending Inquiries', 'value' => count($pendingInquiries), 'icon' => 'inquiries', 'link' => '/counselor/inquiries'],
            ['label' => 'Documents', 'value' => count($documents), 'icon' => 'documents', 'link' => '/counselor/documents'],
        ];

        $this->view('counselor/dashboard', [
            'pageTitle' => 'Counselor Dashboard',
            'pageDescription' => 'Your counseling overview.',
            'currentPage' => 'dashboard',
            'counselor' => $counselor,
            'students' => $students,
            'activeStudents' => count($activeStudents),
            'sessions' => $sessions,
            'upcomingSessions' => array_slice($upcomingSessions, 0, 5),
            'inquiries' => array_slice($inquiries, 0, 6),
            'pendingInquiries' => count($pendingInquiries),
            'documents' => array_slice($documents, 0, 5),
            'pendingDocuments' => count($pendingDocuments),
            'stats' => $stats,
        ]);
    }

    public function sessions() {
        $counselor = $this->currentCounselor();
        $sessions = $counselor ? $this->sessionModel->getByCounselorId($counselor['id']) : [];

        $upcoming = array_filter($sessions, function ($s) {
            return in_array($s['status'] ?? '', ['scheduled', 'in-progress']);
        });
        $completed = array_filter($sessions, function ($s) {
            return ($s['status'] ?? '') === 'completed';
        });

        $this->view('counselor/sessions', [
            'pageTitle' => 'My Sessions',
            'pageDescription' => 'Sessions with your assigned students.',
            'currentPage' => 'sessions',
            'counselor' => $counselor,
            'sessions' => $sessions,
            'upcoming' => $upcoming,
            'completed' => $completed,
        ]);
    }

    public function inquiries() {
        $counselor = $this->currentCounselor();
        $inquiries = $counselor ? $this->inquiryModel->getByCounselorId($counselor['id']) : [];

        $open = array_filter($inquiries, function ($i) {
            return in_array($i['status'] ?? '', ['new', 'assigned', 'in-progress']);
        });

        $this->view('counselor/inquiries', [
            'pageTitle' => 'My Inquiries',
            'pageDescription' => 'Inquiries assigned to you.',
            'currentPage' => 'inquiries',
            'counselor' => $counselor,
            'inquiries' => $inquiries,
            'open' => $open,
            'closed' => count($inquiries) - count($open),
        ]);
    }

    public function profile() {
        $counselor = $this->currentCounselor();

        $this->view('counselor/profile', [
            'pageTitle' => 'My Profile',
            'pageDescription' => 'View and update your profile.',
            'currentPage' => 'profile',
            'counselor' => $counselor,
        ]);
    }

    public function updatePassword() {
        if (!$this->isPost()) {
            $this->redirect(url('/counselor/profile'));
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $this->redirect(url('/login'));
        }

        $data = $this->getInput();
        $result = changeUserPassword($userId, $data['current_password'] ?? '', $data['password'] ?? '');

        if ($result['ok']) {
            flash('success', 'Password updated successfully.');
        } else {
            flash('error', $result['error']);
        }
        $this->redirect(url('/counselor/profile'));
    }

    public function students() {
        $counselor = $this->currentCounselor();
        $students = $counselor ? $this->studentModel->getAll(['counselor_id' => $counselor['id']]) : [];

        $this->view('counselor/students', [
            'pageTitle' => 'My Students',
            'pageDescription' => 'Students assigned to you.',
            'currentPage' => 'students',
            'counselor' => $counselor,
            'students' => $students,
        ]);
    }

    public function studentShow($id) {
        $counselor = $this->currentCounselor();
        if (!$counselor) {
            $this->redirect(url('/login'));
        }

        $student = $this->studentModel->getById($id);
        if (!$student || (int)$student['counselor_id'] !== (int)$counselor['id']) {
            flash('error', 'Student not found or not assigned to you.');
            $this->redirect(url('/counselor/students'));
        }

        $documents = $this->documentModel->getByStudentId($student['id']);
        $sessions = $this->sessionModel->getByStudentId($student['id']);
        $inquiries = $this->inquiryModel->getByStudentId($student['id']);

        $this->view('counselor/student-show', [
            'pageTitle' => 'Student Profile',
            'pageDescription' => 'Profile and records for ' . ($student['name'] ?? ''),
            'currentPage' => 'students',
            'counselor' => $counselor,
            'student' => $student,
            'documents' => $documents,
            'sessions' => $sessions,
            'inquiries' => $inquiries,
        ]);
    }

    public function sessionCreate() {
        $counselor = $this->currentCounselor();
        if (!$counselor) {
            $this->redirect(url('/login'));
        }

        $students = $this->studentModel->getAll(['counselor_id' => $counselor['id']]);

        $this->view('counselor/session-create', [
            'pageTitle' => 'Schedule Session',
            'pageDescription' => 'Schedule a new counseling session for one of your students.',
            'currentPage' => 'sessions',
            'counselor' => $counselor,
            'students' => $students,
            'preselectedStudent' => (int)($_GET['student_id'] ?? 0),
        ]);
    }

    public function sessionStore() {
        if (!$this->isPost()) {
            $this->redirect(url('/counselor/sessions'));
        }

        $counselor = $this->currentCounselor();
        if (!$counselor) {
            $this->redirect(url('/login'));
        }

        $data = $this->sanitize($this->getInput());

        if (empty($data['student_id']) || empty($data['datetime']) || !in_array($data['mode'] ?? '', ['In-Person', 'Video Call'], true)) {
            flash('error', 'Please select a student, mode, and date & time.');
            $this->redirect(url('/counselor/sessions/create'));
        }

        $student = $this->studentModel->getById($data['student_id']);
        if (!$student || (int)$student['counselor_id'] !== (int)$counselor['id']) {
            flash('error', 'Please select one of your assigned students.');
            $this->redirect(url('/counselor/sessions/create'));
        }

        $this->sessionModel->create([
            'session_id' => 'SES-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'student_id' => $data['student_id'],
            'counselor_id' => $counselor['id'],
            'mode' => $data['mode'],
            'datetime' => $data['datetime'],
            'status' => 'scheduled',
        ]);

        flash('success', 'Session scheduled successfully.');
        $this->redirect(url('/counselor/sessions'));
    }

    public function sessionStatus($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/counselor/sessions'));
        }

        $counselor = $this->currentCounselor();
        if (!$counselor) {
            $this->redirect(url('/login'));
        }

        $session = $this->sessionModel->getById($id);
        if (!$session || (int)$session['counselor_id'] !== (int)$counselor['id']) {
            flash('error', 'Session not found.');
            $this->redirect(url('/counselor/sessions'));
        }

        $status = $_POST['status'] ?? '';
        if (!in_array($status, ['scheduled', 'in-progress', 'completed', 'cancelled'], true)) {
            flash('error', 'Invalid session status.');
            $this->redirect(url('/counselor/sessions'));
        }

        $this->sessionModel->update($id, ['status' => $status]);
        flash('success', 'Session marked as ' . ucfirst($status) . '.');
        $this->redirect(url('/counselor/sessions'));
    }

    public function documentReview($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/counselor/documents'));
        }

        $counselor = $this->currentCounselor();
        if (!$counselor) {
            $this->redirect(url('/login'));
        }

        $document = $this->documentModel->getById($id);
        if (!$document) {
            flash('error', 'Document not found.');
            $this->redirect(url('/counselor/documents'));
        }

        $student = $this->studentModel->getById($document['student_id']);
        if (!$student || (int)$student['counselor_id'] !== (int)$counselor['id']) {
            flash('error', 'Document not found.');
            $this->redirect(url('/counselor/documents'));
        }

        $status = $_POST['status'] ?? '';
        $remarks = trim($_POST['remarks'] ?? '');
        if (!in_array($status, ['approved', 'rejected', 'resubmit'], true)) {
            flash('error', 'Invalid review status.');
            $this->redirect(url('/counselor/documents'));
        }

        $this->documentModel->review($id, $status, $remarks, $_SESSION['user_id'] ?? null);
        flash('success', 'Document review submitted.');
        $this->redirect(url('/counselor/documents'));
    }
}