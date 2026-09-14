<?php


session_start();

require_once '/function.php';

$message = getFlash('success');

if ($message === null) {
    $message = 'Your action was completed successfully.';
}

$redirectUrl = $_SESSION['success_redirect']
    ?? 'admin_dashboard.php';

unset($_SESSION['success_redirect']);

$allowedRedirects = [
    'admin_login.php',
    'admin_dashboard.php'
];

if (!in_array($redirectUrl, $allowedRedirects, true)) {
    $redirectUrl = 'admin_dashboard.php';
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

    <title>Success | Kate's Goodies</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f7ead8;
            color: #2b140b;
        }

        .success-card {
            width: 100%;
            max-width: 450px;
            padding: 40px 30px;
            text-align: center;
            background: #fffaf3;
            border-radius: 18px;
            box-shadow:
                0 10px 35px rgba(43, 20, 11, .12);
        }

        .success-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #d9f3df;
            color: #1f7a3f;
            font-size: 38px;
            font-weight: bold;
        }

        h1 {
            margin: 0 0 12px;
        }

        p {
            line-height: 1.6;
            color: #765f50;
        }

        .btn {
            display: inline-block;
            margin-top: 18px;
            padding: 13px 24px;
            border-radius: 8px;
            background: #6f3515;
            color: #fff;
            text-decoration: none;
        }

        .btn:hover {
            background: #35170d;
        }

    </style>

</head>

<body>

    <main class="success-card">

        <div
            class="success-icon"
            aria-hidden="true"
        >
            ✓
        </div>

        <h1>Success!</h1>

        <p><?= e($message) ?></p>

        <a
            class="btn"
            href="<?= e($redirectUrl) ?>"
        >
            Continue
        </a>

    </main>

</body>

</html>