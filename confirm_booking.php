<?php
include 'db.php';

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function dt($s){ return DateTime::createFromFormat('Y-m-d', $s); }
function redirect($url){
  header("Location: $url");
  exit;
}

/* ---------- read inputs (prefer POST, fallback to GET) ---------- */
$stage    = $_POST['stage']    ?? $_GET['stage']    ?? 'review';
$room_id  = isset($_POST['room_id']) ? (int)$_POST['room_id'] : (int)($_GET['room_id'] ?? 0);
$fullname = trim($_POST['fullname'] ?? ($_GET['fullname'] ?? ''));
$email    = trim($_POST['email']    ?? ($_GET['email']    ?? ''));
$check_in = trim($_POST['check_in'] ?? ($_GET['check_in'] ?? ''));
$check_out= trim($_POST['check_out']?? ($_GET['check_out']?? ''));
$guests   = (int)($_POST['guests']  ?? ($_GET['guests']   ?? 1));

/* ---------- must have room_id and minimum fields to review ---------- */
if ($room_id <= 0) {
  // if no room_id at all, go back to rooms instead of dying
  redirect('rooms.php');
}

/* ---------- load room ---------- */
$room = null;
$rs = $conn->prepare("SELECT * FROM rooms WHERE id=? LIMIT 1");
$rs->bind_param("i", $room_id);
$rs->execute();
$room = $rs->get_result()->fetch_assoc();
$rs->close();

if (!$room) {
  // unlikely now, but still handle
  echo '<div style="padding:2rem;font-family:Poppins,system-ui">Room not found. <a href="rooms.php">Back to rooms</a></div>';
  exit;
}

/* ---------- validate basic inputs for review/confirm ---------- */
$d1 = dt($check_in);
$d2 = dt($check_out);

if ($stage === 'review') {
  // If user reached confirm page without required fields, send them back to book form
  if ($fullname==='' || $email==='' || !$d1 || !$d2 || $d1 >= $d2) {
    // Preserve whatever we have to refill the form
    $qs = http_build_query([
      'room_id'   => $room_id,
      'check_in'  => $check_in,
      'check_out' => $check_out,
      'guests'    => max(1,$guests),
    ]);
    redirect("book.php?$qs");
  }
}

$alert = '';
$refId = null;

/* ---------- on confirm: check T&C + overlap + insert ---------- */
if ($stage === 'confirm') {
  if (empty($_POST['agree'])) {
    $alert = '<div class="alert alert-danger">You must agree to the Terms &amp; Conditions to proceed.</div>';
  } elseif ($fullname==='' || $email==='' || !$d1 || !$d2 || $d1 >= $d2) {
    $alert = '<div class="alert alert-danger">Invalid or missing fields. Please go back and review your booking.</div>';
  } else {
    // overlap check
    $sql = "SELECT 1 FROM bookings
            WHERE room_id = ?
              AND NOT (? >= check_out OR ? <= check_in)
            LIMIT 1";
    $st = $conn->prepare($sql);
    $st->bind_param("iss", $room_id, $check_out, $check_in);
    $st->execute();
    $ov = $st->get_result();
    $st->close();

    if ($ov && $ov->num_rows > 0) {
      $alert = '<div class="alert alert-danger">Sorry, the room just became unavailable for those dates. Please pick different dates.</div>';
    } else {
      $nights = (int)$d1->diff($d2)->days;
      $total  = $nights * (float)$room['price'];

      $ins = $conn->prepare("INSERT INTO bookings
        (room_id, check_in, check_out, nights, guests, guest_name, guest_email, total)
        VALUES (?,?,?,?,?,?,?,?)");
      $ins->bind_param(
        "issiiisd",
        $room_id, $check_in, $check_out, $nights, $guests, $fullname, $email, $total
      );

      if ($ins->execute()) {
        $refId = $ins->insert_id;
        $alert = '<div class="alert alert-success mb-4">Booking confirmed! Reference: <strong>#'.$refId.'</strong>. Total <strong>RM '.number_format($total,2).'</strong></div>';
      } else {
        $alert = '<div class="alert alert-danger mb-4">Error saving booking: '.h($conn->error).'</div>';
      }
      $ins->close();
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Confirm Booking | The Pearl Hotel</title>
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
    <h1 class="fw-800 mb-0"><?= $refId ? 'Booking Confirmed' : 'Confirm Booking' ?></h1>
    <a href="book.php?room_id=<?= (int)$room_id ?>&check_in=<?= h($check_in) ?>&check_out=<?= h($check_out) ?>&guests=<?= (int)$guests ?>" class="btn btn-outline-secondary btn-sm">← Edit details</a>
  </div>

  <?= $alert ?>

  <?php if(!$refId): ?>
    <?php
      $nights = (int)$d1->diff($d2)->days;
      $total  = $nights * (float)$room['price'];
    ?>
    <div class="row g-4">
      <div class="col-lg-6">
        <div class="card shadow-sm rounded-4">
          <img src="<?= h($room['image']) ?>" class="card-img-top" alt="<?= h($room['name']) ?>">
          <div class="card-body">
            <h5 class="card-title mb-1"><?= h($room['name']) ?></h5>
            <p class="text-muted small mb-2"><?= h($room['beds']) ?> • <?= h($room['size']) ?></p>
            <p class="mb-0 fw-bold">RM <?= number_format((float)$room['price'],2) ?>/night</p>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card shadow-sm rounded-4">
          <div class="card-body">
            <h5 class="fw-bold mb-3">Review your details</h5>
            <ul class="list-group mb-3">
              <li class="list-group-item d-flex justify-content-between"><span>Guest</span><strong><?= h($fullname) ?></strong></li>
              <li class="list-group-item d-flex justify-content-between"><span>Email</span><strong><?= h($email) ?></strong></li>
              <li class="list-group-item d-flex justify-content-between"><span>Check-in</span><strong><?= h($check_in) ?></strong></li>
              <li class="list-group-item d-flex justify-content-between"><span>Check-out</span><strong><?= h($check_out) ?></strong></li>
              <li class="list-group-item d-flex justify-content-between"><span>Guests</span><strong><?= (int)$guests ?></strong></li>
              <li class="list-group-item d-flex justify-content-between"><span>Nights</span><strong><?= $nights ?></strong></li>
              <li class="list-group-item d-flex justify-content-between"><span>Total</span><strong>RM <?= number_format($total,2) ?></strong></li>
            </ul>

            <form method="post" novalidate>
              <input type="hidden" name="stage"    value="confirm">
              <input type="hidden" name="room_id"  value="<?= (int)$room_id ?>">
              <input type="hidden" name="fullname" value="<?= h($fullname) ?>">
              <input type="hidden" name="email"    value="<?= h($email) ?>">
              <input type="hidden" name="check_in" value="<?= h($check_in) ?>">
              <input type="hidden" name="check_out"value="<?= h($check_out) ?>">
              <input type="hidden" name="guests"   value="<?= (int)$guests ?>">

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="1" id="agree" name="agree" required>
                <label class="form-check-label" for="agree">
                  I agree to the <a href="#" onclick="alert('Standard hotel Terms & Conditions apply.');return false;">Terms &amp; Conditions</a>.
                </label>
              </div>

              <button class="btn btn-brand" type="submit">Confirm &amp; Secure Room</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="card shadow-sm rounded-4">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Reservation Receipt</h5>
        <p class="mb-1"><strong>Reference:</strong> #<?= (int)$refId ?></p>
        <p class="mb-1"><strong>Room:</strong> <?= h($room['name']) ?></p>
        <p class="mb-1"><strong>Guest:</strong> <?= h($fullname) ?> (<?= h($email) ?>)</p>
        <p class="mb-1"><strong>Dates:</strong> <?= h($check_in) ?> → <?= h($check_out) ?></p>
        <p class="mb-1"><strong>Guests:</strong> <?= (int)$guests ?></p>
        <p class="mb-3"><em>We’ve secured your room. See you soon!</em></p>
        <a href="rooms.php" class="btn btn-outline-secondary btn-sm me-2">Back to Rooms</a>
        <button class="btn btn-brand btn-sm" onclick="window.print()">Print Confirmation</button>
      </div>
    </div>
  <?php endif; ?>
</section>

<footer class="py-4 border-top mt-5">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <p class="mb-0 small">© 2025 The Pearl Hotel</p>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
