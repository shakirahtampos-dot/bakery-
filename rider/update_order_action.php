<?php
session_start();

include "db.php";


// Security check: ensure a rider is logged in
if (!isset($_SESSION['rider_id'])) {
    header("Location: login.php");
    exit();
}

$rider_id = $_SESSION['rider_id'];
$order_id = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

if (!$order_id || !$action || !in_array($action, ['accept', 'decline'])) {
    // Redirect if parameters are invalid
    header("Location: dashboard.php");
    exit();
}

$new_status = '';
$new_rider_id = $rider_id;

if ($action === 'accept') {
    // When accepted, the status changes to 'processing'
    $new_status = 'processing';
} elseif ($action === 'decline') {
    // When declined, we need to find a new rider.
    // Fetch another random rider who is NOT the current rider.
    $new_rider_stmt = $conn->prepare("SELECT id FROM users WHERE role = 'rider' AND status = 'active' AND id != ? ORDER BY RAND() LIMIT 1");
    $new_rider_stmt->bind_param("i", $rider_id);
    $new_rider_stmt->execute();
    $new_rider_result = $new_rider_stmt->get_result();

    if ($new_rider_result->num_rows > 0) {
        $new_rider = $new_rider_result->fetch_assoc();
        $new_rider_id = $new_rider['id'];
        // The status remains 'pending' or 'paid' so it appears as a new request for the next rider.
        $stmt = $conn->prepare("UPDATE orders SET rider_id = ? WHERE id = ? AND rider_id = ?");
        $stmt->bind_param("iii", $new_rider_id, $order_id, $rider_id);
        $stmt->execute();
        header("Location: dashboard.php");
        exit();
    } else {
        // No other riders available. Un-assign the order for now.
        // A background job would be needed to re-assign this later.
        $new_rider_id = null;
    }
}

// This part now only handles the 'accept' action.
$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ? AND rider_id = ?");
$stmt->bind_param("sii", $new_status, $order_id, $rider_id);
$stmt->execute();

// If the rider marks the order as delivered, update status to 'paid'
if (isset($_GET['action']) && $_GET['action'] === 'delivered') {
    $paid_status = 'paid';
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ? AND rider_id = ?");
    $stmt->bind_param("sii", $paid_status, $order_id, $rider_id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit();