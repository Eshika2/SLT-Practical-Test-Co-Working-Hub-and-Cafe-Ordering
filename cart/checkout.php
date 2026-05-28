<?php
include "../config/db.php";
include "../includes/auth.php";

checkLogin();

$user_id = $_SESSION["user_id"];

// Get cart items for logged-in user
$sql = "SELECT cart.item_id,
               cart.quantity,
               cafe_items.name,
               cafe_items.price
        FROM cart
        JOIN cafe_items ON cart.item_id = cafe_items.id
        WHERE cart.user_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If cart is empty, go back to cart page
if (count($cartItems) == 0) {
    header("Location: index.php");
    exit();
}

// Calculate total
$total = 0;

foreach ($cartItems as $item) {
    $subtotal = $item["price"] * $item["quantity"];
    $total = $total + $subtotal;
}

// Insert order
$sql = "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $total]);

// Get new order id
$order_id = $pdo->lastInsertId();

// Insert order items
foreach ($cartItems as $item) {
    $sql = "INSERT INTO order_items (order_id, item_id, quantity, price)
            VALUES (?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $order_id,
        $item["item_id"],
        $item["quantity"],
        $item["price"]
    ]);
}

// Clear cart after checkout
$sql = "DELETE FROM cart WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);

include "../includes/header.php";
?>

<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Order Receipt</h2>

    <p class="mb-2">
        <strong>Order ID:</strong>
        <?php echo htmlspecialchars($order_id); ?>
    </p>

    <p class="mb-4">
        <strong>Customer:</strong>
        <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
    </p>

    <table class="w-full border mb-4">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">Item</th>
                <th class="border p-2">Price</th>
                <th class="border p-2">Quantity</th>
                <th class="border p-2">Subtotal</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($cartItems as $item) { ?>
                <?php
                    $subtotal = $item["price"] * $item["quantity"];
                ?>

                <tr>
                    <td class="border p-2">
                        <?php echo htmlspecialchars($item["name"]); ?>
                    </td>

                    <td class="border p-2">
                        Rs. <?php echo number_format($item["price"], 2); ?>
                    </td>

                    <td class="border p-2">
                        <?php echo htmlspecialchars($item["quantity"]); ?>
                    </td>

                    <td class="border p-2">
                        Rs. <?php echo number_format($subtotal, 2); ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <h3 class="text-xl font-bold text-right mb-4">
        Total: Rs. <?php echo number_format($total, 2); ?>
    </h3>

    <div class="text-right">
        <a href="../dashboard.php" class="bg-gray-800 text-white px-4 py-2 rounded">
            Back to Dashboard
        </a>
    </div>
</div>

<?php include "../includes/footer.php"; ?>