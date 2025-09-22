<?php
// admin/change_password.php
require_once __DIR__.'/auth.php';
require_login();

$me = current_admin();
$err = '';

// helper: does a column exist? (so we can optionally set password_changed_at)
function column_exists(PDO $pdo, string $table, string $column): bool {
  try {
    $st = $pdo->query("DESCRIBE `$table`");
    return in_array($column, $st->fetchAll(PDO::FETCH_COLUMN, 0), true);
  } catch (Throwable $e) {
    return false;
  }
}

$hasChangedAt = column_exists($pdo, 'admins', 'password_changed_at');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $current = (string)($_POST['current'] ?? '');
  $new     = (string)($_POST['new'] ?? '');
  $confirm = (string)($_POST['confirm'] ?? '');

  // Basic validation
  if ($current === '' || $new === '' || $confirm === '') {
    $err = 'Please fill in all fields.';
  }

  // Verify current password
  if (!$err) {
    $st = $pdo->prepare("SELECT password_hash FROM admins WHERE id=?");
    $st->execute([$me['id']]);
    $row = $st->fetch();
    if (!$row || !password_verify($current, $row['password_hash'])) {
      $err = 'Current password is incorrect.';
    }
  }

  // Check new password rules
  if (!$err) {
    if ($new !== $confirm) {
      $err = 'New password and confirmation do not match.';
    } elseif (strlen($new) < 8) {
      $err = 'New password must be at least 8 characters.';
    } elseif (password_verify($new, $row['password_hash'])) {
      $err = 'New password must be different from the current one.';
    }
  }

  // Update password
  if (!$err) {
    $hash = password_hash($new, PASSWORD_DEFAULT);

    if ($hasChangedAt) {
      $sql = "UPDATE admins SET password_hash=?, password_changed_at=NOW() WHERE id=?";
      $pdo->prepare($sql)->execute([$hash, $me['id']]);
    } else {
      $sql = "UPDATE admins SET password_hash=? WHERE id=?";
      $pdo->prepare($sql)->execute([$hash, $me['id']]);
    }

    // Refresh session id and flash message, then redirect to profile
    session_regenerate_id(true);
    $_SESSION['flash'] = 'Password updated successfully.';
    header('Location: profile.php');
    exit;
  }
}

$page_title = 'Change Password';
include __DIR__.'/partials/head.php';
include __DIR__.'/partials/top.php';
?>

<div class="row">
  <div class="col-lg-6">
    <div class="card p-4">
      <h5 class="fw-bold mb-3">Change Password</h5>

      <?php if ($err): ?>
        <div class="alert alert-danger"><?= h($err) ?></div>
      <?php endif; ?>

      <form method="post" autocomplete="off" novalidate>
        <label class="form-label">Current password</label>
        <input class="form-control mb-3" type="password" name="current">

        <label class="form-label">New password</label>
        <input class="form-control mb-1" type="password" name="new">
        <div class="form-text">At least 8 characters. Use a mix of letters, numbers, and symbols.</div>

        <label class="form-label mt-3">Confirm new password</label>
        <input class="form-control mb-4" type="password" name="confirm">

        <div class="d-flex gap-2">
          <button class="btn btn-primary" type="submit">Update</button>
          <a class="btn btn-outline-secondary" href="profile.php">Back to Profile</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__.'/partials/foot.php'; ?>
