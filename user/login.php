<?php
session_start();
require_once "config.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "") {
        $errors[] = "Please enter your username.";
    }

    if ($password === "") {
        $errors[] = "Please enter your password.";
    }

    if (empty($errors)) {

        try {

            $pdo = getConnection();

            $sql = "SELECT id, full_name, username, email, password, role
                    FROM users
                    WHERE username = :username
                    LIMIT 1";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(
                ":username",
                $username,
                PDO::PARAM_STR
            );

            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user["password"])) {

                session_regenerate_id(true);

                $_SESSION["user_id"] = (int)$user["id"];
                $_SESSION["full_name"] = $user["full_name"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];

                /*
                 * Change this page if your actual
                 * customer dashboard has a different filename.
                 */
                header("Location: index.php");
                exit;

            } else {

                $errors[] = "Invalid username or password.";

            }

        } catch (PDOException $e) {

            error_log("Login failed: " . $e->getMessage());

            $errors[] = "Unable to login right now. Please try again.";

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

            <h2>Login</h2>

            <p class="subtitle">
                Please enter your credentials
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
                 LOGIN FORM
                 ================================================= -->

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
                            maxlength="30"
                            value="<?= htmlspecialchars(
                                $_POST["username"] ?? "",
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>"
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


            <!-- =================================================
                 REGISTRATION LINK
                 ================================================= -->

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