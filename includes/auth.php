<?php
// Start session on every page that uses login data
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// This function checks whether user is logged in
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }
}

// This function checks whether logged user is admin
function checkAdmin() {
    if (!isset($_SESSION['user_type_id']) || $_SESSION['user_type_id'] != 1) {
        header("Location: ../dashboard.php");
        exit();
    }
}
?>