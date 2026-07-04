<?php
// db connection settings - change these to match your MySQL setup
$host = "localhost";
$dbname = "smart_logistics";
$dbuser = "root";
$dbpass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to database: " . $e->getMessage());
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
