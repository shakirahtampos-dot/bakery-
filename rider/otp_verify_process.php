<?php
session_start();

// Include the database connection file
require_once 'db.php';

if ($conn->connect_error) {
    // In a real application, you would log this error and show a generic message
    $_SESSION['otp_error'] = "Database connection failed. Please contact support.";
    header("Location: otp_verification.php");
    exit();
}

// Redirect if signup data or OTP is not in session/POST
if (!isset($_SESSION['signup_data']) || !isset($_POST['otp'])) {
    header("Location: signup.php");
    exit();
}

$signup_data = $_SESSION['signup_data'];
$submitted_otp = $_POST['otp'];

$session_otp = $signup_data['otp'];
$otp_timestamp = $signup_data['otp_timestamp']; // Get the creation timestamp
$otp_validity_duration = 600; // 10 minutes in seconds

$now = time(); // Get the current time as a timestamp

// 1. Verify OTP
if ($submitted_otp != $session_otp) { // Use loose comparison to handle string vs. integer
    $_SESSION['otp_error'] = "Invalid OTP. Please try again.";
    header("Location: otp_verification.php");
    exit();
}

// 2. Check if OTP has expired
if (($now - $otp_timestamp) > $otp_validity_duration) {
    $_SESSION['otp_error'] = "OTP has expired. Please request a new one.";
    header("Location: otp_verification.php");
    exit();
}

// OTP is correct and not expired, proceed with registration

$name = $signup_data['name'];
$username = $signup_data['username'];
$email = $signup_data['email'];
$phone = $signup_data['phone'];
$password_hash = password_hash($signup_data['password'], PASSWORD_DEFAULT); // Hash the password now
$vehicle_type = $signup_data['vehicle_type'];
$is_verified = 1; // Set to verified
$status = 'active'; // Set a default status

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare(
    "INSERT INTO riders (name, username, email, phone, password, vehicle_type, otp, is_verified, status, created_at) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
);

if ($stmt === false) {
    // This will catch errors in the SQL query itself
    $_SESSION['otp_error'] = "Database error: An error occurred during registration. Administrator has been notified.";
    // For debugging: error_log("Prepare failed: " . $conn->error);
    header("Location: otp_verification.php");
    exit();
}

// Bind parameters
// The types string "ssssssisi" corresponds to the types of the variables:
// s: string, i: integer.
$stmt->bind_param(
    "ssssssiis",
    $name,
    $username,
    $email,
    $phone,
    $password_hash,
    $vehicle_type,
    $session_otp, // Storing the used OTP for reference
    $is_verified, // This is an integer
    $status
);

if ($stmt->execute()) {
    // Registration successful
    // Clean up session data
    unset($_SESSION['signup_data']);
    unset($_SESSION['otp_error']);

    // Redirect to login page with a success message
    $_SESSION['register_success'] = "Registration successful! You can now log in.";
    header("Location: login.php"); // Redirect to your login page
} else {
    // Execution failed
    $_SESSION['otp_error'] = "Database error: An error occurred during registration. Administrator has been notified.";
    // For debugging: error_log("Execute failed: " . $stmt->error);
    header("Location: otp_verification.php");
}

$stmt->close();
$conn->close();
exit();