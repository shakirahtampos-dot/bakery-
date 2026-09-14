<?php
session_start();

// If rider is already logged in, redirect to the dashboard
if (isset($_SESSION['rider_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error_message = '';
if (isset($_SESSION['signup_error'])) {
    $error_message = $_SESSION['signup_error'];
    unset($_SESSION['signup_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Signup</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; margin: 0; background-color: #111; display: flex; justify-content: center; align-items: center; min-height: 100vh; color: #eee; padding: 20px 0; }
        .signup-wrapper { max-width: 500px; width: 100%; background: #222; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); overflow: hidden; border: 1px solid #44d62c; }
        .signup-form { padding: 40px; }
        .signup-form h2 { text-align: center; color: #fff; margin-top: 0; margin-bottom: 20px; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; }
        input, select { width: 100%; padding: 12px; border: 1px solid #555; border-radius: 5px; box-sizing: border-box; font-family: 'Poppins', sans-serif; background-color: #333; color: #fff; }
        .btn { width: 100%; padding: 12px; border: none; border-radius: 5px; cursor: pointer; color: #fff; font-size: 1rem; font-weight: 500; }
        .btn-primary { background-color: #44d62c; color: #111; }
        .btn-primary:hover { background-color: #59f441; }
        .message { text-align: center; margin-bottom: 1rem; padding: 10px; border-radius: 5px; }
        .error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; }
        .login-link { text-align: center; margin-top: 1rem; }
        .login-link a { color: #44d62c; text-decoration: none; font-weight: 500; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="signup-wrapper">
        <div class="signup-form">
            <h2>Create Rider Account</h2>
            <?php if ($error_message): ?>
                <p class="message error"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>
            <form action="signup_process.php" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="form-group">
                    <label for="vehicle_type">Vehicle Type</label>
                    <select id="vehicle_type" name="vehicle_type" required>
                        <option value="Motorcycle">Motorcycle</option>
                        <option value="Bicycle">Bicycle</option>
                        <option value="Car">Car</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Sign Up</button>
            </form>
            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>
</html>