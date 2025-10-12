<?php
require_once __DIR__ . '/auth.php';
require_login();

$me = current_admin();
$page_title = 'Dashboard';

/* -------------------- Helpers -------------------- */
function qcount(PDO $pdo, string $sql, array $params = []) {
  try {
    $st = $pdo->prepare($sql);
    $st->execute($params);
    return (int)$st->fetchColumn();
  } catch (Throwable $e) { return null; }
}
function qall(PDO $pdo, string $sql, array $params = []) {
  try {
    $st = $pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  } catch (Throwable $e) { return []; }
}
function has_column(PDO $pdo, string $table, string $col) {
  try {
    $st = $pdo->prepare("SELECT 1
       FROM INFORMATION_SCHEMA.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME   = ?
        AND COLUMN_NAME  = ?");
    $st->execute([$table, $col]);
    return (bool)$st->fetchColumn();
  } catch (Throwable $e) { return false; }
}
function stat_cell($val) {
  return ($val === null) ? '<span class="empty-badge">—</span>' : number_format((int)$val);
}

/* -------------------- STATS -------------------- */
// Total bookings (all time)
$totalBookings = qcount($pdo, "SELECT COUNT(*) FROM bookings");

// Feedback count (all time) — change to `feedbacks` if your table name differs
$feedbackCount = qcount($pdo, "SELECT COUNT(*) FROM feedback");

// Available rooms (simple mode: direct count from rooms table)
$availableRooms = qcount($pdo, "SELECT COUNT(*) FROM rooms");

/* -------------------- CHART DATA -------------------- */
// Bookings last 7 days by check_in
$rows = qall($pdo, "SELECT DATE(check_in) AS d, COUNT(*) AS c
                      FROM bookings
                     WHERE check_in >= (CURDATE() - INTERVAL 6 DAY)
                  GROUP BY DATE(check_in)
                  ORDER BY d ASC");

// Normalize to full 7-day series (fill missing dates with 0)
$labels = [];
$counts = [];
for ($i = 6; $i >= 0; $i--) {
  $date = (new DateTime())->modify("-{$i} day")->format('Y-m-d');
  $labels[] = $date;
  $counts[] = 0;
}
$idx = array_flip($labels);
foreach ($rows as $r) {
  if (isset($idx[$r['d']])) $counts[$idx[$r['d']]] = (int)$r['c'];
}

// Feedback sources (optional pie) if you have a `feedback.source` column
$fbSources = [];
if (has_column($pdo, 'feedback', 'source')) {
  $fbSources = qall($pdo, "SELECT COALESCE(NULLIF(TRIM(source),''),'Unknown') AS s, COUNT(*) AS c
                              FROM feedback
                          GROUP BY s
                          ORDER BY c DESC, s ASC");
}

include __DIR__ . '/partials/head.php';
include __DIR__ . '/partials/top.php';
?>

<!-- Chart.js (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<div class="container-fluid">

  <!-- Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Dashboard</h1>
  </div>

  <!-- Stat cards -->
  <div class="row g-3 mb-3">
    <div class="col-xl-4 col-md-6">
      <div class="card p-3 h-100">
        <div class="d-flex justify-content-between align-items-center sb-stat">
          <div>
            <div class="title">TOTAL BOOKINGS</div>
            <div class="num"><?= stat_cell($totalBookings) ?></div>
            <div class="card-sub"><?= $totalBookings===null?'No data':'All time' ?></div>
          </div>
          <i class="bi bi-calendar2-check fs-2 text-primary"></i>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-md-6">
      <div class="card p-3 h-100">
        <div class="d-flex justify-content-between align-items-center sb-stat">
          <div>
            <div class="title">AVAILABLE ROOMS</div>
            <div class="num"><?= stat_cell($availableRooms) ?></div>
            <div class="card-sub"><?= $availableRooms===null?'No data':'Live count' ?></div>
          </div>
          <i class="bi bi-door-open fs-2 text-success"></i>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-md-6">
      <div class="card p-3 h-100">
        <div class="d-flex justify-content-between align-items-center sb-stat">
          <div>
            <div class="title">FEEDBACK</div>
            <div class="num"><?= stat_cell($feedbackCount) ?></div>
            <div class="card-sub"><?= $feedbackCount===null?'No data':'All time' ?></div>
          </div>
          <i class="bi bi-chat-dots fs-2 text-warning"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts row -->
  <div class="row g-3 mb-3">
    <!-- Bookings Overview (small line chart for screenshot) -->
    <div class="col-lg-8">
      <div class="card p-3 h-100 shadow-sm">
        <h6 class="fw-bold text-primary mb-2">Bookings Overview</h6>
        <?php if (array_sum($counts) === 0): ?>
          <div class="text-muted">No data yet</div>
        <?php else: ?>
          <div style="height:180px">
            <canvas id="bookingsChart"></canvas>
          </div>
          <script>
            (() => {
              const ctx = document.getElementById('bookingsChart');
              new Chart(ctx, {
                type: 'line',
                data: {
                  labels: <?= json_encode($labels) ?>,
                  datasets: [{
                    label: 'Bookings',
                    data: <?= json_encode($counts) ?>,
                    tension: 0.25,
                    borderWidth: 2,
                    fill: false,
                    pointRadius: 2,
                    pointHoverRadius: 3
                  }]
                },
                options: {
                  responsive: true,
                  maintainAspectRatio: true,
                  layout: { padding: { top: 4, right: 6, bottom: 0, left: 0 } },
                  plugins: { legend: { display: false } },
                  scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                  }
                }
              });
            })();
          </script>
        <?php endif; ?>
      </div>
    </div>

    <!-- Feedback Sources -->
    <div class="col-lg-4">
      <div class="card p-3 h-100 shadow-sm">
        <h6 class="fw-bold text-primary mb-2">Feedback Sources</h6>
        <?php if (empty($fbSources)): ?>
          <div class="text-muted">No data yet</div>
        <?php else: ?>
          <div style="height:180px">
            <canvas id="fbChart"></canvas>
          </div>
          <script>
            (() => {
              const labels = <?= json_encode(array_column($fbSources,'s')) ?>;
              const counts = <?= json_encode(array_map('intval', array_column($fbSources,'c'))) ?>;
              new Chart(document.getElementById('fbChart'), {
                type: 'doughnut',
                data: { labels, datasets: [{ data: counts }] },
                options: {
                  responsive: true,
                  maintainAspectRatio: true,
                  plugins: { legend: { position: 'bottom' } }
                }
              });
            })();
          </script>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Projects + Illustration (unchanged) -->
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

