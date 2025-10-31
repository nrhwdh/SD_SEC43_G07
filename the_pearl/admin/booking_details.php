<?php
require_once __DIR__.'/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: bookings_view.php'); exit; }

// ambil 1 rekod
$st = $pdo->prepare("
  SELECT id, guest_name, guest_email, room_id, check_in, check_out, nights, guests, total, created_at
  FROM bookings
  WHERE id = ?
  LIMIT 1
");
$st->execute([$id]);
$bk = $st->fetch();
if (!$bk) { header('Location: bookings_view.php'); exit; }

// kira status ikut tarikh
$today = new DateTimeImmutable('today');
$ci    = new DateTimeImmutable($bk['check_in']);
$co    = new DateTimeImmutable($bk['check_out']);
if ($today < $ci)       { $status = ['Upcoming','secondary']; }
elseif ($today > $co)   { $status = ['Completed','success']; }
else                    { $status = ['Ongoing','info']; }

$page_title = 'Booking Details';
include __DIR__.'/partials/head.php';
include __DIR__.'/partials/top.php';
?>
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Booking #<?= (int)$bk['id'] ?> Details</h1>
    <a class="btn btn-outline-secondary" href="bookings_view.php">
      &larr; Back
    </a>
  </div>

  <div class="row g-3">
    <div class="col-lg-6">
      <div class="card p-3">
        <h6 class="fw-bold text-primary mb-3">Guest</h6>
        <dl class="row mb-0">
          <dt class="col-sm-4">Name</dt>
          <dd class="col-sm-8"><?= h($bk['guest_name']) ?: '—' ?></dd>

          <dt class="col-sm-4">Email</dt>
          <dd class="col-sm-8"><?= h($bk['guest_email']) ?></dd>

          <dt class="col-sm-4">Room ID</dt>
          <dd class="col-sm-8"><?= (int)$bk['room_id'] ?></dd>

          <dt class="col-sm-4">Created At</dt>
          <dd class="col-sm-8"><?= h($bk['created_at']) ?></dd>
        </dl>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card p-3">
        <h6 class="fw-bold text-primary mb-3">Stay</h6>
        <dl class="row mb-0">
          <dt class="col-sm-4">Check-In</dt>
          <dd class="col-sm-8"><?= h($bk['check_in']) ?></dd>

          <dt class="col-sm-4">Check-Out</dt>
          <dd class="col-sm-8"><?= h($bk['check_out']) ?></dd>

          <dt class="col-sm-4">Nights</dt>
          <dd class="col-sm-8"><?= (int)$bk['nights'] ?></dd>

          <dt class="col-sm-4">Guests</dt>
          <dd class="col-sm-8"><?= (int)$bk['guests'] ?></dd>

          <dt class="col-sm-4">Total (RM)</dt>
          <dd class="col-sm-8"><?= number_format((float)$bk['total'], 2) ?></dd>

          <dt class="col-sm-4">Status</dt>
          <dd class="col-sm-8">
            <span class="badge text-bg-<?= $status[1] ?>"><?= $status[0] ?></span>
          </dd>
        </dl>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__.'/partials/foot.php';