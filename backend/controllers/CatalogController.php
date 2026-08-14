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

        if (empty($data['name']) || empty($data['code']) || empty($data['country'])) {
            flash('error', 'Name, code, and country are required.');
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

        if (empty($data['name']) || empty($data['code']) || empty($data['college_id']) || empty($data['level']) || empty($data['duration'])) {
            flash('error', 'All required fields must be filled.');
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
