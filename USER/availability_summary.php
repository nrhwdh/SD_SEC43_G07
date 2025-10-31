<?php
// availability_summary.php
include 'db.php';

// --- Helpers ---
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function d($s){
    $t = DateTime::createFromFormat('Y-m-d', $s);
    return $t && $t->format('Y-m-d') === $s ? $s : null;
}

// --- Read + validate inputs ---
$check_in  = isset($_GET['check_in'])  ? d($_GET['check_in'])  : '';
$check_out = isset($_GET['check_out']) ? d($_GET['check_out']) : '';
$today     = (new DateTime('today'))->format('Y-m-d');

$errors = [];
if($check_in !== '' || $check_out !== ''){
    if(!$check_in)  $errors[] = "Please choose a valid check-in date.";
    if(!$check_out) $errors[] = "Please choose a valid check-out date.";
    if(!$errors){
        if($check_in < $today) $errors[] = "Check-in can’t be in the past.";
        if($check_out <= $check_in) $errors[] = "Check-out must be after check-in.";
    }
}

// --- Query available rooms when dates are valid ---
$rooms_summary = [];
if(!$errors && $check_in && $check_out){
    $sql = "
    SELECT r.room_type,
           r.total_rooms - IFNULL(SUM(b.booked_rooms),0) AS available_rooms
    FROM rooms r
    LEFT JOIN bookings b
           ON r.id = b.room_id
           AND b.status IN ('confirmed','paid','pending')
           AND b.check_in < ?
           AND b.check_out > ?
    GROUP BY r.id
    ORDER BY r.price ASC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $check_out, $check_in);
    $stmt->execute();
    $res = $stmt->get_result();
    while($row = $res->fetch_assoc()){
        $rooms_summary[] = $row;
    }
    $stmt->close();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Available Rooms Summary</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<h1>Available Rooms</h1>

<form method="get" action="">
    <label>Check-in: <input type="date" name="check_in" value="<?= h($check_in) ?>" min="<?= h($today) ?>" required></label>
    <label>Check-out: <input type="date" name="check_out" value="<?= h($check_out) ?>" min="<?= h($today) ?>" required></label>
    <button class="btn btn-primary">Check</button>
</form>

<?php if($errors): ?>
    <div class="alert alert-danger"><?= implode('<br>', array_map('h', $errors)) ?></div>
<?php endif; ?>

<?php if($rooms_summary): ?>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Room Type</th>
                <th>Available Rooms</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rooms_summary as $r): ?>
            <tr>
                <td><?= h($r['room_type']) ?></td>
                <td><?= h($r['available_rooms']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif(!$errors && $check_in && $check_out): ?>
    <div class="alert alert-secondary mt-3">No rooms available for the selected dates.</div>
<?php endif; ?>
</body>
</html>
