<?php
include 'db.php';

/* --------- Read dates from GET & validate ---------- */
$ci_raw = $_GET['check_in']  ?? '';
$co_raw = $_GET['check_out'] ?? '';

$check_in  = '';
$check_out = '';
$errors    = [];

// Normalize to Y-m-d if provided
if ($ci_raw !== '') {
  $ts = strtotime($ci_raw);
  if ($ts) $check_in = date('Y-m-d', $ts);
  else $errors[] = 'Invalid check-in date.';
}
if ($co_raw !== '') {
  $ts = strtotime($co_raw);
  if ($ts) $check_out = date('Y-m-d', $ts);
  else $errors[] = 'Invalid check-out date.';
}

// Validate range
if ($check_in && $check_out && $check_out <= $check_in) {
  $errors[] = 'Check-out must be after check-in.';
}

/* --------- Build rooms query ---------- */
$params = [];
$sql = "SELECT r.* FROM rooms r";

$filtering = ($check_in && $check_out && empty($errors));
if ($filtering) {
  // Exclude any room that has a conflicting booking (no status filter)
  $sql .= "
    WHERE NOT EXISTS (
      SELECT 1 FROM bookings b
      WHERE b.room_id = r.id
        AND b.check_in  < ?   -- overlap rule
        AND b.check_out > ?
    )
    ORDER BY r.price DESC, r.name ASC
  ";
  $params = [$check_out, $check_in];
} else {
  $sql .= " ORDER BY r.price DESC, r.name ASC";
}

$stmt = $conn->prepare($sql);
if ($params) {
  $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$roomsRes = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>The Pearl Hotel | Rooms</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/fancy.css">
  <style>
    body{font-family:'Poppins',sans-serif;}
    .brand-logo{height:40px;width:auto;}
    .room-fancy .room-img{object-fit:cover;width:100%;height:100%;}
    .price-tag{font-weight:800;}
    /* footer */
    .site-footer{background:linear-gradient(180deg,#fff 0%,#faf7f8 100%);margin-top:3rem;}
    .footer-logo{height:36px;width:auto;}
    .footer-links li{margin-bottom:.35rem;}
    .footer-links a,.footer-link{color:#5b5b5b;text-decoration:none;}
    .footer-links a:hover,.footer-link:hover{color:#8a1538;}
    .footer-bottom{background:#f5f1f3;border-top:1px solid #eee;}
    .back-to-top{position:fixed;right:18px;bottom:18px;width:42px;height:42px;border-radius:999px;border:0;background:#8a1538;color:#fff;font-weight:700;box-shadow:0 6px 20px rgba(0,0,0,.15);cursor:pointer;display:none;}
    .back-to-top:hover{opacity:.92;}

    /* availability bar */
    .mini-availability .form-label{font-size:.8rem;margin-bottom:.25rem}
    .mini-availability .btn{white-space:nowrap}
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
        <li class="nav-item"><a class="nav-link active" href="rooms.php">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="container py-5">
  <!-- Header + availability filter -->
  <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-lg-between gap-3 mb-3">
    <h1 class="fw-800 mb-0">Rooms</h1>

    <form class="row g-2 align-items-end mini-availability" action="rooms.php" method="get">
      <div class="col-auto">
        <label class="form-label">Check-in</label>
        <input type="date" name="check_in" class="form-control form-control-sm"
               min="<?php echo date('Y-m-d'); ?>"
               value="<?php echo htmlspecialchars($check_in ?: ''); ?>">
      </div>
      <div class="col-auto">
        <label class="form-label">Check-out</label>
        <input type="date" name="check_out" class="form-control form-control-sm"
               min="<?php echo date('Y-m-d'); ?>"
               value="<?php echo htmlspecialchars($check_out ?: ''); ?>">
      </div>
      <div class="col-auto">
        <button class="btn btn-outline-secondary btn-sm">Check availability</button>
      </div>
      <?php if ($check_in && $check_out && empty($errors)): ?>
      <div class="col-auto">
        <a class="btn btn-link btn-sm" href="rooms.php">Clear</a>
      </div>
      <?php endif; ?>
    </form>
  </div>

  <!-- Validation errors -->
  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></div>
  <?php endif; ?>

  <!-- Result info -->
  <?php if ($check_in && $check_out && empty($errors)): ?>
    <div class="alert alert-info py-2 small">
      Showing rooms available from
      <strong><?php echo htmlspecialchars($check_in); ?></strong>
      to
      <strong><?php echo htmlspecialchars($check_out); ?></strong>.
    </div>
  <?php endif; ?>

  <div class="row g-4">
  <?php
  if ($roomsRes && $roomsRes->num_rows > 0) {
    while ($row = $roomsRes->fetch_assoc()) {
      // Preserve selected dates in Book URL
      $qs = [];
      if ($check_in)  $qs['check_in']  = $check_in;
      if ($check_out) $qs['check_out'] = $check_out;
      $qs['room_id'] = (int)$row['id'];
      $bookUrl = 'book.php?'.http_build_query($qs);

      echo '<div class="col-md-6 col-lg-4">
              <div class="card room-fancy h-100 shadow-sm">
                <div class="ratio ratio-4x3">
                  <img src="'.htmlspecialchars($row['image']).'" alt="'.htmlspecialchars($row['name']).'" class="room-img">
                </div>
                <div class="card-body d-flex flex-column">
                  <h5 class="fw-bold mb-1">'.htmlspecialchars($row['name']).'</h5>
                  <p class="small text-muted mb-2">'.htmlspecialchars($row['beds']).' • '.htmlspecialchars($row['size']).'</p>
                  <div class="mt-auto d-flex justify-content-between align-items-center">
                    <span class="price-tag">RM '.htmlspecialchars($row['price']).'<span class="small text-muted">/night</span></span>
                    <a href="'.$bookUrl.'" class="btn btn-brand btn-sm">Book</a>
                  </div>
                </div>
              </div>
            </div>';
    }
  } else {
    echo '<div class="col-12"><div class="alert alert-warning mb-0">No rooms match your selection.</div></div>';
  }
  ?>
  </div>
</section>

<footer class="site-footer">
  <div class="container py-5">
    <div class="row g-4">
      <div class="col-md-4">
        <a href="index.php" class="d-inline-flex align-items-center mb-2 text-decoration-none">
          <img src="assets/img/logo-pearl.png" alt="The Pearl Hotel" class="footer-logo">
        </a>
        <p class="text-muted mb-0">City Views. Calm Nights. Pearl Luxury.</p>
      </div>
      <div class="col-6 col-md-4">
        <h6 class="fw-bold mb-3">Quick Links</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="rooms.php">Rooms</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="feedback.php">Feedback</a></li>
        </ul>
      </div>
      <div class="col-6 col-md-4">
        <h6 class="fw-bold mb-3">Contact</h6>
        <ul class="list-unstyled small mb-0">
          <li>The Pearl Kuala Lumpur,<br>Batu 5, Jalan Klang Lama,<br>58000 Kuala Lumpur</li>
          <li>Phone: +603-7983 1111</li>
          <li>Email: info@pearl.com.my</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="footer-bottom py-3">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
    </div>
  </div>
  <button class="back-to-top" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'});"></button>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  const b=document.querySelector('.back-to-top');
  if(!b) return;
  const t=()=>{ b.style.display = (window.scrollY>400) ? 'inline-flex' : 'none'; };
  addEventListener('scroll',t,{passive:true}); t();
})();
</script>
</body>
</html>
