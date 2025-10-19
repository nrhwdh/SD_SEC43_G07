<?php
include('config.php'); // database connection
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch latest booking for this user
$query = "SELECT * FROM bookings WHERE user_id = '$user_id' ORDER BY booking_id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);

if ($booking) {
    $to = $_SESSION['email'];
    $subject = "Your Booking Invoice - The Pearl Hotel";
    $message = "
        <h2>Booking Invoice</h2>
        <p><b>Booking ID:</b> {$booking['booking_id']}</p>
        <p><b>Room:</b> {$booking['room_type']}</p>
        <p><b>Check-In:</b> {$booking['check_in']}</p>
        <p><b>Check-Out:</b> {$booking['check_out']}</p>
        <p><b>Total Price:</b> RM{$booking['total_price']}</p>
        <p>Thank you for booking with The Pearl Hotel. We look forward to your stay!</p>
    ";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: The Pearl Hotel <noreply@thepearl.com>" . "\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "<script>alert('Invoice
