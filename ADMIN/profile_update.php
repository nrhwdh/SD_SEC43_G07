<?php
require_once __DIR__.'/auth.php';
require_login();
require_once __DIR__.'/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = $_SESSION['admin_id']; // ambil ID admin semasa
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    try {
        $stmt = $pdo->prepare("UPDATE admins SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $id]);
        header("Location: profile.php?success=1");
        exit;
    } catch (Exception $e) {
        header("Location: profile.php?error=1");
        exit;
    }
} else {
    header("Location: profile.php");
    exit;
}