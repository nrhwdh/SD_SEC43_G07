<?php include 'db.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>The Pearl Hotel | Availability</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css"><link rel="stylesheet" href="assets/css/fancy.css">
  <style>.brand-logo{height:40px}</style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="assets/img/logo-pearl.png" class="brand-logo" alt="">
      <span class="visually-hidden">The Pearl Hotel</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
        <li class="nav-item"><a class="nav-link active" href="availability.php">Availability</a></li>
        <li class="nav-item"><a class="nav-link" href="bookings.php">Bookings</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="container py-5">
  <h1 class="fw-800 mb-3">Check availability</h1>

  <?php
  // inputs
  $checkin  = $_GET['checkin']  ?? '';
  $checkout = $_GET['checkout'] ?? '';
  $guests   = (int)($_GET['guests'] ?? 1);

  ?>
  <form class="row g-3 mb-4" method="get">
    <div class="col-md-3">
      <label class="form-label">Check-in</label>
      <input type="date" name="checkin" required class="form-control" value="<?= htmlspecialchars($checkin) ?>">
    </div>
    <div class="col-md-3">
      <label class="form-label">Check-out</label>
      <input type="date" name="checkout" required class="form-control" value="<?= htmlspecialchars($checkout) ?>">
    </div>
    <div class="col-md-2">
      <label class="form-label">Guests</label>
      <input type="number" min="1" max="6" name="guests" class="form-control" value="<?= $guests ?: 1 ?>">
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button class="btn btn-brand w-100">Search</button>
    </div>
  </form>

<?php
$valid = $checkin && $checkout && $checkin < $checkout;
if($valid){
  // availability rule: a room is free if NO booking overlaps the requested window
  $sql = "
    SELECT r.*
    FROM rooms r
    WHERE NOT EXISTS (
      SELECT 1 FROM bookings b
      WHERE b.room_id = r.id
        AND b.status IN ('PENDING','CONFIRMED')
        AND (b.checkin < ? AND b.checkout > ?)
    )
    ORDER BY r.price ASC, r.name ASC
  ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $checkout, $checkin);
  $stmt->execute();
  $res = $stmt->get_result();

  if($res->num_rows === 0){
    echo '<div class="alert alert-warning">No rooms are available for those dates.</div>';
  } else {
    echo '<div class="row g-4">';
    while($r = $res->fetch_assoc()){
      echo '<div class="col-md-6 col-lg-4">
              <div class="card h-100 room-fancy">
                <div class="ratio ratio-4x3">
                  <img class="room-img" alt="'.htmlspecialchars($r['name']).'" src="'.htmlspecialchars($r['image']).'">
                </div>
                <div class="card-body d-flex flex-column">
                  <h5 class="fw-bold mb-1">'.htmlspecialchars($r['name']).'</h5>
                  <p class="small text-muted mb-2">'.htmlspecialchars($r['beds']).' • '.htmlspecialchars($r['size']).'</p>
                  <div class="mt-auto d-flex justify-content-between align-items-center">
                    <span class="price-tag">RM '.htmlspecialchars($r['price']).'<span class="small text-muted">/night</span></span>
                    <a class="btn btn-brand btn-sm"
                       href="book.php?room_id='.(int)$r['id'].'&checkin='.urlencode($checkin).'&checkout='.urlencode($checkout).'&guests='.$guests.'">Book</a>
                  </div>
                </div>
              </div>
            </div>';
    }
    echo '</div>';
  }
  $stmt->close();
} else {
  echo '<div class="alert alert-secondary">Pick a check-in and check-out date, then hit Search.</div>';
}
?>
</section>

<footer class="py-4 border-top mt-5"><div class="container"></div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
