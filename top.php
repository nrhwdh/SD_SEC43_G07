<?php 
require_once __DIR__.'/../auth.php'; 
$me = current_admin(); 
?>
<div class="sb-wrap">
  <!-- Sidebar -->
  <aside class="sb-side" id="sbSide">
    <div class="sb-brand">
      <i class="bi bi-building"></i> The Pearl Hotel
    </div>

    <nav class="sb-nav nav flex-column">
      <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
      <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : '' ?>" href="profile.php">
        <i class="bi bi-person"></i> Profile
      </a>

      <div class="border-top my-2 mx-3"></div>

      <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'charts.php' ? 'active' : '' ?>" href="charts.php">
        <i class="bi bi-graph-up"></i> Charts
      </a>
      <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'tables.php' ? 'active' : '' ?>" href="tables.php">
        <i class="bi bi-table"></i> Tables
      </a>
    </nav>
  </aside>

  <!-- Main -->
  <div class="sb-main">
    <!-- Top bar -->
    <div class="sb-top">
      <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-secondary btn-sm d-lg-none"
                onclick="document.getElementById('sbSide').classList.toggle('open')">
          <i class="bi bi-list"></i>
        </button>

        <form class="sb-search d-none d-md-block">
          <div class="input-group">
            <input class="form-control" placeholder="Search for...">
            <button class="btn btn-primary" type="button"><i class="bi bi-search"></i></button>
          </div>
        </form>
      </div>

      <div class="d-flex align-items-center gap-3">
        <span class="small text-muted">Hi, <?= h($me['name'] ?? 'Admin') ?></span>
        <a class="btn btn-outline-secondary btn-sm" href="logout.php">Log out</a>
      </div>
    </div>

    <!-- Content wrapper (opened here, closed in foot.php) -->
    <div class="sb-content">
