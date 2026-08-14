<?php
class College {
    private $db;
    private $table = 'colleges';

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR code LIKE ? OR country LIKE ? OR city LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['country'])) {
            $sql .= " AND country = ?";
            $params[] = $filters['country'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY name ASC";

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

    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR code LIKE ? OR country LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['country'])) {
            $sql .= " AND country = ?";
            $params[] = $filters['country'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getCountries() {
        $stmt = $this->db->prepare("SELECT DISTINCT country FROM {$this->table} ORDER BY country ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (name, code, country, city, website, contact_email, contact_phone, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['name'],
            $data['code'],
            $data['country'],
            $data['city'] ?? null,
            $data['website'] ?? null,
            $data['contact_email'] ?? null,
            $data['contact_phone'] ?? null,
            $data['status'] ?? 'active'
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];

        $allowed = ['name', 'code', 'country', 'city', 'website', 'contact_email', 'contact_phone', 'status'];
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
