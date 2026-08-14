<?php
class User {
    private $db;
    private $table = 'users';

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($filters = []) {
        $sql = "(SELECT id, user_id, name, email, role, status, created_at FROM users
                 UNION ALL
                 SELECT id, student_id AS user_id, name, email, 'student' AS role, status, created_at FROM students
                 UNION ALL
                 SELECT id, email AS user_id, name, email, 'counselor' AS role, status, created_at FROM counselors) AS all_users WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR email LIKE ? OR user_id LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['role'])) {
            $sql .= " AND role = ?";
            $params[] = $filters['role'];
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

        $stmt = $this->db->prepare("SELECT * FROM {$sql}");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare(
            "(SELECT id, user_id, name, email, 'admin' AS role, status, created_at FROM users WHERE id = ?
             UNION ALL
             SELECT id, student_id AS user_id, name, email, 'student' AS role, status, created_at FROM students WHERE id = ?
             UNION ALL
             SELECT id, email AS user_id, name, email, 'counselor' AS role, status, created_at FROM counselors WHERE id = ?)"
        );
        $stmt->execute([$id, $id, $id]);
        return $stmt->fetch();
    }

    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM (SELECT id, role, status FROM users
                 UNION ALL
                 SELECT id, 'student' AS role, status FROM students
                 UNION ALL
                 SELECT id, 'counselor' AS role, status FROM counselors) AS all_users WHERE 1=1";
        $params = [];

        if (!empty($filters['role'])) {
            $sql .= " AND role = ?";
            $params[] = $filters['role'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
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
                0 AS new_this_month
             FROM (SELECT status FROM users UNION ALL SELECT status FROM students UNION ALL SELECT status FROM counselors) AS all_users"
        );
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (user_id, name, email, password, role, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())"
        );
        $stmt->execute([
            $data['user_id'],
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'],
            $data['status'] ?? 'active'
        ]);
        return $this->db->lastInsertId();
    }

    private function getTableForRole($role) {
        $map = [
            'admin' => 'users',
            'student' => 'students',
            'counselor' => 'counselors',
        ];
        return $map[$role] ?? 'users';
    }

    public function update($id, $data) {
        $user = $this->getById($id);
        if (!$user) return false;

        $table = $this->getTableForRole($user['role']);
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            if ($key === 'password') {
                if (empty($value)) continue;
                $fields[] = "password = ?";
                $params[] = password_hash($value, PASSWORD_DEFAULT);
            } else {
                $fields[] = "{$key} = ?";
                $params[] = $value;
            }
        }

        if (empty($fields)) return true;

        $params[] = $id;
        $sql = "UPDATE {$table} SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $user = $this->getById($id);
        if (!$user) return false;

        $table = $this->getTableForRole($user['role']);
        $stmt = $this->db->prepare("DELETE FROM {$table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}