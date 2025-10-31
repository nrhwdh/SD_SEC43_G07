<?php
require_once __DIR__.'/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  header('Location: bookings_view.php');
  exit;
}

// Confirm record exists
$chk = $pdo->prepare("SELECT id FROM bookings WHERE id=?");
$chk->execute([$id]);
if (!$chk->fetch()) {
  header('Location: bookings_view.php');
  exit;
}

// Delete + redirect with success flag
$del = $pdo->prepare("DELETE FROM bookings WHERE id=? LIMIT 1");
$del->execute([$id]);

header('Location: bookings_view.php?msg=deleted');
exit;