<?php
include "../config/db.php";
include "../includes/auth.php";

checkLogin();

// Check cart id from URL
if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$cart_id = $_GET["id"];
$user_id = $_SESSION["user_id"];

// Delete only logged-in user's cart item
$sql = "DELETE FROM cart 
        WHERE id = ? AND user_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$cart_id, $user_id]);

header("Location: index.php");
exit();
?>