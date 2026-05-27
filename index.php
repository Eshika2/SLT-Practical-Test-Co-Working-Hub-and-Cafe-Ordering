<?php
// Start session to check login status
session_start();

// If user is already logged in, send to dashboard
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

include "includes/header.php";
?>

<div class="bg-white p-8 rounded shadow text-center max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-4">
        Co-Working Hub & Cafe Ordering System
    </h1>

    <p class="text-gray-600 mb-6">
        Book workspaces and order cafe items from one simple system.
    </p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
    <div class="bg-white p-5 rounded shadow">
        <h3 class="font-bold text-lg mb-2">Workspace Booking</h3>
        <p class="text-gray-600">
            Members create, edit, and delete their own desk or meeting room bookings.
        </p>
    </div>

    <div class="bg-white p-5 rounded shadow">
        <h3 class="font-bold text-lg mb-2">Cafe Menu</h3>
        <p class="text-gray-600">
            Members browse cafe items and add products to their cart.
        </p>
    </div>

    <div class="bg-white p-5 rounded shadow">
        <h3 class="font-bold text-lg mb-2">Cart & Checkout</h3>
        <p class="text-gray-600">
            Members view cart items, update quantity, remove items, and get a receipt.
        </p>
    </div>
</div>

<?php include "includes/footer.php"; ?>