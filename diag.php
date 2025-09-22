<?php require_once __DIR__.'/db.php';
echo "<h3>Diag</h3>";
try{$pdo->query("SELECT 1"); echo "<p>DB: <b>OK</b></p>";}catch(Throwable $e){echo "<p>DB: <b>FAIL</b></p>";exit;}
$cnt=$pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn(); echo "<p>Admins count: <b>$cnt</b></p>";
$st=$pdo->prepare("SELECT email,password_hash FROM admins WHERE email=?"); $st->execute(['admin@thepearl.test']); $r=$st->fetch();
if($r){ echo "<p>Found admin: <b>{$r['email']}</b></p>"; echo "<p>password_verify('Admin@123'): <b>".(password_verify('Admin@123',$r['password_hash'])?'TRUE ✅':'FALSE ❌')."</b></p>"; }
else{ echo "<p>No admin row.</p>"; }