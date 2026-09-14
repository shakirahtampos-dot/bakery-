
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

$old = [
    "fullname" => "",
    "username" => "",
    "email"    => ""
];


/*
|--------------------------------------------------------------------------
| REGISTRATION PROCESS
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /*
    |--------------------------------------------------------------------------
    | GET FORM DATA
    |--------------------------------------------------------------------------
    */

    $fullName = cleanInput(
        $_POST["fullname"] ?? ""
    );

    $username = cleanInput(
        $_POST["username"] ?? ""
    );

    $email = cleanInput(
        $_POST["email"] ?? ""
    );

    $password = $_POST["password"] ?? "";

    $confirmPassword = $_POST["confirm_password"] ?? "";


    /*
    |--------------------------------------------------------------------------
    | KEEP OLD FORM VALUES
    |--------------------------------------------------------------------------
    */

    $old["fullname"] = $fullName;
    $old["username"] = $username;
    $old["email"]    = $email;


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validationErrors = [

        validateFullName($fullName),

        validateUsername($username),

        validateEmail($email),

        validatePassword($password),

        validateConfirmPassword(
            $password,
            $confirmPassword
        )

    ];


    foreach ($validationErrors as $error) {

        if ($error !== null && $error !== "") {
            $errors[] = $error;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | DATABASE REGISTRATION
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $conn = null;

        try {

            $conn = getConnection();


            /*
            |--------------------------------------------------------------------------
            | CHECK EXISTING USERNAME OR EMAIL
            |--------------------------------------------------------------------------
            */

            if (
                usernameOrEmailExists(
                    $conn,
                    $username,
                    $email
                )
            ) {

                $errors[] =
                    "That username or email is already registered.";

            } else {


                /*
                |--------------------------------------------------------------------------
                | REGISTER USER
                |--------------------------------------------------------------------------
                */

                $newUserId = registerUser(
                    $conn,
                    $fullName,
                    $username,
                    $email,
                    $password
                );


                /*
                |--------------------------------------------------------------------------
                | REGISTRATION SUCCESS
                |--------------------------------------------------------------------------
                */

                if ($newUserId !== false) {

                    /*
                    | Set the success message.
                    | This is read by success.php.
                    */

                    setFlashMessage(
                        "Account created successfully. Please log in."
                    );


                    /*
                    | Tell success.php where to continue.
                    */

                    $_SESSION["success_redirect"] = "login.php";


                    /*
                    | Close database connection.
                    */

                    closeConnection($conn);


                    /*
                    | Redirect to the user success page.
                    */

                    redirect("success.php");

                } else {

                    $errors[] =
                        "Unable to register right now. Please try again.";

                }

            }


            /*
            |--------------------------------------------------------------------------
            | CLOSE CONNECTION
            |--------------------------------------------------------------------------
            */

            if ($conn !== null) {
                closeConnection($conn);
            }

        } catch (Throwable $e) {

            error_log(
                "Registration failed: " . $e->getMessage()
            );

            $errors[] =
                "Unable to register right now. Please try again.";


            if ($conn !== null) {
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

    <!-- Registration CSS -->

    <link
        rel="stylesheet"
        href="registration.css"
    >

    <title>Kates Goodies - Register</title>

</head>

<body>

<div class="container">

    <!-- IMAGE SECTION -->

    <div class="image-section">

        <img
            src="Coffeeshop.jpg"
            alt="Kates Goodies Coffee Shop"
        >

    </div>


    <!-- REGISTRATION FORM SECTION -->

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

            <h2 class="signup-title">
                Create an Account
            </h2>

            <p class="subtitle">
                Please fill in your details
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


            <!-- REGISTRATION FORM -->

            <form
                id="registerForm"
                method="POST"
                action="registration.php"
                novalidate
            >


                <!-- FULL NAME -->

                <div class="input-group">

                    <label for="fullname">
                        Full Name
                    </label>

                    <div class="input-field">

                        <span class="icon">
                            <i class="fa-solid fa-id-card"></i>
                        </span>

                        <input
                            type="text"
                            id="fullname"
                            name="fullname"
                            placeholder="Enter your full name"
                            autocomplete="name"
                            maxlength="100"
                            value="<?= e($old["fullname"]) ?>"
                            required
                        >

                    </div>

                </div>


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
                            placeholder="Choose a username"
                            autocomplete="username"
                            maxlength="50"
                            value="<?= e($old["username"]) ?>"
                            required
                        >

                    </div>

                </div>


                <!-- EMAIL -->

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
                            autocomplete="email"
                            maxlength="100"
                            value="<?= e($old["email"]) ?>"
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
                            placeholder="Create a password"
                            autocomplete="new-password"
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


                <!-- CONFIRM PASSWORD -->

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
                            placeholder="Re-enter your password"
                            autocomplete="new-password"
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


                <!-- REGISTER BUTTON -->

                <button
                    type="submit"
                    class="register-btn"
                >
                    REGISTER
                </button>

            </form>


            <!-- LOGIN LINK -->

            <div class="login-redirect">

                <p>

                    Already have an account?

                    <a href="login.php">
                        Log in
                    </a>

                </p>

            </div>


        </div>

    </div>

</div>


<script src="registration.js"></script>

</body>

</html>