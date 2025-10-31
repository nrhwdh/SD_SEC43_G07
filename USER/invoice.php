<?php
include 'db.php';

$ref = isset($_GET['ref']) ? (int)$_GET['ref'] : 0;
if ($ref <= 0) {
  die('<div style="padding:2rem;font-family:Poppins,system-ui">Invalid reference. <a href="rooms.php">Back</a></div>');
}

// Ambil booking + room
$sql = "SELECT b.*, r.name AS room_name, r.price AS nightly, r.image
        FROM bookings b
        JOIN rooms r ON r.id = b.room_id
        WHERE b.id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ref);
$stmt->execute();
$bk = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$bk) {
  die('<div style="padding:2rem;font-family:Poppins,system-ui">Invoice not found. <a href="rooms.php">Back</a></div>');
}

// Kiraan pecahan caj
$nights   = (int)$bk['nights'];
$nightly  = (float)$bk['nightly'];
$subtotal = $nights * $nightly;

// Contoh cukai/fi (ubah ikut keperluan projek; boleh 0 jika tak guna)
$taxRate  = 0.00;          // 0% (set 0.10 untuk 10% dll)
$taxAmt   = $subtotal * $taxRate;
$fees     = 0.00;          // caj tetap jika ada
$total    = $subtotal + $taxAmt + $fees; // anda juga boleh guna $bk['total'] jika dah disimpan

// Status/kaedah bayar (optional – jika ada kolum payment_status/method)
$paymentStatus = $bk['payment_status'] ?? 'Unpaid';
$paymentMethod = $bk['payment_method'] ?? '-';

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Invoice #<?= $ref ?> | The Pearl Hotel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/styles.css">
<style>
  body{font-family:'Poppins',sans-serif;}
  .brand-logo{height:40px;width:auto;}
  @media print {
    .no-print{display:none!important}
    .card{box-shadow:none;border:0}
    body{background:#fff}
  }
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top no-print">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="assets/img/logo-pearl.png" alt="The Pearl Hotel" class="brand-logo">
    </a>
    <div class="collapse navbar-collapse"></div>
  </div>
</nav>

<section class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-800 mb-0">Invoice</h1>
    <div class="no-print">
      <a class="btn btn-outline-secondary btn-sm" href="confirm_booking.php?ref=<?= $ref ?>">Back to room</a>
      <button class="btn btn-brand btn-sm" onclick="window.print()">Print / Save PDF</button>
    </div>
  </div>

  <div class="card shadow-sm rounded-4">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <h5 class="fw-bold mb-1">The Pearl Hotel</h5>
          <div class="text-muted small">Batu 5, Jalan Klang Lama, 58000 Kuala Lumpur<br>‪+603-7983 1111‬ • info@pearl.com.my</div>
        </div>
        <div class="col-md-6 text-md-end">
          <div><strong>Invoice #<?= $ref ?></strong></div>
          <div class="text-muted small">Generated: <?= date('Y-m-d H:i') ?></div>
          <div class="text-muted small">Status: <?= h($paymentStatus) ?></div>
        </div>
      </div>

      <hr>

      <div class="row g-3 align-items-center">
        <div class="col-md-6">
          <h6 class="mb-1">Billed To</h6>
          <div><?= h($bk['guest_name']) ?></div>
          <div class="text-muted small"><?= h($bk['guest_email']) ?></div>
        </div>
        <div class="col-md-6 text-md-end">
          <h6 class="mb-1">Reservation</h6>
          <div><?= h($bk['room_name']) ?></div>
          <div class="text-muted small"><?= h($bk['check_in']) ?> → <?= h($bk['check_out']) ?> (<?= $nights ?> night<?= $nights>1?'s':'' ?>)</div>
          <div class="text-muted small">Guests: <?= (int)$bk['guests'] ?></div>
        </div>
      </div>

      <div class="table-responsive mt-4">
        <table class="table">
          <thead>
            <tr>
              <th>Description</th>
              <th class="text-center" style="width:120px;">Nights</th>
              <th class="text-end" style="width:150px;">Rate (RM)</th>
              <th class="text-end" style="width:150px;">Amount (RM)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?= h($bk['room_name']) ?></td>
              <td class="text-center"><?= $nights ?></td>
              <td class="text-end"><?= number_format($nightly,2) ?></td>
              <td class="text-end"><?= number_format($subtotal,2) ?></td>
            </tr>
            <?php if($fees>0): ?>
            <tr>
              <td>Fees</td><td class="text-center">-</td>
              <td class="text-end">-</td><td class="text-end"><?= number_format($fees,2) ?></td>
            </tr>
            <?php endif; ?>
            <?php if($taxAmt>0): ?>
            <tr>
              <td>Tax (<?= (int)($taxRate*100) ?>%)</td><td class="text-center">-</td>
              <td class="text-end">-</td><td class="text-end"><?= number_format($taxAmt,2) ?></td>
            </tr>
            <?php endif; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="text-end">Total (RM)</th>
              <th class="text-end"><?= number_format($total,2) ?></th>
            </tr>
            <tr>
              <td colspan="4" class="text-end text-muted small">Payment method: <?= h($paymentMethod) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <div class="mt-4 text-muted small">
        This invoice remains available through the system until your stay is completed.
      </div>
    </div>
  </div>
</section>
</body>
</html>