<?php
// ===== DB config =====
$DB_HOST = '127.0.0.1';
$DB_NAME = 'the_pearl';
$DB_USER = 'root';
$DB_PASS = '';

// ===== PDO connection =====
$pdo = new PDO(
  "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
  $DB_USER,
  $DB_PASS,
  [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ]
);

// ===== Helper escape (guard) =====
if (!function_exists('h')) {
  function h($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
}
