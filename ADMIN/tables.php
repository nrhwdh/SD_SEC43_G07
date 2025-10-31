<?php
// admin/tables.php
require_once __DIR__.'/auth.php';
require_login();

$page_title = 'Tables';
include __DIR__.'/partials/head.php';
include __DIR__.'/partials/top.php';

// current admin (for future use if needed)
$me = current_admin();

// quick stats
$totalBookings = (int)$pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$totalFeedback = (int)$pdo->query("SELECT COUNT(*) FROM feedback")->fetchColumn();
$totalRooms    = (int)$pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
?>
<style>
/* ===== Banner (match Charts vibe) ===== */
.hero-banner{
  background: linear-gradient(90deg, #4e73df 0%, #6dd5fa 100%);
  color:#fff;
  padding:1rem 1.5rem;
  border-radius:12px;
  box-shadow:0 3px 10px rgba(0,0,0,.15);
  display:flex; align-items:center; justify-content:space-between;
}
.hero-banner h1{margin:0; font-weight:800; font-size:1.5rem;}
.hero-banner p{margin:0; opacity:.9; font-size:.95rem; font-weight:500;}

/* cards */
.glow-card{transition:.25s; border-radius:12px;}
.glow-card:hover{transform:translateY(-3px); box-shadow:0 0 14px rgba(78,115,223,.25);}
.stat-card{border-radius:12px;}
.stat-icon{font-size:1.75rem; opacity:.85;}
</style>

<div class="container-fluid">

  <div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0 text-primary">Data Dashboard</h1>
  </div>

  <div class="row g-4">

  <!-- Quick stats -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card stat-card shadow-sm border-0 p-3 bg-light">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small">Bookings</div>
            <div class="h3 m-0 text-primary fw-bold"><?= $totalBookings ?></div>
          </div>
          <i class="bi bi-calendar-check text-primary stat-icon"></i>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card shadow-sm border-0 p-3 bg-light">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small">Feedback</div>
            <div class="h3 m-0 text-success fw-bold"><?= $totalFeedback ?></div>
          </div>
          <i class="bi bi-chat-dots text-success stat-icon"></i>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card shadow-sm border-0 p-3 bg-light">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small">Rooms</div>
            <div class="h3 m-0 text-warning fw-bold"><?= $totalRooms ?></div>
          </div>
          <i class="bi bi-house-door text-warning stat-icon"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Main data access -->
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card glow-card p-4 shadow-sm">
        <h5 class="fw-bold text-primary mb-2">
          <i class="bi bi-journal-text me-1"></i> View Bookings
        </h5>
        <p class="text-muted small mb-3">See all hotel reservations including room details, nights, guests & totals.</p>
        <a href="bookings_view.php" class="btn btn-primary w-100">Open Bookings Table</a>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card glow-card p-4 shadow-sm">
        <h5 class="fw-bold text-success mb-2">
          <i class="bi bi-chat-quote me-1"></i> View Feedback
        </h5>
        <p class="text-muted small mb-3">Check customer satisfaction, ratings and submission dates.</p>
        <a href="feedback_view.php" class="btn btn-success w-100">Open Feedback Table</a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__.'/partials/foot.php'; ?>