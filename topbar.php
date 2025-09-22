<?php require_once __DIR__.'/../auth.php'; $me = current_admin(); ?>
<div class="sidewrap">
  <aside class="sidebar" id="sidebar">
    <div class="brand"><i class="bi bi-building"></i> The Pearl</div>
    <hr class="border-light">
    <nav class="nav flex-column small">
      <a class="nav-link <?=basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':''?>" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
      <a class="nav-link <?=basename($_SERVER['PHP_SELF'])=='profile.php'?'active':''?>" href="profile.php"><i class="bi bi-person me-2"></i>Profile</a>
      <a class="nav-link" href="#"><i class="bi bi-table me-2"></i>Bookings (later)</a>
      <a class="nav-link" href="#"><i class="bi bi-chat-left-text me-2"></i>Feedback (later)</a>
    </nav>
  </aside>
  <div class="main">
    <div class="topbar">
      <button class="btn btn-outline-secondary btn-sm d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="bi bi-list"></i></button>
      <div class="fw-semibold">Admin · <?=h($page_title)?></div>
      <div class="d-flex align-items-center gap-3">
        <span class="small text-muted">Hi, <?=h($me['name']??'Admin')?></span>
        <a href="logout.php" class="btn btn-sm btn-outline-secondary">Log out</a>
      </div>
    </div>
    <div class="page">
