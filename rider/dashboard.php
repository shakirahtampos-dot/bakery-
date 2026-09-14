<?php
session_start();

if (!isset($_SESSION['rider_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// The dashboard now serves as a router.
// By default, it will redirect to the new order requests page.
header("Location: new_order_requests.php");
exit();