<?php
include "includes/auth.php";
checkLogin();

include "includes/header.php";
?>

<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">
        Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
    </h1>

    <p class="mb-6">Use this system to manage workspace bookings and cafe orders.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="bookings/index.php" class="block bg-gray-200 p-4 rounded">
            <h3 class="font-bold">Workspace Bookings</h3>
            <p>Create, edit, and delete your bookings.</p>
        </a>

        <a href="menu.php" class="block bg-gray-200 p-4 rounded">
            <h3 class="font-bold">Cafe Menu</h3>
            <p>View cafe items and add them to cart.</p>
        </a>

        <a href="cart/index.php" class="block bg-gray-200 p-4 rounded">
            <h3 class="font-bold">My Cart</h3>
            <p>View your selected cafe items.</p>
        </a>
    </div>
</div>

<?php include "includes/footer.php"; ?>