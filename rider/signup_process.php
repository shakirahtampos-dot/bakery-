<?php
session_start();

require_once __DIR__ . '/../../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "db.php"; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $vehicle_type = $_POST['vehicle_type'];

    if ($password !== $confirm_password) {
        $_SESSION['signup_error'] = "Passwords do not match.";
        header("Location: signup.php");
        exit();
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT id FROM riders WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['signup_error'] = "Username or email already exists.";
        header("Location: signup.php");
        exit();
    }
    $stmt->close();

    // Generate OTP
    $otp = rand(100000, 999999);

    // Store rider data and OTP in session to be used after verification
    $_SESSION['signup_data'] = [
        'name' => $name,
        'username' => $username,
        'email' => $email,
        'phone' => $phone,
        'password' => $password, // Will be hashed before saving
        'vehicle_type' => $vehicle_type,
        'otp' => $otp,
        'otp_timestamp' => time() // Add the current timestamp
    ];

    // --- PHPMailer Implementation ---
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER; // Enable verbose debug output for troubleshooting
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through (e.g., smtp.gmail.com)
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        // IMPORTANT: Replace with your own credentials
        $mail->Username   = 'angelo.oso@mdci.edu.ph';          // SMTP username (your Gmail address)
        $mail->Password   = 'pasd ucjx rzqm vuon';                     // SMTP password (your 16-character Gmail App Password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit TLS encryption
        $mail->Port       = 465;                                    // TCP port to connect to; use 587 for `PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('angelo.oso@mdci.edu.ph', 'Your App Name');
        $mail->addAddress($email, $name);     // Add a recipient

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Your Email Verification Code';
        $mail->Body    = "Hello " . htmlspecialchars($name) . ",<br><br>Your verification code is: <b>" . $otp . "</b><br><br>Thank you for registering.";
        $mail->AltBody = "Hello " . htmlspecialchars($name) . ",\n\nYour verification code is: " . $otp . "\n\nThank you for registering.";

        $mail->send();

        // Redirect to OTP verification page
        header("Location: otp_verification.php");
        exit();

    } catch (Exception $e) {
        // In a production environment, you should log this error instead of showing it to the user.
        // error_log("Mailer Error: {$mail->ErrorInfo}");

        // Provide a user-friendly error message.
        // For development, it's useful to see the actual error.
        $_SESSION['signup_error'] = "Could not send OTP email. Please try again later. Mailer Error: " . htmlspecialchars($mail->ErrorInfo);
        header("Location: signup.php");
        exit();
    }

} else {
    header("Location: signup.php");
    exit();
}
?>