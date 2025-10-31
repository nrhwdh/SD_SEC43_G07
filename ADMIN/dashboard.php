<?php
require_once __DIR__.'/auth.php';
require_login();
$page_title = 'Dashboard';
include __DIR__.'/partials/head.php';
include __DIR__.'/partials/top.php';

$me = current_admin();

// --- Total counts ---
$totalBookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$totalFeedback = $pdo->query("SELECT COUNT(*) FROM feedback")->fetchColumn();

// Feedback rating breakdown
$ratings = [1=>0,2=>0,3=>0,4=>0,5=>0];
$st = $pdo->query("SELECT rating, COUNT(*) AS count FROM feedback GROUP BY rating");
foreach ($st as $r) {
    $ratings[(int)$r['rating']] = (int)$r['count'];
}

// --- Total rooms ---
$totalRooms = $pdo->query("SELECT SUM(total_rooms) FROM rooms")->fetchColumn();

// --- Date picker ---
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// --- Live booked & available rooms for selected date ---
$stmt = $pdo->prepare("
    SELECT SUM(quantity) as booked
    FROM bookings
    WHERE check_in < :date_plus
      AND check_out > :date
");
$stmt->execute([
    ':date' => $selectedDate,
    ':date_plus' => date('Y-m-d', strtotime($selectedDate . ' +1 day'))
]);
$bookedRooms = $stmt->fetchColumn() ?: 0;
$availableRooms = max(0, $totalRooms - $bookedRooms);
?>

<style>
.hero-banner {
    background: linear-gradient(90deg, #007bff 0%, #6dd5fa 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.hero-banner h1 { margin: 0; font-weight: 700; }
.hero-banner span { font-size: 0.9rem; opacity: 0.9; }

.stat-card { border: none; border-radius: 12px; transition: 0.3s ease; padding: 1.2rem; }
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 0 15px rgba(0,0,0,0.1); }
.stat-icon { font-size: 2rem; opacity: 0.8; }
</style>

<div class="container-fluid">

    <!-- 🩵 WELCOME BANNER -->
    <div class="hero-banner">
        <div>
            <h1>Welcome back, <?= htmlspecialchars($me['name'] ?? 'Admin') ?> 👋</h1>
            <span>Here’s your daily overview for The Pearl Hotel operations.</span>
        </div>
        <i class="bi bi-bar-chart-line fs-1"></i>
    </div>

    <!-- 🗓️ Date Picker -->
    <form method="get" class="mb-3 d-flex align-items-end gap-2">
        <div>
            <label for="date" class="form-label">Check Date</label>
            <input type="date" id="date" name="date" class="form-control"
                   value="<?= htmlspecialchars($selectedDate) ?>"
                   max="<?= date('Y-m-d', strtotime('+1 year')) ?>">
        </div>
        <div class="mt-3">
            <button class="btn btn-primary">View</button>
        </div>
    </form>

    <!-- 💠 STATS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Bookings</h6>
                        <h3 class="fw-bold text-primary"><?= (int)$totalBookings ?></h3>
                    </div>
                    <i class="bi bi-calendar-check stat-icon text-primary"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card shadow-sm bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Available Rooms</h6>
                        <h3 class="fw-bold text-warning" id="availableCard"><?= (int)$availableRooms ?></h3>
                    </div>
                    <i class="bi bi-house-door stat-icon text-warning"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card shadow-sm bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Feedback Entries</h6>
                        <h3 class="fw-bold text-success"><?= (int)$totalFeedback ?></h3>
                    </div>
                    <i class="bi bi-chat-dots stat-icon text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 🌈 CHARTS SECTION -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm p-4">
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-graph-up-arrow me-2"></i> Customer Satisfaction Ratings</h5>
                <canvas id="ratingChart" height="140"></canvas>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm p-4">
                <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-pie-chart-fill me-2"></i> Room Occupancy</h5>
                <canvas id="roomChart" height="200"></canvas>
                <p class="text-muted small mt-3 mb-0">
                    Booked Rooms: <strong id="bookedCard"><?= $bookedRooms ?></strong><br>
                    Available Rooms: <strong id="availableCardText"><?= $availableRooms ?></strong><br>
                    Total Rooms: <strong><?= $totalRooms ?></strong><br>
                    For Date: <strong id="selectedDateText"><?= htmlspecialchars($selectedDate) ?></strong>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ⭐ Rating Bar Chart
const ctx1 = document.getElementById('ratingChart');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
        datasets: [{
            label: 'Number of Feedbacks',
            data: [<?= implode(',', $ratings) ?>],
            backgroundColor: ['#ff4d4d','#ffb84d','#ffe066','#99e699','#33cc33'],
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// 🥧 Room Occupancy Pie Chart
const ctx2 = document.getElementById('roomChart');
const roomChart = new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: ['Booked Rooms', 'Available Rooms'],
        datasets: [{
            data: [<?= $bookedRooms ?>, <?= $availableRooms ?>],
            backgroundColor: ['#007bff', '#ffc107'],
            borderWidth: 1
        }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
});

// --- Auto-refresh live room occupancy ---
function updateRoomData() {
    const date = document.getElementById('date').value;
    fetch(`dashboard_data.php?date=${date}`)
        .then(res => res.json())
        .then(data => {
            roomChart.data.datasets[0].data = [data.bookedRooms, data.availableRooms];
            roomChart.update();
            document.getElementById('bookedCard').textContent = data.bookedRooms;
            document.getElementById('availableCardText').textContent = data.availableRooms;
            document.getElementById('selectedDateText').textContent = date;
        });
}

// Refresh every 5 seconds
setInterval(updateRoomData, 5000);
</script>

<?php include __DIR__.'/partials/foot.php'; ?>
