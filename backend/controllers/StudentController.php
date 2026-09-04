<?php
require_once MODEL_PATH . '/Student.php';
require_once MODEL_PATH . '/Counselor.php';
require_once MODEL_PATH . '/Inquiry.php';
require_once MODEL_PATH . '/Document.php';
require_once MODEL_PATH . '/College.php';
require_once MODEL_PATH . '/Course.php';
require_once MODEL_PATH . '/Session.php';

class StudentController extends Controller {
    private $studentModel;
    private $counselorModel;
    private $inquiryModel;
    private $documentModel;
    private $sessionModel;

    public function __construct() {
        parent::__construct();
        $this->studentModel = new Student();
        $this->counselorModel = new Counselor();
        $this->inquiryModel = new Inquiry();
        $this->documentModel = new Document();
        $this->sessionModel = new Session();
    }

    public function index() {
        $filters = [
            'search' => $_GET['search'] ?? '',
            'level' => $_GET['level'] ?? '',
            'counselor_id' => $_GET['counselor_id'] ?? '',
            'limit' => 10,
            'offset' => max(0, ((int)($_GET['page'] ?? 1) - 1) * 10),
        ];

        $students = $this->studentModel->getAll($filters);
        $total = $this->studentModel->count($filters);
        $counselors = $this->counselorModel->getAvailable();

        $this->view('admin/students/index', [
            'pageTitle' => 'Students',
            'pageDescription' => 'Manage and track student profiles and counseling progress.',
            'currentPage' => 'students',
            'students' => $students,
            'total' => $total,
            'counselors' => $counselors,
            'filters' => $filters,
        ]);
    }

    public function create() {
        $counselors = $this->counselorModel->getAvailable();

        $this->view('admin/students/create', [
            'pageTitle' => 'Register Student',
            'pageDescription' => 'Add a new student to the system.',
            'currentPage' => 'students',
            'counselors' => $counselors,
        ]);
    }

    public function store() {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/students'));
        }

        $data = $this->sanitize($this->getInput());

        $errors = [];
        if (empty($data['student_id'])) {
            $data['student_id'] = 'STU-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }
        if (empty($data['name'])) {
            $errors['name'] = 'Full name is required.';
        }
        if (empty($data['email'])) {
            $errors['email'] = 'Email address is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }
        $allowedLevels = ['High School', 'Undergraduate', 'Postgraduate'];
        if (empty($data['education_level']) || !in_array($data['education_level'], $allowedLevels, true)) {
            $errors['education_level'] = 'Please select a valid education level.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $_SESSION['old'] = $data;
            $this->redirect(url('/admin/students/create'));
        }

        try {
            $id = $this->studentModel->create($data);
        } catch (PDOException $e) {
            flash('error', 'Could not register student. Check that the email and student ID are unique.');
            $this->redirect(url('/admin/students'));
        }

        $password = $data['password'] ?? '';
        if (strlen($password) < 8) {
            $password = generateTempPassword();
        }

        $result = createLoginAccount('student', $data['student_id'] ?? ('USR-STU-' . $id), $data['name'], $data['email'], $password);

        if ($result['created']) {
            $msg = 'Student registered successfully. ';
            $msg .= $result['emailed']
                ? 'Login credentials emailed to ' . $data['email'] . '.'
                : 'A login account was created but the email could not be sent. Temporary password: ' . $result['password'];
        } else {
            $msg = 'Student registered successfully, but a login account already exists for ' . $data['email'] . '.';
        }

        flash('success', $msg);
        $this->redirect(url('/admin/students'));
    }

    public function show($id) {
        $student = $this->studentModel->getById($id);
        if (!$student) {
            $this->redirect(url('/admin/students'));
        }

        $inquiries = $this->inquiryModel->getByStudentId($id);
        $documents = $this->documentModel->getByStudentId($id);

        $this->view('admin/students/show', [
            'pageTitle' => 'Student Details',
            'pageDescription' => 'View student profile.',
            'currentPage' => 'students',
            'student' => $student,
            'inquiries' => $inquiries,
            'documents' => $documents,
        ]);
    }

    public function edit($id) {
        $student = $this->studentModel->getById($id);
        if (!$student) {
            $this->redirect(url('/admin/students'));
        }

        $counselors = $this->counselorModel->getAll();

        $this->view('admin/students/edit', [
            'pageTitle' => 'Edit Student',
            'pageDescription' => 'Update student profile.',
            'currentPage' => 'students',
            'student' => $student,
            'counselors' => $counselors,
        ]);
    }

    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/students'));
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
        $allowedLevels = ['High School', 'Undergraduate', 'Postgraduate'];
        if (!empty($data['education_level']) && !in_array($data['education_level'], $allowedLevels, true)) {
            $errors['education_level'] = 'Please select a valid education level.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $_SESSION['old'] = $data;
            $this->redirect(url('/admin/students/' . $id . '/edit'));
        }

        $this->studentModel->update($id, $data);

        flash('success', 'Student updated successfully.');
        $this->redirect(url('/admin/students/' . $id));
    }

    public function destroy($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/students'));
        }

        $this->studentModel->delete($id);
        flash('success', 'Student deleted successfully.');
        $this->redirect(url('/admin/students'));
    }

    public function dashboard() {
        $user = $_SESSION['user'] ?? null;
        $email = $user['email'] ?? '';

        $student = $email !== '' ? $this->studentModel->getByEmail($email) : null;

        $sessions = $student ? $this->sessionModel->getByStudentId($student['id']) : [];
        $documents = $student ? $this->documentModel->getByStudentId($student['id']) : [];
        $inquiries = $student ? $this->inquiryModel->getByStudentId($student['id']) : [];

        $upcoming = array_filter($sessions, function ($s) {
            return ($s['status'] ?? '') === 'scheduled';
        });

        $requiredDocs = array_filter($documents, function ($d) {
            return in_array($d['status'] ?? '', ['assigned', 'resubmit']);
        });

        $stats = [
            ['label' => 'Total Sessions', 'value' => count($sessions), 'icon' => 'sessions'],
            ['label' => 'Upcoming', 'value' => count($upcoming), 'icon' => 'upcoming'],
            ['label' => 'Required Documents', 'value' => count($requiredDocs), 'icon' => 'documents'],
            ['label' => 'Inquiries', 'value' => count($inquiries), 'icon' => 'inquiries'],
        ];

        $this->view('student/dashboard', [
            'pageTitle' => 'Student Dashboard',
            'pageDescription' => 'Your counseling overview.',
            'currentPage' => 'dashboard',
            'student' => $student,
            'sessions' => $sessions,
            'documents' => $documents,
            'inquiries' => $inquiries,
            'stats' => $stats,
        ]);
    }

    private function currentStudent() {
        $email = $_SESSION['user']['email'] ?? '';
        return $email !== '' ? $this->studentModel->getByEmail($email) : null;
    }

    public function sessions() {
        $student = $this->currentStudent();
        $sessions = $student ? $this->sessionModel->getByStudentId($student['id']) : [];

        $this->view('student/sessions', [
            'pageTitle' => 'My Sessions',
            'pageDescription' => 'Your counseling sessions.',
            'currentPage' => 'sessions',
            'student' => $student,
            'sessions' => $sessions,
        ]);
    }

    public function documents() {
        $student = $this->currentStudent();
        $documents = $student ? $this->documentModel->getByStudentId($student['id']) : [];

        $this->view('student/documents', [
            'pageTitle' => 'My Documents',
            'pageDescription' => 'Your uploaded documents.',
            'currentPage' => 'documents',
            'student' => $student,
            'documents' => $documents,
        ]);
    }

    public function submitDocument($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/student/documents'));
        }

        $student = $this->currentStudent();
        if (!$student) {
            $this->redirect(url('/student/documents'));
        }

        $document = $this->documentModel->getById($id);
        if (!$document || (int)$document['student_id'] !== (int)$student['id']) {
            flash('error', 'Document not found.');
            $this->redirect(url('/student/documents'));
        }

        if (!in_array($document['status'], ['assigned', 'resubmit'], true)) {
            flash('error', 'This document is not waiting for your submission.');
            $this->redirect(url('/student/documents'));
        }

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            flash('error', 'Please choose a file to upload.');
            $this->redirect(url('/student/documents'));
        }

        if ($_FILES['file']['size'] > 10 * 1024 * 1024) {
            flash('error', 'File is too large. Maximum file size is 10 MB.');
            $this->redirect(url('/student/documents'));
        }

        $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'doc', 'docx', 'xls', 'xlsx'];
        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedTypes, true)) {
            flash('error', 'File type not allowed. Allowed: ' . implode(', ', $allowedTypes));
            $this->redirect(url('/student/documents'));
        }

        $uploadDir = BASE_PATH . '/uploads/documents/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = time() . '_' . $id . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            flash('error', 'File upload failed. Please try again.');
            $this->redirect(url('/student/documents'));
        }

        $this->documentModel->submit($id, [
            'file_path' => '/uploads/documents/' . $fileName,
            'size' => $_FILES['file']['size'],
            'type' => $ext,
        ]);

        flash('success', 'Document submitted successfully. It is now awaiting review.');
        $this->redirect(url('/student/documents'));
    }

    public function inquiries() {
        $student = $this->currentStudent();
        $allInquiries = $student ? $this->inquiryModel->getByStudentId($student['id']) : [];

        $inquiredCountries = [];
        foreach ($allInquiries as $inq) {
            if (($inq['status'] ?? '') !== 'closed') {
                $inquiredCountries[] = $inq['country_of_interest'];
            }
        }

        $this->view('student/inquiries', [
            'pageTitle' => 'My Inquiries',
            'pageDescription' => 'Submit and track your inquiries.',
            'currentPage' => 'inquiries',
            'student' => $student,
            'inquiredCountries' => $inquiredCountries,
        ]);
    }

    public function storeInquiry() {
        if (!$this->isPost()) {
            $this->redirect(url('/student/inquiries'));
        }

        $student = $this->currentStudent();
        if (!$student) {
            $this->redirect(url('/student/inquiries'));
        }

        $data = $this->sanitize($this->getInput());

        $allowedCountries = ['USA', 'UK', 'Canada', 'Australia', 'Germany', 'New Zealand', 'Other'];
        $allowedLevels = ['High School', 'Undergraduate', 'Postgraduate'];

        $errors = [];
        if (empty($data['country_of_interest']) || !in_array($data['country_of_interest'], $allowedCountries, true)) {
            $errors['country_of_interest'] = 'Please select a valid study destination.';
        }
        if (empty($data['level_of_study']) || !in_array($data['level_of_study'], $allowedLevels, true)) {
            $errors['level_of_study'] = 'Please select a valid study level.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $this->redirect(url('/student/inquiries'));
        }

        if ($this->inquiryModel->hasInquiryForCountry($student['id'], $data['country_of_interest'])) {
            flash('error', 'You have already submitted an inquiry for ' . $data['country_of_interest'] . '. You can ask for its status instead.');
            $this->redirect(url('/student/inquiries'));
        }

        $this->inquiryModel->create([
            'student_id' => $student['id'],
            'country_of_interest' => $data['country_of_interest'],
            'level_of_study' => $data['level_of_study'],
            'message' => $data['message'] ?? '',
        ]);

        $adminEmail = 'admin@ecms.edu';
        createNotificationByEmail($adminEmail, 'New Student Inquiry', $student['name'] . ' has submitted a new inquiry about ' . e($data['country_of_interest']) . '.', '/admin/inquiries');

        flash('success', 'Inquiry submitted successfully.');
        $this->redirect(url('/student/inquiries'));
    }

    public function profile() {
        $student = $this->currentStudent();

        $this->view('student/profile', [
            'pageTitle' => 'My Profile',
            'pageDescription' => 'View and update your profile.',
            'currentPage' => 'profile',
            'student' => $student,
        ]);
    }

    public function updateProfile() {
        if (!$this->isPost()) {
            $this->redirect(url('/student/profile'));
        }

        $student = $this->currentStudent();
        if (!$student) {
            $this->redirect(url('/student/profile'));
        }

        $data = $this->sanitize($this->getInput());

        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'Name is required.';
        }

        $allowedLevels = ['High School', 'Undergraduate', 'Postgraduate'];
        if (empty($data['education_level']) || !in_array($data['education_level'], $allowedLevels, true)) {
            $errors['education_level'] = 'Please select a valid education level.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $this->redirect(url('/student/profile'));
        }

        $clean = [
            'name' => $data['name'],
            'education_level' => $data['education_level'],
        ];
        $this->studentModel->update($student['id'], $clean);

        flash('success', 'Profile updated successfully.');
        $this->redirect(url('/student/profile'));
    }

    public function updatePassword() {
        if (!$this->isPost()) {
            $this->redirect(url('/student/profile'));
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
        $this->redirect(url('/student/profile'));
    }

    public function uploadAvatar() {
        if (!$this->isPost()) {
            $this->redirect(url('/student/profile'));
        }

        $student = $this->currentStudent();
        if (!$student) {
            $this->redirect(url('/student/profile'));
        }

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            flash('error', 'Please choose an image to upload.');
            $this->redirect(url('/student/profile'));
        }

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedTypes, true)) {
            flash('error', 'Only JPG, PNG, GIF, and WebP images are allowed.');
            $this->redirect(url('/student/profile'));
        }

        if ($_FILES['avatar']['size'] > 5 * 1024 * 1024) {
            flash('error', 'Image is too large. Maximum size is 5 MB.');
            $this->redirect(url('/student/profile'));
        }

        $uploadDir = BASE_PATH . '/uploads/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!empty($student['avatar'])) {
            $oldPath = BASE_PATH . $student['avatar'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $fileName = 'avatar_student_' . $student['id'] . '_' . time() . '.' . $ext;
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $filePath)) {
            flash('error', 'Failed to upload image. Please try again.');
            $this->redirect(url('/student/profile'));
        }

        $avatarUrl = '/uploads/profiles/' . $fileName;
        $this->studentModel->update($student['id'], ['avatar' => $avatarUrl]);

        $db = getDB();
        $stmt = $db->prepare("UPDATE users SET avatar = ? WHERE email = ?");
        $stmt->execute([$avatarUrl, $student['email']]);
        $_SESSION['user']['avatar'] = $avatarUrl;

        flash('success', 'Profile picture updated successfully.');
        $this->redirect(url('/student/profile'));
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

        $this->view('student/catalog', [
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