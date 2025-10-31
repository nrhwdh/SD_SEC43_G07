<?php
require_once __DIR__.'/auth.php';
require_login();
$page_title = 'Charts Overview';

// ===== Fetch Data =====
$bookings = $pdo->query("
  SELECT id, room_id, total, check_in 
  FROM bookings
")->fetchAll(PDO::FETCH_ASSOC);

$rooms = $pdo->query("
  SELECT id, name FROM rooms
")->fetchAll(PDO::FETCH_KEY_PAIR);

// ===== Prepare Data =====
$trend = [];
foreach ($bookings as $b) {
  $date = date('Y-m-d', strtotime($b['check_in']));
  $trend[$date] = ($trend[$date] ?? 0) + 1;
}
ksort($trend);

$roomCount = [];
foreach ($bookings as $b) {
  $roomName = $rooms[$b['room_id']] ?? 'Unknown';
  $roomCount[$roomName] = ($roomCount[$roomName] ?? 0) + 1;
}

$monthlyRevenue = [];
foreach ($bookings as $b) {
  $month = date('M Y', strtotime($b['check_in']));
  $monthlyRevenue[$month] = ($monthlyRevenue[$month] ?? 0) + (float)$b['total'];
}
ksort($monthlyRevenue);

include __DIR__.'/partials/head.php';
include __DIR__.'/partials/top.php';
?>

<style>
  canvas {
    max-height: 280px !important; /* 🩵 Smaller charts, still readable */
  }
</style>

<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0 text-primary">Analytics Dashboard</h1>
  </div>

  <div class="row g-4">

    <!-- Bookings Trend -->
    <div class="col-lg-6">
      <div class="card shadow-sm p-3">
        <h6 class="fw-bold text-primary mb-2">Bookings Trend</h6>
        <canvas id="bookingsTrend"></canvas>
      </div>
    </div>

    <!-- Room Popularity -->
    <div class="col-lg-6">
      <div class="card shadow-sm p-3">
        <h6 class="fw-bold text-success mb-2">Room Popularity</h6>
        <canvas id="roomPopularity"></canvas>
      </div>
    </div>

    <!-- Monthly Revenue -->
    <div class="col-12">
      <div class="card shadow-sm p-3">
        <h6 class="fw-bold text-warning mb-2">Monthly Revenue Overview (RM)</h6>
        <canvas id="monthlyRevenue"></canvas>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const trendLabels = <?= json_encode(array_keys($trend)) ?>;
  const trendData = <?= json_encode(array_values($trend)) ?>;
  const roomLabels = <?= json_encode(array_keys($roomCount)) ?>;
  const roomData = <?= json_encode(array_values($roomCount)) ?>;
  const revLabels = <?= json_encode(array_keys($monthlyRevenue)) ?>;
  const revData = <?= json_encode(array_values($monthlyRevenue)) ?>;

  // Common chart option
  const simpleOpts = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true } }
  };

  // Bookings Trend
  new Chart(document.getElementById('bookingsTrend'), {
    type: 'line',
    data: {
      labels: trendLabels,
      datasets: [{
        label: 'Bookings',
        data: trendData,
        borderColor: '#4e73df',
        backgroundColor: 'rgba(78,115,223,0.1)',
        fill: true,
        tension: 0.3
      }]
    },
    options: simpleOpts
  });

  // Room Popularity
  new Chart(document.getElementById('roomPopularity'), {
    type: 'pie',
    data: {
      labels: roomLabels,
      datasets: [{
        data: roomData,
        backgroundColor: ['#36b9cc','#1cc88a','#f6c23e','#e74a3b','#858796','#4e73df','#20c997','#fd7e14']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } }
    }
  });

  // Monthly Revenue
  new Chart(document.getElementById('monthlyRevenue'), {
    type: 'bar',
    data: {
      labels: revLabels,
      datasets: [{
        label: 'Revenue (RM)',
        data: revData,
        backgroundColor: 'rgba(246,194,62,0.8)',
        borderColor: '#f6c23e',
        borderWidth: 1
      }]
    },
    options: simpleOpts
  });
</script>

<?php include __DIR__.'/partials/foot.php'; ?>