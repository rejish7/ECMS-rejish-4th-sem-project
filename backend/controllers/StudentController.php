<?php
require_once MODEL_PATH . '/Student.php';
require_once MODEL_PATH . '/Counselor.php';
require_once MODEL_PATH . '/Inquiry.php';
require_once MODEL_PATH . '/Document.php';

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
}