<?php 
require_once __DIR__.'/auth.php'; 
require_login(); 
$me = current_admin();

$page_title = 'Profile'; 
include __DIR__.'/partials/head.php'; 
include __DIR__.'/partials/top.php'; 
?>

<div class="row">
  <div class="col-lg-6">

    <?php if (!empty($_SESSION['flash'])): ?>
      <div class="alert alert-success"><?= h($_SESSION['flash']) ?></div>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="card p-4">
      <h5 class="fw-bold">My Profile</h5>
      <dl class="row mt-3">
        <dt class="col-sm-4">Name</dt>
        <dd class="col-sm-8"><?= h($me['name']) ?></dd>

        <dt class="col-sm-4">Email</dt>
        <dd class="col-sm-8"><?= h($me['email']) ?></dd>
      </dl>

      <a href="profile_edit.php" class="btn btn-primary btn-sm">Edit profile</a>
      <a href="change_password.php" class="btn btn-outline-secondary btn-sm">Change password</a>
    </div>
  </div>
</div>

<?php include __DIR__.'/partials/foot.php'; ?>
