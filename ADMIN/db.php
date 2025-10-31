<?php
// db.php - database connection
$host = "localhost";       // XAMPP default
$user = "root";            // XAMPP default
$pass = "";                // XAMPP default
$db   = "the_pearl";       // your database name

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
