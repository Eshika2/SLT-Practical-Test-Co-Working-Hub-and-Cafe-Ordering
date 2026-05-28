<?php
include "../config/db.php";
include "../includes/auth.php";

checkLogin();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $space_name = trim($_POST["space_name"]);
    $booking_date = $_POST["booking_date"];
    $booking_time = $_POST["booking_time"];
    $user_id = $_SESSION["user_id"];

    // Simple validation
    if ($space_name == "" || $booking_date == "" || $booking_time == "") {
        $message = "All fields are required.";
    } else {

        // Check whether same space is already booked at same date and time
        $sql = "SELECT id FROM bookings 
                WHERE space_name = ? 
                AND booking_date = ? 
                AND booking_time = ? 
                AND is_active = 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$space_name, $booking_date, $booking_time]);
        $existingBooking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingBooking) {
            $message = "This space is already booked for this date and time.";
        } else {
            // Insert booking
            $sql = "INSERT INTO bookings (user_id, space_name, booking_date, booking_time) 
                    VALUES (?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id, $space_name, $booking_date, $booking_time]);

            header("Location: index.php");
            exit();
        }
    }
}

include "../includes/header.php";
?>

<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-4">Add Booking</h2>

    <?php if ($message != "") { ?>
        <p class="text-red-600 mb-4"><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>

    <form method="POST">
        <label class="block mb-2">Space Name</label>

        <select name="space_name" class="w-full border p-2 mb-4" required>
            <option value="">Select Space</option>
            <option value="Desk 1">Desk 1</option>
            <option value="Desk 2">Desk 2</option>
            <option value="Desk 3">Desk 3</option>
            <option value="Meeting Room A">Meeting Room A</option>
            <option value="Meeting Room B">Meeting Room B</option>
        </select>

        <label class="block mb-2">Booking Date</label>
        <input type="date" name="booking_date" class="w-full border p-2 mb-4" required>

        <label class="block mb-2">Booking Time</label>
        <input type="time" name="booking_time" class="w-full border p-2 mb-4" required>

        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">
            Save Booking
        </button>

        <a href="index.php" class="ml-2 text-gray-600">Back</a>
    </form>
</div>

<?php include "../includes/footer.php"; ?>