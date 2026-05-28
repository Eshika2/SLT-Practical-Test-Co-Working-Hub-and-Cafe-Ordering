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

// Get cart item that belongs to logged-in user
$sql = "SELECT * FROM cart 
        WHERE id = ? AND user_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$cart_id, $user_id]);
$cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

// If cart item not found, go back
if (!$cartItem) {
    header("Location: index.php");
    exit();
}

// If quantity is more than 1, reduce quantity by 1
if ($cartItem["quantity"] > 1) {
    $sql = "UPDATE cart 
            SET quantity = quantity - 1 
            WHERE id = ? AND user_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cart_id, $user_id]);

} else {
    // If quantity is 1, remove item from cart
    $sql = "DELETE FROM cart 
            WHERE id = ? AND user_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cart_id, $user_id]);
}

header("Location: index.php");
exit();
?>