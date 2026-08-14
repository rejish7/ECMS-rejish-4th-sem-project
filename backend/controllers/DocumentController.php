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

        $status = $_POST['status'] ?? '';
        $remarks = $_POST['remarks'] ?? '';
        $validStatuses = ['approved', 'rejected', 'resubmit'];

        if (!in_array($status, $validStatuses)) {
            flash('error', 'Invalid review status.');
            $this->redirect(url('/admin/documents/review-queue'));
        }

        $this->documentModel->review($id, $remarks, $_SESSION['user_id'] ?? 1, $status);

        flash('success', 'Document review submitted successfully.');
        $this->redirect(url('/admin/documents/review-queue'));
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

        if (empty($data['student_id'])) {
            flash('error', 'Please select a student.');
            $this->redirect(url('/admin/documents/create'));
        }

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = BASE_PATH . '/uploads/documents/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = time() . '_' . basename($_FILES['file']['name']);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                $data['file_path'] = '/uploads/documents/' . $fileName;
                $data['size'] = $_FILES['file']['size'];
                $data['type'] = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            }
        }

        $data['uploaded_by'] = $_SESSION['user_id'] ?? 1;
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
        $this->documentModel->update($id, $data);

        flash('success', 'Document updated successfully.');
        $this->redirect(url('/admin/documents/' . $id));
    }

    public function destroy($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/documents'));
        }

        $this->documentModel->delete($id);
        flash('success', 'Document deleted successfully.');
        $this->redirect(url('/admin/documents'));
    }
}
