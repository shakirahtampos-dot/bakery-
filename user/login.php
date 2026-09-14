<?php

/*
|--------------------------------------------------------------------------
| START SESSION
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| REQUIRED FILES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/function.php";
require_once __DIR__ . "/validation.php";


/*
|--------------------------------------------------------------------------
| ALREADY LOGGED IN
|--------------------------------------------------------------------------
*/

if (isLoggedIn()) {
    redirect("home.php");
}


/*
|--------------------------------------------------------------------------
| VARIABLES
|--------------------------------------------------------------------------
*/

$errors = [];

$username = "";
$password = "";


/*
|--------------------------------------------------------------------------
| LOGIN PROCESS
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = cleanInput($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $usernameError = validateUsername($username);

    if ($usernameError !== null) {
        $errors[] = $usernameError;
    }

    if ($password === "") {
        $errors[] = "Please enter your password.";
    }


    /*
    |--------------------------------------------------------------------------
    | DATABASE LOGIN
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $conn = null;

        try {

            $conn = getConnection();

            $sql = "
                SELECT
                    id,
                    fullname,
                    username,
                    email,
                    password,
                    role
                FROM users
                WHERE username = ?
                LIMIT 1
            ";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param("s", $username);

            $stmt->execute();

            $result = $stmt->get_result();

            $user = $result->fetch_assoc();


            /*
            |--------------------------------------------------------------------------
            | CHECK LOGIN
            |--------------------------------------------------------------------------
            */

            if ($user && password_verify($password, $user["password"])) {

                /*
                |------------------------------------------------------------------
                | REGENERATE SESSION ID
                |------------------------------------------------------------------
                */

                session_regenerate_id(true);


                /*
                |------------------------------------------------------------------
                | SAVE USER INFORMATION
                |------------------------------------------------------------------
                */

                $_SESSION["user_id"]   = (int) $user["id"];
                $_SESSION["fullname"] = $user["fullname"];
                $_SESSION["username"]  = $user["username"];
                $_SESSION["email"]     = $user["email"];
                $_SESSION["role"]      = $user["role"];


                /*
                |------------------------------------------------------------------
                | SUCCESS MESSAGE
                |------------------------------------------------------------------
                */

                setFlashMessage(
                    "Welcome back, " . $user["fullname"] . "!"
                );


                /*
                |------------------------------------------------------------------
                | CLOSE STATEMENT
                |------------------------------------------------------------------
                */

                $stmt->close();

                /*
                |------------------------------------------------------------------
                | REDIRECT
                |------------------------------------------------------------------
                */

                redirect("home.php");

            } else {

                $errors[] = "Invalid username or password.";

            }


            /*
            |--------------------------------------------------------------------------
            | CLOSE STATEMENT
            |--------------------------------------------------------------------------
            */

            if (isset($stmt)) {
                $stmt->close();
            }


            /*
            |--------------------------------------------------------------------------
            | CLOSE CONNECTION
            |--------------------------------------------------------------------------
            */

            closeConnection($conn);

        } catch (Throwable $e) {

            error_log("User login failed: " . $e->getMessage());

            $errors[] = "Unable to login right now. Please try again.";


            if ($conn instanceof mysqli) {
                closeConnection($conn);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <!-- Google Fonts -->

    <link
        href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Playfair+Display:wght@400;500;600&display=swap"
        rel="stylesheet"
    >

    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <!-- Login CSS -->

    <link
        rel="stylesheet"
        href="login.css"
    >

    <title>Kates Goodies - Login</title>

</head>

<body>

<div class="container">

    <!-- =====================================================
         IMAGE SECTION
         ===================================================== -->

    <div class="image-section">

        <img
            src="Coffeeshop.jpg"
            alt="Kates Goodies Coffee Shop"
        >

    </div>


    <!-- =====================================================
         LOGIN FORM SECTION
         ===================================================== -->

    <div class="form-section">

        <div class="form-wrapper">


            <!-- LOGO -->

            <div class="imagelogo">

                <img
                    src="Katelogo.png"
                    alt="Kates Goodies Logo"
                >

            </div>


            <!-- TITLE -->

            <h2>
                Login
            </h2>

            <p class="subtitle">
                Please enter your credentials
            </p>


            <!-- ERROR MESSAGE -->

            <?php if (!empty($errors)): ?>

                <div class="error-box">

                    <?php foreach ($errors as $error): ?>

                        <p>
                            <?= e($error) ?>
                        </p>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <!-- LOGIN FORM -->

            <form
                id="loginForm"
                method="POST"
                action="login.php"
                novalidate
            >

                <!-- USERNAME -->

                <div class="input-group">

                    <label for="username">
                        Username
                    </label>

                    <div class="input-field">

                        <span class="icon">
                            <i class="fa-solid fa-user"></i>
                        </span>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Enter your username"
                            autocomplete="username"
                            maxlength="50"
                            value="<?= e($username) ?>"
                            required
                        >

                    </div>

                </div>


                <!-- PASSWORD -->

                <div class="input-group">

                    <label for="password">
                        Password
                    </label>

                    <div class="input-field">

                        <span class="icon">
                            <i class="fa-solid fa-lock"></i>
                        </span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            id="passwordToggle"
                            aria-label="Show password"
                        >

                            <i class="fa-solid fa-eye"></i>

                        </button>

                    </div>

                </div>


                <!-- LOGIN BUTTON -->

                <button
                    type="submit"
                    class="btn-login"
                >
                    LOGIN
                </button>

            </form>


            <!-- REGISTRATION LINK -->

            <div class="login-redirect">

                <p>

                    Don't have an account?

                    <a href="registration.php">
                        Create an account
                    </a>

                </p>

            </div>


        </div>

    </div>

</div>


<script src="login.js"></script>

</body>

</html>