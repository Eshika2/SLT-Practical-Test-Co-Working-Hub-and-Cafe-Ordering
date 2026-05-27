<?php
include "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form values
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Simple validation
    if ($name == "" || $email == "" || $password == "") {
        $message = "All fields are required.";
    } else {
        // Hash password before saving
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // user_type_id 2 means normal user
            $sql = "INSERT INTO users (user_type_id, name, email, password) VALUES (2, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $email, $hashedPassword]);

            $message = "User registered successfully.";
            header("Location: login.php");
            exit();

        } catch (PDOException $e) {
            $message = "Email already exists.";
        }
    }
}

include "includes/header.php";
?>

<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center">User Register</h2>

    <?php if ($message != "") { ?>
        <p class=" text-center mb-4 text-green-600"><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>

    <form method="POST">
        <label class="block mb-2">Name</label>
        <input type="text" name="name" class="w-full border p-2 mb-4" required>

        <label class="block mb-2">Email</label>
        <input type="email" name="email" class="w-full border p-2 mb-4" required>

        <label class="block mb-2">Password</label>
        <input type="password" name="password" class="w-full border p-2 mb-4" required>

        <button type="submit" class="bg-gray-800 text-white rounded p-2 ">
            Register
        </button>
    </form>
</div>

<?php include "includes/footer.php"; ?>