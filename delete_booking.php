<?php
require_once __DIR__ . '/auth.php';
require_login();
$page_title = 'Delete Booking (Admin)';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $page_title ?></title>
</head>
<body>
  <h2><?= $page_title ?></h2>
  <form method="post">
    <label>Booking ID:</label><br><input type="text" name="booking_id" required><br><br>
    <button type="submit" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</button>
  </form>
  <p>[Placeholder: booking record will be deleted from database]</p>
</body>
</html>
