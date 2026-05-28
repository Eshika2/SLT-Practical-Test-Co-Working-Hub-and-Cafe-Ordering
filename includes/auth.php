<?php
// Start session on every page that uses login data
session_start();

// This function checks whether user is logged in
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /SLT_cafe/login.php");
        exit();
    }
}

?>