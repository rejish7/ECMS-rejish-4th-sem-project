<?php
class Counselor {
    private $db;
    private $table = 'counselors';

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR email LIKE ? OR specialization LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['specialization'])) {
            $sql .= " AND specialization = ?";
            $params[] = $filters['specialization'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY created_at DESC";

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
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR email LIKE ? OR specialization LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['specialization'])) {
            $sql .= " AND specialization = ?";
            $params[] = $filters['specialization'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (name, email, specialization, max_students, status, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())"
        );
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['specialization'],
            $data['max_students'] ?? 50,
            $data['status'] ?? 'available'
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];

        $allowed = ['name', 'email', 'specialization', 'max_students', 'status'];
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

    public function getAvailable() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'available' ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getLeastBusy() {
        $stmt = $this->db->prepare(
            "SELECT c.*, COALESCE(s.cnt, 0) AS student_count
             FROM {$this->table} c
             LEFT JOIN (
                 SELECT counselor_id, COUNT(*) AS cnt
                 FROM students
                 GROUP BY counselor_id
             ) s ON c.id = s.counselor_id
             WHERE c.status = 'available'
             ORDER BY student_count ASC, c.name ASC
             LIMIT 1"
        );
        $stmt->execute();
        return $stmt->fetch();
    }
}