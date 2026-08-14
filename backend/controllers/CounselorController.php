<?php
require_once MODEL_PATH . '/Counselor.php';

class CounselorController extends Controller {
    private $counselorModel;

    public function __construct() {
        parent::__construct();
        $this->counselorModel = new Counselor();
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
}