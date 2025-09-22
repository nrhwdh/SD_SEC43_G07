<?php
require_once __DIR__.'/auth.php';
require_login();
$page_title = 'Charts';
include __DIR__.'/partials/head.php';
include __DIR__.'/partials/top.php';
?>
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Charts</h1>
  </div>

  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-3 h-100">
        <h6 class="fw-bold text-primary mb-2">Bookings Trend</h6>
        <div class="text-muted">No data yet</div>
        <!-- Optional: <canvas id="lineChart" height="110"></canvas> -->
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card p-3 h-100">
        <h6 class="fw-bold text-primary mb-2">Feedback Overview</h6>
        <div class="text-muted">No data yet</div>
        <!-- Optional: <canvas id="pieChart" height="110"></canvas> -->
      </div>
    </div>
  </div>
</div>
<?php include __DIR__.'/partials/foot.php'; ?>
