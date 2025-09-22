<?php
require_once __DIR__.'/auth.php';
require_login();
$page_title = 'Tables';
include __DIR__.'/partials/head.php';
include __DIR__.'/partials/top.php';
?>
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Tables</h1>
  </div>

  <div class="card p-3">
    <h6 class="fw-bold text-primary mb-2">Data Listing</h6>
    <p class="text-muted mb-0">No data yet</p>
  </div>
</div>
<?php include __DIR__.'/partials/foot.php'; ?>
