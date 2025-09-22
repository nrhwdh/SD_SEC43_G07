<?php
require_once __DIR__.'/auth.php';

$err = '';

// kalau dah login, terus ke dashboard
if (!empty($_SESSION['admin_id'])) {
  header('Location: dashboard.php'); exit;
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
  [$ok, $msg] = attempt_login($_POST['email'] ?? '', $_POST['password'] ?? '');
  if ($ok) {
    header('Location: dashboard.php'); exit;
  }
  $err = $msg ?? 'Login failed.';
}

$page_title='Login';
include __DIR__.'/partials/head.php';
?>
<div class="login-hero d-flex align-items-center justify-content-center">
  <div class="container py-5">
    <div class="row justify-content-center"><div class="col-xl-5 col-lg-6">
      <div class="card p-4 shadow-sm">
        <h3 class="text-center fw-bold mb-3">Welcome Back!</h3>
        <?php if ($err): ?><div class="alert alert-danger"><?=h($err)?></div><?php endif; ?>
        <form method="post" autocomplete="off">
          <input class="form-control mb-3" type="email" name="email" placeholder="Enter Email Address..." required>
          <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
          <div class="d-grid"><button class="btn btn-primary" type="submit">Login</button></div>
        </form>
        <div class="text-center mt-3"><a href="forgot_password.php">Forgot Password?</a></div>
      </div>
    </div></div>
  </div>
</div>
<?php include __DIR__.'/partials/foot.php'; ?>
