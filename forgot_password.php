<?php
include('config.php');

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $query = "SELECT * FROM admin WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));
        mysqli_query($conn, "UPDATE admin SET reset_token='$token', token_expiry='$expiry' WHERE email='$email'");

        $reset_link = "http://localhost/ThePearl/reset_password.php?token=$token";
        $subject = "Password Reset Request - The Pearl Hotel Admin";
        $message = "
            <h3>Password Reset Request</h3>
            <p>Click the link below to reset your password:</p>
            <a href='$reset_link'>Reset Password</a>
            <p>This link will expire in 1 hour.</p>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: The Pearl Hotel <noreply@thepearl.com>" . "\r\n";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('Password reset link sent to your email.'); window.location='admin_login.php';</script>";
        } else {
            echo "<script>alert('Error sending email. Please try again later.');</script>";
        }
    } else {
        echo "<script>alert('Email not found. Please check again.');</script>";
    }
}
?>

<form method="POST">
    <h2>Forgot Password (Admin)</h2>
    <input type="email" name="email" placeholder="Enter your registered email" required>
    <button type="submit" name="submit">Send Reset Link</button>
</form>
