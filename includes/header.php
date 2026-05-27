<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SLT Cafe</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100">

<nav class="bg-gray-800 text-white p-4">
    <div class="flex justify-between items-center">
        <a href="/SLT_cafe/dashboard.php" class="font-bold text-lg">SLT Cafe</a>

        <div class="space-x-4">
            <?php if (isset($_SESSION['user_id'])) { ?>
                <a href="/SLT_cafe/dashboard.php">Dashboard</a>
                <a href="/SLT_cafe/bookings/index.php">Bookings</a>
                <a href="/SLT_cafe/menu.php">Cafe Menu</a>
                <a href="/SLT_cafe/cart/index.php">Cart</a>
                <a href="/SLT_cafe/logout.php">Logout</a>
            <?php } else { ?>
                <a href="/SLT_cafe/login.php">Login</a>
                <a href="/SLT_cafe/register.php">Register</a>
            <?php } ?>
        </div>
    </div>
</nav>

<div class="mt-8">