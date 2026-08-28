<?php
class Inquiry {
    private $db;
    private $table = 'inquiries';

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($filters = []) {
        $sql = "SELECT i.*, st.name AS student_name, st.student_id AS student_code, c.name AS counselor_name
                FROM {$this->table} i
                LEFT JOIN students st ON i.student_id = st.id
                LEFT JOIN counselors c ON i.counselor_id = c.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (st.name LIKE ? OR i.inquiry_id LIKE ? OR i.country_of_interest LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['status'])) {
            $sql .= " AND i.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['counselor_id'])) {
            $sql .= " AND i.counselor_id = ?";
            $params[] = $filters['counselor_id'];
        }

        $sql .= " ORDER BY i.created_at DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $filters['limit'];
            $params[] = $filters['offset'] ?? 0;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare(
            "SELECT i.*, st.name AS student_name, st.email AS student_email, st.student_id AS student_code, c.name AS counselor_name
             FROM {$this->table} i
             LEFT JOIN students st ON i.student_id = st.id
             LEFT JOIN counselors c ON i.counselor_id = c.id
             WHERE i.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table} i LEFT JOIN students st ON i.student_id = st.id WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (st.name LIKE ? OR i.inquiry_id LIKE ? OR i.country_of_interest LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['status'])) {
            $sql .= " AND i.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['counselor_id'])) {
            $sql .= " AND i.counselor_id = ?";
            $params[] = $filters['counselor_id'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getStats() {
        $stmt = $this->db->prepare(
            "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) AS new_count,
                SUM(CASE WHEN status = 'assigned' THEN 1 ELSE 0 END) AS assigned,
                SUM(CASE WHEN status = 'in-progress' THEN 1 ELSE 0 END) AS in_progress,
                SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) AS closed
             FROM {$this->table}"
        );
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($data) {
        $inquiry_id = 'INQ-' . date('Y') . '-' . str_pad(rand(1, 9999), 3, '0', STR_PAD_LEFT);
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (inquiry_id, student_id, country_of_interest, level_of_study, message, status, created_at)
             VALUES (?, ?, ?, ?, ?, 'new', NOW())"
        );
        $stmt->execute([
            $inquiry_id,
            $data['student_id'],
            $data['country_of_interest'],
            $data['level_of_study'],
            $data['message'] ?? null,
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];

        $allowed = ['counselor_id', 'status', 'country_of_interest', 'level_of_study', 'message'];
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $fields[] = "{$key} = ?";
                $params[] = $value;
            }
        }

        if (empty($fields)) return false;

        $params[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByStudentId($student_id) {
        $stmt = $this->db->prepare(
            "SELECT i.*, c.name AS counselor_name
             FROM {$this->table} i
             LEFT JOIN counselors c ON i.counselor_id = c.id
             WHERE i.student_id = ?
             ORDER BY i.created_at DESC"
        );
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    }

    public function hasInquiryForCountry($student_id, $country) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM {$this->table} WHERE student_id = ? AND country_of_interest = ? AND status != 'closed'"
        );
        $stmt->execute([$student_id, $country]);
        return $stmt->fetchColumn() > 0;
    }

    public function getByCounselorId($counselor_id) {
        $stmt = $this->db->prepare(
            "SELECT i.*, st.name AS student_name, st.student_id AS student_code
             FROM {$this->table} i
             LEFT JOIN students st ON i.student_id = st.id
             WHERE i.counselor_id = ?
             ORDER BY i.created_at DESC"
        );
        $stmt->execute([$counselor_id]);
        return $stmt->fetchAll();
    }
}
