
<?php

/*
|--------------------------------------------------------------------------
| START SESSION SAFELY
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| LOAD FUNCTIONS
|--------------------------------------------------------------------------
|
| function.php should be in the same folder as success.php.
|
*/

require_once __DIR__ . '/function.php';


/*
|--------------------------------------------------------------------------
| GET SUCCESS MESSAGE
|--------------------------------------------------------------------------
|
| This checks the session for a success message.
| If there is no message, a default message is displayed.
|
*/

$message = 'Your action was completed successfully.';

if (
    isset($_SESSION['flash']) &&
    isset($_SESSION['flash']['success'])
) {
    $message = $_SESSION['flash']['success'];

    // Remove the message after displaying it
    unset($_SESSION['flash']['success']);
}


/*
|--------------------------------------------------------------------------
| GET REDIRECT URL
|--------------------------------------------------------------------------
*/

$redirectUrl = 'admin_dashboard.php';

if (isset($_SESSION['success_redirect'])) {
    $redirectUrl = $_SESSION['success_redirect'];

    // Remove redirect after reading
    unset($_SESSION['success_redirect']);
}


/*
|--------------------------------------------------------------------------
| ALLOWED REDIRECT PAGES
|--------------------------------------------------------------------------
|
| Only these pages can be used as the Continue destination.
| This prevents unexpected external redirects.
|
*/

$allowedRedirects = [
    'admin_login.php',
    'admin_dashboard.php'
];

if (!in_array($redirectUrl, $allowedRedirects, true)) {
    $redirectUrl = 'admin_dashboard.php';
}


/*
|--------------------------------------------------------------------------
| SAFE HTML OUTPUT
|--------------------------------------------------------------------------
|
| If e() already exists in function.php, use it.
| Otherwise, htmlspecialchars() is used directly.
|
*/

$safeMessage = function_exists('e')
    ? e((string) $message)
    : htmlspecialchars(
        (string) $message,
        ENT_QUOTES,
        'UTF-8'
    );

$safeRedirectUrl = function_exists('e')
    ? e((string) $redirectUrl)
    : htmlspecialchars(
        (string) $redirectUrl,
        ENT_QUOTES,
        'UTF-8'
    );

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

        /*
        |--------------------------------------------------------------------------
        | RESET
        |--------------------------------------------------------------------------
        */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        /*
        |--------------------------------------------------------------------------
        | BODY
        |--------------------------------------------------------------------------
        */

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #f7ead8;
            color: #2b140b;
        }


        /*
        |--------------------------------------------------------------------------
        | SUCCESS CARD
        |--------------------------------------------------------------------------
        */

        .success-card {
            width: 100%;
            max-width: 450px;

            padding: 45px 30px;

            text-align: center;

            background: #fffaf3;

            border-radius: 20px;

            box-shadow:
                0 10px 35px rgba(43, 20, 11, 0.12);
        }


        /*
        |--------------------------------------------------------------------------
        | SUCCESS ICON
        |--------------------------------------------------------------------------
        */

        .success-icon {
            width: 75px;
            height: 75px;

            margin: 0 auto 22px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #d9f3df;
            color: #1f7a3f;

            font-size: 40px;
            font-weight: bold;

            box-shadow:
                0 5px 15px rgba(31, 122, 63, 0.12);
        }


        /*
        |--------------------------------------------------------------------------
        | TITLE
        |--------------------------------------------------------------------------
        */

        h1 {
            margin-bottom: 12px;

            font-size: 32px;
            font-weight: 700;

            color: #2b140b;
        }


        /*
        |--------------------------------------------------------------------------
        | MESSAGE
        |--------------------------------------------------------------------------
        */

        .message {
            margin: 0 auto;

            max-width: 350px;

            font-size: 16px;
            line-height: 1.6;

            color: #765f50;
        }


        /*
        |--------------------------------------------------------------------------
        | CONTINUE BUTTON
        |--------------------------------------------------------------------------
        */

        .btn {
            display: inline-block;

            margin-top: 25px;

            padding: 13px 28px;

            border-radius: 9px;

            background: #6f3515;
            color: #ffffff;

            font-size: 15px;
            font-weight: 600;

            text-decoration: none;

            transition:
                background 0.25s ease,
                transform 0.2s ease,
                box-shadow 0.25s ease;
        }


        /*
        |--------------------------------------------------------------------------
        | BUTTON HOVER
        |--------------------------------------------------------------------------
        */

        .btn:hover {
            background: #35170d;

            transform: translateY(-2px);

            box-shadow:
                0 6px 15px rgba(43, 20, 11, 0.18);
        }


        /*
        |--------------------------------------------------------------------------
        | BUTTON ACTIVE
        |--------------------------------------------------------------------------
        */

        .btn:active {
            transform: translateY(0);
        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 480px) {

            body {
                padding: 15px;
            }

            .success-card {
                padding: 35px 22px;
                border-radius: 16px;
            }

            .success-icon {
                width: 65px;
                height: 65px;

                margin-bottom: 18px;

                font-size: 34px;
            }

            h1 {
                font-size: 28px;
            }

            .message {
                font-size: 15px;
            }

            .btn {
                width: 100%;

                padding: 13px 20px;
            }

        }

    </style>

</head>


<body>


    <!--
    |--------------------------------------------------------------------------
    | SUCCESS CARD
    |--------------------------------------------------------------------------
    -->

    <main
        class="success-card"
        role="main"
        aria-labelledby="success-title"
    >


        <!-- SUCCESS ICON -->

        <div
            class="success-icon"
            aria-hidden="true"
        >
            ✓
        </div>


        <!-- TITLE -->

        <h1 id="success-title">
            Success!
        </h1>


        <!-- MESSAGE -->

        <p class="message">
            <?= $safeMessage ?>
        </p>


        <!-- CONTINUE -->

        <a
            class="btn"
            href="<?= $safeRedirectUrl ?>"
        >
            Continue
        </a>


    </main>


</body>

</html>
