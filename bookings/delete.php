<?php
include "../config/db.php";
include "../includes/auth.php";

checkLogin();

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$id = $_GET["id"];
$user_id = $_SESSION["user_id"];

// Soft delete booking
// This keeps data in database but hides it from list
$sql = "UPDATE bookings 
        SET is_active = 0 
        WHERE id = ? AND user_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $user_id]);

header("Location: index.php");
exit();
?>