<?php
require_once __DIR__ . '/auth.php';
require_login();
$page_title = 'Edit Booking (Admin)';
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
    <label>Booking ID:</label><br><input type="text" name="booking_id"><br><br>
    <label>New Status:</label><br>
    <select name="status">
      <option>Pending</option>
      <option>Confirmed</option>
      <option>Cancelled</option>
    </select><br><br>
    <button type="submit">Update Booking</button>
  </form>
  <p>[Placeholder: update booking details in DB]</p>
</body>
</html>
