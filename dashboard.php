<?php
require_once __DIR__ . '/auth.php';
require_login();
$me = current_admin();

$page_title = 'Dashboard';
include __DIR__ . '/partials/head.php';
include __DIR__ . '/partials/top.php';

/* ================================
   LIVE / PLACEHOLDER METRICS
   ================================ */

// Sprint-2: Bookings & Feedback belum siap ➜ placeholder (—).
$totalBookings = null;
$feedbackCount = null;

// Rooms: table 'rooms' dah ada → live count
$availableRooms = null;
try {
  $availableRooms = (int)$pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
} catch (Throwable $e) {
  // kalau table belum wujud / error, kekalkan null supaya keluar dash
  $availableRooms = null;
}

// Helper: tunjuk nombor atau dash
function stat_cell($val) {
  return ($val === null)
    ? '<span class="empty-badge">—</span>'
    : number_format((int)$val);
}
?>

<div class="container-fluid">

  <!-- Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Dashboard</h1>
  </div>

  <!-- Stat cards -->
  <div class="row g-3 mb-3">

    <!-- TOTAL BOOKINGS (placeholder) -->
    <div class="col-xl-4 col-md-6">
      <div class="card p-3 h-100">
        <div class="d-flex justify-content-between align-items-center sb-stat">
          <div>
            <div class="title">TOTAL BOOKINGS</div>
            <div class="num"><?= stat_cell($totalBookings) ?></div>
            <div class="card-sub">Not implemented yet</div>
          </div>
          <i class="bi bi-calendar2-check fs-2 text-primary"></i>
        </div>
      </div>
    </div>

    <!-- AVAILABLE ROOMS (live) -->
    <div class="col-xl-4 col-md-6">
      <div class="card p-3 h-100">
        <div class="d-flex justify-content-between align-items-center sb-stat">
          <div>
            <div class="title">AVAILABLE ROOMS</div>
            <div class="num"><?= stat_cell($availableRooms) ?></div>
            <div class="card-sub"><?= $availableRooms === null ? 'No data' : 'Live count' ?></div>
          </div>
          <i class="bi bi-door-open fs-2 text-success"></i>
        </div>
      </div>
    </div>

    <!-- FEEDBACK (placeholder) -->
    <div class="col-xl-4 col-md-6">
      <div class="card p-3 h-100">
        <div class="d-flex justify-content-between align-items-center sb-stat">
          <div>
            <div class="title">FEEDBACK</div>
            <div class="num"><?= stat_cell($feedbackCount) ?></div>
            <div class="card-sub">Not implemented yet</div>
          </div>
          <i class="bi bi-chat-dots fs-2 text-warning"></i>
        </div>
      </div>
    </div>

  </div>

  <!-- Charts / placeholders -->
  <div class="row g-3 mb-3">
    <div class="col-lg-8">
      <div class="card p-3 h-100">
        <h6 class="fw-bold text-primary mb-2">Bookings Overview</h6>
        <p class="text-muted mb-0">No data yet</p>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card p-3 h-100">
        <h6 class="fw-bold text-primary mb-2">Feedback Overview</h6>
        <p class="text-muted mb-0">No data yet</p>
      </div>
    </div>
  </div>

  <!-- Projects + Illustration -->
  <div class="row g-3">
    <div class="col-lg-6">
      <div class="card p-3 h-100">
        <h6 class="fw-bold text-primary mb-3">Projects</h6>

        <h6 class="small fw-semibold mb-1">
          Server Migration <span class="float-end card-sub">0%</span>
        </h6>
        <div class="progress mb-3"><div class="progress-bar bg-danger" style="width:0%"></div></div>

        <h6 class="small fw-semibold mb-1">
          Sales Tracking <span class="float-end card-sub">0%</span>
        </h6>
        <div class="progress mb-3"><div class="progress-bar bg-warning" style="width:0%"></div></div>

        <h6 class="small fw-semibold mb-1">
          Customer Database <span class="float-end card-sub">0%</span>
        </h6>
        <div class="progress mb-3"><div class="progress-bar bg-info" style="width:0%"></div></div>

        <h6 class="small fw-semibold mb-1">
          Payout Details <span class="float-end card-sub">0%</span>
        </h6>
        <div class="progress"><div class="progress-bar bg-success" style="width:0%"></div></div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card p-3 h-100">
        <h6 class="fw-bold text-primary mb-3">Illustrations</h6>
        <div class="text-center p-4">
          <i class="bi bi-images fs-1 text-secondary"></i>
          <p class="mt-3 small text-muted mb-0">No artwork yet — add later.</p>
        </div>
      </div>
    </div>
  </div>

</div>

<?php include __DIR__ . '/partials/foot.php'; ?>
