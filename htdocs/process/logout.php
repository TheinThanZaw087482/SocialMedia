<?php
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Optional: Redirect to login or home page
header("Location: ../index.php");
exit;
?>
