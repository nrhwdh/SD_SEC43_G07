<?php
require_once __DIR__.'/auth.php';
require_once __DIR__.'/db.php';
require_once __DIR__.'/../vendor/autoload.php';
$mailConfig = require __DIR__.'/mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email === '') {
        $msg = '<div class="alert alert-danger">Please enter your email address.</div>';
    } else {
        $st = $pdo->prepare("SELECT id, email FROM admins WHERE email = ? LIMIT 1");
        $st->execute([$email]);
        $admin = $st->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            $msg = '<div class="alert alert-danger">No account found with that email.</div>';
        } else {
            // generate token
            $token = bin2hex(random_bytes(24));
            $token_hash = hash('sha256', $token);
            $expires_at = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry

            // insert into password_resets table
            $pdo->prepare("INSERT INTO password_resets (email, token_hash, expires_at) VALUES (?, ?, ?)")
                ->execute([$email, $token_hash, $expires_at]);

            // build reset link
            $baseUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}" . dirname($_SERVER['REQUEST_URI']);
            $resetLink = $baseUrl . '/reset_password.php?token=' . $token;

            // setup PHPMailer
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = $mailConfig['host'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $mailConfig['username'];
                $mail->Password   = $mailConfig['password'];
                $mail->SMTPSecure = $mailConfig['smtp_secure'];
                $mail->Port       = $mailConfig['port'];

                // sender and receiver
                $mail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);
                $mail->addAddress($email);

                // email content
                $mail->isHTML(true);
                $mail->Subject = 'Reset your The Pearl Admin password';
                $mail->Body = "
                    <p>Hi,</p>
                    <p>You requested a password reset for <b>The Pearl Hotel Admin Panel</b>.</p>
                    <p><a href='{$resetLink}' style='background:#007bff;color:white;padding:10px 15px;text-decoration:none;border-radius:5px;'>Reset Password</a></p>
                    <p>This link will expire in 1 hour.</p>
                    <p>— The Pearl Hotel Team</p>
                ";

                // try sending the email
                if ($mail->send()) {
                    $msg = '<div class="alert alert-success">Reset link sent to your email! Please check your inbox.</div>';
                }
            } catch (Exception $e) {
                // show the actual mail error for debugging
                $msg = '<div class="alert alert-danger">Email could not be sent. Error: ' . htmlspecialchars($mail->ErrorInfo) . '</div>';
            }
        }
    }
}

$page_title = 'Forgot Password';
include __DIR__.'/partials/head.php';
?>

<div class="login-hero d-flex align-items-center justify-content-center">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-xl-5 col-lg-6">
        <div class="card p-4 shadow-sm">
          <h4 class="fw-bold mb-3 text-center">Forgot Your Password?</h4>
          <?= $msg ?>
          <form method="post" autocomplete="off">
            <div class="mb-3">
              <input class="form-control" type="email" name="email" placeholder="Enter your registered email" required>
            </div>
            <div class="d-grid">
              <button class="btn btn-primary" type="submit">Send Reset Link</button>
            </div>
          </form>
          <div class="text-center mt-3">
            <a href="login.php" class="btn btn-outline-secondary">Back to Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__.'/partials/foot.php'; ?>
