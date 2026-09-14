<?php
session_start();

// Check if the user is actually in the middle of a signup process.
if (!isset($_SESSION['signup_data']) || !isset($_SESSION['signup_data']['email'])) {
    // If not, redirect them to the start.
    header("Location: signup.php");
    exit();
}

// --- IMPORTANT: Email Sending Logic ---
// You need to have a mail server (like PHPMailer or Symfony Mailer) configured for this to work reliably.
// The basic mail() function below might not work on a local XAMPP setup without proper configuration.
// Replace this with the same email sending code you used in your initial signup script.

require_once __DIR__ . '/../vendor/autoload.php'; // Assuming you use Composer for a mailer library.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Generate a new OTP and update the timestamp
$new_otp = rand(100000, 999999);
$_SESSION['signup_data']['otp'] = $new_otp;
$_SESSION['signup_data']['otp_timestamp'] = time();

$email = $_SESSION['signup_data']['email'];
$name = $_SESSION['signup_data']['name'];

$subject = "Your New Verification Code";
$message_body = "Hello " . htmlspecialchars($name) . ",<br><br>Your new verification code is: <b>" . $new_otp . "</b><br><br>This code will expire in 10 minutes.<br><br>Thank you.";

// Example using PHPMailer (recommended)
$mail = new PHPMailer(true);
try {
    //Server settings - replace with your mail server details
    // $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER; // Enable for troubleshooting
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    // IMPORTANT: Using your credentials from signup_process.php
    $mail->Username   = 'angelo.oso@mdci.edu.ph';
    $mail->Password   = 'pasd ucjx rzqm vuon';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    //Recipients
    $mail->setFrom('angelo.oso@mdci.edu.ph', 'Your App Name');
    $mail->addAddress($email, $name);

    //Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $message_body;

    $mail->send();
    $_SESSION['otp_resent_success'] = "A new OTP has been sent to your email address.";
} catch (Exception $e) {
    // You might want to log the detailed error: error_log("Mailer Error: " . $mail->ErrorInfo);
    $_SESSION['otp_error'] = "Could not send the new OTP. Please try again later.";
}

// Redirect back to the OTP verification page
header("Location: otp_verification.php");
exit();
?>