<?php
include "../config/db.php";
include "../includes/auth.php";

checkLogin();

// Check item_id came from form
if (!isset($_POST["item_id"])) {
    header("Location: ../menu.php");
    exit();
}

$item_id = $_POST["item_id"];
$user_id = $_SESSION["user_id"];

// Check whether item exists and active
$sql = "SELECT * FROM cafe_items 
        WHERE id = ? AND is_active = 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([$item_id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    header("Location: ../menu.php");
    exit();
}

// Check whether this item already exists in user's cart
$sql = "SELECT * FROM cart 
        WHERE user_id = ? AND item_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $item_id]);
$cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cartItem) {
    // If item exists, increase quantity by 1
    $sql = "UPDATE cart 
            SET quantity = quantity + 1 
            WHERE user_id = ? AND item_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $item_id]);
} else {
    // If item does not exist, insert new cart row
    $sql = "INSERT INTO cart (user_id, item_id, quantity) 
            VALUES (?, ?, 1)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $item_id]);
}

// Go to cart page
header("Location: index.php");
exit();
