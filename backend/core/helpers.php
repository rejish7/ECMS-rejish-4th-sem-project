<?php
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function url($path = '') {
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    return $base . '/' . ltrim($path, '/');
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}

function old($key, $default = '') {
    return $_SESSION['old'][$key] ?? $default;
}

function field_error($key) {
    return $_SESSION['errors'][$key] ?? '';
}

function flash($key, $message = null) {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
    } else {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }
}

function csrf_token() {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf() {
    return !empty($_POST['csrf_token']) && hash_equals($_SESSION['_csrf'] ?? '', $_POST['csrf_token']);
}

function setRememberCookie($user) {
    $secret = getenv('APP_SECRET') ?: 'ecms-change-this-secret-key';
    $signature = hash_hmac('sha256', $user['id'] . $user['password'], $secret);
    $value = json_encode(['uid' => $user['id'], 'sig' => $signature]);
    setcookie('ecms_remember', $value, time() + (30 * 24 * 60 * 60), '/');
}

function clearRememberCookie() {
    setcookie('ecms_remember', '', time() - 3600, '/');
}

function restoreRememberedUser() {
    if (empty($_COOKIE['ecms_remember'])) {
        return false;
    }

    $data = json_decode($_COOKIE['ecms_remember'], true);
    if (!is_array($data) || empty($data['uid']) || empty($data['sig'])) {
        return false;
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$data['uid']]);
    $user = $stmt->fetch();

    $secret = getenv('APP_SECRET') ?: 'ecms-change-this-secret-key';
    if (!$user || !hash_equals(hash_hmac('sha256', $user['id'] . $user['password'], $secret), $data['sig'])) {
        return false;
    }

    session_regenerate_id(true);
    $authUser = $user;
    unset($authUser['password']);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user'] = $authUser;
    return true;
}

function isLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        return true;
    }
    return restoreRememberedUser();
}

function getUser() {
    return $_SESSION['user'] ?? null;
}

function dashboardPathFor($role = null) {
    $role = $role ?? (getUser()['role'] ?? 'admin');
    switch ($role) {
        case 'counselor':
            return '/counselor/dashboard';
        case 'student':
            return '/student/dashboard';
        case 'admin':
        default:
            return '/admin/dashboard';
    }
}

function redirectToDashboard($role = null) {
    redirect(url(dashboardPathFor($role)));
}

function sendMail($to, $subject, $body) {
    $mailer = new Mailer();
    return $mailer->send($to, $subject, $body);
}

function generateTempPassword($length = 10) {
    $alphabet = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789';
    $out = '';
    for ($i = 0; $i < $length; $i++) {
        $out .= $alphabet[random_int(0, strlen($alphabet) - 1)];
    }
    return $out;
}

function createLoginAccount($role, $user_id, $name, $email, $password = null) {
    $db = getDB();

    $stmt = $db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['created' => false, 'reason' => 'exists'];
    }

    $password = $password ?: generateTempPassword();
    $stmt = $db->prepare(
        "INSERT INTO users (user_id, name, email, password, role, status, created_at)
         VALUES (?, ?, ?, ?, ?, 'active', NOW())"
    );
    $stmt->execute([$user_id, $name, $email, password_hash($password, PASSWORD_DEFAULT), $role]);

    $roleLabel = $role === 'student' ? 'student' : 'counselor';
    $sent = sendMail(
        $email,
        'Your ' . $roleLabel . ' account on ECMS',
        buildAccountEmailBody($roleLabel, $name, $email, $password)
    );

    return ['created' => true, 'password' => $password, 'emailed' => $sent];
}

function changeUserPassword($userId, $current, $new) {
    $db = getDB();

    if (strlen($new) < 8) {
        return ['ok' => false, 'error' => 'New password must be at least 8 characters.'];
    }

    $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    if (!$row || !password_verify($current, $row['password'])) {
        return ['ok' => false, 'error' => 'Current password is incorrect.'];
    }

    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([password_hash($new, PASSWORD_DEFAULT), $userId]);
    return ['ok' => true];
}

function createNotification($userId, $title, $message, $link = null) {
    require_once MODEL_PATH . '/Notification.php';
    $model = new Notification();
    return $model->create([
        'user_id' => $userId,
        'title' => $title,
        'message' => $message,
        'link' => $link,
    ]);
}

function createNotificationByEmail($email, $title, $message, $link = null) {
    require_once MODEL_PATH . '/Notification.php';
    $model = new Notification();
    $userId = $model->findUserIdByEmail($email);
    if ($userId) {
        return $model->create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'link' => $link,
        ]);
    }
    return false;
}

function getUnreadNotificationCount($userId) {
    require_once MODEL_PATH . '/Notification.php';
    $model = new Notification();
    return $model->getUnreadCount($userId);
}

function buildAccountEmailBody($roleLabel, $name, $email, $password) {
    $loginUrl = url('/login');
    return '<html><body style="font-family:Arial,Helvetica,sans-serif;color:#0b1c30;">'
        . '<h2 style="margin:0 0 16px;color:#0b1c30;">Welcome to ECMS</h2>'
        . '<p style="margin:0 0 16px;">Hi ' . $name . ',</p>'
        . '<p style="margin:0 0 16px;">Your ' . $roleLabel . ' account has been created. Use the credentials below to sign in:</p>'
        . '<table cellpadding="0" cellspacing="0" style="background:#f5f7fa;border-radius:8px;padding:16px;margin:16px 0;">'
        . '<tr><td style="padding:4px 12px 4px 0;color:#73777f;">Email</td><td style="font-weight:600;">' . $email . '</td></tr>'
        . '<tr><td style="padding:4px 12px 4px 0;color:#73777f;">Password</td><td style="font-weight:600;">' . $password . '</td></tr>'
        . '</table>'
        . '<p><a href="' . $loginUrl . '" style="display:inline-block;background:#0054cb;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;">Sign in to your dashboard</a></p>'
        . '<p style="color:#73777f;font-size:13px;">For security, please change this password after your first login.</p>'
        . '</body></html>';
}
