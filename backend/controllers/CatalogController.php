<?php
require_once MODEL_PATH . '/College.php';
require_once MODEL_PATH . '/Course.php';

class CatalogController extends Controller {
    private $collegeModel;
    private $courseModel;

    public function __construct() {
        parent::__construct();
        $this->collegeModel = new College();
        $this->courseModel = new Course();
    }

    public function index() {
        $filters = [
            'search' => $_GET['search'] ?? '',
            'country' => $_GET['country'] ?? '',
            'level' => $_GET['level'] ?? '',
            'limit' => 10,
            'offset' => max(0, ((int)($_GET['page'] ?? 1) - 1) * 10),
        ];

        $colleges = $this->collegeModel->getAll($filters);
        $collegeTotal = $this->collegeModel->count($filters);

        $courseFilters = [
            'search' => $filters['search'],
            'level' => $filters['level'],
            'country' => $filters['country'],
            'limit' => 10,
            'offset' => $filters['offset'],
        ];
        $courses = $this->courseModel->getAll($courseFilters);
        $courseTotal = $this->courseModel->count($courseFilters);

        $countries = $this->collegeModel->getCountries();
        $courseStats = $this->courseModel->getStats();

        $this->view('admin/catalog/index', [
            'pageTitle' => 'College & Course Catalog',
            'pageDescription' => 'Manage institutional partnerships and academic offerings.',
            'currentPage' => 'catalog',
            'colleges' => $colleges,
            'collegeTotal' => $collegeTotal,
            'courses' => $courses,
            'courseTotal' => $courseTotal,
            'countries' => $countries,
            'courseStats' => $courseStats,
            'filters' => $filters,
        ]);
    }

    public function collegeCreate() {
        $this->view('admin/catalog/college-create', [
            'pageTitle' => 'Add College',
            'pageDescription' => 'Add a new partner institution.',
            'currentPage' => 'catalog',
        ]);
    }

    public function collegeStore() {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/catalog'));
        }

        $data = $this->sanitize($this->getInput());

        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'College name is required.';
        }
        if (empty($data['code'])) {
            $errors['code'] = 'College code is required.';
        }
        if (empty($data['country'])) {
            $errors['country'] = 'Country is required.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            flash('error', 'Please fix the errors below.');
            $this->redirect(url('/admin/catalog/college/create'));
        }

        $this->collegeModel->create($data);
        flash('success', 'College added successfully.');
        $this->redirect(url('/admin/catalog'));
    }

    public function collegeEdit($id) {
        $college = $this->collegeModel->getById($id);
        if (!$college) {
            $this->redirect(url('/admin/catalog'));
        }

        $this->view('admin/catalog/college-edit', [
            'pageTitle' => 'Edit College',
            'pageDescription' => 'Update institution details.',
            'currentPage' => 'catalog',
            'college' => $college,
        ]);
    }

    public function collegeUpdate($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/catalog'));
        }

        $data = $this->sanitize($this->getInput());

        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'College name is required.';
        }
        if (empty($data['code'])) {
            $errors['code'] = 'College code is required.';
        }
        if (empty($data['country'])) {
            $errors['country'] = 'Country is required.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $this->redirect(url('/admin/catalog/college/' . $id . '/edit'));
        }

        $this->collegeModel->update($id, $data);
        flash('success', 'College updated successfully.');
        $this->redirect(url('/admin/catalog'));
    }

    public function collegeDelete($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/catalog'));
        }

        $this->collegeModel->delete($id);
        flash('success', 'College deleted successfully.');
        $this->redirect(url('/admin/catalog'));
    }

    public function courseCreate() {
        $colleges = $this->collegeModel->getAll(['status' => 'active']);

        $this->view('admin/catalog/course-create', [
            'pageTitle' => 'Add Course',
            'pageDescription' => 'Add a new course offering.',
            'currentPage' => 'catalog',
            'colleges' => $colleges,
        ]);
    }

    public function courseStore() {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/catalog'));
        }

        $data = $this->sanitize($this->getInput());

        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'Course name is required.';
        }
        if (empty($data['code'])) {
            $errors['code'] = 'Course code is required.';
        }
        if (empty($data['college_id'])) {
            $errors['college_id'] = 'College is required.';
        }
        if (!empty($data['level']) && !in_array($data['level'], ['bachelor', 'master', 'diploma', 'phd'], true)) {
            $errors['level'] = 'Please select a valid course level.';
        }
        if (empty($data['duration'])) {
            $errors['duration'] = 'Duration is required.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            flash('error', 'Please fix the errors below.');
            $this->redirect(url('/admin/catalog/course/create'));
        }

        $this->courseModel->create($data);
        flash('success', 'Course added successfully.');
        $this->redirect(url('/admin/catalog'));
    }

    public function courseEdit($id) {
        $course = $this->courseModel->getById($id);
        if (!$course) {
            $this->redirect(url('/admin/catalog'));
        }

        $colleges = $this->collegeModel->getAll(['status' => 'active']);

        $this->view('admin/catalog/course-edit', [
            'pageTitle' => 'Edit Course',
            'pageDescription' => 'Update course details.',
            'currentPage' => 'catalog',
            'course' => $course,
            'colleges' => $colleges,
        ]);
    }

    public function courseUpdate($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/catalog'));
        }

        $data = $this->sanitize($this->getInput());

        $errors = [];
        if (empty($data['name'])) {
            $errors['name'] = 'Course name is required.';
        }
        if (empty($data['code'])) {
            $errors['code'] = 'Course code is required.';
        }
        if (empty($data['college_id'])) {
            $errors['college_id'] = 'Please select a college.';
        }
        $allowedLevels = ['bachelor', 'master', 'diploma', 'phd'];
        if (!empty($data['level']) && !in_array($data['level'], $allowedLevels, true)) {
            $errors['level'] = 'Please select a valid level.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['error'] = 'Please correct the highlighted fields.';
            $this->redirect(url('/admin/catalog/course/' . $id . '/edit'));
        }

        $this->courseModel->update($id, $data);
        flash('success', 'Course updated successfully.');
        $this->redirect(url('/admin/catalog'));
    }

    public function courseDelete($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/catalog'));
        }

        $this->courseModel->delete($id);
        flash('success', 'Course deleted successfully.');
        $this->redirect(url('/admin/catalog'));
    }
}
