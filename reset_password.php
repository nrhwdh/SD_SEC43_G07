<?php
require_once __DIR__.'/auth.php';
require_once __DIR__.'/db.php';

$msg = '';
$showForm = false;
$tokenParam = trim($_GET['token'] ?? $_POST['token'] ?? '');

if ($tokenParam === '') {
  $msg = '<div class="alert alert-danger">Invalid reset link.</div>';
} else {
  $token_hash = hash('sha256', $tokenParam);
  $st = $pdo->prepare("SELECT * FROM password_resets WHERE token_hash = ? LIMIT 1");
  $st->execute([$token_hash]);
  $row = $st->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    $msg = '<div class="alert alert-danger">Invalid or expired reset link.</div>';
  } elseif (strtotime($row['expires_at']) < time()) {
    $msg = '<div class="alert alert-danger">Reset link expired. Please request a new one.</div>';
  } else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $new = trim($_POST['new_password'] ?? '');
      $confirm = trim($_POST['confirm_password'] ?? '');

      if ($new === '' || $confirm === '') {
        $msg = '<div class="alert alert-danger">Please fill in all fields.</div>';
      } elseif ($new !== $confirm) {
        $msg = '<div class="alert alert-danger">Passwords do not match.</div>';
      } elseif (strlen($new) < 8) {
        $msg = '<div class="alert alert-danger">Password must be at least 8 characters.</div>';
      } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE admins SET password_hash=? WHERE email=?")->execute([$hashed, $row['email']]);
        $pdo->prepare("DELETE FROM password_resets WHERE id=?")->execute([$row['id']]);

        $_SESSION['flash_success'] = 'Password updated successfully! You may now log in.';
        header('Location: login.php');
        exit;
      }
      $showForm = true;
    } else {
      $showForm = true;
    }
  }
}

$page_title = 'Reset Password';
include __DIR__.'/partials/head.php';
?>
<div class="login-hero d-flex align-items-center justify-content-center">
  <div class="container py-5">
    <div class="row justify-content-center"><div class="col-xl-5 col-lg-6">
      <div class="card p-4 shadow-sm">
        <h4 class="fw-bold mb-3 text-center">Reset Your Password</h4>

        <?= $msg ?>

        <?php if ($showForm): ?>
          <form method="post" autocomplete="off">
            <input type="hidden" name="token" value="<?= htmlspecialchars($tokenParam) ?>">
            <div class="mb-3">
              <input class="form-control" type="password" name="new_password" placeholder="New password" required>
            </div>
            <div class="mb-3">
              <input class="form-control" type="password" name="confirm_password" placeholder="Confirm new password" required>
            </div>
            <div class="d-grid">
              <button class="btn btn-primary" type="submit">Set New Password</button>
            </div>
          </form>
        <?php endif; ?>

        <div class="text-center mt-3">
          <a href="login.php" class="btn btn-outline-secondary">Back to Login</a>
        </div>
      </div>
    </div></div>
  </div>
</div>
<?php include __DIR__.'/partials/foot.php'; ?>