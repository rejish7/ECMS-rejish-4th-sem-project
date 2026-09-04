<?php
class Notification {
    private $db;
    private $table = 'notifications';

    public function __construct() {
        $this->db = getDB();
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (user_id, title, message, link, is_read, created_at)
             VALUES (?, ?, ?, ?, 0, NOW())"
        );
        $stmt->execute([
            $data['user_id'],
            $data['title'],
            $data['message'],
            $data['link'] ?? null,
        ]);
        return $this->db->lastInsertId();
    }

    public function getByUserId($userId, $limit = 20) {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table}
             WHERE user_id = ?
             ORDER BY created_at DESC
             LIMIT ?"
        );
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }

    public function getUnreadCount($userId) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM {$this->table} WHERE user_id = ? AND is_read = 0"
        );
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public function markAsRead($id, $userId) {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET is_read = 1 WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([$id, $userId]);
    }

    public function markAllAsRead($userId) {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET is_read = 1 WHERE user_id = ? AND is_read = 0"
        );
        return $stmt->execute([$userId]);
    }

    public function delete($id, $userId) {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([$id, $userId]);
    }

    public function notifyUsers($userIds, $title, $message, $link = null) {
        if (empty($userIds)) return;
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (user_id, title, message, link, is_read, created_at)
             VALUES (?, ?, ?, ?, 0, NOW())"
        );
        foreach ($userIds as $uid) {
            $stmt->execute([$uid, $title, $message, $link]);
        }
    }

    public function findUserIdByEmail($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ? $row['id'] : null;
    }
}
