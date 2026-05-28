<?php
include "../config/db.php";
include "../includes/auth.php";

checkLogin();

$message = "";

// Check id from URL
if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$id = $_GET["id"];
$user_id = $_SESSION["user_id"];

// Get booking that belongs to this user
$sql = "SELECT * FROM bookings 
        WHERE id = ? AND user_id = ? AND is_active = 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $user_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

// If booking not found, go back
if (!$booking) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $space_name = trim($_POST["space_name"]);
    $booking_date = $_POST["booking_date"];
    $booking_time = $_POST["booking_time"];

    if ($space_name == "" || $booking_date == "" || $booking_time == "") {
        $message = "All fields are required.";
    } else {

        // Check duplicate booking, but ignore current booking id
        $sql = "SELECT id FROM bookings 
                WHERE space_name = ? 
                AND booking_date = ? 
                AND booking_time = ? 
                AND is_active = 1
                AND id != ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$space_name, $booking_date, $booking_time, $id]);
        $existingBooking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingBooking) {
            $message = "This space is already booked for this date and time.";
        } else {
            // Update own booking only
            $sql = "UPDATE bookings 
                    SET space_name = ?, booking_date = ?, booking_time = ?
                    WHERE id = ? AND user_id = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$space_name, $booking_date, $booking_time, $id, $user_id]);

            header("Location: index.php");
            exit();
        }
    }
}

include "../includes/header.php";
?>

<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-4">Edit Booking</h2>

    <?php if ($message != "") { ?>
        <p class="text-red-600 mb-4"><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>

    <form method="POST">
        <label class="block mb-2">Space Name</label>

        <select name="space_name" class="w-full border p-2 mb-4" required>
            <option value="Desk 1" <?php if ($booking["space_name"] == "Desk 1") echo "selected"; ?>>Desk 1</option>
            <option value="Desk 2" <?php if ($booking["space_name"] == "Desk 2") echo "selected"; ?>>Desk 2</option>
            <option value="Desk 3" <?php if ($booking["space_name"] == "Desk 3") echo "selected"; ?>>Desk 3</option>
            <option value="Meeting Room A" <?php if ($booking["space_name"] == "Meeting Room A") echo "selected"; ?>>Meeting Room A</option>
            <option value="Meeting Room B" <?php if ($booking["space_name"] == "Meeting Room B") echo "selected"; ?>>Meeting Room B</option>
        </select>

        <label class="block mb-2">Booking Date</label>
        <input 
            type="date" 
            name="booking_date" 
            class="w-full border p-2 mb-4" 
            value="<?php echo htmlspecialchars($booking["booking_date"]); ?>" 
            required
        >

        <label class="block mb-2">Booking Time</label>
        <input 
            type="time" 
            name="booking_time" 
            class="w-full border p-2 mb-4" 
            value="<?php echo htmlspecialchars($booking["booking_time"]); ?>" 
            required
        >

        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">
            Update Booking
        </button>

        <a href="index.php" class="ml-2 text-gray-600">Back</a>
    </form>
</div>

<?php include "../includes/footer.php"; ?>