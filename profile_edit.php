<?php
require_once __DIR__ . '/auth.php';
require_login();

$me  = current_admin();
$ok  = '';
$err = '';

// Helper: check if a column exists (so we can support optional `phone`)
function column_exists(PDO $pdo, string $table, string $column): bool {
  try {
    $st = $pdo->query("DESCRIBE `$table`");
    $cols = $st->fetchAll(PDO::FETCH_COLUMN, 0);
    return in_array($column, $cols, true);
  } catch (Throwable $e) {
    return false;
  }
}
$hasPhone = column_exists($pdo, 'admins', 'phone');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name  = trim($_POST['name']  ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');

  // Basic validation
  if ($name === '') {
    $err = 'Name is required.';
  } elseif ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $err = 'A valid email is required.';
  } elseif ($hasPhone && $phone !== '' && !preg_match('/^[0-9 +()\-.\x{00A0}]{6,30}$/u', $phone)) {
    $err = 'Phone number format looks invalid.';
  } else {
    // Ensure email is unique across other admins
    $st = $pdo->prepare("SELECT id FROM admins WHERE LOWER(email)=LOWER(?) AND id<>? LIMIT 1");
    $st->execute([$email, $me['id']]);
    if ($st->fetch()) {
      $err = 'Email is already used by another admin.';
    } else {
      // Build dynamic UPDATE
      $fields = ['name = ?', 'email = ?'];
      $params = [$name, $email];

      if ($hasPhone) {
        $fields[] = 'phone = ?';
        $params[] = ($phone === '' ? null : $phone);
      }
      $params[] = $me['id'];

      $sql = "UPDATE admins SET " . implode(', ', $fields) . " WHERE id = ?";
      $st  = $pdo->prepare($sql);
      $st->execute($params);

      $ok = 'Profile updated.';

      // Refresh $me from DB so UI shows latest data
      $st = $pdo->prepare("SELECT id, name, email, " . ($hasPhone ? "phone, " : "") . "avatar, created_at FROM admins WHERE id = ?");
      $st->execute([$me['id']]);
      $me = $st->fetch();
    }
  }
}

$page_title = 'Edit Profile';
include __DIR__ . '/partials/head.php';
include __DIR__ . '/partials/top.php';
?>
<div class="row">
  <div class="col-lg-6">
    <div class="card p-4">
      <h5 class="fw-bold mb-3">Edit Profile</h5>

      <?php if ($ok): ?>
        <div class="alert alert-success"><?= h($ok) ?></div>
      <?php endif; ?>

      <?php if ($err): ?>
        <div class="alert alert-danger"><?= h($err) ?></div>
      <?php endif; ?>

      <form method="post" autocomplete="off" novalidate>
        <label class="form-label">Name</label>
        <input class="form-control mb-3" name="name" value="<?= h($me['name'] ?? '') ?>" required>

        <label class="form-label">Email</label>
        <input class="form-control mb-3" type="email" name="email" value="<?= h($me['email'] ?? '') ?>" required>

        <label class="form-label">Phone number</label>
        <input
          class="form-control mb-3"
          name="phone"
          value="<?= h($me['phone'] ?? '') ?>"
          placeholder="<?= $hasPhone ? 'e.g. +60 12-345 6789' : 'Phone column not in DB (optional)'; ?>"
          <?= $hasPhone ? '' : 'disabled' ?>
        >

        <div class="d-flex gap-2">
          <button class="btn btn-primary" type="submit">Save Changes</button>
          <a class="btn btn-secondary" href="profile.php">Back to Profile</a>
        </div>
      </form>

      <?php if (!$hasPhone): ?>
        <p class="mt-3 small text-muted">
          Tip: Tambah lajur <code>phone</code> dalam jadual <code>admins</code> untuk guna medan ini:<br>
          <code>ALTER TABLE admins ADD COLUMN phone VARCHAR(30) NULL;</code>
        </p>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php include __DIR__ . '/partials/foot.php'; ?>
