<?php
include 'db.php';
$ref = $_GET['ref'] ?? '';
$bk  = null;
if($ref){
  $st = $conn->prepare("SELECT b.*, r.name AS room_name, r.price AS room_price FROM bookings b JOIN rooms r ON r.id=b.room_id WHERE b.ref=?");
  $st->bind_param("s",$ref); $st->execute(); $bk = $st->get_result()->fetch_assoc(); $st->close();
}
if(!$bk){ die("Booking not found."); }

$alert = "";
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['confirm'])){
  $u = $conn->prepare("UPDATE bookings SET status='CONFIRMED' WHERE ref=? AND status='PENDING'");
  $u->bind_param("s",$ref);
  if($u->execute()){
    header("Location: invoice.php?ref=".urlencode($ref));
    exit;
  } else { $alert = '<div class="alert alert-danger">Error confirming: '.htmlspecialchars($conn->error).'</div>'; }
  $u->close();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>The Pearl Hotel | Confirm Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css"><link rel="stylesheet" href="assets/css/fancy.css">
  <style>.brand-logo{height:40px}</style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="assets/img/logo-pearl.png" class="brand-logo" alt="">
    </a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav"><ul class="navbar-nav ms-auto"><li class="nav-item"><a class="nav-link" href="bookings.php">My bookings</a></li></ul></div>
  </div>
</nav>

<section class="container py-5">
  <h1 class="fw-800 mb-3">Confirm your booking</h1>
  <?= $alert ?>
  <div class="card">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <h5 class="fw-bold mb-1"><?= htmlspecialchars($bk['room_name']) ?></h5>
          <p class="text-muted small mb-2">Ref: <?= htmlspecialchars($bk['ref']) ?> · Status: <span class="badge bg-secondary"><?= htmlspecialchars($bk['status']) ?></span></p>
          <p class="mb-0">Guest: <strong><?= htmlspecialchars($bk['name']) ?></strong><br>Email: <?= htmlspecialchars($bk['email']) ?><br>Phone: <?= htmlspecialchars($bk['phone']) ?></p>
        </div>
        <div class="col-md-6">
          <p class="mb-1">Check-in: <strong><?= htmlspecialchars($bk['checkin']) ?></strong></p>
          <p class="mb-1">Check-out: <strong><?= htmlspecialchars($bk['checkout']) ?></strong></p>
          <p class="mb-1">Nights: <strong><?= (int)$bk['nights'] ?></strong> · Guests: <strong><?= (int)$bk['guests'] ?></strong></p>
          <p class="mb-0 h5">Total: RM <?= htmlspecialchars($bk['total']) ?></p>
        </div>
      </div>
    </div>
    <div class="card-footer d-flex justify-content-end gap-2">
      <a class="btn btn-outline-secondary" href="bookings.php?email=<?= urlencode($bk['email']) ?>">View later</a>
      <?php if($bk['status']==='PENDING'): ?>
        <form method="post" class="d-inline"><button name="confirm" class="btn btn-brand">Confirm &amp; get invoice</button></form>
      <?php else: ?>
        <a class="btn btn-brand" href="invoice.php?ref=<?= urlencode($bk['ref']) ?>">Open invoice</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<footer class="py-4 border-top mt-5"><div class="container"></div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
