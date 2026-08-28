<?php
class Document {
    private $db;
    private $table = 'documents';

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($filters = []) {
        $sql = "SELECT d.*, s.name AS student_name, s.student_id AS student_code,
                       u.name AS uploaded_by_name, r.name AS reviewed_by_name, ab.name AS assigned_by_name
                FROM {$this->table} d
                LEFT JOIN students s ON d.student_id = s.id
                LEFT JOIN users u ON d.uploaded_by = u.id
                LEFT JOIN users r ON d.reviewed_by = r.id
                LEFT JOIN users ab ON d.assigned_by = ab.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (d.name LIKE ? OR s.name LIKE ? OR s.student_id LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['category'])) {
            $sql .= " AND d.category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND d.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['student_id'])) {
            $sql .= " AND d.student_id = ?";
            $params[] = $filters['student_id'];
        }

        $sql .= " ORDER BY d.created_at DESC";

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
            "SELECT d.*, s.name AS student_name, s.student_id AS student_code, s.email AS student_email,
                    u.name AS uploaded_by_name, r.name AS reviewed_by_name, ab.name AS assigned_by_name
             FROM {$this->table} d
             LEFT JOIN students s ON d.student_id = s.id
             LEFT JOIN users u ON d.uploaded_by = u.id
             LEFT JOIN users r ON d.reviewed_by = r.id
             LEFT JOIN users ab ON d.assigned_by = ab.id
             WHERE d.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByStudentId($student_id) {
        $stmt = $this->db->prepare(
            "SELECT d.*, s.name AS student_name, s.student_id AS student_code,
                    u.name AS uploaded_by_name, r.name AS reviewed_by_name, ab.name AS assigned_by_name
             FROM {$this->table} d
             LEFT JOIN students s ON d.student_id = s.id
             LEFT JOIN users u ON d.uploaded_by = u.id
             LEFT JOIN users r ON d.reviewed_by = r.id
             LEFT JOIN users ab ON d.assigned_by = ab.id
             WHERE d.student_id = ?
             ORDER BY FIELD(d.status, 'assigned', 'pending', 'resubmit', 'approved', 'rejected'), d.created_at DESC"
        );
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    }

    public function getByCounselorId($counselor_id) {
        $stmt = $this->db->prepare(
            "SELECT d.*, s.name AS student_name, s.student_id AS student_code,
                    u.name AS uploaded_by_name, r.name AS reviewed_by_name, ab.name AS assigned_by_name
             FROM {$this->table} d
             LEFT JOIN students s ON d.student_id = s.id
             LEFT JOIN users u ON d.uploaded_by = u.id
             LEFT JOIN users r ON d.reviewed_by = r.id
             LEFT JOIN users ab ON d.assigned_by = ab.id
             WHERE s.counselor_id = ?
             ORDER BY FIELD(d.status, 'assigned', 'pending', 'resubmit', 'approved', 'rejected'), d.created_at DESC"
        );
        $stmt->execute([$counselor_id]);
        return $stmt->fetchAll();
    }

    public function getReviewQueue($filters = []) {
        $sql = "SELECT d.*, s.name AS student_name, s.student_id AS student_code, s.email AS student_email,
                       u.name AS uploaded_by_name, ab.name AS assigned_by_name
                FROM {$this->table} d
                LEFT JOIN students s ON d.student_id = s.id
                LEFT JOIN users u ON d.uploaded_by = u.id
                LEFT JOIN users ab ON d.assigned_by = ab.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND d.status = ?";
            $params[] = $filters['status'];
        } else {
            $sql .= " AND d.status = 'pending'";
        }

        if (!empty($filters['category'])) {
            $sql .= " AND d.category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (d.name LIKE ? OR s.name LIKE ? OR s.student_id LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $sql .= " ORDER BY d.created_at ASC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $filters['limit'];
            $params[] = $filters['offset'] ?? 0;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table} d LEFT JOIN students s ON d.student_id = s.id WHERE 1=1";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND d.category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND d.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['student_id'])) {
            $sql .= " AND d.student_id = ?";
            $params[] = $filters['student_id'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (d.name LIKE ? OR s.name LIKE ? OR s.student_id LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getStats() {
        $stmt = $this->db->prepare(
            "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'assigned' THEN 1 ELSE 0 END) AS assigned,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) AS approved,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) AS rejected,
                SUM(CASE WHEN status = 'resubmit' THEN 1 ELSE 0 END) AS resubmit,
                SUM(CASE WHEN category = 'education' THEN 1 ELSE 0 END) AS education,
                SUM(CASE WHEN category = 'visa' THEN 1 ELSE 0 END) AS visa
             FROM {$this->table}"
        );
        $stmt->execute();
        return $stmt->fetch();
    }

    public function assign($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (student_id, name, category, status, assigned_by, assigned_at, created_at)
             VALUES (?, ?, ?, 'assigned', ?, NOW(), NOW())"
        );
        $stmt->execute([
            $data['student_id'],
            $data['name'],
            $data['category'],
            $data['assigned_by']
        ]);
        return $this->db->lastInsertId();
    }

    public function submit($id, $data) {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET file_path = ?, size = ?, type = ?, status = 'pending', submitted_at = NOW() WHERE id = ?"
        );
        return $stmt->execute([
            $data['file_path'],
            $data['size'],
            $data['type'],
            $id
        ]);
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (student_id, name, size, type, category, status, file_path, uploaded_by, created_at)
             VALUES (?, ?, ?, ?, ?, 'pending', ?, ?, NOW())"
        );
        $stmt->execute([
            $data['student_id'],
            $data['name'],
            $data['size'],
            $data['type'],
            $data['category'],
            $data['file_path'] ?? null,
            $data['uploaded_by']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];

        $allowed = ['name', 'category', 'student_id', 'status', 'remarks', 'reviewed_by', 'reviewed_at'];
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

    public function review($id, $status, $remarks, $reviewed_by) {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET status = ?, remarks = ?, reviewed_by = ?, reviewed_at = NOW() WHERE id = ?"
        );
        return $stmt->execute([$status, $remarks, $reviewed_by, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
