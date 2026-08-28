<?php
class Course {
    private $db;
    private $table = 'courses';

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($filters = []) {
        $sql = "SELECT c.*, col.name AS college_name, col.code AS college_code, col.country AS college_country
                FROM {$this->table} c
                LEFT JOIN colleges col ON c.college_id = col.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (c.name LIKE ? OR c.code LIKE ? OR col.name LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['college_id'])) {
            $sql .= " AND c.college_id = ?";
            $params[] = $filters['college_id'];
        }

        if (!empty($filters['level'])) {
            $sql .= " AND c.level = ?";
            $params[] = $filters['level'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND c.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['country'])) {
            $sql .= " AND col.country = ?";
            $params[] = $filters['country'];
        }

        $sql .= " ORDER BY c.name ASC";

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
            "SELECT c.*, col.name AS college_name, col.code AS college_code, col.country AS college_country
             FROM {$this->table} c
             LEFT JOIN colleges col ON c.college_id = col.id
             WHERE c.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table} c LEFT JOIN colleges col ON c.college_id = col.id WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (c.name LIKE ? OR c.code LIKE ? OR col.name LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['college_id'])) {
            $sql .= " AND c.college_id = ?";
            $params[] = $filters['college_id'];
        }

        if (!empty($filters['level'])) {
            $sql .= " AND c.level = ?";
            $params[] = $filters['level'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND c.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['country'])) {
            $sql .= " AND col.country = ?";
            $params[] = $filters['country'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getStats() {
        $stmt = $this->db->prepare(
            "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS active,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) AS inactive,
                SUM(CASE WHEN status = 'review' THEN 1 ELSE 0 END) AS review
             FROM {$this->table}"
        );
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (college_id, name, code, level, duration, description, requirements, tuition_fee, currency, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['college_id'],
            $data['name'],
            $data['code'],
            $data['level'],
            $data['duration'],
            $data['description'] ?? null,
            $data['requirements'] ?? null,
            $data['tuition_fee'] ?? null,
            $data['currency'] ?? 'USD',
            $data['status'] ?? 'active'
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];

        $allowed = ['college_id', 'name', 'code', 'level', 'duration', 'description', 'requirements', 'tuition_fee', 'currency', 'status'];
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
