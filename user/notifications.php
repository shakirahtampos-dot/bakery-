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
| USER ACCESS
|--------------------------------------------------------------------------
*/

if (!isLoggedIn()) {
    redirect("login.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| USER INFORMATION
|--------------------------------------------------------------------------
*/

$fullname = $_SESSION["fullname"] ?? "Guest User";
$username = $_SESSION["username"] ?? "Guest";
$email    = $_SESSION["email"] ?? "";
$role     = $_SESSION["role"] ?? "customer";


/*
|--------------------------------------------------------------------------
| BRAND NAME
|--------------------------------------------------------------------------
*/

$brandName = $brandName ?? "Kates Goodies";


/*
|--------------------------------------------------------------------------
| SAFE OUTPUT
|--------------------------------------------------------------------------
*/

$fullname = e($fullname);
$username = e($username);
$email    = e($email);
$role     = e($role);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Kates Goodies Notifications"
    >

    <!-- GOOGLE FONT -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&display=swap"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css?family=Poppins"
        rel="stylesheet"
    >

    <!-- FONT AWESOME -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <!-- NOTIFICATIONS CSS -->

    <link
        rel="stylesheet"
        href="notifications.css"
    >

    <title>
        Notifications |
        <?php
        echo htmlspecialchars(
            $brandName,
            ENT_QUOTES,
            "UTF-8"
        );
        ?>
    </title>

</head>


<body>


<!-- =========================================================
     NAVBAR
========================================================= -->

<header class="notification-navbar">


    <div class="notification-navbar-inner">


        <!-- LOGO -->

        <a
            href="home.php"
            class="notification-logo"
        >

            <img
                src="Katelogo.png"
                alt="Kates Goodies"
            >

        </a>


        <!-- PAGE TITLE -->

        <div class="notification-navbar-title">

            <i class="fa-solid fa-bell"></i>

            <span>
                Notifications
            </span>

        </div>


        <!-- ACCOUNT -->

        <div class="notification-navbar-user">

            <div class="notification-user-icon">
                👤
            </div>


            <div class="notification-user-details">

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $fullname,
                        ENT_QUOTES,
                        "UTF-8"
                    );
                    ?>
                </strong>

                <small>
                    <?php
                    echo htmlspecialchars(
                        $role,
                        ENT_QUOTES,
                        "UTF-8"
                    );
                    ?>
                </small>

            </div>

        </div>


    </div>

</header>


<!-- =========================================================
     MAIN
========================================================= -->

<main class="notifications-page">


    <div class="notifications-container">


        <!-- =================================================
             HEADER
        ================================================== -->

        <section class="notifications-header">


            <div class="notifications-heading">


                <a
                    href="home.php"
                    class="back-home"
                >

                    <i
                        class="fa-solid fa-arrow-left"
                    ></i>

                    Back to Home

                </a>


                <span class="notifications-label">
                    YOUR ACCOUNT
                </span>


                <h1>
                    Notifications
                </h1>


                <p>
                    Stay updated with your orders,
                    promotions and important messages.
                </p>

            </div>


            <button
                type="button"
                class="mark-all-button"
                id="markAllRead"
            >

                <i
                    class="fa-solid fa-check-double"
                ></i>

                Mark All as Read

            </button>


        </section>


        <!-- =================================================
             FILTER
        ================================================== -->

        <section class="notification-toolbar">


            <div
                class="notification-filters"
                role="tablist"
                aria-label="Notification filters"
            >

                <button
                    type="button"
                    class="notification-filter active"
                    data-filter="all"
                >
                    All
                </button>


                <button
                    type="button"
                    class="notification-filter"
                    data-filter="unread"
                >
                    Unread
                    <span id="unreadFilterCount">
                        0
                    </span>
                </button>


                <button
                    type="button"
                    class="notification-filter"
                    data-filter="order"
                >
                    Orders
                </button>


                <button
                    type="button"
                    class="notification-filter"
                    data-filter="promo"
                >
                    Promotions
                </button>

            </div>


            <div class="notification-total">

                <span>
                    <strong id="notificationTotal">
                        0
                    </strong>

                    notifications
                </span>

            </div>


        </section>


        <!-- =================================================
             NOTIFICATIONS LIST
        ================================================== -->

        <section
            class="notifications-list"
            id="notificationsList"
        >

            <!-- JavaScript inserts notifications here -->

        </section>


        <!-- =================================================
             EMPTY STATE
        ================================================== -->

        <section
            class="notifications-empty"
            id="notificationsEmpty"
        >

            <div class="empty-icon">

                <i
                    class="fa-regular fa-bell-slash"
                ></i>

            </div>


            <h2>
                No notifications
            </h2>


            <p>
                You're all caught up!
                New notifications will appear here.
            </p>


            <a
                href="home.php"
                class="empty-home-button"
            >
                RETURN HOME
            </a>

        </section>


    </div>

</main>


<!-- =========================================================
     DELETE CONFIRMATION MODAL
========================================================= -->

<div
    class="notification-modal"
    id="deleteModal"
    aria-hidden="true"
>

    <div
        class="notification-modal-backdrop"
        data-close-delete
    ></div>


    <div class="notification-modal-box">


        <div class="modal-danger-icon">

            <i
                class="fa-solid fa-trash"
            ></i>

        </div>


        <h2>
            Delete Notification?
        </h2>


        <p>
            This notification will be permanently removed
            from your notification portal.
        </p>


        <div class="modal-actions">

            <button
                type="button"
                class="modal-cancel"
                data-close-delete
            >
                Cancel
            </button>


            <button
                type="button"
                class="modal-delete"
                id="confirmDelete"
            >
                Delete
            </button>

        </div>


    </div>

</div>


<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script src="notifications.js"></script>

</body>
</html>