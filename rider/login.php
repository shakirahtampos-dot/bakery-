
<?php

session_start();

include "db.php";


// Check database connection
if (!isset($conn) || !$conn) {
    die("Database connection failed. Please check your db.php file.");
}


// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($email) || empty($password)) {

        $_SESSION['login_error'] = "Email and password are required.";

        header("Location: login.php");
        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | FIND RIDER
    |--------------------------------------------------------------------------
    */
    $stmt = $conn->prepare("
        SELECT id, name, password, status
        FROM riders
        WHERE email = ?
        LIMIT 1
    ");

    if ($stmt === false) {

        $_SESSION['login_error'] = "Database error. Please try again later.";

        header("Location: login.php");
        exit();
    }


    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();


    /*
    |--------------------------------------------------------------------------
    | CHECK RIDER
    |--------------------------------------------------------------------------
    */
    if ($result->num_rows === 1) {

        $rider = $result->fetch_assoc();


        /*
        |--------------------------------------------------------------------------
        | VERIFY PASSWORD
        |--------------------------------------------------------------------------
        */
        if (password_verify($password, $rider['password'])) {


            /*
            |--------------------------------------------------------------------------
            | CHECK ACCOUNT STATUS
            |--------------------------------------------------------------------------
            */
            if ($rider['status'] !== 'active') {

                $_SESSION['login_error'] =
                    "Your account is not active. Please contact support.";

                $stmt->close();

                header("Location: login.php");
                exit();
            }


            /*
            |--------------------------------------------------------------------------
            | LOGIN SUCCESSFUL
            |--------------------------------------------------------------------------
            */
            $_SESSION['rider_id'] = $rider['id'];
            $_SESSION['rider_name'] = $rider['name'];


            $stmt->close();

            header("Location: dashboard.php");
            exit();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN FAILED
    |--------------------------------------------------------------------------
    */
    $stmt->close();

    $_SESSION['login_error'] = "Invalid email or password.";

    header("Location: login.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| IF RIDER IS ALREADY LOGGED IN
|--------------------------------------------------------------------------
*/
if (isset($_SESSION['rider_id'])) {

    header("Location: dashboard.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| DISPLAY ERROR MESSAGE
|--------------------------------------------------------------------------
*/
$error_message = '';

if (isset($_SESSION['login_error'])) {

    $error_message = $_SESSION['login_error'];

    unset($_SESSION['login_error']);
}


/*
|--------------------------------------------------------------------------
| DISPLAY SUCCESS MESSAGE
|--------------------------------------------------------------------------
*/
$success_message = '';

if (isset($_SESSION['register_success'])) {

    $success_message = $_SESSION['register_success'];

    unset($_SESSION['register_success']);
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rider Login</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #111;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #eee;
            padding: 20px;
        }

        .login-wrapper {
            display: flex;
            max-width: 900px;
            width: 100%;
            background: #222;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            overflow: hidden;
            border: 1px solid #44d62c;
        }

        .login-banner {
            background: #000;
            color: #fff;
            padding: 40px;
            width: 40%;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .login-banner img {
            max-width: 150px;
            max-height: 80px;
            margin-bottom: 20px;
        }

        .login-banner h1 {
            font-size: 2.5rem;
            margin: 0;
        }

        .login-banner p {
            font-size: 1.1rem;
        }

        .login-form {
            padding: 40px;
            width: 60%;
        }

        .login-form h2 {
            text-align: center;
            color: #fff;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #555;
            border-radius: 5px;
            font-family: 'Poppins', sans-serif;
            background-color: #333;
            color: #fff;
        }

        input:focus {
            outline: none;
            border-color: #44d62c;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            font-size: 1rem;
            font-weight: 500;
        }

        .btn-primary {
            background-color: #44d62c;
            color: #111;
        }

        .btn-primary:hover {
            background-color: #59f441;
        }

        .message {
            text-align: center;
            margin-bottom: 1rem;
            padding: 10px;
            border-radius: 5px;
        }

        .error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .signup-link {
            text-align: center;
            margin-top: 1rem;
        }

        .signup-link a {
            color: #44d62c;
            text-decoration: none;
            font-weight: 500;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {

            .login-wrapper {
                flex-direction: column;
            }

            .login-banner,
            .login-form {
                width: 100%;
            }

            .login-banner {
                padding: 30px 20px;
            }

            .login-banner h1 {
                font-size: 2rem;
            }

            .login-form {
                padding: 30px 20px;
            }
        }

    </style>

</head>

<body>

    <div class="login-wrapper">

        <div class="login-banner">

            <img
                src="../store/image/logo.png"
                alt="Logo"
            >

            <h1>Rider Portal</h1>

            <p>Your delivery partner.</p>

        </div>


        <div class="login-form">

            <h2>Welcome Back!</h2>


            <?php if ($success_message): ?>

                <p class="message success">
                    <?= htmlspecialchars($success_message) ?>
                </p>

            <?php endif; ?>


            <?php if ($error_message): ?>

                <p class="message error">
                    <?= htmlspecialchars($error_message) ?>
                </p>

            <?php endif; ?>


            <form action="login.php" method="POST">

                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Login
                </button>

            </form>


            <div class="signup-link">

                <p>
                    Don't have an account?
                    <a href="signup.php">Sign up here</a>
                </p>

            </div>

        </div>

    </div>

</body>

</html>
```
