<?php
include 'db.php';

/* ----------------------- Load room ----------------------- */
$room_id = isset($_GET['room_id']) ? (int)$_GET['room_id'] : 0;
$room = null;
if ($room_id > 0) {
  $rs = $conn->prepare("SELECT * FROM rooms WHERE id=? LIMIT 1");
  $rs->bind_param("i", $room_id);
  $rs->execute();
  $room = $rs->get_result()->fetch_assoc();
  $rs->close();
}
if (!$room) {
  die('<div style="padding:2rem;font-family:Poppins,system-ui">Room not found. <a href="rooms.php">Back to rooms</a></div>');
}

/* ----------------------- Prefill from GET ----------------------- */
$prefill_check_in  = '';
$prefill_check_out = '';
$prefill_guests    = 1;

if (!empty($_GET['check_in']))  { $prefill_check_in  = preg_replace('/[^0-9\-]/', '', $_GET['check_in']); }
if (!empty($_GET['check_out'])) { $prefill_check_out = preg_replace('/[^0-9\-]/', '', $_GET['check_out']); }
if (!empty($_GET['guests']))    { $prefill_guests    = max(1, (int)$_GET['guests']); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Book Room | The Pearl Hotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
  <style>
    body{font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;}
    .brand-logo{height:100px;width:auto;}
    @media (max-width:576px){ .brand-logo{height:72px;} }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="assets/img/logo-pearl.png" alt="The Pearl Hotel" class="brand-logo">
      <span class="visually-hidden">The Pearl Hotel</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-800 mb-0">Book Room</h1>
    <a href="rooms.php" class="btn btn-outline-secondary btn-sm">← Back to Rooms</a>
  </div>

  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card shadow-sm rounded-4">
        <img src="<?= htmlspecialchars($room['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($room['name']) ?>">
        <div class="card-body">
          <h5 class="card-title mb-1"><?= htmlspecialchars($room['name']) ?></h5>
          <p class="text-muted small mb-2"><?= htmlspecialchars($room['beds']) ?> • <?= htmlspecialchars($room['size']) ?></p>
          <p class="mb-0 fw-bold">RM <?= number_format((float)$room['price'],2) ?>/night</p>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card shadow-sm rounded-4">
        <div class="card-body">
          <!-- Step 1: collect info, then go to confirmation -->
          <form method="post" action="confirm_booking.php" novalidate>
            <input type="hidden" name="stage" value="review">
            <input type="hidden" name="room_id" value="<?= (int)$room_id ?>">

            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" name="fullname" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Check-in</label>
                <input type="date" name="check_in" class="form-control" required
                       value="<?= htmlspecialchars($prefill_check_in) ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Check-out</label>
                <input type="date" name="check_out" class="form-control" required
                       value="<?= htmlspecialchars($prefill_check_out) ?>">
              </div>
            </div>

            <div class="mt-3 mb-4">
              <label class="form-label">Guests</label>
              <select name="guests" class="form-select">
                <?php for($i=1;$i<=6;$i++): ?>
                  <option value="<?= $i ?>" <?= $i==$prefill_guests?'selected':'' ?>><?= $i ?></option>
                <?php endfor; ?>
              </select>
            </div>

            <button class="btn btn-brand" type="submit">Review Booking</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<footer class="py-4 border-top mt-5">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <p class="mb-0 small">© 2025 The Pearl Hotel</p>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
