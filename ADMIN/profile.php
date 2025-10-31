<?php
require_once __DIR__.'/auth.php';
require_login();

$page_title = 'Profile';
$me = current_admin();

include __DIR__.'/partials/head.php';
include __DIR__.'/partials/top.php';
?>

<style>
/* ===== Banner: minimal & luxury ===== */
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
  letter-spacing: 0.5px;
}
.hero-banner p {
  margin: 0;
  font-size: 0.95rem;
  opacity: 0.9;
}

/* ===== Glassy Card Style ===== */
.profile-wrapper {
  display: flex;
  justify-content: center;
  margin-top: 3rem;
}

.profile-card {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.08);
  padding: 2rem;
  width: 380px;
  text-align: center;
  transition: all 0.3s ease;
}
.profile-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* ===== Profile Details ===== */
.profile-avatar {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  margin-bottom: 1rem;
  object-fit: cover;
  border: 3px solid #2980b9;
  box-shadow: 0 0 10px rgba(41, 128, 185, 0.2);
}
.profile-card h4 {
  font-weight: 700;
  color: #2c3e91;
  margin-bottom: 0.5rem;
}
.profile-info {
  margin-bottom: 1.5rem;
  font-size: 0.95rem;
}
.profile-info strong {
  color: #2c3e91;
}

/* ===== Buttons ===== */
.btn-luxe {
  border-radius: 12px;
  font-weight: 600;
  letter-spacing: 0.4px;
  padding: 0.6rem;
  transition: 0.3s ease;
}
.btn-luxe-primary {
  background: linear-gradient(90deg, #4e73df 0%, #6dd5fa 100%);
  color: #fff;
  border: none;
}
.btn-luxe-primary:hover {
  opacity: 0.9;
}
.btn-luxe-outline {
  border: 2px solid #4e73df;
  color: #4e73df;
  background: transparent;
}
.btn-luxe-outline:hover {
  background: #4e73df;
  color: #fff;
}
</style>

<div class="container-fluid">
  <!-- Header -->
  <div class="hero-banner mt-2">
    <div>
      <h1>Account Settings</h1>
      <p>Manage your profile and login information securely.</p>
    </div>
    <i class="bi bi-person-circle fs-1 opacity-75"></i>
  </div>

  <!-- Profile Card -->
  <div class="profile-wrapper">
    <div class="profile-card">
      <!-- Avatar (optional, placeholder if not uploaded) -->
      <img src="https://cdn-icons-png.flaticon.com/512/219/219986.png" alt="Profile" class="profile-avatar">

      <h4><?= htmlspecialchars($me['name'] ?? 'Admin') ?></h4>
      <p class="text-muted mb-3"><?= htmlspecialchars($me['email'] ?? 'No email') ?></p>

      <div class="d-grid gap-2">
        <a href="profile_edit.php" class="btn btn-luxe btn-luxe-primary w-100">Edit Profile</a>
        <a href="change_password.php" class="btn btn-luxe btn-luxe-outline w-100">Change Password</a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__.'/partials/foot.php'; ?>