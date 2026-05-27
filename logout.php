<?php
session_start();

// Remove all session data
session_destroy();

// Send user back to login
header("Location: login.php");
exit();

?>