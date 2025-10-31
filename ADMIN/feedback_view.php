<?php
require_once __DIR__.'/auth.php';
require_login();
$page_title = 'Guest Feedback';

// ambil data dari DB
$st = $pdo->query("
  SELECT id, name, message, rating, stay_date, room_id, created_at
  FROM feedback
  ORDER BY created_at DESC
");
$rows = $st->fetchAll();

include __DIR__.'/partials/head.php';
include __DIR__.'/partials/top.php';
?>

<style>
/* sembunyi element datatables global (pagination/info/length) */
.dataTables_length,
.dataTables_info,
.dataTables_paginate {
  display: none !important;
}
</style>

<div class="container-fluid">
  <!-- header bar -->
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Guest Feedback</h1>
    <a href="tables.php" class="btn btn-outline-secondary btn-sm ms-auto">
      ← Back
    </a>
  </div>

  <!-- main card -->
  <div class="card p-3 shadow-sm">
    <div class="table-responsive">
      <table id="feedbackTable" class="table table-striped align-middle">
        <thead class="table-primary">
          <tr>
            <th>ID</th>
            <th>Guest Name</th>
            <th>Room ID</th>
            <th>Rating</th>
            <th>Feedback Message</th>
            <th>Stay Date</th>
            <th>Submitted At</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($rows): ?>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?= (int)$r['id'] ?></td>
                <td><?= h($r['name']) ?></td>
                <td><?= (int)$r['room_id'] ?></td>
                <td>
                  <?php
                    $stars = max(0, min(5, (int)$r['rating']));
                    for ($i = 0; $i < $stars; $i++) echo '⭐';
                    for ($i = $stars; $i < 5; $i++) echo '☆';
                  ?>
                </td>
                <td><?= h($r['message']) ?></td>
                <td><?= h($r['stay_date']) ?></td>
                <td><?= h($r['created_at']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-3">
                No feedback found.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
// disable datatable paging/info for this table
(function(){
  if (window.jQuery && $.fn.DataTable) {
    const sel = '#feedbackTable';
    if ($.fn.DataTable.isDataTable(sel)) {
      $(sel).DataTable().destroy();
    }
    $(sel).DataTable({
      paging: false,
      info: false,
      lengthChange: false,
      searching: false
    });
  }
})();
</script>

<?php include __DIR__.'/partials/foot.php'; ?>