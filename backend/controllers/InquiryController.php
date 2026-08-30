<?php
require_once MODEL_PATH . '/Inquiry.php';
require_once MODEL_PATH . '/Student.php';
require_once MODEL_PATH . '/Counselor.php';

class InquiryController extends Controller {
    private $inquiryModel;
    private $studentModel;
    private $counselorModel;

    public function __construct() {
        parent::__construct();
        $this->inquiryModel = new Inquiry();
        $this->studentModel = new Student();
        $this->counselorModel = new Counselor();
    }

    public function index() {
        $filters = [
            'search' => $_GET['search'] ?? '',
            'status' => $_GET['status'] ?? '',
            'counselor_id' => $_GET['counselor_id'] ?? '',
            'limit' => 10,
            'offset' => max(0, ((int)($_GET['page'] ?? 1) - 1) * 10),
        ];

        $inquiries = $this->inquiryModel->getAll($filters);
        $total = $this->inquiryModel->count($filters);
        $stats = $this->inquiryModel->getStats();
        $counselors = $this->counselorModel->getAvailable();

        $this->view('admin/inquiries/index', [
            'pageTitle' => 'Student Inquiries',
            'pageDescription' => 'View and manage inquiries submitted by students.',
            'currentPage' => 'inquiries',
            'inquiries' => $inquiries,
            'total' => $total,
            'stats' => $stats,
            'filters' => $filters,
            'counselors' => $counselors,
        ]);
    }

    public function show($id) {
        $inquiry = $this->inquiryModel->getById($id);
        if (!$inquiry) {
            $this->redirect(url('/admin/inquiries'));
        }

        $counselors = $this->counselorModel->getAvailable();

        $this->view('admin/inquiries/show', [
            'pageTitle' => 'Inquiry Details',
            'pageDescription' => 'View inquiry details.',
            'currentPage' => 'inquiries',
            'inquiry' => $inquiry,
            'counselors' => $counselors,
        ]);
    }

    public function assign($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/inquiries'));
        }

        $counselor_id = $_POST['counselor_id'] ?? null;
        if (empty($counselor_id)) {
            flash('error', 'Please select a counselor.');
            $this->redirect(url('/admin/inquiries/' . $id));
        }

        $this->inquiryModel->update($id, [
            'counselor_id' => $counselor_id,
            'status' => 'assigned',
        ]);

        flash('success', 'Counselor assigned successfully.');
        $this->redirect(url('/admin/inquiries/' . $id));
    }

    public function autoAssign($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/inquiries'));
        }

        $inquiry = $this->inquiryModel->getById($id);
        if (!$inquiry) {
            flash('error', 'Inquiry not found.');
            $this->redirect(url('/admin/inquiries'));
        }

        if (!empty($inquiry['counselor_id'])) {
            flash('error', 'This inquiry is already assigned to a counselor. Please remove the current assignment first before auto-assigning.');
            $this->redirect(url('/admin/inquiries/' . $id));
        }

        $counselor = $this->counselorModel->getLeastBusy();
        if (!$counselor) {
            flash('error', 'No available counselors found.');
            $this->redirect(url('/admin/inquiries/' . $id));
        }

        $this->inquiryModel->update($id, [
            'counselor_id' => $counselor['id'],
            'status' => 'assigned',
        ]);

        flash('success', 'Inquiry auto-assigned to ' . e($counselor['name']) . '.');
        $this->redirect(url('/admin/inquiries/' . $id));
    }

    public function close($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/inquiries'));
        }

        $inquiry = $this->inquiryModel->getById($id);
        if (!$inquiry) {
            flash('error', 'Inquiry not found.');
            $this->redirect(url('/admin/inquiries'));
        }

        $this->inquiryModel->update($id, ['status' => 'closed']);

        flash('success', 'Inquiry marked as closed. The student can now submit a new inquiry for this country.');
        $this->redirect(url('/admin/inquiries/' . $id));
    }

    public function destroy($id) {
        if (!$this->isPost()) {
            $this->redirect(url('/admin/inquiries'));
        }

        $this->inquiryModel->delete($id);
        flash('success', 'Inquiry deleted successfully.');
        $this->redirect(url('/admin/inquiries'));
    }
}
