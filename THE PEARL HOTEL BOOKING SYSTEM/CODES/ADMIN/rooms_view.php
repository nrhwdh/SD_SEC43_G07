<?php
require_once __DIR__.'/auth.php';
require_login();
$page_title = 'Room Listing';
include __DIR__.'/partials/head.php';
include __DIR__.'/partials/top.php';

$st = $pdo->query("SELECT * FROM rooms ORDER BY id ASC");
$rooms = $st->fetchAll();
?>

<div class="container-fluid">
  <h1 class="h4 mb-3">Room Listing</h1>
  <div class="card p-3">
    <table class="table table-striped table-bordered">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>Room Name</th>
          <th>Type</th>
          <th>Price (RM)</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rooms as $r): ?>
        <tr>
          <td><?=h($r['id'])?></td>
          <td><?=h($r['room_name'])?></td>
          <td><?=h($r['type'])?></td>
          <td><?=h($r['price'])?></td>
          <td><?=h($r['status'])?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__.'/partials/foot.php'; ?>