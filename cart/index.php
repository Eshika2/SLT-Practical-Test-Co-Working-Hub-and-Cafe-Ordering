<?php
include "../config/db.php";
include "../includes/auth.php";

checkLogin();

$user_id = $_SESSION["user_id"];

// Get cart items with cafe item details
$sql = "SELECT cart.id AS cart_id,
               cart.item_id,
               cart.quantity,
               cafe_items.name,
               cafe_items.price
        FROM cart
        JOIN cafe_items ON cart.item_id = cafe_items.id
        WHERE cart.user_id = ?
        ORDER BY cart.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;

include "../includes/header.php";
?>

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">My Cart</h2>

    <?php if (count($cartItems) > 0) { ?>

        <table class="w-full border mb-4">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Item</th>
                    <th class="border p-2">Price</th>
                    <th class="border p-2">Quantity</th>
                    <th class="border p-2">Subtotal</th>
                    <th class="border p-2">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($cartItems as $item) { ?>
                    <?php
                    // Calculate subtotal
                    $subtotal = $item["price"] * $item["quantity"];

                    // Add subtotal to total
                    $total = $total + $subtotal;
                    ?>

                    <tr>
                        <td class="border p-2">
                            <?php echo htmlspecialchars($item["name"]); ?>
                        </td>

                        <td class="border p-2">
                            Rs. <?php echo number_format($item["price"], 2); ?>
                        </td>

                        <td class="border p-2 text-center">
                            <a
                                href="decrease.php?id=<?php echo $item["cart_id"]; ?>"
                                class="bg-black text-white px-2 py-1 rounded">
                                -
                            </a>

                            <span class="mx-2">
                                <?php echo htmlspecialchars($item["quantity"]); ?>
                            </span>

                            <form method="POST" action="add.php" class="inline">
                                <input type="hidden" name="item_id" value="<?php echo $item["item_id"]; ?>">

                                <button type="submit" class="bg-black text-white px-2 py-1 rounded">
                                    +
                                </button>
                            </form>
                        </td>

                        <td class="border p-2">
                            Rs. <?php echo number_format($subtotal, 2); ?>
                        </td>

                        <td class="border p-2 text-center font-bold">
                            <a
                                href="remove.php?id=<?php echo $item["cart_id"]; ?>"
                                class="text-red-600"
                                onclick="return confirm('Remove this item?')">
                                Remove
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="text-right">
            <h3 class="text-xl font-bold mb-4">
                Total: Rs. <?php echo number_format($total, 2); ?>
            </h3>

            <a href="checkout.php" class="bg-gray-800 text-white px-4 py-2 rounded">
                Checkout
            </a>
        </div>

    <?php } else { ?>

        <p class="mb-4">Your cart is empty.</p>

        <a href="../menu.php" class="bg-gray-800 text-white px-4 py-2 rounded">
            Go to Cafe Menu
        </a>

    <?php } ?>
</div>

<?php include "../includes/footer.php"; ?>