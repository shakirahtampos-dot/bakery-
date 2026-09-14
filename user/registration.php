<?php

session_start();

require_once "config.php";
require_once "validation.php";


/* =========================================================
   CSRF TOKEN
   ========================================================= */

if (empty($_SESSION["csrf_token"])) {

    $_SESSION["csrf_token"] =
        bin2hex(random_bytes(32));

}


$errors = [];

$full_name = "";
$username = "";
$email = "";

$password = "";


/* =========================================================
   DEFAULT ROLE
   ========================================================= */

$role = "customer";


/* =========================================================
   FORM SUBMISSION
   ========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    /* =====================================================
       CSRF CHECK
       ===================================================== */

    if (
        !isset($_POST["csrf_token"]) ||
        !hash_equals(
            $_SESSION["csrf_token"],
            $_POST["csrf_token"]
        )
    ) {

        $errors[] =
            "Your session expired or the form was submitted incorrectly. Please try again.";

    }


    /* =====================================================
       VALIDATION
       ===================================================== */

    $result =
        validateRegistrationInput($_POST);


    $errors =
        array_merge(
            $errors,
            $result["errors"]
        );


    $full_name =
        $result["data"]["full_name"];

    $username =
        $result["data"]["username"];

    $email =
        $result["data"]["email"];

    $password =
        $_POST["password"] ?? "";


    /* =====================================================
       DATABASE CONNECTION
       ===================================================== */

    $pdo = null;


    if (empty($errors)) {

        try {

            $pdo = getConnection();


            /* =================================================
               CHECK DUPLICATE EMAIL / USERNAME
               ================================================= */

            $check_sql = "
                SELECT id
                FROM users
                WHERE email = :email
                   OR username = :username
                LIMIT 1
            ";


            $check_stmt =
                $pdo->prepare($check_sql);


            $check_stmt->bindValue(
                ":email",
                $email,
                PDO::PARAM_STR
            );


            $check_stmt->bindValue(
                ":username",
                $username,
                PDO::PARAM_STR
            );


            $check_stmt->execute();


            if ($check_stmt->fetch()) {

                $errors[] =
                    "Email or username is already registered.";

            }


        } catch (PDOException $e) {

            error_log(
                "Registration database check failed: " .
                $e->getMessage()
            );

            $errors[] =
                "Unable to process registration right now.";

        }

    }


    /* =====================================================
       INSERT USER
       ===================================================== */

    if (empty($errors)) {

        try {

            $hashed_password =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );


            $sql = "
                INSERT INTO users
                (
                    full_name,
                    username,
                    email,
                    password,
                    role
                )
                VALUES
                (
                    :full_name,
                    :username,
                    :email,
                    :password,
                    :role
                )
            ";


            $stmt =
                $pdo->prepare($sql);


            $stmt->bindValue(
                ":full_name",
                $full_name,
                PDO::PARAM_STR
            );


            $stmt->bindValue(
                ":username",
                $username,
                PDO::PARAM_STR
            );


            $stmt->bindValue(
                ":email",
                $email,
                PDO::PARAM_STR
            );


            $stmt->bindValue(
                ":password",
                $hashed_password,
                PDO::PARAM_STR
            );


            $stmt->bindValue(
                ":role",
                $role,
                PDO::PARAM_STR
            );


            $stmt->execute();


            $newId =
                (int)$pdo->lastInsertId();


            /* =================================================
               ROTATE CSRF TOKEN
               ================================================= */

            $_SESSION["csrf_token"] =
                bin2hex(random_bytes(32));


            /* =================================================
               REDIRECT
               ================================================= */

            header(
                "Location: success.php?id=" .
                $newId
            );

            exit;


        } catch (PDOException $e) {

            error_log(
                "Registration insert failed: " .
                $e->getMessage()
            );

            $errors[] =
                "Registration failed. Please try again.";

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

    <link
        rel="stylesheet"
        href="registration.css"
    >

    <title>Kates Goodies - Registration</title>

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
         FORM SECTION
         ===================================================== -->

    <div class="form-section">

        <div class="form-wrapper">


            <!-- =================================================
                 LOGO
                 ================================================= -->

            <div class="imagelogo">

                <img
                    src="Katelogo.png"
                    alt="Kates Goodies Logo"
                >

            </div>


            <!-- =================================================
                 TITLE
                 ================================================= -->

            <h2 class="signup-title">
                Sign Up
            </h2>


            <p class="subtitle">
                Create your account
            </p>


            <!-- =================================================
                 ERROR MESSAGE
                 ================================================= -->

            <?php if (!empty($errors)): ?>

                <div class="error-box">

                    <?php foreach ($errors as $error): ?>

                        <p>

                            <?= htmlspecialchars(
                                $error,
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>

                        </p>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 REGISTRATION FORM
                 ================================================= -->

            <form
                id="registerForm"
                method="POST"
                action="registration.php"
                novalidate
            >


                <!-- CSRF -->

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars(
                        $_SESSION["csrf_token"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                >


                <!-- =================================================
                     FULL NAME
                     ================================================= -->

                <div class="input-group">

                    <label for="full_name">
                        Full Name
                    </label>

                    <div class="input-field">

                        <span class="icon">

                            <i class="fa-solid fa-user"></i>

                        </span>

                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            placeholder="Enter your full name"
                            value="<?= htmlspecialchars(
                                $full_name,
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>"
                            maxlength="100"
                            autocomplete="name"
                            required
                        >

                    </div>

                </div>


                <!-- =================================================
                     USERNAME
                     ================================================= -->

                <div class="input-group">

                    <label for="username">
                        Username
                    </label>

                    <div class="input-field">

                        <span class="icon">

                            <i class="fa-solid fa-tag"></i>

                        </span>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Enter your username"
                            value="<?= htmlspecialchars(
                                $username,
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>"
                            pattern="[a-zA-Z0-9_]{3,30}"
                            maxlength="30"
                            autocomplete="username"
                            required
                        >

                    </div>

                </div>


                <!-- =================================================
                     EMAIL
                     ================================================= -->

                <div class="input-group">

                    <label for="email">
                        Email Address
                    </label>

                    <div class="input-field">

                        <span class="icon">

                            <i class="fa-solid fa-envelope"></i>

                        </span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter your email"
                            value="<?= htmlspecialchars(
                                $email,
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>"
                            maxlength="255"
                            autocomplete="email"
                            required
                        >

                    </div>

                </div>


                <!-- =================================================
                     PASSWORD
                     ================================================= -->

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
                            autocomplete="new-password"
                            minlength="8"
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


                <!-- =================================================
                     CONFIRM PASSWORD
                     ================================================= -->

                <div class="input-group">

                    <label for="confirm_password">
                        Confirm Password
                    </label>

                    <div class="input-field">

                        <span class="icon">

                            <i class="fa-solid fa-lock"></i>

                        </span>

                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            placeholder="Confirm your password"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            id="confirmPasswordToggle"
                            aria-label="Show password"
                        >

                            <i class="fa-solid fa-eye"></i>

                        </button>

                    </div>

                </div>


                <!-- =================================================
                     REGISTER BUTTON
                     ================================================= -->

                <button
                    type="submit"
                    class="register-btn"
                >
                    REGISTER
                </button>


            </form>


            <!-- =================================================
                 LOGIN LINK
                 ================================================= -->

            <div class="login-redirect">

                Already have an account?

                <a href="login.php">
                    Login
                </a>

            </div>


        </div>

    </div>

</div>


<script src="registration.js"></script>

</body>
</html>