<?php include 'db.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>The Pearl Hotel | Home</title>

  <!-- Vendor & fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <!-- Your styles -->
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/fancy.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="assets/img/logo-pearl.png" alt="The Pearl Hotel" class="brand-logo" />
      <span class="visually-hidden">The Pearl Hotel</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
        <li class="nav-item"><a class="nav-link" href="bookings.php">Bookings</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero hero-luxe"
  style="background:
           linear-gradient(rgba(0,0,0,.45), rgba(0,0,0,.45)),
           url('assets/img/hero-luxe.jpg') center/cover no-repeat;">
  <div class="container py-5">
    <div class="row align-items-center">
      <div class="col-lg-9 text-white py-5">
        <span class="badge rounded-pill bg-light text-dark mb-3 px-3 py-2 shadow-sm">Welcome to The Pearl Hotel</span>
        <h1 class="display-3 fw-800 mb-2">City Views. Calm Nights.</h1>
        <h2 class="display-6 fw-800 text-brand mb-3">Pearl Luxury.</h2>
        <p class="lead mb-4">Comfort crafted for business &amp; weekend escapes — right between Kuala Lumpur and Petaling Jaya.</p>
        <a href="rooms.php" class="btn btn-brand btn-lg me-2">Explore Rooms</a>
      </div>
    </div>
  </div>
</section>

<!-- Highlights -->
<section class="py-5 section-soft">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="highlight-card h-100">
          <img src="assets/img/lobby.jpg" class="rounded-4 w-100" alt="Lobby" loading="lazy">
          <div class="p-3">
            <h5 class="fw-bold mb-1">Central & Connected</h5>
            <p class="text-muted mb-0">Linked to Pearl Shopping Gallery via sky-bridge. Food, retail, all in one place.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="highlight-card h-100">
          <img src="assets/img/pool.jpg" class="rounded-4 w-100" alt="Pool" loading="lazy">
          <div class="p-3">
            <h5 class="fw-bold mb-1">Relaxing Facilities</h5>
            <p class="text-muted mb-0">Fitness, café &amp; comfy lounges — refresh after meetings or city tours.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="highlight-card h-100">
          <img src="assets/img/dining.jpg" class="rounded-4 w-100" alt="Dining" loading="lazy">
          <div class="p-3">
            <h5 class="fw-bold mb-1">All-day Dining</h5>
            <p class="text-muted mb-0">Hearty breakfast to late-night bites at our Melo Café &amp; Deli.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Gallery -->
<section class="py-5 section-soft">
  <div class="container">
    <h2 class="fw-800 mb-4">Gallery</h2>
    <div class="row g-3 gallery">
      <div class="col-6 col-md-4"><img src="assets/img/gallery-1.jpg" class="rounded-4 w-100" alt="Gallery 1" loading="lazy"></div>
      <div class="col-6 col-md-4"><img src="assets/img/gallery-2.jpg" class="rounded-4 w-100" alt="Gallery 2" loading="lazy"></div>
      <div class="col-6 col-md-4"><img src="assets/img/gallery-3.jpg" class="rounded-4 w-100" alt="Gallery 3" loading="lazy"></div>
      <div class="col-6 col-md-4"><img src="assets/img/gallery-4.jpg" class="rounded-4 w-100" alt="Gallery 4" loading="lazy"></div>
      <div class="col-6 col-md-4"><img src="assets/img/gallery-5.jpg" class="rounded-4 w-100" alt="Gallery 5" loading="lazy"></div>
      <div class="col-6 col-md-4"><img src="assets/img/gallery-6.jpg" class="rounded-4 w-100" alt="Gallery 6" loading="lazy"></div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="py-4 border-top mt-5">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <p class="mb-0 small">© 2025 The Pearl Hotel</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
