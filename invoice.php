<?php
include 'db.php';
$ref = $_GET['ref'] ?? '';
$bk  = null;
if($ref){
  $st = $conn->prepare("SELECT b.*, r.name AS room_name, r.price AS room_price FROM bookings b JOIN rooms r ON r.id=b.room_id WHERE b.ref=?");
  $st->bind_param("s",$ref); $st->execute(); $bk = $st->get_result()->fetch_assoc(); $st->close();
}
if(!$bk){ die("Invoice not found."); }
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Invoice <?= htmlspecialchars($bk['ref']) ?> | The Pearl Hotel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  .invoice{max-width:900px;margin:2rem auto}
  @media print {.no-print{display:none}}
</style>
</head>
<body>
<div class="invoice card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <h4 class="mb-1">The Pearl Kuala Lumpur</h4>
        <div class="text-muted small">Batu 5, Jalan Klang Lama, 58000 Kuala Lumpur · +603-7983 1111</div>
      </div>
      <div class="text-end">
        <img src="assets/img/logo-pearl.png" alt="" style="height:36px">
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-md-6">
        <h5 class="fw-bold mb-1">Invoice</h5>
        <div class="small">Reference: <strong><?= htmlspecialchars($bk['ref']) ?></strong></div>
        <div class="small">Status: <strong><?= htmlspecialchars($bk['status']) ?></strong></div>
        <div class="small">Created: <?= htmlspecialchars($bk['created_at']) ?></div>
      </div>
      <div class="col-md-6">
        <div class="small">Guest: <strong><?= htmlspecialchars($bk['name']) ?></strong></div>
        <div class="small">Email: <?= htmlspecialchars($bk['email']) ?></div>
        <div class="small">Phone: <?= htmlspecialchars($bk['phone']) ?></div>
      </div>
    </div>

    <div class="table-responsive my-4">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr><th>Description</th><th class="text-end">Rate (RM)</th><th class="text-center">Nights</th><th class="text-end">Amount (RM)</th></tr>
        </thead>
        <tbody>
          <tr>
            <td><?= htmlspecialchars($bk['room_name']) ?> (<?= $bk['checkin'] ?> → <?= $bk['checkout'] ?>)</td>
            <td class="text-end"><?= number_format((float)$bk['room_price'],2) ?></td>
            <td class="text-center"><?= (int)$bk['nights'] ?></td>
            <td class="text-end"><?= number_format((float)$bk['total'],2) ?></td>
          </tr>
        </tbody>
        <tfoot>
          <tr><th colspan="3" class="text-end">Total</th><th class="text-end"><?= number_format((float)$bk['total'],2) ?></th></tr>
        </tfoot>
      </table>
    </div>

    <p class="small text-muted mb-0">This is a system-generated invoice. For assistance, contact info@pearl.com.my.</p>
  </div>
  <div class="card-footer d-flex justify-content-end gap-2 no-print">
    <a class="btn btn-outline-secondary" href="bookings.php?email=<?= urlencode($bk['email']) ?>">Back to bookings</a>
    <button class="btn btn-primary" onclick="window.print()">Print / Save PDF</button>
  </div>
</div>
</body>
</html>
