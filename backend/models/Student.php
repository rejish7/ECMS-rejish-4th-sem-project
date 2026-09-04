<?php
class Student {
    private $db;
    private $table = 'students';

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($filters = []) {
        $sql = "SELECT s.*, c.name AS counselor_name FROM {$this->table} s LEFT JOIN counselors c ON s.counselor_id = c.id WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR email LIKE ? OR student_id LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['level'])) {
            $sql .= " AND education_level = ?";
            $params[] = $filters['level'];
        }

        if (!empty($filters['counselor_id'])) {
            $sql .= " AND counselor_id = ?";
            $params[] = $filters['counselor_id'];
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
        $stmt = $this->db->prepare("SELECT s.*, c.name AS counselor_name FROM {$this->table} s LEFT JOIN counselors c ON s.counselor_id = c.id WHERE s.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByEmail($email) {
        $stmt = $this->db->prepare("SELECT s.*, c.name AS counselor_name FROM {$this->table} s LEFT JOIN counselors c ON s.counselor_id = c.id WHERE s.email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR email LIKE ? OR student_id LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['level'])) {
            $sql .= " AND education_level = ?";
            $params[] = $filters['level'];
        }

        if (!empty($filters['counselor_id'])) {
            $sql .= " AND counselor_id = ?";
            $params[] = $filters['counselor_id'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (student_id, name, email, education_level, counselor_id, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())"
        );
        $stmt->execute([
            $data['student_id'],
            $data['name'],
            $data['email'],
            $data['education_level'],
            !empty($data['counselor_id']) ? $data['counselor_id'] : null,
            $data['status'] ?? 'active'
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];

        $allowed = ['student_id', 'name', 'email', 'education_level', 'counselor_id', 'status', 'avatar'];
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                if ($key === 'counselor_id') {
                    $value = !empty($value) ? $value : null;
                }
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