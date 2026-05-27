<?php
include "config/db.php";

session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($email == "" || $password == "") {
        $message = "Email and password are required.";
    } else {

        // Find user by email
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check password
        if ($user && password_verify($password, $user["password"])) {

            // Store user details in session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_type_id"] = $user["user_type_id"];

            header("Location: dashboard.php");
            exit();

        } else {
            $message = "Invalid email or password.";
        }
    }
}

include "includes/header.php";
?>

<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>

    <?php if ($message != "") { ?>
        <p class=" text-center mb-4 text-red-600"><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>

    <form method="POST">
        <label class="block mb-2">Email</label>
        <input type="email" name="email" class="w-full border p-2 mb-4" required>

        <label class="block mb-2">Password</label>
        <input type="password" name="password" class="w-full border p-2 mb-4" required>

        <button type="submit" class="bg-gray-800 text-white rounded p-2">
            Login
        </button>
    </form>
</div>

<?php include "includes/footer.php"; ?>