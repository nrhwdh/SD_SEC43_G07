<?php
require_once __DIR__.'/auth.php';
require_login();

$page_title = 'Edit Profile';
$me = current_admin();

include __DIR__.'/partials/head.php';
include __DIR__.'/partials/top.php';
?>

<style>
/* ===== Banner ===== */
.hero-banner {
  background: linear-gradient(90deg, #6dd5fa 0%, #2980b9 100%);
  color: #fff;
  padding: 1rem 1.5rem;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.hero-banner h1 {
  margin: 0;
  font-weight: 700;
  font-size: 1.6rem;
}
.hero-banner p {
  margin: 0;
  font-size: 0.95rem;
  opacity: 0.9;
}

/* ===== Card Form ===== */
.form-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.08);
  max-width: 550px;
  margin: 3rem auto;
  padding: 2.5rem;
  transition: 0.3s ease;
}
.form-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.form-card h4 {
  font-weight: 700;
  color: #2c3e91;
  margin-bottom: 1.5rem;
}

/* ===== Form Style ===== */
.form-control {
  border-radius: 10px;
  padding: 0.6rem 0.9rem;
  border: 1.5px solid #d8dee9;
  transition: all 0.2s ease;
}
.form-control:focus {
  border-color: #4e73df;
  box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
}

/* ===== Buttons ===== */
.btn-luxe {
  border-radius: 10px;
  font-weight: 600;
  letter-spacing: 0.4px;
  padding: 0.6rem;
  transition: 0.3s ease;
}
.btn-save {
  background: linear-gradient(90deg, #4e73df 0%, #6dd5fa 100%);
  border: none;
  color: #fff;
}
.btn-save:hover {
  opacity: 0.9;
}
.btn-back {
  border: 2px solid #4e73df;
  color: #4e73df;
  background: transparent;
}
.btn-back:hover {
  background: #4e73df;
  color: #fff;
}
</style>

<div class="container-fluid">
  <!-- Banner -->
  <div class="hero-banner mt-2">
    <div>
      <h1>Edit Profile</h1>
      <p>Update your personal information and contact details below.</p>
    </div>
    <i class="bi bi-pencil-square fs-1 opacity-75"></i>
  </div>

  <!-- Form Card -->
  <div class="form-card">
    <h4 class="text-center mb-4">Profile Information</h4>
    <form method="POST" action="profile_update.php">
      <div class="mb-3">
        <label class="form-label fw-semibold">Name</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($me['name'] ?? '') ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($me['email'] ?? '') ?>" required>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold">Phone Number</label>
        <input type="text" name="phone" class="form-control" placeholder="e.g. +60 12-345 6789" value="<?= htmlspecialchars($me['phone'] ?? '') ?>">
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-luxe btn-save">Save Changes</button>
        <a href="profile.php" class="btn btn-luxe btn-back">Back to Profile</a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__.'/partials/foot.php'; ?>