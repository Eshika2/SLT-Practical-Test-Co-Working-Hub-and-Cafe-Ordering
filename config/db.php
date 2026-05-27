<?php
// Database connection file
// We include this file in other PHP pages when we need database access

$host = "localhost";
$dbname = "coworking_cafe_db";
$username = "root";
$password = "";

try {
    // PDO connects PHP with MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // This shows database errors clearly during development
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // If connection fails, show error
    die("Database connection failed: " . $e->getMessage());
}
?>