<?php
class PasswordReset {
    private $db;
    private $table = 'password_resets';

    public function __construct() {
        $this->db = getDB();
    }

    public function createToken($email) {
        $token = bin2hex(random_bytes(32));
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (email, token, created_at)
             VALUES (?, ?, NOW())"
        );
        $stmt->execute([$email, $token]);
        return $token;
    }

    public function getToken($token) {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table}
             WHERE token = ?
             ORDER BY created_at DESC
             LIMIT 1"
        );
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function deleteByEmail($email) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
    }

    public function deleteToken($token) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE token = ?");
        $stmt->execute([$token]);
    }

    public function isExpired($createdAt, $minutes = 60) {
        $created = strtotime($createdAt);
        $now = time();
        return ($now - $created) > ($minutes * 60);
    }

    public function updatePassword($email, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE email = ?");
        return $stmt->execute([$hashed, $email]);
    }
}
