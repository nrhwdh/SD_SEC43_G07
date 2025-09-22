<?php
include 'db.php';

// Load room
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

$alert = '';
$refId = null;

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullname  = trim($_POST['fullname'] ?? '');
  $email     = trim($_POST['email'] ?? '');
  $check_in  = trim($_POST['check_in'] ?? '');   // YYYY-MM-DD from <input type="date">
  $check_out = trim($_POST['check_out'] ?? '');  // YYYY-MM-DD from <input type="date">
  $guests    = (int)($_POST['guests'] ?? 1);

  // Basic validation
  if (!$fullname || !$email || !$check_in || !$check_out) {
    $alert = '<div class="alert alert-danger">Please fill all fields (name, email, check-in, check-out).</div>';
  } else {
    // Validate date order
    $d1 = DateTime::createFromFormat('Y-m-d', $check_in);
    $d2 = DateTime::createFromFormat('Y-m-d', $check_out);

    if (!$d1 || !$d2) {
      $alert = '<div class="alert alert-danger">Please select valid dates.</div>';
    } elseif ($d1 >= $d2) {
      $alert = '<div class="alert alert-danger">Check-out must be after check-in.</div>';
    } else {
      // Check availability (overlap logic)
      // Overlap exists if NOT (new_end <= existing_start OR new_start >= existing_end)
      $sql = "SELECT 1 FROM bookings
              WHERE room_id = ?
              AND NOT (? >= check_out OR ? <= check_in)";
      $st = $conn->prepare($sql);
      $st->bind_param("iss", $room_id, $check_out, $check_in);
      $st->execute();
      $ov = $st->get_result();
      $st->close();

      if ($ov && $ov->num_rows > 0) {
        $alert = '<div class="alert alert-danger">Sorry, this room is not available for the selected dates.</div>';
      } else {
        // compute nights & total
        $nights = (int)$d1->diff($d2)->days;
        $total  = $nights * (float)$room['price'];

        // Insert booking
        $ins = $conn->prepare("INSERT INTO bookings
          (room_id, check_in, check_out, nights, guests, guest_name, guest_email, total)
          VALUES (?,?,?,?,?,?,?,?)");
        $ins->bind_param(
          "issiiisd",
          $room_id,
          $check_in,
          $check_out,
          $nights,
          $guests,
          $fullname,
          $email,
          $total
        );
        if ($ins->execute()) {
          $refId = $ins->insert_id;
          $alert = '<div class="alert alert-success">Booking confirmed! Reference: <strong>#'.$refId.
                   '</strong>. Total RM '.number_format($total,2).'</div>';
        } else {
          $alert = '<div class="alert alert-danger">Error saving booking: '.htmlspecialchars($conn->error).'</div>';
        }
        $ins->close();
      }
    }
  }
}
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
    .brand-logo{ height:100px; width:auto; }
    @media (max-width:576px){ .brand-logo{ height:72px; } }
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
        <li class="nav-item"><a class="nav-link" href="bookings.php">Bookings</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="container py-5">
  <h1 class="fw-bold mb-4">Book Room</h1>

  <?php echo $alert; ?>

  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card shadow-sm rounded-4">
        <img src="<?php echo htmlspecialchars($room['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($room['name']); ?>">
        <div class="card-body">
          <h5 class="card-title mb-1"><?php echo htmlspecialchars($room['name']); ?></h5>
          <p class="text-muted small mb-2"><?php echo htmlspecialchars($room['beds']); ?> • <?php echo htmlspecialchars($room['size']); ?></p>
          <p class="mb-0 fw-bold">RM <?php echo number_format($room['price'],2); ?>/night</p>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card shadow-sm rounded-4">
        <div class="card-body">
          <form method="post" novalidate>
            <input type="hidden" name="room_id" value="<?php echo (int)$room_id; ?>">

            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" name="fullname" class="form-control" required
                     value="<?php echo htmlspecialchars($_POST['fullname'] ?? ''); ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required
                     value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Check-in</label>
                <input type="date" name="check_in" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['check_in'] ?? ''); ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Check-out</label>
                <input type="date" name="check_out" class="form-control" required
                       value="<?php echo htmlspecialchars($_POST['check_out'] ?? ''); ?>">
              </div>
            </div>

            <div class="mt-3 mb-4">
              <label class="form-label">Guests</label>
              <select name="guests" class="form-select">
                <?php
                $g = (int)($_POST['guests'] ?? 1);
                for($i=1;$i<=6;$i++){
                  echo '<option value="'.$i.'"'.($i==$g?' selected':'').'>'.$i.'</option>';
                }
                ?>
              </select>
            </div>

            <button class="btn btn-brand" type="submit">Confirm Booking</button>
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
