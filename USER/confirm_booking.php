<?php
include 'db.php';
include 'config_mail.php';
require 'vendor/autoload.php'; // autoload PHPMailer & Dompdf

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function dt($s){ return DateTime::createFromFormat('Y-m-d', $s); }
function redirect($url){ header("Location: $url"); exit; }

/* ---------- read inputs ---------- */
$stage    = $_POST['stage']    ?? $_GET['stage']    ?? 'review';
$room_id  = isset($_POST['room_id']) ? (int)$_POST['room_id'] : (int)($_GET['room_id'] ?? 0);
$fullname = trim($_POST['fullname'] ?? ($_GET['fullname'] ?? ''));
$email    = trim($_POST['email']    ?? ($_GET['email']    ?? ''));
$check_in = trim($_POST['check_in'] ?? ($_GET['check_in'] ?? ''));
$check_out= trim($_POST['check_out']?? ($_GET['check_out']?? ''));
$guests   = (int)($_POST['guests']  ?? ($_GET['guests']   ?? 1));

if ($room_id <= 0) redirect('rooms.php');

/* ---------- load room ---------- */
$rs = $conn->prepare("SELECT * FROM rooms WHERE id=? LIMIT 1");
$rs->bind_param("i", $room_id);
$rs->execute();
$room = $rs->get_result()->fetch_assoc();
$rs->close();

if (!$room){
  echo '<div style="padding:2rem;font-family:Poppins,system-ui">Room not found. <a href="rooms.php">Back to rooms</a></div>';
  exit;
}

/* ---------- validate ---------- */
$d1 = dt($check_in);
$d2 = dt($check_out);
if ($stage === 'review'){
  if ($fullname==='' || $email==='' || !$d1 || !$d2 || $d1 >= $d2){
    $qs = http_build_query([
      'room_id'=>$room_id,'check_in'=>$check_in,'check_out'=>$check_out,'guests'=>max(1,$guests)
    ]);
    redirect("book.php?$qs");
  }
}

$alert=''; $refId=null;

/* ---------- confirm ---------- */
if ($stage==='confirm'){
  if (empty($_POST['agree'])){
    $alert = '<div class="alert alert-danger">You must agree to the Terms &amp; Conditions to proceed.</div>';
  } elseif ($fullname==='' || $email==='' || !$d1 || !$d2 || $d1 >= $d2){
    $alert = '<div class="alert alert-danger">Invalid or missing fields. Please go back and review your booking.</div>';
  } else {
    // overlap check
    $sql = "SELECT 1 FROM bookings
            WHERE room_id=? AND NOT (? >= check_out OR ? <= check_in) LIMIT 1";
    $st = $conn->prepare($sql);
    $st->bind_param("iss", $room_id, $check_out, $check_in);
    $st->execute();
    $ov = $st->get_result();
    $st->close();

    if ($ov && $ov->num_rows>0){
      $alert = '<div class="alert alert-danger">Sorry, the room just became unavailable for those dates. Please pick different dates.</div>';
    } else {
      $nights = (int)$d1->diff($d2)->days;
      $total  = $nights * (float)$room['price'];

      $ins = $conn->prepare("INSERT INTO bookings
        (room_id, check_in, check_out, nights, guests, guest_name, guest_email, total)
        VALUES (?,?,?,?,?,?,?,?)");
      $ins->bind_param("issiiisd",
        $room_id, $check_in, $check_out, $nights, $guests, $fullname, $email, $total
      );
      if ($ins->execute()){
        $refId = $ins->insert_id;

        // =============== GENERATE PDF INVOICE ===============
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

       $html = "
<div style='font-family:Poppins,sans-serif; padding:30px; border:1px solid #ddd; border-radius:10px; background-color:#fffaf7;'>
  <div style='text-align:center; margin-bottom:20px;'>
    <img src='https://i.imgur.com/yh7Nv1k.png' alt='The Pearl Hotel' style='height:80px; margin-bottom:10px;'>
    <h2 style='color:#5a4b81; margin:0;'>The Pearl Hotel Kuala Lumpur</h2>
    <p style='color:#777; margin:0;'>Booking Invoice</p>
    <hr style='margin-top:15px;'>
  </div>

  <table style='width:100%; font-size:14px; border-collapse:collapse;'>
    <tr><td style='padding:6px 0;'><strong>Reference:</strong></td><td>#$refId</td></tr>
    <tr><td style='padding:6px 0;'><strong>Guest:</strong></td><td>$fullname ($email)</td></tr>
    <tr><td style='padding:6px 0;'><strong>Room:</strong></td><td>{$room['name']}</td></tr>
    <tr><td style='padding:6px 0;'><strong>Dates:</strong></td><td>$check_in → $check_out</td></tr>
    <tr><td style='padding:6px 0;'><strong>Guests:</strong></td><td>$guests</td></tr>
    <tr><td style='padding:6px 0;'><strong>Total:</strong></td><td><strong>RM " . number_format($total, 2) . "</strong></td></tr>
  </table>

  <hr style='margin:20px 0;'>
  <p style='font-style:italic; text-align:center; color:#555;'>We’ve secured your room. See you soon!</p>

  <div style='text-align:center; font-size:12px; color:#888; margin-top:15px;'>
    <p></p>
  </div>
</div>
";

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        $pdfPath = __DIR__ . "/invoice_$refId.pdf";
        file_put_contents($pdfPath, $pdfOutput);

        // =============== SEND EMAIL ===============
        $mail = new PHPMailer(true);
        try {
          $mail->isSMTP();
          $mail->Host = 'smtp.gmail.com';
          $mail->SMTPAuth = true;
          $mail->Username = HOTEL_EMAIL;
          $mail->Password = HOTEL_APP_PASSWORD;
          $mail->SMTPSecure = 'tls';
          $mail->Port = 587;

          $mail->setFrom(HOTEL_EMAIL, HOTEL_NAME);
          $mail->addAddress($email, $fullname);
          $mail->addAttachment($pdfPath, "Invoice_$refId.pdf");

          $mail->isHTML(true);
          $mail->Subject = "Booking Invoice - The Pearl Hotel (Ref #$refId)";
          $mail->Body = "
          <div style='font-family:Poppins,sans-serif;'>
            <h2 style='color:#5a4b81;'>Thank you for booking with The Pearl Hotel!</h2>
            <p>Dear <strong>$fullname</strong>,</p>
            <p>Your booking reference: <b>#$refId</b></p>
            <p>Room: {$room['name']}<br>
               Check-in: $check_in<br>
               Check-out: $check_out<br>
               Total: RM " . number_format($total, 2) . "</p>
            <p>You can download your invoice attached below.</p>
            <br><p style='font-style:italic;color:#777;'>Warm regards,<br>The Pearl Hotel Kuala Lumpur</p>
          </div>
          ";

          $mail->send();
          $alert = '<div class="alert alert-success mb-4">Booking confirmed! Reference: <strong>#'.$refId.
                   '</strong>. Total <strong>RM '.number_format($total,2).'</strong><br>Invoice sent to <strong>'.
                   h($email).'</strong>.</div>';
        } catch (Exception $e) {
          $alert = '<div class="alert alert-warning mb-4">Booking saved but email not sent. Error: ' . h($mail->ErrorInfo) . '</div>';
        }
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
  <style>
    body{font-family:'Poppins',system-ui;}
    .brand-logo{height:100px;width:auto;}
    .btn-brand{background:#5a4b81;color:white;font-weight:600;border-radius:10px;}
    .btn-brand:hover{background:#49386b;}
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="assets/img/logo-pearl.png" alt="The Pearl Hotel" class="brand-logo">
      <span class="visually-hidden">The Pearl Hotel</span>
    </a>
  </div>
</nav>

<section class="container py-5">
  <h1 class="fw-bold mb-4"><?= $refId ? 'Booking Confirmed 🎉' : 'Confirm Your Booking' ?></h1>
  <?= $alert ?>

  <?php if(!$refId): ?>
    <?php $nights=(int)$d1->diff($d2)->days; $total=$nights * (float)$room['price']; ?>
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
            <h5 class="fw-bold mb-3">Review Your Details</h5>
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
              <button class="btn btn-brand w-100" type="submit">Confirm & Secure Room</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="card shadow-sm rounded-4">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Reservation Receipt</h5>
        <p><strong>Reference:</strong> #<?= (int)$refId ?></p>
        <p><strong>Room:</strong> <?= h($room['name']) ?></p>
        <p><strong>Guest:</strong> <?= h($fullname) ?> (<?= h($email) ?>)</p>
        <p><strong>Dates:</strong> <?= h($check_in) ?> → <?= h($check_out) ?></p>
        <p><strong>Guests:</strong> <?= (int)$guests ?></p>
        <p class="text-success"><strong>Your booking confirmation and invoice have been sent to your email:</strong> <?= h($email) ?></p>
        <a href="rooms.php" class="btn btn-outline-secondary btn-sm me-2">Back to Rooms</a>
        <a href="invoice.php?ref=<?= (int)$refId ?>" class="btn btn-brand btn-sm me-2">View Invoice</a>
        <button class="btn btn-outline-primary btn-sm" onclick="window.print()">Print Confirmation</button>
      </div>
    </div>
  <?php endif; ?>
</section>

<footer class="py-4 border-top mt-5 text-center text-muted small">
  <p>© The Pearl Hotel Kuala Lumpur</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>