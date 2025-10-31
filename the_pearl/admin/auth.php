<?php
// admin/auth.php
require_once __DIR__ .'/db.php';

// Start session sekali sahaja
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', 1);   // elak session hijack
    session_start();
}

// Helper kecil utk escape HTML
function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

// --- LOGIN CORE ---
function attempt_login(string $email, string $password): array {
    global $pdo;

    $email = trim(strtolower($email));
    $password = (string)$password;

    if ($email === '' || $password === '') {
        return [false, 'Please fill in email and password.'];
    }

    // Cari admin ikut email
    $st = $pdo->prepare("SELECT id, name, email, password_hash FROM admins WHERE LOWER(email)=? LIMIT 1");
    $st->execute([$email]);
    $row = $st->fetch();

    // Helper utk log attempt
    $logAttempt = function(int $success) use ($pdo, $email) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $pdo->prepare("INSERT INTO login_attempts(email, ip, created_at, success) VALUES(?, ?, NOW(), ?)")
            ->execute([$email, $ip, $success]);
    };

    // Kalau email tak jumpa
    if (!$row) {
        $logAttempt(0);
        return [false, 'Email not found.'];
    }

    // Kalau password salah
    if (!password_verify($password, $row['password_hash'])) {
        $logAttempt(0);
        return [false, 'Wrong password.'];
    }

    // Kalau berjaya login
    $logAttempt(1);
    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int)$row['id'];
    return [true, null];
}

// --- GUARD / CURRENT USER ---
function require_login(): void {
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

function current_admin(bool $force=false) {
    if (empty($_SESSION['admin_id'])) return null;
    static $me;
    if ($force || !$me) {
        global $pdo;
        $st = $pdo->prepare("SELECT id, name, email, avatar, created_at, phone FROM admins WHERE id=? LIMIT 1");
        $st->execute([$_SESSION['admin_id']]);
        $me = $st->fetch();
    }
    return $me;
}