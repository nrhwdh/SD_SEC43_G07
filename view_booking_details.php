<?php
require_once __DIR__ . '/auth.php';
require_login();
$page_title = 'View Booking Details (Admin)';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $page_title ?></title>
</head>
<body>
  <h2><?= $page_title ?></h2>
  <p>[Placeholder: show detailed booking info, e.g. customer name, dates, payment, etc.]</p>
</body>
</html>
