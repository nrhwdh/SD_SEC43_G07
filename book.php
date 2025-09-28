<?php
include 'db.php';

function nights_between($a,$b){ return max(1,(int)round((strtotime($b)-strtotime($a))/86400)); }
function money_my($v){ return number_format((float)$v,2,'.',''); }

$room_id = (int)($_GET['room_id'] ?? $_POST['room_id'] ?? 0);
$checkin = $_GET['checkin']  ?? $_POST['checkin']  ?? '';
$checkout= $_GET['checkout'] ?? $_POST['checkout'] ?? '';
$guests  = (int)($_GET['guests']   ?? $_POST['guests']   ?? 1);

$room = null;
if($room_id){
  $room = $conn->query("SELECT * FROM rooms WHERE id=".$room_id)->fetch_assoc();
  if(!$room){ die("Room not found."); }
}

$alert = "";
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name  = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  if(!$name || !$email || !$phone){ $alert = '<div class="alert alert-danger">Please fill in all guest details.</div>'; }
  elseif(!$checkin || !$checkout || $checkin >= $checkout){ $alert = '<div class="alert alert-danger">Invalid dates.</div>'; }
  else{
    // ensure still available
    $sql = "SELECT COUNT(*) c FROM bookings
            WHERE room_id=? AND status IN ('PENDING','CONFIRMED')
              AND (checkin < ? AND checkout > ?)";
    $st  = $conn->prepare($sql);
    $st->bind_param("iss", $room_id, $checkout, $checkin);
    $st->execute(); $c = (int)($st->get_result()->fetch_assoc()['c'] ?? 0); $st->close();
    if($c>0){ $alert = '<div class="alert alert-danger">Sorry, the room just got booked. Try another room or dates.</div>'; }
    else{
      $nights = nights_between($checkin,$checkout);
      $total  = money_my($nights * (float)$room['price']);
      // simple ref
      $ref = 'P'.date('ymd').'-'.strtoupper(bin2hex(random_bytes(3)));

      $ins = $conn->prepare("INSERT INTO bookings (ref,room_id,name,email,phone,checkin,checkout,nights,guests,total,status)
                             VALUES (?,?,?,?,?,?,?,?,?,?,'PENDING')");
      $ins->bind_param("sissssssids",
           $ref,$room_id,$name,$email,$phone,$checkin,$checkout,$nights,$guests,$total);
      // bind types trick: nights int, guests int, total decimal -> use s for date strings, i for int, d for decimal
      // correct types below:
      $ins->bind_param("sisssssii d",
        $ref,$room_id,$name,$email,$phone,$checkin,$checkout,$nights,$guests,$total); // <- editor spacing fix
