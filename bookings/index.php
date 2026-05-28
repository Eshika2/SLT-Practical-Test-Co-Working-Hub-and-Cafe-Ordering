<?php
include "../config/db.php";
include "../includes/auth.php";

checkLogin();

// Get all active bookings with user name
$sql = "SELECT bookings.*, users.name 
        FROM bookings 
        JOIN users ON bookings.user_id = users.id
        WHERE bookings.is_active = 1
        ORDER BY bookings.booking_date ASC, bookings.booking_time ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "../includes/header.php";
?>

<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Workspace Bookings</h2>

        <a href="add.php" class="bg-gray-800 text-white px-4 py-2 rounded">
            Add Booking
        </a>
    </div>

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">Member Name</th>
                <th class="border p-2">Space Name</th>
                <th class="border p-2">Booking Date</th>
                <th class="border p-2">Booking Time</th>
                <th class="border p-2">Created At</th>
                <th class="border p-2">Action</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($bookings as $booking) { ?>
                <tr>
                    <td class="border p-2">
                        <?php echo htmlspecialchars($booking["name"]); ?>
                    </td>

                    <td class="border p-2">
                        <?php echo htmlspecialchars($booking["space_name"]); ?>
                    </td>

                    <td class="border p-2">
                        <?php echo htmlspecialchars($booking["booking_date"]); ?>
                    </td>

                    <td class="border p-2">
                        <?php echo date("h:i A", strtotime($booking["booking_time"])); ?>
                    </td>

                    <td class="border p-2">
                        <?php echo htmlspecialchars($booking["created_at"]); ?>
                    </td>

                    <td class="border p-2 text-center font-bold">
                        <?php if ($booking["user_id"] == $_SESSION["user_id"]) { ?>
                            <a href="edit.php?id=<?php echo $booking["id"]; ?>" class="text-blue-600">
                                Edit
                            </a>

                            <a href="delete.php?id=<?php echo $booking["id"]; ?>" 
                               class="text-red-600 ml-2"
                               onclick="return confirm('Delete this booking?')">
                                Delete
                            </a>
                        <?php } else { ?>
                            -
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>

            <?php if (count($bookings) == 0) { ?>
                <tr>
                    <td colspan="6" class="border p-4 text-center text-gray-600">
                        No bookings found.
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include "../includes/footer.php"; ?>