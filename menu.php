<?php
include "config/db.php";
include "includes/auth.php";

checkLogin();

// Search value
$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}

// Get cafe items
if ($search != "") {
    // Search cafe items by name
    $sql = "SELECT * FROM cafe_items 
            WHERE is_active = 1 AND name LIKE ?
            ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%" . $search . "%"]);
} else {
    // Get all active cafe items
    $sql = "SELECT * FROM cafe_items 
            WHERE is_active = 1
            ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "includes/header.php";
?>

<div class="bg-white p-6 rounded shadow ">
    <h2 class="text-2xl font-bold mb-4">Cafe Menu</h2>

    <!-- Search form -->
    <form method="GET" class="mb-6 flex gap-2">
        <input
            type="text"
            name="search"
            placeholder="Search cafe item"
            value="<?php echo htmlspecialchars($search); ?>"
            class="border p-2 w-full rounded">

        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">
            Search
        </button>

        <a href="menu.php" class="bg-gray-300 px-4 py-2 rounded">
            Clear
        </a>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 md:grid-cols-5 gap-4">
        <?php foreach ($items as $item) { ?>
            <div class="border rounded p-3 bg-gray-50 w-full max-w-[300px] mx-auto">

                <!-- Cafe item image -->
                <img
                    src="assets/images/<?php echo htmlspecialchars($item["image"]); ?>"
                    alt="<?php echo htmlspecialchars($item["name"]); ?>"
                    class="w-full h-[300px] object-cover rounded mb-3">

                <h3 class="text-base font-bold">
                    <?php echo htmlspecialchars($item["name"]); ?>
                </h3>

                <p class="mb-3 text-sm">
                    Rs. <?php echo number_format($item["price"], 2); ?>
                </p>

                <!-- Add to cart form -->
                <form method="POST" action="cart/add.php">
                    <input type="hidden" name="item_id" value="<?php echo $item["id"]; ?>">

                    <button type="submit" class="bg-gray-800 text-white px-3 py-2 rounded w-full text-sm">
                        Add to Cart
                    </button>
                </form>
            </div>
        <?php } ?>
    </div>

    <?php if (count($items) == 0) { ?>
        <p class="text-gray-600">No cafe items found.</p>
    <?php } ?>
</div>

<?php include "includes/footer.php"; ?>