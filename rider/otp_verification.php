<?php
session_start();

// If signup data is not in session, redirect to signup
if (!isset($_SESSION['signup_data'])) {
    header("Location: signup.php");
    exit();
}

$error_message = '';
if (isset($_SESSION['otp_error'])) {
    $error_message = $_SESSION['otp_error'];
    unset($_SESSION['otp_error']);
}

$success_message = '';
if (isset($_SESSION['otp_resent_success'])) {
    $success_message = $_SESSION['otp_resent_success'];
    unset($_SESSION['otp_resent_success']);
}

$email = $_SESSION['signup_data']['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; margin: 0; background-color: #111; display: flex; justify-content: center; align-items: center; height: 100vh; color: #eee; }
        .otp-wrapper { max-width: 450px; width: 100%; background: #222; padding: 40px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid #44d62c; text-align: center; }
        h2 { color: #fff; margin-top: 0; }
        p { margin-bottom: 20px; }
        .form-group { margin-bottom: 1.5rem; }
        input { width: 100%; padding: 12px; border: 1px solid #555; border-radius: 5px; box-sizing: border-box; font-family: 'Poppins', sans-serif; background-color: #333; color: #fff; text-align: center; font-size: 1.2rem; letter-spacing: 5px; }
        .btn { width: 100%; padding: 12px; border: none; border-radius: 5px; cursor: pointer; color: #fff; font-size: 1rem; font-weight: 500; }
        .btn-primary { background-color: #44d62c; color: #111; }
        .btn-primary:hover { background-color: #59f441; }
        .message { margin-bottom: 1rem; padding: 10px; border-radius: 5px; }
        .error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; }
        .success { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="otp-wrapper">
        <h2>Verify Your Email</h2>
        <p>An OTP has been sent to <strong><?= htmlspecialchars($email) ?></strong>. Please enter it below.</p>
        <?php if ($error_message): ?>
            <p class="message error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p class="message success"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>
        <form action="otp_verify_process.php" method="POST">
            <div class="form-group">
                <input type="text" id="otp" name="otp" required maxlength="6" pattern="\d{6}" title="Enter 6-digit OTP">
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>
        <div style="margin-top: 20px;">
            <p>Didn't receive the code? <a href="resend_otp.php" style="color: #44d62c;">Resend OTP</a></p>
        </div>
    </div>
</body>
</html>