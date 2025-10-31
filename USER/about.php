<?php ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>The Pearl Hotel | About</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/fancy.css">
  <style>
    body{font-family:'Poppins',sans-serif;}
    .brand-logo{height:40px;width:auto;}
    .section-gradient{background:linear-gradient(135deg,#fdfbfb 0%,#ebedee 100%);}
    .about-feature{background:#fff;border:1px solid #eee;border-radius:16px;overflow:hidden;box-shadow:0 6px 20px rgba(0,0,0,.05);}
    .about-feature img{width:100%;height:180px;object-fit:cover;}
    .about-feature .card-body{padding:1rem}
    /* footer */
    .site-footer{background:linear-gradient(180deg,#fff 0%,#faf7f8 100%);margin-top:3rem;}
    .footer-logo{height:36px;width:auto;}
    .footer-links li{margin-bottom:.35rem;}
    .footer-links a,.footer-link{color:#5b5b5b;text-decoration:none;}
    .footer-links a:hover,.footer-link:hover{color:#8a1538;}
    .footer-bottom{background:#f5f1f3;border-top:1px solid #eee;}
    .back-to-top{position:fixed;right:18px;bottom:18px;width:42px;height:42px;border-radius:999px;border:0;background:#8a1538;color:#fff;font-weight:700;box-shadow:0 6px 20px rgba(0,0,0,.15);cursor:pointer;display:none;}
    .back-to-top:hover{opacity:.92;}
    /* timeline */
    .timeline{position:relative;margin-left:12px}
    .timeline:before{content:"";position:absolute;left:6px;top:0;bottom:0;width:2px;background:#e9ecef}
    .timeline-item{position:relative;padding-left:24px;margin-bottom:14px}
    .timeline-dot{position:absolute;left:0;top:.5rem;width:12px;height:12px;background:#8a1538;border-radius:50%;outline:4px solid #f7f7f7}
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="assets/img/logo-pearl.png" alt="The Pearl Hotel" class="brand-logo">
      <span class="visually-hidden">The Pearl Hotel</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
        <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="py-5 section-gradient">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6"><img src="assets/img/lobby.jpg" class="rounded-4 w-100 shadow" alt="About The Pearl"></div>
      <div class="col-lg-6">
        <span class="badge soft rounded-pill mb-2">Our Story</span>
        <h1 class="fw-800 mb-3">Our Reputation Proven Through Time</h1>
        <p class="text-muted">Owned and Operated By<br><strong>Aikbee Timbers Sdn Bhd</strong><br>197701005868 (36911-K)</p>
        <p>At The Pearl Kuala Lumpur, we pride ourselves on providing our guests with a comfortable and convenient stay. Our four-star hotel offers a variety of amenities and services to make your stay as enjoyable as possible.</p>
        <p>Strategically located between Kuala Lumpur and Petaling Jaya, our modern rooms come with Naturatex mattresses, free Wi-Fi, minibars and deep soaking bathtubs — perfect for business or family getaways.</p>
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row g-4 align-items-stretch">
      <div class="col-lg-6">
        <div class="card shadow-sm rounded-4 h-100">
          <div class="card-body p-4">
            <span class="badge soft rounded-pill mb-2">Mission</span>
            <h3 class="fw-800 mb-2">Comfort, Care & Class</h3>
            <p class="text-muted mb-0">To deliver a warm, seamless and classy stay for every guest — whether on business, family trip or weekend escape.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card shadow-sm rounded-4 h-100">
          <div class="card-body p-4">
            <span class="badge soft rounded-pill mb-2">Vision</span>
            <h3 class="fw-800 mb-2">Kuala Lumpur’s Preferred City Retreat</h3>
            <p class="text-muted mb-0">To be the most loved 4-star urban sanctuary between Kuala Lumpur and Petaling Jaya — known for space, style and service.</p>
          </div>
        </div>
      </div>
    </div>

    <h2 class="fw-800 my-4">Why Choose The Pearl</h2>
    <div class="row g-4">
      <div class="col-sm-6 col-lg-3">
        <div class="about-feature h-100">
          <img src="assets/img/why/spacious-room.jpg" alt="Spacious Rooms">
          <div class="card-body">
            <h6 class="fw-bold mb-1">Spacious Rooms</h6>
            <p class="text-muted mb-0 small">Super King / Twin options with Naturatex mattresses.</p>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="about-feature h-100">
          <img src="assets/img/why/dining.jpg" alt="Dining & Café">
          <div class="card-body">
            <h6 class="fw-bold mb-1">Dining & Café</h6>
            <p class="text-muted mb-0 small">Hearty breakfast, Melo Café & Deli and great coffee.</p>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="about-feature h-100">
          <img src="assets/img/why/facilities.jpg" alt="Relaxing Facilities">
          <div class="card-body">
            <h6 class="fw-bold mb-1">Relaxing Facilities</h6>
            <p class="text-muted mb-0 small">Pool, gym and comfy lounges to reset after city tours.</p>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="about-feature h-100">
          <img src="assets/img/why/location.jpg" alt="Connected Location">
          <div class="card-body">
            <h6 class="fw-bold mb-1">Connected Location</h6>
            <p class="text-muted mb-0 small">Linked to Pearl Shopping Gallery via sky-bridge.</p>
          </div>
        </div>
      </div>
    </div>

    <h2 class="fw-800 my-4">Our Journey</h2>
    <div class="timeline">
      <div class="timeline-item"><span class="timeline-dot"></span><div class="timeline-content"><h6 class="fw-bold mb-1">1997 — Opening</h6><p class="text-muted mb-0 small">The Pearl welcomes its first guests in Kuala Lumpur.</p></div></div>
      <div class="timeline-item"><span class="timeline-dot"></span><div class="timeline-content"><h6 class="fw-bold mb-1">2010 — Major Refresh</h6><p class="text-muted mb-0 small">Guestrooms and public spaces receive a modern uplift.</p></div></div>
      <div class="timeline-item"><span class="timeline-dot"></span><div class="timeline-content"><h6 class="fw-bold mb-1">2025 — 4-Star Recognition</h6><p class="text-muted mb-0 small">Recognised for comfort, cleanliness and service.</p></div></div>
    </div>
  </div>
</section>

<footer class="site-footer">
  <div class="container py-5">
    <div class="row g-4">
      <div class="col-md-4"><a href="index.php" class="d-inline-flex align-items-center mb-2 text-decoration-none"><img src="assets/img/logo-pearl.png" alt="The Pearl Hotel" class="footer-logo"></a><p class="text-muted mb-0">City Views. Calm Nights. Pearl Luxury.</p></div>
      <div class="col-6 col-md-4"><h6 class="fw-bold mb-3">Quick Links</h6><ul class="list-unstyled footer-links"><li><a href="index.php">Home</a></li><li><a href="rooms.php">Rooms</a></li><li><a href="about.php">About</a></li><li><a href="contact.php">Contact</a></li><li><a href="feedback.php">Feedback</a></li></ul></div>
      <div class="col-6 col-md-4"><h6 class="fw-bold mb-3">Contact</h6><ul class="list-unstyled small mb-0"><li>The Pearl Kuala Lumpur,<br>Batu 5, Jalan Klang Lama,<br>58000 Kuala Lumpur</li><li>Phone: +603-7983 1111</li><li>Email: info@pearl.com.my</li></ul></div>
    </div>
  </div>
  <div class="footer-bottom py-3"><div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
  </div></div>
  <button class="back-to-top" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'});"></button>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>(function(){const b=document.querySelector('.back-to-top');if(!b)return;const t=()=>{b.style.display=window.scrollY>400?'inline-flex':'none'};addEventListener('scroll',t,{passive:true});t();})();</script>
</body>
</html>
