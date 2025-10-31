<?php
require_once __DIR__.'/auth.php';
require_login();
$page_title = 'Change Password';
include __DIR__.'/partials/head.php';
include __DIR__.'/partials/top.php';
?>

<style>
/* 🌈 Gradient background for contrast */
body {
  background: linear-gradient(180deg, #eaf6ff 0%, #ffffff 100%);
  min-height: 100vh;
}

/* 💎 Banner */
.hero-banner {
  background: linear-gradient(90deg, #a9d8ff, #7fc8ff);
  color: #003366;
  padding: 1.2rem 1.5rem;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  margin-bottom: 1.5rem;
}
.hero-banner h3 {
  font-weight: 700;
  margin: 0;
}
.hero-banner span {
  font-size: 0.9rem;
  opacity: 0.9;
}

/* 🌸 Card with glassmorphism effect */
.profile-card {
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(12px);
  border-radius: 16px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.1);
  padding: 2.5rem 2rem;
  max-width: 440px;
  margin: 40px auto;
  transition: all 0.3s ease;
}
.profile-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 28px rgba(0,0,0,0.12);
}
.profile-card h4 {
  color: #004080;
  font-weight: 700;
  text-align: center;
  margin-bottom: 1.8rem;
}

/* 🪄 Input fields */
.form-label {
  font-weight: 600;
  color: #333;
}
.input-group {
  position: relative;
}
.input-group input {
  border-radius: 10px;
  padding-right: 40px;
  border: 1px solid #ccc;
  transition: all 0.2s ease;
}
.input-group input:focus {
  border-color: #007bff;
  box-shadow: 0 0 8px rgba(0,123,255,0.3);
}
.toggle-pass {
  position: absolute;
  right: 12px;
  top: 9px;
  color: #777;
  cursor: pointer;
  transition: color 0.2s ease;
}
.toggle-pass:hover {
  color: #007bff;
}

/* 💫 Buttons */
.btn-primary {
  background: linear-gradient(90deg, #007bff, #00a8ff);
  border: none;
  border-radius: 8px;
  font-weight: 600;
  box-shadow: 0 4px 10px rgba(0,123,255,0.2);
  transition: all 0.3s ease;
}
.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 14px rgba(0,123,255,0.3);
}
.btn-secondary {
  border-radius: 8px;
  transition: all 0.3s ease;
}
.btn-secondary:hover {
  background-color: #f0f0f0;
}

/* 🍞 Toast pop-up */
.toast {
  position: fixed;
  top: 1rem;
  right: 1rem;
  background: rgba(51, 51, 51, 0.85);
  color: #fff;
  padding: 0.9rem 1.2rem;
  border-radius: 10px;
  backdrop-filter: blur(10px);
  box-shadow: 0 4px 15px rgba(0,0,0,0.15);
  opacity: 0;
  pointer-events: none;
  transform: translateY(-10px);
  transition: all 0.4s ease;
  z-index: 9999;
}
.toast.show {
  opacity: 1;
  pointer-events: auto;
  transform: translateY(0);
}
.toast-success {
  background: rgba(40, 167, 69, 0.9);
}
.toast-error {
  background: rgba(220, 53, 69, 0.9);
}
</style>

<div class="container-fluid">

  <!-- 🌊 Header Banner -->
  <div class="hero-banner">
    <h3><i class="bi bi-lock-fill me-2"></i>Change Password</h3>
    <span>Keep your login credentials secure and up to date.</span>
  </div>

  <!-- 🍞 Toast -->
  <?php if (!empty($_SESSION['flash_success']) || !empty($_SESSION['flash_error'])): ?>
    <div id="toast" class="toast <?= !empty($_SESSION['flash_success']) ? 'toast-success' : 'toast-error' ?>">
      <?= htmlspecialchars($_SESSION['flash_success'] ?? $_SESSION['flash_error']) ?>
    </div>
    <script>
      const toast = document.getElementById('toast');
      if (toast) {
        setTimeout(() => toast.classList.add('show'), 200);
        setTimeout(() => toast.classList.remove('show'), 3500);
      }
    </script>
    <?php unset($_SESSION['flash_success'], $_SESSION['flash_error']); ?>
  <?php endif; ?>

  <!-- 💎 Card -->
  <div class="profile-card">
    <h4><i class="bi bi-shield-lock"></i> Security Information</h4>
    <form method="POST" action="change_password_update.php">
      <div class="mb-3">
        <label class="form-label">Current Password</label>
        <div class="input-group">
          <input type="password" name="current_password" class="form-control" required>
          <i class="bi bi-eye toggle-pass"></i>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">New Password</label>
        <div class="input-group">
          <input type="password" name="new_password" class="form-control" minlength="8" required>
          <i class="bi bi-eye toggle-pass"></i>
        </div>
        <small class="text-muted">At least 8 characters, include letters, numbers & symbols.</small>
      </div>

      <div class="mb-4">
        <label class="form-label">Confirm New Password</label>
        <div class="input-group">
          <input type="password" name="confirm_password" class="form-control" required>
          <i class="bi bi-eye toggle-pass"></i>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary px-4">Update</button>
        <a href="profile.php" class="btn btn-secondary">Back to Profile</a>
      </div>
    </form>
  </div>
</div>

<script>
/* 👁 Toggle password visibility */
document.querySelectorAll('.toggle-pass').forEach(icon => {
  icon.addEventListener('click', () => {
    const input = icon.previousElementSibling;
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
  });
});
</script>

<?php include __DIR__.'/partials/foot.php'; ?>