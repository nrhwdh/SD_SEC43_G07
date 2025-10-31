<?php
session_start();
include 'db.php';

$TB = 'feedback'; // table name

/* ----------------------- helpers ----------------------- */
function mask_name($name){
  $name = trim($name);
  if($name==='') return 'Anonymous';
  $len = strlen($name);
  if($len >= 4){ return substr($name,0,1) . '**' . substr($name,3); }
  if($len == 3){ return substr($name,0,1) . '*' . substr($name,-1); }
  if($len == 2){ return substr($name,0,1) . '*'; }
  return $name;
}
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

/* ----------------------- handle submit ----------------------- */
$alert = "";
if($_SERVER['REQUEST_METHOD']==='POST'){
  // honeypot (anti-spam)
  if(!empty($_POST['website'])){ die("No bots"); }

  $name    = trim($_POST['name'] ?? ''); if($name==='') $name='Anonymous';
  $email   = '-';                 // not used on this page
  $message = trim($_POST['message'] ?? '');
  $rating  = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
  $room_id = !empty($_POST['room_id']) ? (int)$_POST['room_id'] : null;
  $stay_dt = !empty($_POST['stay_date']) ? $_POST['stay_date'] : null;

  if(strlen($message) < 2){
    $alert = '<div class="alert alert-danger">Please write your message (min 2 chars).</div>';
  } elseif($rating < 1 || $rating > 5){
    $alert = '<div class="alert alert-danger">Please select a rating.</div>';
  } else {
    // simple throttle: max 1 post / 60s per session
    $last = $_SESSION['last_fb'] ?? 0;
    if(time() - $last < 60){
      $alert = '<div class="alert alert-warning">Please wait a minute before posting another review.</div>';
    } else {
      $stmt = $conn->prepare("INSERT INTO $TB (name,email,message,rating,room_id,stay_date) VALUES (?,?,?,?,?,?)");
      $stmt->bind_param("sssiss", $name,$email,$message,$rating,$room_id,$stay_dt);
      if($stmt->execute()){
        $_SESSION['last_fb']=time();
        $alert = '<div class="alert alert-success">Thanks! Your feedback has been posted. 🫶</div>';
      } else {
        $alert = '<div class="alert alert-danger">Error: '.h($conn->error).'</div>';
      }
      $stmt->close();
    }
  }
}

/* ----------------------- filters / query ----------------------- */
$min  = isset($_GET['min'])  ? (int)$_GET['min'] : 0;
$room = isset($_GET['room']) ? (int)$_GET['room'] : 0;
$sort = $_GET['sort'] ?? 'new';

$where=[]; $params=[]; $types='';
if($min){  $where[]="rating >= ?"; $types.='i'; $params[]=$min; }
if($room){ $where[]="room_id = ?"; $types.='i'; $params[]=$room; }
$whereSql = $where ? "WHERE ".implode(" AND ",$where) : "";

$orderSql = "ORDER BY created_at DESC";
if($sort==='high') $orderSql="ORDER BY rating DESC, created_at DESC";
if($sort==='low')  $orderSql="ORDER BY rating ASC, created_at DESC";

$page=max(1,(int)($_GET['page']??1));
$perPage=10;
$offset=($page-1)*$perPage;

/* total count */
$cq = $conn->prepare("SELECT COUNT(*) c FROM $TB $whereSql");
if($types) $cq->bind_param($types, ...$params);
$cq->execute();
$total=(int)($cq->get_result()->fetch_assoc()['c'] ?? 0);
$cq->close();

/* list */
$lq = $conn->prepare("SELECT id,name,message,created_at,rating,room_id,stay_date FROM $TB $whereSql $orderSql LIMIT ? OFFSET ?");
if($types){
  $types2=$types.'ii'; $params2=array_merge($params,[$perPage,$offset]);
  $lq->bind_param($types2,...$params2);
}else{
  $lq->bind_param('ii',$perPage,$offset);
}
$lq->execute();
$list=$lq->get_result();
$lq->close();

/* average + distribution (for bars) */
$avg = $conn->query("SELECT ROUND(AVG(rating),1) AS avg, COUNT(*) AS cnt FROM $TB WHERE rating IS NOT NULL")->fetch_assoc();
$avgScore = $avg['avg'] ?? null;
$cnt=(int)($avg['cnt'] ?? 0);

$distRes = $conn->query("SELECT rating, COUNT(*) c FROM $TB WHERE rating IS NOT NULL GROUP BY rating");
$map = array_fill(1,5,0);
$totalRated = 0;
while($d = $distRes->fetch_assoc()){
  $map[(int)$d['rating']] = (int)$d['c'];
  $totalRated += (int)$d['c'];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>The Pearl Hotel | Feedback</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/fancy.css">
  <style>
    body{font-family:'Poppins',sans-serif;}
    .brand-logo{height:40px;width:auto;}

    .feedback-card{border:1px solid #eee;border-radius:1rem;padding:1rem 1.25rem;box-shadow:0 6px 20px rgba(0,0,0,.05);}
    .feedback-card+.feedback-card{margin-top:1rem;}
    .feedback-badge{background:#fff5f7;border:1px solid #f1c2cf;color:#a10f36;font-weight:700;padding:.35rem .6rem;border-radius:9999px;letter-spacing:.2px;}
    .star{color:#ffb703;font-size:1.1rem;line-height:1;}

    /* rating bars (no percentage text) */
    .bar-row{display:flex;align-items:center;gap:10px;margin-bottom:6px;}
    .bar-label{width:24px;text-align:right;font-size:.9rem;}
    .bar-bg{flex:1;height:8px;border-radius:999px;background:#f1f3f5;overflow:hidden;}
    .bar-fg{height:8px;background:#8a1538;}

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
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link active" href="feedback.php">Feedback</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="container py-5">
  <h1 class="fw-800 mb-3">Recent feedback</h1>

  <?php if($avgScore): ?>
  <div class="row g-4 align-items-center mb-4">
    <!-- left: score -->
    <div class="col-md-auto">
      <div class="display-5 fw-bold mb-0"><?= h($avgScore) ?></div>
      <div class="fs-4">
        <?php $full=(int)floor($avgScore); for($i=1;$i<=5;$i++) echo $i <= $full ? '⭐' : '☆'; ?>
      </div>
      <div class="text-muted small"><?= $cnt ?> reviews</div>
    </div>
    <!-- right: bars (no percentages) -->
    <div class="col-md">
      <?php for($i=5;$i>=1;$i--):
            $pct = $totalRated ? round($map[$i]*100/$totalRated) : 0; ?>
        <div class="bar-row">
          <span class="bar-label"><?= $i ?>★</span>
          <div class="bar-bg">
            <div class="bar-fg" style="width:<?= $pct ?>%"></div>
          </div>
        </div>
      <?php endfor; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Filters -->
  <form method="get" class="row g-2 align-items-end mb-3">
    <div class="col-auto">
      <label class="form-label small">Min rating</label>
      <select name="min" class="form-select form-select-sm">
        <option value="" <?= $min? '':'selected'?>>All</option>
        <option value="5" <?= $min==5?'selected':''?>>5★</option>
        <option value="4" <?= $min==4?'selected':''?>>4★+</option>
        <option value="3" <?= $min==3?'selected':''?>>3★+</option>
      </select>
    </div>
    <div class="col-auto">
      <label class="form-label small">Room</label>
      <select name="room" class="form-select form-select-sm">
        <option value="" <?= $room? '':'selected'?>>All</option>
        <?php
          $rooms=$conn->query("SELECT id,name FROM rooms ORDER BY name");
          while($r=$rooms->fetch_assoc()){
            $sel=($room && (int)$room==(int)$r['id'])?'selected':'';
            echo '<option value="'.$r['id'].'" '.$sel.'>'.h($r['name']).'</option>';
          }
        ?>
      </select>
    </div>
    <div class="col-auto">
      <label class="form-label small">Sort</label>
      <select name="sort" class="form-select form-select-sm">
        <option value="new"  <?= $sort==='new' ? 'selected':''; ?>>Newest</option>
        <option value="high" <?= $sort==='high'? 'selected':''; ?>>Highest rating</option>
        <option value="low"  <?= $sort==='low' ? 'selected':''; ?>>Lowest rating</option>
      </select>
    </div>
    <div class="col-auto"><button class="btn btn-outline-secondary btn-sm">Apply</button></div>
  </form>

  <!-- LIST -->
  <?php
  if($total===0){
    echo '<div class="alert alert-secondary">No feedback yet. Be the first!</div>';
  } else {
    while($row=$list->fetch_assoc()){
      $masked = mask_name($row['name'] ?: 'Anonymous');
      echo '<div class="feedback-card">';
      echo   '<div class="d-flex justify-content-between align-items-start">';
      echo     '<div class="d-flex align-items-center gap-2">';
      echo       '<span class="badge rounded-pill feedback-badge">'.h($masked).'</span>';
      if(!is_null($row['rating'])){ echo '<span class="star">'; for($i=1;$i<=5;$i++) echo $i<=$row['rating']?'★':'☆'; echo '</span>'; }
      if($row['room_id']){ $rn=$conn->query("SELECT name FROM rooms WHERE id=".(int)$row['room_id'])->fetch_assoc()['name'] ?? null; if($rn) echo '<span class="small text-muted">· '.h($rn).'</span>'; }
      if($row['stay_date']){ echo '<span class="small text-muted">· stayed '.h($row['stay_date']).'</span>'; }
      echo     '</div>';
      echo     '<small class="text-muted">'.h($row['created_at']).'</small>';
      echo   '</div>';
      echo   '<p class="mb-0 mt-2">'.nl2br(h($row['message'])).'</p>';
      echo '</div>';
    }
    // pagination
    $pages=max(1,ceil($total/$perPage));
    if($pages>1){
      echo '<nav class="mt-3"><ul class="pagination pagination-sm">';
      for($p=1;$p<=$pages;$p++){
        $qs=$_GET; $qs['page']=$p;
        $url='?'.http_build_query($qs);
        $active=$p==$page?'active':'';
        echo "<li class='page-item $active'><a class='page-link' href='$url'>$p</a></li>";
      }
      echo '</ul></nav>';
    }
  }
  ?>

  <div class="my-4"></div>

  <!-- FORM -->
  <h2 class="fw-800 mb-3">Leave your feedback</h2>
  <?= $alert ?>

  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow-sm rounded-4">
        <div class="card-body">
          <form method="post" novalidate>
            <!-- honeypot -->
            <input type="text" name="website" autocomplete="off" tabindex="-1" style="position:absolute;left:-9999px">

            <div class="mb-3">
              <label class="form-label">Name (will be masked)</label>
              <input type="text" name="name" class="form-control" placeholder="e.g., Huwaidah">
            </div>

            <div class="mb-3">
              <label class="form-label">Rating</label>
              <div class="d-flex gap-3 small">
                <?php for($i=5;$i>=1;$i--): ?>
                  <label class="me-2"><input type="radio" name="rating" value="<?= $i ?>"> <?= str_repeat('⭐',$i) ?></label>
                <?php endfor; ?>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Room (optional)</label>
                <select name="room_id" class="form-select">
                  <option value="">— Not specified —</option>
                  <?php
                    $rooms3=$conn->query("SELECT id,name FROM rooms ORDER BY name");
                    while($r=$rooms3->fetch_assoc()){
                      echo '<option value="'.$r['id'].'">'.h($r['name']).'</option>';
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Stay date (optional)</label>
                <input type="date" name="stay_date" class="form-control">
              </div>
            </div>

            <div class="mb-3 mt-3">
              <label class="form-label">Message</label>
              <textarea name="message" class="form-control" rows="4" maxlength="500" oninput="ccount(this)" required></textarea>
              <div class="text-muted small"><span id="cc">0</span>/500</div>
            </div>

            <button class="btn btn-brand" type="submit">Submit</button>
          </form>
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
</footer>

<button class="back-to-top" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'});"></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function ccount(el){ document.getElementById('cc').textContent = el.value.length; }
  (function(){
    const b=document.querySelector('.back-to-top');
    if(!b) return;
    const t=()=>{ b.style.display = window.scrollY>400 ? 'inline-flex' : 'none'; };
    addEventListener('scroll',t,{passive:true}); t();
  })();
</script>
</body>
</html>
