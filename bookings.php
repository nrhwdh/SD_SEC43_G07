<?php include 'db.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>The Pearl Hotel | My Bookings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css"><link rel="stylesheet" href="assets/css/fancy.css">
  <style>.brand-logo{height:40px}.badge-status{letter-spacing:.3px}</style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="assets/img/logo-pearl.png" class="brand-logo" alt="">
    </a>
  </div>
</nav>

<section class="container py-5">
  <h1 class="fw-800 mb-3">My bookings</h1>

  <?php
  $email = trim($_GET['email'] ?? '');
  $phone = trim($_GET['phone'] ?? '');
  $ref   = trim($_GET['ref']   ?? '');
  ?>
  <form class="row g-3 mb-4" method="get">
    <div class="col-md-4"><label class="form-label">Email</label><input name="email" type="email" required class="form-control" value="<?= htmlspecialchars($email) ?>"></div>
    <div class="col-md-3"><label class="form-label">Phone (optional)</label><input name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>"></div>
    <div class="col-md-3"><label class="form-label">Ref (optional)</label><input name="ref" class="form-control" value="<?= htmlspecialchars($ref) ?>"></div>
    <div class="col-md-2 d-flex align-items-end"><button class="btn btn-brand w-100">Find</button></div>
  </form>

  <?php
  if($email){
    $where = "WHERE b.email = ?";
    $types = "s";
    $params= [$email];
    if($phone){ $where .= " AND b.phone = ?"; $types.="s"; $params[]=$phone; }
    if($ref){   $where .= " AND b.ref   = ?"; $types.="s"; $params[]=$ref; }

    $sql = "SELECT b.*, r.name AS room_name
            FROM bookings b JOIN rooms r ON r.id=b.room_id
            $where
            ORDER BY b.created_at DESC";
    $st = $conn->prepare($sql);
    $st->bind_param($types, ...$params);
    $st->execute(); $res = $st->get_result();
    if($res->num_rows===0){
      echo '<div class="alert alert-warning">No bookings found for that info.</div>';
    } else {
      echo '<div class="list-group">';
      while($b=$res->fetch_assoc()){
        $badge = $b['status']==='CONFIRMED' ? 'bg-success' : ($b['status']==='PENDING'?'bg-warning text-dark':'bg-secondary');
        echo '<a class="list-group-item list-group-item-action" href="invoice.php?ref='.urlencode($b['ref']).'">
                <div class="d-flex w-100 justify-content-between">
                  <h6 class="mb-1">'.htmlspecialchars($b['room_name']).' · <span class="text-muted">Ref '.$b['ref'].'</span></h6>
                  <span class="badge '.$badge.' badge-status">'.$b['status'].'</span>
                </div>
                <p class="mb-1 small">Stay '.$b['checkin'].' → '.$b['checkout'].' ('.$b['nights'].' nights, '.$b['guests'].' guests)</p>
                <small>Total RM '.$b['total'].' · created '.$b['created_at'].'</small>
              </a>';
      }
      echo '</div>';
    }
    $st->close();
  } else {
    echo '<div class="alert alert-secondary">Enter your email to see bookings.</div>';
  }
  ?>
</section>

<footer class="py-4 border-top mt-5"><div class="container"></div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
