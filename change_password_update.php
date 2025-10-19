<?php
require_once __DIR__.'/auth.php';
require_login();
require_once __DIR__.'/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['admin_id'];
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // ambil hash dari DB
    $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE id = ?");
    $stmt->execute([$id]);
    $hash = $stmt->fetchColumn();

    if (!$hash) {
        $_SESSION['flash_error'] = "Account not found.";
    } elseif (!password_verify($current, $hash)) {
        $_SESSION['flash_error'] = "Current password is incorrect.";
    } elseif ($new !== $confirm) {
        $_SESSION['flash_error'] = "New passwords do not match.";
    } elseif (strlen($new) < 8) {
        $_SESSION['flash_error'] = "Password must be at least 8 characters.";
    } else {
        $new_hash = password_hash($new, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE admins SET password_hash = ?, password_changed_at = NOW() WHERE id = ?");
        $update->execute([$new_hash, $id]);
        $_SESSION['flash_success'] = "Password updated successfully!";
    }

    header("Location: change_password.php");
    exit;
}