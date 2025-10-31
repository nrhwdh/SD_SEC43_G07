<?php
// Example template page (index.php / rooms.php / about.php etc.)
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>The Pearl Hotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
  <style>
    body{font-family:'Poppins',sans-serif;}

    /* Navbar */
    .navbar-brand img{height:40px;width:auto;}

    /* Footer */
    .site-footer{
      background: linear-gradient(180deg,#ffffff 0%,#faf7f8 100%);
      position: relative;
      margin-top: 3rem;
    }
    .footer-logo{height:36px;width:auto;}
    .footer-links li{margin-bottom:.35rem;}
    .footer-links a,
    .footer-link{color:#5b5b5b;text-decoration:none;}
    .footer-links a:hover,
    .footer-link:hover{color:#8a1538;}
    .footer-bottom{background:#f5f1f3;border-top:1px solid #eee;}
    .back-to-top{
      position: fixed;
      right: 18px;
      bottom: 18px;
      width: 42px;
      height: 42px;
      border-radius: 999px;
      border: 0;
      background:#8a1538;
      color:#fff;
      font-weight:700;
      box-shadow: 0 6px 20px rgba(0,0,0,.15);
      cursor:pointer;
      display:none;
    }
    .back-to-top:hover{opacity:.92;}
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="assets/img/logo-pearl.png" alt="The Pearl Hotel">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
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

<!-- CONTENT -->
<section class="container py-5">
  <h1 class="fw-bold">Welcome to The Pearl Hotel</h1>
  <p class="lead">This is a sample content area. Replace with your actual page content.</p>
</section>

<!-- FOOTER -->
<footer class="site-footer">
  <div class="container py-5">
    <div class="row g-4">
      <!-- Brand -->
      <div class="col-md-4">
        <a href="index.php" class="d-inline-flex align-items-center mb-2 text-decoration-none">
          <img src="assets/img/logo-pearl.png" alt="The Pearl Hotel" class="footer-logo">
        </a>
        <p class="text-muted mb-0">City Views. Calm Nights. Pearl Luxury.</p>
      </div>

      <!-- Quick Links -->
      <div class="col-6 col-md-4">
        <h6 class="fw-bold mb-3">Quick Links</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="rooms.php">Rooms</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="feedback.php">Feedback</a></li>
          <li><a href="bookings.php">Bookings</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div class="col-6 col-md-4">
        <h6 class="fw-bold mb-3">Contact</h6>
        <ul class="list-unstyled small mb-0">
          <li class="mb-2">
            The Pearl Kuala Lumpur,<br>
            Batu 5, Jalan Klang Lama,<br>
            58000 Kuala Lumpur, Malaysia
          </li>
          <li class="mb-2">Phone: <a href="tel:+60379831111" class="footer-link">+603-7983 1111</a></li>
          <li>Email: <a href="mailto:info@pearl.com.my" class="footer-link">info@pearl.com.my</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Bottom bar -->
  <div class="footer-bottom py-3">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
      <small class="text-muted mb-0">© <?= date('Y'); ?> The Pearl Hotel. All rights reserved.</small>
      <div class="d-flex gap-3 small">
        <a href="#" class="footer-link">Terms</a>
        <a href="#" class="footer-link">Privacy</a>
      </div>
    </div>
  </div>

  <!-- Back to top -->
  <button class="back-to-top" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'});">↑</button>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Back-to-top toggle
  (function(){
    const btn = document.querySelector('.back-to-top');
    if(!btn) return;
    const toggle = () => {
      if(window.scrollY > 400){ btn.style.display = 'inline-flex'; }
      else { btn.style.display = 'none'; }
    };
    window.addEventListener('scroll', toggle, {passive:true});
    toggle();
  })();
</script>
</body>
</html>
