<?php
require_once MODEL_PATH . '/Student.php';
require_once MODEL_PATH . '/Counselor.php';
require_once MODEL_PATH . '/Inquiry.php';
require_once MODEL_PATH . '/Document.php';
require_once MODEL_PATH . '/Session.php';

class StudentController extends Controller {
    private $studentModel;
    private $counselorModel;
    private $inquiryModel;
    private $documentModel;

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
        $this->studentModel->create($data);

        flash('success', 'Student registered successfully.');
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

        $uploadDir = BASE_PATH . '/uploads/documents/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        chmod($uploadDir, 0777);

        $fileName = time() . '_' . $id . '_' . basename($_FILES['file']['name']);
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            flash('error', 'File upload failed. Please try again.');
            $this->redirect(url('/student/documents'));
        }

        $this->documentModel->submit($id, [
            'file_path' => '/uploads/documents/' . $fileName,
            'size' => $_FILES['file']['size'],
            'type' => pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION),
        ]);

        flash('success', 'Document submitted successfully. It is now awaiting review.');
        $this->redirect(url('/student/documents'));
    }

    public function inquiries() {
        $student = $this->currentStudent();
        $inquiries = $student ? $this->inquiryModel->getByStudentId($student['id']) : [];

        $inquiredCountries = [];
        if ($student) {
            foreach ($inquiries as $inq) {
                $inquiredCountries[] = $inq['country_of_interest'];
            }
        }

        $this->view('student/inquiries', [
            'pageTitle' => 'My Inquiries',
            'pageDescription' => 'Submit and track your inquiries.',
            'currentPage' => 'inquiries',
            'student' => $student,
            'inquiries' => $inquiries,
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

        if (empty($data['country_of_interest']) || !in_array($data['country_of_interest'], $allowedCountries, true)) {
            flash('error', 'Please select a valid study destination.');
            $this->redirect(url('/student/inquiries'));
        }
        if (empty($data['level_of_study']) || !in_array($data['level_of_study'], $allowedLevels, true)) {
            flash('error', 'Please select a valid study level.');
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

        if (empty($data['name'])) {
            flash('error', 'Name is required.');
            $this->redirect(url('/student/profile'));
        }

        $allowedLevels = ['High School', 'Undergraduate', 'Postgraduate'];
        if (empty($data['education_level']) || !in_array($data['education_level'], $allowedLevels, true)) {
            flash('error', 'Please select a valid education level.');
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
}