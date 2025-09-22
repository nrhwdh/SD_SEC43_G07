<?php
require_once __DIR__.'/auth.php';

$selector = $_GET['selector'] ?? '';
$validator = $_GET['validator'] ?? '';
$err = $ok = '';
$validLink = false;
$resetRow = null;

if ($selector && $validator && ctype_xdigit($selector) && ctype_xdigit($validator)) {
  $st = $pdo->prepare("SELECT r.*, a.email FROM password_resets r JOIN admins a ON a.id=r.admin_id WHERE r.selector=? LIMIT 1");
  $st->execute([$selector]);
  $row = $st->fetch();

  if ($row) {
    if ($row['used_at']) {
      $err = 'This reset link has already been used.';
    } elseif (strtotime($row['expires_at']) < time()) {
      $err = 'This reset link has expired.';
    } else {
      // check token
      if (hash('sha256', $validator) === $row['token_hash']) {
        $validLink = true;
        $resetRow = $row;
      } else {
        $err = 'Invalid reset token.';
      }
    }
  } else {
    $err = 'Invalid reset link.';
  }
} else {
  $err = 'Invalid reset link.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validLink) {
  $new = (string)($_POST['new'] ?? '');
  $confirm = (string)($_POST['confirm'] ?? '');

  if (strlen($new) < 8)           $err = 'New password must be at least 8 characters.';
  elseif ($new !== $confirm)      $err = 'Password confirmation does not match.';

  if (!$err) {
    $hash = password_hash($new, PASSWORD_DEFAULT);
    $pdo->beginTransaction();
    try {
      $up1 = $pdo->prepare("UPDATE admins SET password_hash=? WHERE id=?");
      $up1->execute([$hash, $resetRow['admin_id']]);

      $up2 = $pdo->prepare("UPDATE password_resets SET used_at=NOW() WHERE id=?");
      $up2->execute([$resetRow['id']]);

      $pdo->commit();
      // After success, redirect to login with a one-time flag
      header('Location: login.php?reset=1'); exit;
    } catch (Throwable $e) {
      $pdo->rollBack();
      $err = 'Something went wrong. Please try again.';
    }
  }
}

$page_title = 'Reset Password';
include __DIR__.'/partials/head.php';
?>
<div class="container py-5">
  <div class="row justify-content-center"><div class="col-xl-5 col-lg-6">
    <div class="card p-4">
      <h3 class="fw-bold mb-3">Reset Password</h3>
      <?php if($err): ?><div class="alert alert-danger"><?=h($err)?></div><?php endif; ?>

      <?php if($validLink): ?>
        <form method="post" autocomplete="off">
          <label class="form-label">New password</label>
          <input class="form-control mb-1" type="password" name="new" required>
          <div class="form-text">At least 8 characters. Use a mix of letters, numbers, symbols.</div>
          <label class="form-label mt-3">Confirm new password</label>
          <input class="form-control mb-4" type="password" name="confirm" required>
          <div class="d-grid"><button class="btn btn-primary">Update Password</button></div>
        </form>
      <?php else: ?>
        <p class="text-muted mb-0">Please request a new link from the <a href="forgot_password.php">Forgot Password</a> page.</p>
      <?php endif; ?>
    </div>
  </div></div>
</div>
<?php include __DIR__.'/partials/foot.php'; ?>
