<?php include 'db.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>The Pearl Hotel | Rooms</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/fancy.css">
  <style>
    body{font-family:'Poppins',sans-serif;}
    .brand-logo{height:40px;width:auto;}
    .room-fancy .room-img{object-fit:cover;width:100%;height:100%;}
    .price-tag{font-weight:800;}
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
        <li class="nav-item"><a class="nav-link active" href="rooms.php">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="container py-5">
  <h1 class="fw-800 mb-4">Rooms</h1>
  <div class="row g-4">
<?php
$res = $conn->query("SELECT * FROM rooms ORDER BY price DESC, name ASC");
if($res && $res->num_rows>0){
  while($row = $res->fetch_assoc()){
    echo '<div class="col-md-6 col-lg-4">
            <div class="card room-fancy h-100 shadow-sm">
              <div class="ratio ratio-4x3">
                <img src="'.htmlspecialchars($row['image']).'" alt="'.htmlspecialchars($row['name']).'" class="room-img">
              </div>
              <div class="card-body d-flex flex-column">
                <h5 class="fw-bold mb-1">'.htmlspecialchars($row['name']).'</h5>
                <p class="small text-muted mb-2">'.htmlspecialchars($row['beds']).' • '.htmlspecialchars($row['size']).'</p>
                <div class="mt-auto d-flex justify-content-between align-items-center">
                  <span class="price-tag">RM '.htmlspecialchars($row['price']).'<span class="small text-muted">/night</span></span>
                  <a href="book.php?room_id='.(int)$row['id'].'" class="btn btn-brand btn-sm">Book</a>
                </div>
              </div>
            </div>
          </div>';
  }
}else{
  echo '<div class="col-12"><div class="alert alert-warning">No rooms found.</div></div>';
}
?>
  </div>
</section>

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
          <li><a href="index.php">Home</a></li><li><a href="rooms.php">Rooms</a></li>
          <li><a href="about.php">About</a></li><li><a href="contact.php">Contact</a></li>
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
  <button class="back-to-top" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'});"></button>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){const b=document.querySelector('.back-to-top');if(!b)return;const t=()=>{b.style.display=window.scrollY>400?'inline-flex':'none'};addEventListener('scroll',t,{passive:true});t();})();
</script>
</body>
</html>
