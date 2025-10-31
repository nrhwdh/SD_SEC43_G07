<?php
require_once __DIR__.'/auth.php';
require_login();
$pdo = new PDO(...); // sama macam connection dashboard

$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Total rooms
$totalRooms = $pdo->query("SELECT SUM(total_rooms) FROM rooms")->fetchColumn();

// Live booked rooms
$stmt = $pdo->prepare("
    SELECT SUM(quantity) as booked
    FROM bookings
    WHERE check_in < :date_plus
      AND check_out > :date
");
$stmt->execute([
    ':date' => $selectedDate,
    ':date_plus' => date('Y-m-d', strtotime($selectedDate . ' +1 day'))
]);
$bookedRooms = $stmt->fetchColumn() ?: 0;
$availableRooms = max(0, $totalRooms - $bookedRooms);

// Return JSON
echo json_encode([
    'bookedRooms' => (int)$bookedRooms,
    'availableRooms' => (int)$availableRooms
]);
