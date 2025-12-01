<?php
// Database configuration - UPDATE THESE VALUES
$host = 'sql100.infinityfree.com';
$dbname = 'if0_40568447_Theintel';
$username = 'if0_40568447';
$password = 'kLmHkGvvCl1F';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database connection failed. Check your credentials.");
}

session_start();
?>