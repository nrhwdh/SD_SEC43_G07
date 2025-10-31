<?php include 'db.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>The Pearl Hotel | Messages</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">The Pearl Hotel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="messages.php">Messages</a></li>
        <li class="nav-item"><a class="nav-link" href="bookings.php">Bookings</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="container py-5">
  <h1 class="fw-bold mb-4">Contact Messages</h1>
<?php
$res = $conn->query("SELECT id,name,email,message,created_at FROM contact ORDER BY created_at DESC");
if($res && $res->num_rows>0){
  echo '<div class="table-responsive"><table class="table table-bordered align-middle">';
  echo '<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Created</th></tr></thead><tbody>';
  while($r = $res->fetch_assoc()){
    echo '<tr><td>'.(int)$r['id'].'</td><td>'.htmlspecialchars($r['name']).'</td><td>'.htmlspecialchars($r['email']).'</td><td>'.nl2br(htmlspecialchars($r['message'])).'</td><td>'.htmlspecialchars($r['created_at']).'</td></tr>';
  }
  echo '</tbody></table></div>';
} else { echo '<div class="alert alert-secondary">No messages yet.</div>'; }
?>
</section>

<footer class="py-4 border-top mt-5">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <p class="mb-0 small">© 2025 The Pearl Hotel — Coursework Demo</p>
    <ul class="nav">
      <li class="nav-item"><a class="nav-link px-2 small" href="index.php">Home</a></li>
      <li class="nav-item"><a class="nav-link px-2 small" href="rooms.php">Rooms</a></li>
      <li class="nav-item"><a class="nav-link px-2 small" href="about.php">About</a></li>
      <li class="nav-item"><a class="nav-link px-2 small" href="contact.php">Contact</a></li>
      <li class="nav-item"><a class="nav-link px-2 small" href="messages.php">Messages</a></li>
      <li class="nav-item"><a class="nav-link px-2 small" href="bookings.php">Bookings</a></li>
    </ul>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
