<?php
require_once MODEL_PATH . '/Counselor.php';
require_once MODEL_PATH . '/Student.php';
require_once MODEL_PATH . '/Session.php';
require_once MODEL_PATH . '/Inquiry.php';
require_once MODEL_PATH . '/Document.php';
require_once MODEL_PATH . '/College.php';
require_once MODEL_PATH . '/Course.php';

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

        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'Full name is required.';
        }
        if (empty($data['email'])) {
            $errors['email'] = 'Email address is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }
        if (empty($data['specialization'])) {
            $errors['specialization'] = 'Specialization is required.';
        }
        if (!empty($data['max_students']) && (!is_numeric($data['max_students']) || (int)$data['max_students'] < 1)) {
            $errors['max_students'] = 'Max students must be a positive number.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $_SESSION['old'] = $data;
            $this->redirect(url('/admin/counselors/create'));
        }

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

        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'Full name is required.';
        }
        if (empty($data['email'])) {
            $errors['email'] = 'Email address is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }
        if (!empty($data['max_students']) && (!is_numeric($data['max_students']) || (int)$data['max_students'] < 1)) {
            $errors['max_students'] = 'Max students must be a positive number.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $_SESSION['old'] = $data;
            $this->redirect(url('/admin/counselors/' . $id . '/edit'));
        }

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

    public function reviewQueue() {
        $counselor = $this->currentCounselor();
        if (!$counselor) {
            $this->redirect(url('/login'));
        }

        $filters = [
            'counselor_id' => $counselor['id'],
            'search' => $_GET['search'] ?? '',
            'category' => $_GET['category'] ?? '',
            'status' => $_GET['status'] ?? 'pending',
            'limit' => 20,
            'offset' => max(0, ((int)($_GET['page'] ?? 1) - 1) * 20),
        ];

        $documents = $this->documentModel->getReviewQueue($filters);
        $total = $this->documentModel->count($filters);
        $stats = $this->documentModel->getStats($counselor['id']);

        $selectedDoc = null;
        if (!empty($_GET['doc_id'])) {
            $selectedDoc = $this->documentModel->getById($_GET['doc_id']);
        }

        $this->view('counselor/review-queue', [
            'pageTitle' => 'Document Review Queue',
            'pageDescription' => 'Review and process student documents.',
            'currentPage' => 'documents',
            'documents' => $documents,
            'total' => $total,
            'stats' => $stats,
            'filters' => $filters,
            'selectedDoc' => $selectedDoc,
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

        $errors = [];
        if (empty($data['student_id'])) {
            $errors['student_id'] = 'Please select a student.';
        }
        if (empty($data['name'])) {
            $errors['name'] = 'Document name is required.';
        }
        $allowedCategories = ['education', 'visa'];
        if (empty($data['category']) || !in_array($data['category'], $allowedCategories, true)) {
            $errors['category'] = 'Please select a valid category.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $this->redirect(url('/counselor/documents/assign'));
        }

        $this->documentModel->assign([
            'student_id' => $data['student_id'],
            'name' => $data['name'],
            'category' => $data['category'],
            'assigned_by' => $_SESSION['user_id'] ?? null,
        ]);

        $student = $this->studentModel->getById($data['student_id']);
        if ($student) {
            createNotificationByEmail($student['email'], 'Document Required', 'You have been required to submit: ' . e($data['name']) . '.', '/student/documents');
        }

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

    public function uploadAvatar() {
        if (!$this->isPost()) {
            $this->redirect(url('/counselor/profile'));
        }

        $counselor = $this->currentCounselor();
        if (!$counselor) {
            $this->redirect(url('/counselor/profile'));
        }

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            flash('error', 'Please choose an image to upload.');
            $this->redirect(url('/counselor/profile'));
        }

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedTypes, true)) {
            flash('error', 'Only JPG, PNG, GIF, and WebP images are allowed.');
            $this->redirect(url('/counselor/profile'));
        }

        if ($_FILES['avatar']['size'] > 5 * 1024 * 1024) {
            flash('error', 'Image is too large. Maximum size is 5 MB.');
            $this->redirect(url('/counselor/profile'));
        }

        $uploadDir = BASE_PATH . '/uploads/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!empty($counselor['avatar'])) {
            $oldPath = BASE_PATH . $counselor['avatar'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $fileName = 'avatar_counselor_' . $counselor['id'] . '_' . time() . '.' . $ext;
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $filePath)) {
            flash('error', 'Failed to upload image. Please try again.');
            $this->redirect(url('/counselor/profile'));
        }

        $avatarUrl = '/uploads/profiles/' . $fileName;
        $this->counselorModel->update($counselor['id'], ['avatar' => $avatarUrl]);

        $db = getDB();
        $stmt = $db->prepare("UPDATE users SET avatar = ? WHERE email = ?");
        $stmt->execute([$avatarUrl, $counselor['email']]);
        $_SESSION['user']['avatar'] = $avatarUrl;

        flash('success', 'Profile picture updated successfully.');
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

        $errors = [];
        if (empty($data['student_id'])) {
            $errors['student_id'] = 'Student is required.';
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

        createNotificationByEmail($student['email'], 'New Session Scheduled', 'Your counselor has scheduled a session for you on ' . date('M d, Y g:i A', strtotime($data['datetime'])) . '.', '/student/sessions');

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

        $input = $_POST;
        $status = $input['status'] ?? '';

        $errors = [];
        if (!in_array($status, ['scheduled', 'in-progress', 'completed', 'cancelled'], true)) {
            $errors['status'] = 'Invalid session status. Please select a valid status.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $input;
            flash('error', 'Please fix the errors below.');
            $this->redirect(url('/counselor/sessions'));
        }

        $this->sessionModel->update($id, ['status' => $status]);

        $student = $this->studentModel->getById($session['student_id']);
        if ($student) {
            createNotificationByEmail($student['email'], 'Session Status Updated', 'Your session has been marked as ' . ucfirst($status) . '.', '/student/sessions');
        }

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

        $input = $_POST;
        $status = $input['status'] ?? '';
        $remarks = trim($input['remarks'] ?? '');

        $errors = [];
        if (!in_array($status, ['approved', 'rejected', 'resubmit'], true)) {
            $errors['status'] = 'Invalid review status. Please select approved, rejected, or resubmit.';
        }
        if ($status === 'resubmit' && empty($remarks)) {
            $errors['remarks'] = 'Please provide remarks when requesting resubmission.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $input;
            flash('error', 'Please fix the errors below.');
            $this->redirect(url('/counselor/documents'));
        }

        $this->documentModel->review($id, $status, $remarks, $_SESSION['user_id'] ?? null);

        if ($student) {
            $statusMsg = $status === 'approved' ? 'approved' : ($status === 'rejected' ? 'rejected' : 'marked for resubmission');
            createNotificationByEmail($student['email'], 'Document ' . ucfirst($status), 'Your document "' . e($document['name']) . '" has been ' . $statusMsg . '.', '/student/documents');
        }

        flash('success', 'Document review submitted.');
        $this->redirect(url('/counselor/documents'));
    }

    public function catalog() {
        $collegeModel = new College();
        $courseModel = new Course();

        $collegeFilters = [
            'search' => $_GET['search'] ?? '',
            'country' => $_GET['country'] ?? '',
            'status' => 'active',
            'limit' => 20,
            'offset' => 0,
        ];
        $colleges = $collegeModel->getAll($collegeFilters);
        $countries = $collegeModel->getCountries();

        $courseFilters = [
            'search' => $_GET['search'] ?? '',
            'level' => $_GET['level'] ?? '',
            'country' => $_GET['country'] ?? '',
            'status' => 'active',
            'limit' => 20,
            'offset' => 0,
        ];
        $courses = $courseModel->getAll($courseFilters);

        $this->view('counselor/catalog', [
            'pageTitle' => 'College & Course Catalog',
            'pageDescription' => 'Browse partner colleges and available courses.',
            'currentPage' => 'catalog',
            'colleges' => $colleges,
            'courses' => $courses,
            'countries' => $countries,
            'filters' => $_GET,
        ]);
    }
}