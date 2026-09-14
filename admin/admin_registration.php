<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/function.php';
require_once __DIR__ . '/validation.php';


if (
    isset($_SESSION['user_id']) &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'admin'
) {
    header('Location: admin_dashboard.php');
    exit();
}


$message = '';
$messageType = '';

$fullname = '';
$email = '';
$username = '';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    

$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');

$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

$terms = isset($_POST['terms']);

$errors = [];


$fullname = cleanInput($fullname);
$email = cleanInput($email);
$username = cleanInput($username);


$nameError = validateFullName($fullname);

    if ($nameError !== null) {
        $errors[] = $nameError;
    }

    $emailError = validateEmail($email);

    if ($emailError !== null) {
        $errors[] = $emailError;
    }

    $usernameError = validateUsername($username);

    if ($usernameError !== null) {
        $errors[] = $usernameError;
    }


    $passwordError = validatePassword($password);

    if ($passwordError !== null) {
        $errors[] = $passwordError;
    }

    $confirmError = validateConfirmPassword(
        $password,
        $confirmPassword
    );

    if ($confirmError !== null) {
        $errors[] = $confirmError;
    }

    if (!$terms) {
        $errors[] = 'You must agree to the Terms and Conditions.';
    }

    if (!empty($errors)) {

        $message = $errors[0];
        $messageType = 'error';

    } else {


        $conn = null;
        $checkStmt = null;
        $insertStmt = null;

try {

    $conn = getConnection();

    if (!($conn instanceof mysqli)) {
        throw new RuntimeException(
            'Database connection is invalid.'
        );
    }


$checkSql = "
    SELECT id
    FROM users
    WHERE email = ?
        OR username = ?
    LIMIT 1
";

$checkStmt = $conn->prepare($checkSql);

$checkStmt->bind_param(
    'ss',
    $email,
    $username
);

$checkStmt->execute();

$checkStmt->store_result();


            

    if ($checkStmt->num_rows > 0) {

        $message =
            'The email address or username is already registered.';

        $messageType = 'error';

    } else {

        
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        if ($hashedPassword === false) {
            throw new RuntimeException(
                'Password hashing failed.'
            );
        }


        $insertSql = "
            INSERT INTO users
            (
                fullname,
                email,
                username,
                password,
                role
            )
            VALUES (?, ?, ?, ?, 'admin')
        ";

        $insertStmt = $conn->prepare($insertSql);

        $insertStmt->bind_param(
            'ssss',
            $fullname,
            $email,
            $username,
            $hashedPassword
        );

        $insertStmt->execute();



$_SESSION['flash_success'] =
    'Your administrator account was created successfully. You can now log in.';



                $_SESSION['success_redirect'] =
                    'admin_login.php';


                header('Location: success.php');
                exit();
                }


                } catch (mysqli_sql_exception $e) {

                error_log(
                    'Admin registration MySQL error: ' .
                    $e->getMessage()
                );

                $message =
                    'Unable to create the administrator account. Please check your database configuration and users table.';

                $messageType = 'error';


                } catch (Throwable $e) {

                error_log(
                    'Admin registration error: ' .
                    $e->getMessage()
                );

                $message =
                    'A system error occurred. Please try again later.';

                $messageType = 'error';


                } finally {

                        
            if ($checkStmt instanceof mysqli_stmt) {
                $checkStmt->close();
            }

            if ($insertStmt instanceof mysqli_stmt) {
                $insertStmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&family=Poppins:wght@300;400;500;600;700&family=Croissant+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet"href="admin_registration.css">
    <title>KATES GOODIES ADMIN REGISTRATION</title>
    
</head>
<body>

       <main class="auth-page">

        <div class="auth-card">
           <div class="logo-container">
             <img src="Katelogo.png" alt="Kates Goodies Logo" class="auth-logo">

</div>

        <div class="auth-header">
           <h1>Admin Registration</h1>
             <p>Create your Kates Goodies administrator account</p>

</div>

                <?php if ($message !== ''): ?>

        <div id="formMessage" class="form-message <?= e($messageType) ?>" role="alert" aria-live="polite">
                <?= e($message) ?>
</div>

                <?php else: ?>

        <div id="formMessage" class="form-message" aria-live="polite"></div>

                <?php endif; ?>

        <form id="adminRegistrationForm" action="<?= e($_SERVER['PHP_SELF']) ?>" method="POST" novalidate> 

        <div class="input-group">

                <label for="fullname">
                    Full Name
                </label>

                <div class="input-wrapper">

                    <i
                        class="fa-solid fa-user"
                        aria-hidden="true"
                    ></i>

                    <input
                        type="text"
                        id="fullname"
                        name="fullname"
                        placeholder="Enter your full name"
                        autocomplete="name"
                        value="<?= e($fullname) ?>"
                        maxlength="100"
                        required
                    >

                </div>

                <small
                    class="input-error"
                    id="fullnameError"
                ></small>

            </div>


            <!-- EMAIL -->

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
                        placeholder="Enter your email address"
                        autocomplete="email"
                        value="<?= e($email) ?>"
                        maxlength="255"
                        required
                    >

                </div>

                <small
                    class="input-error"
                    id="emailError"
                ></small>

            </div>


            <!-- USERNAME -->

            <div class="input-group">

                <label for="username">
                    Username
                </label>

                <div class="input-wrapper">

                    <i
                        class="fa-solid fa-at"
                        aria-hidden="true"
                    ></i>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Enter your username"
                        autocomplete="username"
                        value="<?= e($username) ?>"
                        maxlength="50"
                        required
                    >

                </div>

                <small
                    class="input-error"
                    id="usernameError"
                ></small>

            </div>


            <!-- PASSWORD -->

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
                        placeholder="Create a password"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        id="passwordToggle"
                        aria-label="Show password"
                        aria-pressed="false"
                    >

                        <i
                            class="fa-solid fa-eye"
                            aria-hidden="true"
                        ></i>

                    </button>

                </div>

                <small
                    class="input-error"
                    id="passwordError"
                ></small>

            </div>


            <!-- CONFIRM PASSWORD -->

            <div class="input-group">

                <label for="confirmPassword">
                    Confirm Password
                </label>

                <div class="input-wrapper">

                    <i
                        class="fa-solid fa-lock"
                        aria-hidden="true"
                    ></i>

                    <input
                        type="password"
                        id="confirmPassword"
                        name="confirmPassword"
                        placeholder="Confirm your password"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        id="confirmPasswordToggle"
                        aria-label="Show confirm password"
                        aria-pressed="false"
                    >

                        <i
                            class="fa-solid fa-eye"
                            aria-hidden="true"
                        ></i>

                    </button>

                </div>

                <small
                    class="input-error"
                    id="confirmPasswordError"
                ></small>

            </div>


            <!-- TERMS -->

            <div class="terms-container">

                <label class="checkbox-container">

                    <input
                        type="checkbox"
                        id="terms"
                        name="terms"
                        required
                    >

                    <span
                        class="custom-checkbox"
                        aria-hidden="true"
                    ></span>

                    <span class="terms-text">

                        I agree to the

                        <a
                            href="#"
                            onclick="return false;"
                        >
                            Terms and Conditions
                        </a>

                    </span>

                </label>

                <small
                    class="input-error"
                    id="termsError"
                ></small>

            </div>


            <!-- SUBMIT -->

            <button
                type="submit"
                class="btn-primary"
                id="registerButton"
            >

                <span>
                    Create Admin Account
                </span>

            </button>

        </form>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div class="auth-footer">

            <p>
                Already have an admin account?
            </p>

            <a
                href="admin_login.php"
                class="login-link"
            >

                <i
                    class="fa-solid fa-right-to-bracket"
                    aria-hidden="true"
                ></i>

                Login Here

            </a>

        </div>

    </div>

</main>


<!-- =====================================================
     JAVASCRIPT
====================================================== -->

<script src="admin_registration.js"></script>

</body>

</html>