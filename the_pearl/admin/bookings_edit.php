<?php
require_once __DIR__ . '/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: bookings_view.php'); exit; }

// --- Helper: kira bilangan malam (min 1) ---
function count_nights(string $in, string $out): int {
    $d1 = new DateTime($in);
    $d2 = new DateTime($out);
    $n  = (int)$d1->diff($d2)->days;
    return max(1, $n);
}

// --- Dapatkan booking + info bilik semasa ---
$sql = "SELECT b.*, r.name AS room_name, r.price AS room_price
        FROM bookings b
        LEFT JOIN rooms r ON r.id = b.room_id
        WHERE b.id = ?";
$st  = $pdo->prepare($sql);
$st->execute([$id]);
$bk  = $st->fetch();

if (!$bk) { header('Location: bookings_view.php'); exit; }

// --- Proses submit ---
$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guest_name  = trim($_POST['guest_name']  ?? $bk['guest_name']);
    $guest_email = trim($_POST['guest_email'] ?? $bk['guest_email']);
    $room_id     = (int)($_POST['room_id'] ?? $bk['room_id']);
    $check_in    = trim($_POST['check_in']    ?? $bk['check_in']);
    $check_out   = trim($_POST['check_out']   ?? $bk['check_out']);
    $guests      = (int)($_POST['guests']     ?? $bk['guests']);

    // harga semasa bilik yang dipilih
    $rp = $pdo->prepare("SELECT name, price FROM rooms WHERE id=?");
    $rp->execute([$room_id]);
    $room = $rp->fetch();

    $nights = count_nights($check_in, $check_out);
    $price_per_night = $room ? (float)$room['price'] : (float)($bk['room_price'] ?? 0);
    $total  = $price_per_night * $nights;

    $upd = $pdo->prepare("
        UPDATE bookings
        SET guest_name=?, guest_email=?, room_id=?, check_in=?, check_out=?,
            nights=?, guests=?, total=?
        WHERE id=? LIMIT 1
    ");
    $upd->execute([
        $guest_name, $guest_email, $room_id, $check_in, $check_out,
        $nights, $guests, $total, $id
    ]);

    // refresh data utk paparan selepas simpan
    $st->execute([$id]);
    $bk = $st->fetch();
    $flash = 'Changes saved.';
}

$page_title = "Edit Booking #{$bk['id']}";
include __DIR__ . '/partials/head.php';
include __DIR__ . '/partials/top.php';
?>

<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Edit Booking #<?= (int)$bk['id'] ?></h1>
    <!-- TIADA butang Back di header lagi -->
  </div>

  <?php if ($flash): ?>
    <div class="alert alert-success"><?= h($flash) ?></div>
  <?php endif; ?>

  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-3">
        <form method="post" autocomplete="off">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Guest Name</label>
              <input name="guest_name" class="form-control" value="<?= h($bk['guest_name']) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Guest Email</label>
              <input name="guest_email" type="email" class="form-control" value="<?= h($bk['guest_email']) ?>">
            </div>

            <div class="col-md-6">
              <label class="form-label">Room</label>
              <select name="room_id" class="form-select">
                <?php
                  $rooms = $pdo->query("SELECT id, name, price FROM rooms ORDER BY id")->fetchAll();
                  foreach ($rooms as $r):
                    $sel = ($r['id'] == $bk['room_id']) ? 'selected' : '';
                ?>
                  <option value="<?= (int)$r['id'] ?>" <?= $sel ?>>
                    <?= h($r['name']) ?> — RM <?= number_format((float)$r['price'],2) ?>/night
                  </option>
                <?php endforeach; ?>
              </select>
              <div class="form-text">(Current) <?= h($bk['room_name']) ?></div>
            </div>

            <div class="col-md-3">
              <label class="form-label">Check-in</label>
              <input name="check_in" type="date" class="form-control" value="<?= h($bk['check_in']) ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">Check-out</label>
              <input name="check_out" type="date" class="form-control" value="<?= h($bk['check_out']) ?>">
            </div>

            <div class="col-md-3">
              <label class="form-label">Guests</label>
              <input name="guests" type="number" min="1" class="form-control" value="<?= (int)$bk['guests'] ?>">
            </div>

            <div class="col-md-3">
              <label class="form-label">Nights</label>
              <input class="form-control" value="<?= (int)$bk['nights'] ?>" disabled>
            </div>

            <div class="col-md-3">
              <label class="form-label">Price / Night (RM)</label>
              <input class="form-control" value="<?= number_format((float)($bk['room_price'] ?? 0), 2) ?>" disabled>
            </div>

            <div class="col-md-3">
              <label class="form-label">Total (RM)</label>
              <input class="form-control" value="<?= number_format((float)$bk['total'],2) ?>" disabled>
            </div>
          </div>

          <div class="d-flex gap-2 mt-3">
            <!-- SATU je Back button (bawah kiri) -->
            <a href="bookings_view.php" class="btn btn-outline-secondary">← Back</a>
            <button class="btn btn-primary" type="submit">💾 Save changes</button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card p-3">
        <h6 class="fw-bold text-primary mb-2">Current Summary</h6>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between">
            <span>Booking ID</span><span>#<?= (int)$bk['id'] ?></span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Room</span><span><?= h($bk['room_name']) ?></span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Price/Night</span><span>RM <?= number_format((float)($bk['room_price'] ?? 0),2) ?></span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Nights</span><span><?= (int)$bk['nights'] ?></span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Total</span><span>RM <?= number_format((float)$bk['total'],2) ?></span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Created At</span><span><?= h($bk['created_at']) ?></span>
          </li>
        </ul>

      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/partials/foot.php'; ?>