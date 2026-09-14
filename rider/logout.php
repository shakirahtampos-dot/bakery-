<?php
session_start();

// Unset all of the session variables.
$_SESSION = array();

// If you are using a specific cookie for rider authentication, clear it here.
// Example: setcookie('rider_auth', '', time() - 3600, '/', '', false, true);

// Destroy the session.
session_destroy();

// Redirect to the rider login page.
header("Location: login.php");
exit();
?>