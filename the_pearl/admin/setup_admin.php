<?php
require_once __DIR__.'/db.php';
$email='admin@thepearl.test'; $name='Admin'; $pass='Admin@123'; $hash=password_hash($pass,PASSWORD_BCRYPT);
$pdo->exec("CREATE TABLE IF NOT EXISTS admins (id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100) NOT NULL,email VARCHAR(120) NOT NULL UNIQUE,password_hash VARCHAR(255) NOT NULL,avatar VARCHAR(255) DEFAULT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
$st=$pdo->prepare("SELECT id FROM admins WHERE email=?"); $st->execute([$email]); $row=$st->fetch();
if($row){ $up=$pdo->prepare("UPDATE admins SET name=?,password_hash=? WHERE id=?"); $up->execute([$name,$hash,$row['id']]); echo "Updated admin $email"; }
else{ $ins=$pdo->prepare("INSERT INTO admins (name,email,password_hash) VALUES (?,?,?)"); $ins->execute([$name,$email,$hash]); echo "Created admin $email"; }