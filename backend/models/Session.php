<?php
class Session {
    private $db;
    private $table = 'sessions';

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($filters = []) {
        $sql = "SELECT s.*, st.name AS student_name, c.name AS counselor_name
                FROM {$this->table} s
                LEFT JOIN students st ON s.student_id = st.id
                LEFT JOIN counselors c ON s.counselor_id = c.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (st.name LIKE ? OR c.name LIKE ? OR s.session_id LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['status'])) {
            $sql .= " AND s.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['counselor_id'])) {
            $sql .= " AND s.counselor_id = ?";
            $params[] = $filters['counselor_id'];
        }

        $sql .= " ORDER BY s.created_at DESC";

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
            "SELECT s.*, st.name AS student_name, c.name AS counselor_name
             FROM {$this->table} s
             LEFT JOIN students st ON s.student_id = st.id
             LEFT JOIN counselors c ON s.counselor_id = c.id
             WHERE s.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByStudentId($studentId) {
        $stmt = $this->db->prepare(
            "SELECT s.*, st.name AS student_name, st.student_id AS student_code, c.name AS counselor_name
             FROM {$this->table} s
             LEFT JOIN students st ON s.student_id = st.id
             LEFT JOIN counselors c ON s.counselor_id = c.id
             WHERE s.student_id = ?
             ORDER BY s.datetime DESC"
        );
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public function getByCounselorId($counselorId) {
        $stmt = $this->db->prepare(
            "SELECT s.*, st.name AS student_name, st.student_id AS student_code, c.name AS counselor_name
             FROM {$this->table} s
             LEFT JOIN students st ON s.student_id = st.id
             LEFT JOIN counselors c ON s.counselor_id = c.id
             WHERE s.counselor_id = ?
             ORDER BY s.datetime DESC"
        );
        $stmt->execute([$counselorId]);
        return $stmt->fetchAll();
    }

    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table} s LEFT JOIN students st ON s.student_id = st.id LEFT JOIN counselors c ON s.counselor_id = c.id WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (st.name LIKE ? OR c.name LIKE ? OR s.session_id LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['status'])) {
            $sql .= " AND s.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['counselor_id'])) {
            $sql .= " AND s.counselor_id = ?";
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
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed,
                SUM(CASE WHEN status = 'scheduled' THEN 1 ELSE 0 END) AS upcoming,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled
             FROM {$this->table}"
        );
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (session_id, student_id, counselor_id, mode, datetime, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())"
        );
        $stmt->execute([
            $data['session_id'],
            $data['student_id'],
            $data['counselor_id'],
            $data['mode'],
            $data['datetime'],
            $data['status'] ?? 'scheduled'
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];

        $allowed = ['student_id', 'counselor_id', 'mode', 'datetime', 'status'];
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
}