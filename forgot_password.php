<?php
require_once __DIR__.'/auth.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $email = trim($_POST['email'] ?? '');
    if ($email==='') {
        $msg = '<div class="alert alert-danger">Please enter your email.</div>';
    } else {
        // dummy response — seolah reset link dihantar
        $msg = '<div class="alert alert-info">
                  A reset link will be send to the email.<br>
               
                </div>';
    }
}

$page_title='Forgot Password';
include __DIR__.'/partials/head.php';
?>
<div class="login-hero d-flex align-items-center justify-content-center">
  <div class="container py-5">
    <div class="row justify-content-center"><div class="col-xl-5 col-lg-6">
      <div class="card p-4 shadow-sm">
        <h4 class="fw-bold mb-3">Forgot Your Password?</h4>
        <?= $msg ?>
        <form method="post" autocomplete="off">
          <div class="mb-3">
            <input class="form-control" type="email" name="email" placeholder="Enter your email address" required>
          </div>
          <div class="d-grid">
            <button class="btn btn-primary" type="submit">Reset Password</button>
          </div>
        </form>
        <div class="text-center mt-3">
          <a href="login.php" class="btn btn-outline-secondary">Back to Login</a>
        </div>
      </div>
    </div></div>
  </div>
</div>
<?php include __DIR__.'/partials/foot.php'; ?>
