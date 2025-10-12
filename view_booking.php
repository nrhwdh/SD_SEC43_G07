<?php
require_once __DIR__ . '/auth.php';
require_login();
$page_title = 'View Booking (Admin)';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $page_title ?></title>
</head>
<body>
  <h2><?= $page_title ?></h2>
  <table border="1" cellpadding="5">
    <tr><th>Booking ID</th><th>Customer</th><th>Room</th><th>Status</th></tr>
    <tr><td>1001</td><td>Jane Smith</td><td>Deluxe Room</td><td>Confirmed</td></tr>
  </table>
  <p>[Placeholder: list all bookings from DB]</p>
</body>
</html>
