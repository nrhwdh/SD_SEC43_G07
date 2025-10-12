<?php
require_once __DIR__ . '/auth.php';
require_login();
$page_title = 'View Feedback (Admin)';
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
    <tr><th>User</th><th>Feedback</th><th>Date</th></tr>
    <tr><td>John Doe</td><td>Room was clean and comfortable.</td><td>2025-10-12</td></tr>
  </table>
  <p>[Placeholder: Fetch feedback records from database here later]</p>
</body>
</html>
