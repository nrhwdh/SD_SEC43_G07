<?php if (!isset($page_title)) $page_title='The Pearl Admin'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=h($page_title)?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
:root{
  --sb-primary:#4e73df;--sb-primary-dark:#2e59d9;--sb-info:#36b9cc;
  --sb-success:#1cc88a;--sb-warning:#f6c23e;--sb-gray-300:#e3e6f0;--sb-body:#f8f9fc;
}
body{font-family:'Nunito',sans-serif;background:var(--sb-body);}
.sb-wrap{display:flex;min-height:100vh;}
.sb-side{width:250px;background:linear-gradient(180deg,var(--sb-primary) 10%,#224abe 100%);color:#fff;position:sticky;top:0;height:100vh;}
.sb-brand{padding:1rem;font-weight:900;display:flex;align-items:center;gap:.5rem}
.sb-nav .nav-link{color:#dbe5ff;padding:.65rem 1rem;border-radius:.35rem;margin:.25rem .75rem;display:flex;align-items:center;gap:.5rem}
.sb-nav .nav-link.active,.sb-nav .nav-link:hover{background:rgba(255,255,255,.15);color:#fff}
.sb-main{flex:1;display:flex;flex-direction:column}
.sb-top{background:#fff;border-bottom:1px solid var(--sb-gray-300);padding:.6rem 1rem;display:flex;align-items:center;gap:1rem;justify-content:space-between;position:sticky;top:0;z-index:10}
.sb-search{max-width:560px;flex:1}
.sb-content{padding:1.25rem}
.card{border:0;border-radius:.35rem;box-shadow:0 .15rem 1.75rem rgba(58,59,69,.15)}
.sb-stat .title{font-size:.8rem;text-transform:uppercase;color:#4e73df;font-weight:800}
.sb-stat .num{font-weight:800;font-size:1.5rem}
.progress{height:.6rem}
@media(max-width:991px){.sb-side{position:fixed;left:-270px;transition:left .25s}.sb-side.open{left:0}}
/* Gradient login bg */
.login-hero{background:linear-gradient(180deg,#4e73df 0%, #224abe 100%);min-height:50vh;border-bottom-left-radius:2rem;border-bottom-right-radius:2rem}

/* ====== added polish (placeholders & spacing) ====== */
.empty-badge{
  display:inline-block; padding:.2rem .55rem; border-radius:999px;
  background:#eef2f7; color:#7b8894; font-weight:600; letter-spacing:.3px;
}
.card-sub{ color:#98a5b3; font-size:.8rem; }
/* make stat number a bit tighter on small cards */
.sb-stat .num{ font-size:1.35rem; font-weight:700; }
/* mobile spacing tweak */
@media (max-width: 991.98px){ .card{ margin-bottom:.75rem; } }
</style>
</head>
<body>
