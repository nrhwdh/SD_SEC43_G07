<?php include 'db.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>The Pearl Hotel | Contact</title>

  <!-- Vendor & fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <!-- Your styles -->
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/fancy.css">

  <style>
    .contact-card{border:1px solid #eee;border-radius:1rem;box-shadow:0 8px 24px rgba(0,0,0,.06);}
    .soft-pill{background:#f7f7f9;border:1px solid #ececf1;color:#222;border-radius:9999px;padding:.35rem .7rem;font-weight:700;}
    .mini-title{font-size:.95rem;font-weight:700;letter-spacing:.2px;color:#6c757d;text-transform:uppercase}
    .info-item + .info-item{margin-top:1rem}
    .transport-tile{background:#fff;border:1px solid #eee;border-radius:1rem;padding:1rem 1.25rem;height:100%;box-shadow:0 6px 16px rgba(0,0,0,.05)}
    .transport-tile h6{font-weight:700;margin-bottom:.35rem}
    .faq .accordion-button{font-weight:600}
    .ratio-map{border-radius:1rem;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,.08)}

    /* footer */
    .site-footer{background:linear-gradient(180deg,#fff 0%,#faf7f8 100%);margin-top:3rem;}
    .footer-logo{height:36px;width:auto;}
    .footer-links li{margin-bottom:.35rem;}
    .footer-links a,.footer-link{color:#5b5b5b;text-decoration:none;}
    .footer-links a:hover,.footer-link:hover{color:#8a1538;}
    .footer-bottom{background:#f5f1f3;border-top:1px solid #eee;}
    .back-to-top{position:fixed;right:18px;bottom:18px;width:42px;height:42px;border-radius:999px;border:0;background:#8a1538;color:#fff;font-weight:700;box-shadow:0 6px 20px rgba(0,0,0,.15);cursor:pointer;display:none;}
    .back-to-top:hover{opacity:.92;}
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="assets/img/logo-pearl.png" alt="The Pearl Hotel" class="brand-logo">
      <span class="visually-hidden">The Pearl Hotel</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Contact header -->
<section class="py-5 section-gradient">
  <div class="container">
    <div class="row g-4 align-items-start">
      <!-- Left: Info -->
      <div class="col-lg-5">
        <div class="contact-card p-4 p-md-4">
          <h1 class="fw-800 mb-3">Contact Us</h1>

          <div class="info-item">
            <div class="mini-title mb-1">Address</div>
            <p class="mb-0">
              The Pearl Kuala Lumpur, Batu 5, Jalan Klang Lama,<br>
              58000 Kuala Lumpur, Malaysia
            </p>
          </div>

          <div class="info-item">
            <div class="mini-title mb-1">Phone</div>
            <p class="mb-0">+603-7983 1111</p>
          </div>

          <div class="info-item">
            <div class="mini-title mb-1">Email</div>
            <p class="mb-0">info@pearl.com.my</p>
          </div>

          <hr class="my-4">

          <div class="info-item">
            <div class="mini-title mb-1">Operating Hours</div>
            <p class="mb-0 small">
              <strong>Front Desk / Check-in:</strong> 24 hours daily<br>
              <strong>Reservations Office:</strong> Mon – Fri, 9.00 AM – 6.00 PM<br>
              <strong>Melo Café & Deli:</strong> 7.00 AM – 11.00 PM<br>
              <strong>Swimming Pool & Gym:</strong> 7.00 AM – 10.00 PM
            </p>
          </div>
        </div>
      </div>

      <!-- Right: Map -->
      <div class="col-lg-7">
        <div class="ratio ratio-4x3 ratio-map">
          <iframe
            src="https://maps.google.com/maps?q=The%20Pearl%20Kuala%20Lumpur%20Batu%205%20Jalan%20Klang%20Lama&t=&z=14&ie=UTF8&iwloc=&output=embed"
            style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Getting here -->
<section class="py-5 section-soft">
  <div class="container">
    <h2 class="fw-800 mb-4">Getting Here</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="transport-tile">
          <h6>By Car / E-Hailing</h6>
          <p class="text-muted mb-0 small">
            Pin “The Pearl Kuala Lumpur”. Covered parking available at the mall complex connected to the hotel.
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="transport-tile">
          <h6>By LRT / KTM</h6>
          <p class="text-muted mb-0 small">
            From LRT Abdullah Hukum / LRT Taman Jaya or KTM Pantai Dalam, take a short ride (Grab/Taxi) to the hotel.
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="transport-tile">
          <h6>Airport Transfer</h6>
          <p class="text-muted mb-0 small">
            We can help arrange transfers. Contact the Front Desk in advance for rates and availability.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="py-5">
  <div class="container">
    <h2 class="fw-800 mb-4">FAQ</h2>
    <div class="faq accordion" id="faqPearl">
      <div class="accordion-item">
        <h2 class="accordion-header" id="q1">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#a1" aria-expanded="true" aria-controls="a1">
            Where can I park?
          </button>
        </h2>
        <div id="a1" class="accordion-collapse collapse show" aria-labelledby="q1" data-bs-parent="#faqPearl">
          <div class="accordion-body">
            Covered parking is available at the Pearl Shopping Gallery complex connected to the hotel.
            Standard mall parking rates apply.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="q2">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a2" aria-expanded="false" aria-controls="a2">
            Do you offer luggage storage?
          </button>
        </h2>
        <div id="a2" class="accordion-collapse collapse" aria-labelledby="q2" data-bs-parent="#faqPearl">
          <div class="accordion-body">
            Yes. Complimentary short-term luggage storage is available for in-house guests. Ask our Front Desk team.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="q3">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a3" aria-expanded="false" aria-controls="a3">
            What time is check-in and check-out?
          </button>
        </h2>
        <div id="a3" class="accordion-collapse collapse" aria-labelledby="q3" data-bs-parent="#faqPearl">
          <div class="accordion-body">
            Check-in is from 3:00 PM and check-out is at 12:00 PM. Early check-in/late check-out is subject to availability.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="site-footer">
  <div class="container py-5">
    <div class="row g-4">
      <div class="col-md-4">
        <a href="index.php" class="d-inline-flex align-items-center mb-2 text-decoration-none">
          <img src="assets/img/logo-pearl.png" alt="The Pearl Hotel" class="footer-logo">
        </a>
        <p class="text-muted mb-0">City Views. Calm Nights. Pearl Luxury.</p>
      </div>
      <div class="col-6 col-md-4">
        <h6 class="fw-bold mb-3">Quick Links</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="rooms.php">Rooms</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="feedback.php">Feedback</a></li>
        </ul>
      </div>
      <div class="col-6 col-md-4">
        <h6 class="fw-bold mb-3">Contact</h6>
        <ul class="list-unstyled small mb-0">
          <li>The Pearl Kuala Lumpur,<br>Batu 5, Jalan Klang Lama,<br>58000 Kuala Lumpur</li>
          <li>Phone: +603-7983 1111</li>
          <li>Email: info@pearl.com.my</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="footer-bottom py-3">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
      </div>
    </div>
  </div>
  <button class="back-to-top" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'});"></button>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  const b=document.querySelector('.back-to-top');
  if(!b) return;
  const t=()=>{ b.style.display=window.scrollY>400?'inline-flex':'none'; };
  addEventListener('scroll',t,{passive:true}); t();
})();
</script>
</body>
</html>
