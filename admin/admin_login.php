<?php

/* =========================================================
   START SESSION
========================================================= */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* =========================================================
   REQUIRED FILES
========================================================= */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/function.php';
require_once __DIR__ . '/validation.php';


/* =========================================================
   ALREADY LOGGED-IN ADMIN
========================================================= */

if (
    isset($_SESSION['user_id']) &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'admin'
) {
    header('Location: admin_dashboard.php');
    exit();
}


/* =========================================================
   VARIABLES
========================================================= */

$message = '';
$messageType = '';
$email = '';


/* =========================================================
   LOGIN PROCESS
========================================================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* =====================================================
       GET FORM DATA
    ===================================================== */

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';


    /* =====================================================
       CLEAN EMAIL
    ===================================================== */

    if (function_exists('cleanInput')) {
        $email = cleanInput($email);
    }


    /* =====================================================
       VALIDATION
    ===================================================== */

    $errors = [];


    /* -----------------------------------------------------
       EMAIL VALIDATION
    ----------------------------------------------------- */

    if (function_exists('validateEmail')) {

        $emailError = validateEmail($email);

    } else {

        $emailError = null;

        if ($email === '') {

            $emailError = 'Email address is required.';

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $emailError =
                'Please enter a valid email address.';

        }
    }


    if ($emailError !== null) {
        $errors[] = $emailError;
    }


    /* -----------------------------------------------------
       PASSWORD VALIDATION
    ----------------------------------------------------- */

    if (function_exists('validatePassword')) {

        $passwordError = validatePassword($password);

    } else {

        $passwordError = null;

        if ($password === '') {

            $passwordError =
                'Password is required.';

        } elseif (strlen($password) < 8) {

            $passwordError =
                'Password must be at least 8 characters.';

        }
    }


    if ($passwordError !== null) {
        $errors[] = $passwordError;
    }


    /* =====================================================
       STOP IF VALIDATION FAILED
    ===================================================== */

    if (!empty($errors)) {

        $message = $errors[0];
        $messageType = 'error';

    } else {

        $conn = null;
        $stmt = null;

        try {

            /* =============================================
               DATABASE CONNECTION
            ============================================= */

            $conn = getConnection();

            if (!($conn instanceof mysqli)) {
                throw new RuntimeException('Invalid database connection.');
            }

            $sql = "
                SELECT
                    id,
                    fullname,
                    email,
                    username,
                    password,
                    role
                FROM users
                WHERE email = ?
                LIMIT 1
            ";

            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                throw new RuntimeException('Unable to prepare login query.');
            }

            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows !== 1) {

                $message = 'Incorrect email or password.';
                $messageType = 'error';

            } else {

                $userId = 0;
                $userFullName = '';
                $userEmail = '';
                $userUsername = '';
                $userPassword = '';
                $userRole = '';

                $stmt->bind_result(
                    $userId,
                    $userFullName,
                    $userEmail,
                    $userUsername,
                    $userPassword,
                    $userRole
                );

                $stmt->fetch();

                if ($userRole !== 'admin') {

                    $message = 'Access denied. This account is not an administrator.';
                    $messageType = 'error';

                } elseif (
                    empty($userPassword) ||
                    !password_verify($password, $userPassword)
                ) {

                    $message = 'Incorrect email or password.';
                    $messageType = 'error';

                } else {

                    session_regenerate_id(true);

                    $_SESSION['user_id']  = (int) $userId;
                    $_SESSION['fullname'] = (string) $userFullName;
                    $_SESSION['username'] = (string) $userUsername;
                    $_SESSION['email']    = (string) $userEmail;
                    $_SESSION['role']     = 'admin';

                    header('Location: admin_dashboard.php');
                    exit();
                }
            }

        } catch (Throwable $e) {

            // Log the real error for yourself; never show DB details to the user
            error_log('Admin login error: ' . $e->getMessage());

            $message = 'Something went wrong. Please try again later.';
            $messageType = 'error';

        } finally {

            if (isset($stmt) && $stmt instanceof mysqli_stmt) {
                $stmt->close();
            }

            if (isset($conn) && $conn instanceof mysqli) {
                $conn->close();
            }
        }

    } // end else (validation passed)

} // end if POST

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Login | Kates Goodies</title>


    <!-- =================================================
         GOOGLE FONTS
    ================================================== -->

    <link
        href="https://fonts.googleapis.com/css2?family=Aoboshi+One&family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,900;1,900&family=Croissant+One&display=swap"
        rel="stylesheet"
    >


    <!-- =================================================
         FONT AWESOME
    ================================================== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- =================================================
         ADMIN LOGIN CSS
    ================================================== -->

    <link
        rel="stylesheet"
        href="admin_login.css"
    >

</head>


<body>


<main class="auth-page">


    <div class="auth-card">


        <!-- =================================================
             LOGO
        ================================================== -->

        <div class="auth-logo">

            <img
                src="Katelogo.png"
                alt="Kates Goodies Logo"
            >

        </div>


        <!-- =================================================
             HEADER
        ================================================== -->

        <div class="auth-header">

            <h1>
                Admin Sign In
            </h1>

            <p>
                Sign in to access the admin dashboard
            </p>

        </div>


        <!-- =================================================
             ERROR MESSAGE
        ================================================== -->

        <?php if ($message !== ''): ?>

            <div
                class="message <?= e($messageType) ?>"
                role="alert"
                aria-live="polite"
            >

                <?= e($message) ?>

            </div>

        <?php endif; ?>


        <!-- =================================================
             LOGIN FORM
        ================================================== -->

        <form
            method="POST"
            action="admin_login.php"
            id="adminLoginForm"
            autocomplete="on"
        >


            <!-- =================================================
                 EMAIL
            ================================================== -->

            <div class="input-group">

                <label for="email">
                    Email Address
                </label>


                <div class="input-wrapper">

                    <i
                        class="fa-solid fa-envelope"
                        aria-hidden="true"
                    ></i>


                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        autocomplete="email"
                        value="<?= e($email) ?>"
                        maxlength="255"
                        required
                    >

                </div>

            </div>


            <!-- =================================================
                 PASSWORD
            ================================================== -->

            <div class="input-group">

                <label for="password">
                    Password
                </label>


                <div class="input-wrapper">

                    <i
                        class="fa-solid fa-lock"
                        aria-hidden="true"
                    ></i>


                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >


                    <!-- PASSWORD TOGGLE -->



                </div>

            </div>


            <!-- =================================================
                 LOGIN BUTTON
            ================================================== -->

            <button
                type="submit"
                name="login"
                class="btn-primary"
            >

                <span>
                    Log In
                </span>

            </button>


        </form>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div class="auth-footer">

            <span>

                Don't have an admin account?

                <a href="admin_registration.php">
                    Sign Up
                </a>

            </span>

        </div>


    </div>


</main>


<!-- =====================================================
     JAVASCRIPT
====================================================== -->

<script src="admin_login.js"></script>


</body>

</html>