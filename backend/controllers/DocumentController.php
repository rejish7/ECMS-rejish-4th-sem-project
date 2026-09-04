<?php
require_once MODEL_PATH . '/Document.php';
require_once MODEL_PATH . '/Student.php';

class DocumentController extends Controller {
    private $documentModel;
    private $studentModel;

    public function __construct() {
        parent::__construct();
        $this->documentModel = new Document();
        $this->studentModel = new Student();
    }

    public function index() {
        $filters = [
            'search' => $_GET['search'] ?? '',
            'category' => $_GET['category'] ?? '',
            'status' => $_GET['status'] ?? '',
            'student_id' => $_GET['student_id'] ?? '',
            'limit' => 10,
            'offset' => max(0, ((int)($_GET['page'] ?? 1) - 1) * 10),
        ];

        $documents = $this->documentModel->getAll($filters);
        $total = $this->documentModel->count($filters);
        $stats = $this->documentModel->getStats();
        $students = $this->studentModel->getAll();

        $this->view('admin/documents/index', [
            'pageTitle' => 'Documents',
            'pageDescription' => 'Manage student documents for education and visa processing.',
            'currentPage' => 'documents',
            'documents' => $documents,
            'total' => $total,
            'stats' => $stats,
            'filters' => $filters,
            'students' => $students,
        ]);
    }

    public function reviewQueue() {
        $filters = [
            'search' => $_GET['search'] ?? '',
            'category' => $_GET['category'] ?? '',
            'status' => $_GET['status'] ?? 'pending',
            'limit' => 20,
            'offset' => max(0, ((int)($_GET['page'] ?? 1) - 1) * 20),
        ];

        $documents = $this->documentModel->getReviewQueue($filters);
        $total = $this->documentModel->count(['status' => $filters['status'], 'category' => $filters['category'], 'search' => $filters['search']]);
        $stats = $this->documentModel->getStats();

        $selectedDoc = null;
        if (!empty($_GET['doc_id'])) {
            $selectedDoc = $this->documentModel->getById($_GET['doc_id']);
        }

        $this->view('admin/documents/review-queue', [
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

    public function review($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/documents/review-queue'));
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
            $this->redirect(url('/admin/documents/review-queue'));
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $this->redirect(url('/login'));
        }
        $this->documentModel->review($id, $status, $remarks, $userId);

        $document = $this->documentModel->getById($id);
        if ($document) {
            $statusMsg = $status === 'approved' ? 'approved' : ($status === 'rejected' ? 'rejected' : 'marked for resubmission');
            createNotificationByEmail($document['student_email'] ?? '', 'Document ' . ucfirst($status), 'Your document "' . e($document['name']) . '" has been ' . $statusMsg . '.', '/student/documents');
        }

        flash('success', 'Document review submitted successfully.');
        $this->redirect(url('/admin/documents/review-queue'));
    }

    public function assignCreate() {
        $students = $this->studentModel->getAll();

        $this->view('admin/documents/assign', [
            'pageTitle' => 'Assign Required Document',
            'pageDescription' => 'Require a document from a student.',
            'currentPage' => 'documents',
            'students' => $students,
            'preselectedStudent' => (int)($_GET['student_id'] ?? 0),
        ]);
    }

    public function assignStore() {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/documents'));
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
            $this->redirect(url('/admin/documents/assign'));
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
        $this->redirect(url('/admin/documents/student/' . $data['student_id']));
    }

    public function create() {
        $students = $this->studentModel->getAll();

        $this->view('admin/documents/create', [
            'pageTitle' => 'Upload Document',
            'pageDescription' => 'Upload a new student document.',
            'currentPage' => 'documents',
            'students' => $students,
        ]);
    }

    public function store() {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/documents'));
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
        if (!empty($data['category']) && !in_array($data['category'], $allowedCategories, true)) {
            $errors['category'] = 'Please select a valid category.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $_SESSION['old'] = $data;
            $this->redirect(url('/admin/documents/create'));
        }

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'doc', 'docx', 'xls', 'xlsx'];
            $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedTypes, true)) {
                flash('error', 'File type not allowed. Allowed: ' . implode(', ', $allowedTypes));
                $this->redirect(url('/admin/documents/create'));
            }

            if ($_FILES['file']['size'] > 10 * 1024 * 1024) {
                flash('error', 'File is too large. Maximum file size is 10 MB.');
                $this->redirect(url('/admin/documents/create'));
            }

            $uploadDir = BASE_PATH . '/uploads/documents/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                $data['file_path'] = '/uploads/documents/' . $fileName;
                $data['size'] = $_FILES['file']['size'];
                $data['type'] = $ext;
            }
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $this->redirect(url('/login'));
        }
        $data['uploaded_by'] = $userId;
        $this->documentModel->create($data);

        flash('success', 'Document uploaded successfully.');
        $this->redirect(url('/admin/documents'));
    }

    public function show($id) {
        $document = $this->documentModel->getById($id);
        if (!$document) {
            $this->redirect(url('/admin/documents'));
        }

        $this->view('admin/documents/show', [
            'pageTitle' => 'Document Details',
            'pageDescription' => 'View document details.',
            'currentPage' => 'documents',
            'document' => $document,
        ]);
    }

    public function studentDocs($student_id) {
        $student = $this->studentModel->getById($student_id);
        if (!$student) {
            $this->redirect(url('/admin/documents'));
        }

        $documents = $this->documentModel->getByStudentId($student_id);

        $this->view('admin/documents/student-docs', [
            'pageTitle' => 'Student Documents',
            'pageDescription' => 'Documents for ' . $student['name'],
            'currentPage' => 'documents',
            'student' => $student,
            'documents' => $documents,
        ]);
    }

    public function edit($id) {
        $document = $this->documentModel->getById($id);
        if (!$document) {
            $this->redirect(url('/admin/documents'));
        }

        $students = $this->studentModel->getAll();

        $this->view('admin/documents/edit', [
            'pageTitle' => 'Edit Document',
            'pageDescription' => 'Update document details.',
            'currentPage' => 'documents',
            'document' => $document,
            'students' => $students,
        ]);
    }

    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/documents'));
        }

        $data = $this->sanitize($this->getInput());

        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'Document name is required.';
        }
        $allowedCategories = ['education', 'visa'];
        if (!empty($data['category']) && !in_array($data['category'], $allowedCategories, true)) {
            $errors['category'] = 'Please select a valid category.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $this->redirect(url('/admin/documents/' . $id . '/edit'));
        }

        $this->documentModel->update($id, $data);

        flash('success', 'Document updated successfully.');
        $this->redirect(url('/admin/documents/' . $id));
    }

    public function destroy($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/documents'));
        }

        $doc = $this->documentModel->getById($id);
        if ($doc && !empty($doc['file_path'])) {
            $filePath = BASE_PATH . $doc['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $this->documentModel->delete($id);
        flash('success', 'Document deleted successfully.');
        $this->redirect(url('/admin/documents'));
    }
}
