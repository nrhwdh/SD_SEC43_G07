<?php
// admin/auth.php  —  versi stabil (tanpa mailer/extra)
require_once __DIR__.'/db.php';

// start session sekali sahaja
if (session_status() !== PHP_SESSION_ACTIVE) {
    // optional: kukuhkan cookie session
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// helper kecil
function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

// --- LOGIN CORE ---
function attempt_login(string $email, string $password): array {
    global $pdo;

    $email = trim(strtolower($email));
    $password = (string)$password;

    if ($email === '' || $password === '') {
        return [false, 'Please fill in email and password.'];
    }

    // cari admin
    $st = $pdo->prepare("SELECT id, name, email, password_hash FROM admins WHERE LOWER(email)=? LIMIT 1");
    $st->execute([$email]);
    $row = $st->fetch();

    if (!$row || !password_verify($password, $row['password_hash'])) {
        return [false, 'Invalid credentials.'];
    }

    // success
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
