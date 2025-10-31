<?php
// availability.php
include 'db.php';

// --- Helpers ---
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function d($s){
  $t = DateTime::createFromFormat('Y-m-d', $s);
  return $t && $t->format('Y-m-d') === $s ? $s : null;
}

// --- Read + validate inputs ---
$check_in  = isset($_GET['check_in'])  ? d($_GET['check_in'])  : '';
$check_out = isset($_GET['check_out']) ? d($_GET['check_out']) : '';
$today     = (new DateTime('today'))->format('Y-m-d');

$errors = [];
if($check_in !== '' || $check_out !== ''){
  if(!$check_in)  $errors[] = "Please choose a valid check-in date.";
  if(!$check_out) $errors[] = "Please choose a valid check-out date.";
  if(!$errors){
    if($check_in < $today) $errors[] = "Check-in can’t be in the past.";
    if($check_out <= $check_in) $errors[] = "Check-out must be **after** check-in.";
  }
}

// --- Query available rooms when dates are valid ---
$rooms = null;
if(!$errors && $check_in && $check_out){
  // Overlap rule: a booking blocks a room if (b.check_in < :out AND b.check_out > :in)
  // So available means: NOT EXISTS such a booking in the selected window
  $sql = "
    SELECT r.*
    FROM rooms r
    WHERE NOT EXISTS (
      SELECT 1
      FROM bookings b
      WHERE b.room_id = r.id
        AND b.status IN ('confirmed','paid','pending')
        AND b.check_in < ?
        AND b.check_out > ?
    )
    ORDER BY r.price ASC, r.name ASC
  ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $check_out, $check_in);
  $stmt->execute();
  $rooms = $stmt->get_result();
  $stmt->close();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>The Pearl Hotel | Availability</title>

  <!-- Vendor & fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <!-- Your styles -->
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/fancy.css">
  <style>
    body{font-family:'Poppins',sans-serif;}
    .brand-logo{height:40px;width:auto;}
    .card.room-card{border:1px solid #eee;border-radius:1rem;overflow:hidden;box-shadow:0 6px 20px rgba(0,0,0,.06);}
    .room-img{object-fit:cover;width:100%;height:100%;}
    .ratio-4x3{aspect-ratio:4/3;}
    .form-soft{background:#fff;border:1px solid #eee;border-radius:1rem;box-shadow:0 8px 24px rgba(0,0,0,.06);}
    .price-tag{font-weight:800;color:#8a1538;}
  </style>
</head>
<body>

<!-- Navbar -->
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
        <li class="nav-item"><a class="nav-link active" href="availability.php">Availability</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="container py-5">
  <h1 class="fw-800 mb-3">Check Availability</h1>

  <form class="p-3 p-md-4 form-soft mb-4" method="get" action="availability.php">
    <div class="row g-3 align-items-end">
      <div class="col-sm-6 col-lg-3">
        <label class="form-label">Check-in</label>
        <input type="date" name="check_in" class="form-control"
               value="<?= h($check_in) ?>"
               min="<?= h($today) ?>" required>
      </div>
      <div class="col-sm-6 col-lg-3">
        <label class="form-label">Check-out</label>
        <input type="date" name="check_out" class="form-control"
               value="<?= h($check_out) ?>"
               min="<?= h($today) ?>" required>
      </div>
      <div class="col-lg-3">
        <label class="form-label d-none d-lg-block">&nbsp;</label>
        <button class="btn btn-brand w-100">Search</button>
      </div>
      <div class="col-lg-3 text-lg-end">
        <a href="rooms.php" class="btn btn-outline-secondary w-100">Back to Rooms</a>
      </div>
    </div>
  </form>

  <?php if($errors): ?>
    <div class="alert alert-danger">
      <?= implode('<br>', array_map('h', $errors)) ?>
    </div>
  <?php endif; ?>

  <?php if(!$errors && $check_in && $check_out): ?>
    <div class="d-flex justify-content-between align-items-end mb-3">
      <div>
        <h5 class="fw-700 mb-0">Available rooms</h5>
        <div class="text-muted small">
          For <strong><?= h($check_in) ?></strong> → <strong><?= h($check_out) ?></strong>
        </div>
      </div>
      <?php if($rooms && $rooms->num_rows > 0): ?>
        <span class="badge bg-light text-dark border"><?= $rooms->num_rows ?> result(s)</span>
      <?php endif; ?>
    </div>

    <div class="row g-4">
      <?php
      if(!$rooms || $rooms->num_rows === 0){
        echo '<div class="col-12"><div class="alert alert-secondary">No rooms are available for the selected dates. Try different dates.</div></div>';
      } else {
        while($r = $rooms->fetch_assoc()):
      ?>
        <div class="col-md-6 col-lg-4">
          <div class="card room-card h-100">
            <div class="ratio ratio-4x3">
              <img src="<?= h($r['image']) ?>" alt="<?= h($r['name']) ?>" class="room-img">
            </div>
            <div class="card-body d-flex flex-column">
              <h5 class="fw-bold mb-1"><?= h($r['name']) ?></h5>
              <p class="text-muted small mb-2"><?= h($r['beds']) ?> • <?= h($r['size']) ?></p>
              <div class="mt-auto d-flex justify-content-between align-items-center">
                <span class="price-tag">RM <?= h($r['price']) ?><span class="small text-muted">/night</span></span>
                <a class="btn btn-brand btn-sm"
                   href="book.php?room_id=<?= (int)$r['id'] ?>&check_in=<?= h($check_in) ?>&check_out=<?= h($check_out) ?>">
                  Book
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php
        endwhile;
      }
      ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info">
      Choose your dates above to see which rooms are free.
    </div>
  <?php endif; ?>
</section>

<footer class="py-4 border-top mt-5">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <!-- empty on purpose -->
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
