<?php
require_once __DIR__.'/auth.php';
require_login();
$page_title = 'Bookings List';

// Ambil semua data booking dari DB
$st = $pdo->query("
  SELECT 
    b.id, b.guest_name, b.guest_email, 
    b.room_id, r.name AS room_name, 
    b.check_in, b.check_out, 
    b.nights, b.guests, b.total, b.created_at
  FROM bookings b
  LEFT JOIN rooms r ON b.room_id = r.id
  ORDER BY b.id DESC
");
$rows = $st->fetchAll();

include __DIR__.'/partials/head.php';
include __DIR__.'/partials/top.php';
?>

<div class="container-fluid">

  <?php if (!empty($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
      Booking has been <strong>deleted successfully</strong>.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Bookings List</h1>
    <a href="tables.php" class="btn btn-outline-secondary btn-sm">← Back</a>
  </div>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="table-primary">
          <tr>
            <th>ID</th>
            <th>Guest Name</th>
            <th>Guest Email</th>
            <th>Room ID</th>
            <th>Room Name</th>
            <th>Check-In</th>
            <th>Check-Out</th>
            <th>Nights</th>
            <th>Guests</th>
            <th>Total (RM)</th>
            <th>Created At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= h($r['guest_name']) ?></td>
            <td><?= h($r['guest_email']) ?></td>
            <td><?= (int)$r['room_id'] ?></td>
            <td><?= $r['room_name'] ? h($r['room_name']) : '<i class="text-muted">Unknown</i>' ?></td>
            <td><?= h($r['check_in']) ?></td>
            <td><?= h($r['check_out']) ?></td>
            <td><?= (int)$r['nights'] ?></td>
            <td><?= (int)$r['guests'] ?></td>
            <td><?= number_format((float)$r['total'], 2) ?></td>
            <td><?= h($r['created_at']) ?></td>
            <td class="text-nowrap">
              <a href="booking_details.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
              <a href="bookings_edit.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
              <a href="bookings_delete.php?id=<?= (int)$r['id'] ?>"
                 class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('Are you sure you want to delete this booking? This action cannot be undone.');">
                 Delete
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__.'/partials/foot.php'; ?>