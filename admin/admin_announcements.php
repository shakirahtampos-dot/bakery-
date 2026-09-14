<?php

session_start();

include "db.php";


/* =========================================================
   ADMIN ACCESS
========================================================= */

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: admin_login.php");
    exit();
}


$admin_id = $_SESSION['user_id'];

$message = "";
$message_type = "";


/* =========================================================
   SAFE QUERY
========================================================= */

function safe_query($conn, $sql)
{
    if (!$conn) {
        return false;
    }

    try {
        return mysqli_query($conn, $sql);
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}


/* =========================================================
   CREATE ANNOUNCEMENT
========================================================= */

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["action"]) &&
    $_POST["action"] === "add"
) {

    $title = trim($_POST["title"] ?? "");

    $announcement_message =
        trim($_POST["announcement_message"] ?? "");

    $type =
        trim($_POST["type"] ?? "General");

    $status = "Published";


    /* =============================================
       VALIDATION
    ============================================= */

    if (
        $title === "" ||
        $announcement_message === ""
    ) {

        $message =
            "Please enter both a title and announcement message.";

        $message_type = "error";

    } else {

        /* =============================================
           INSERT ANNOUNCEMENT
        ============================================= */

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO announcements
            (title, message, type, status)
            VALUES (?, ?, ?, ?)"
        );


        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "ssss",
                $title,
                $announcement_message,
                $type,
                $status
            );


            if (mysqli_stmt_execute($stmt)) {

                $message =
                    "Announcement created successfully.";

                $message_type = "success";

            } else {

                $message =
                    "Unable to create announcement.";

                $message_type = "error";

            }


            mysqli_stmt_close($stmt);

        } else {

            $message =
                "Database error.";

            $message_type = "error";

        }

    }

}


/* =========================================================
   GET ANNOUNCEMENTS
========================================================= */

$announcements = [];


$result = safe_query(
    $conn,
    "SELECT
        id,
        title,
        message,
        type,
        status,
        created_at
     FROM announcements
     ORDER BY id DESC"
);


if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $announcements[] = $row;

    }

}


/* =========================================================
   RECENT POSTS
========================================================= */

$recent_posts =
    array_slice(
        $announcements,
        0,
        5
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

    <meta
        name="theme-color"
        content="#252324"
    >

    <title>
        Announcements - Admin
    </title>


    <!-- =====================================================
         GOOGLE FONT
    ====================================================== -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- =====================================================
         FONT AWESOME
    ====================================================== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- =====================================================
         ANNOUNCEMENT CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="admin_announcements.css"
    >

</head>


<body>


<!-- =========================================================
     MOBILE MENU BUTTON
========================================================= -->

<button
    class="mobile-menu-btn"
    id="mobileMenuBtn"
    type="button"
    aria-label="Open navigation"
    aria-expanded="false"
    aria-controls="sidebar"
>
    <i class="fa-solid fa-bars"></i>
</button>


<!-- =========================================================
     SIDEBAR OVERLAY
========================================================= -->

<div
    class="sidebar-overlay"
    id="sidebarOverlay"
></div>


<!-- =========================================================
     ADMIN SIDEBAR
========================================================= -->

<aside
    class="sidebar"
    id="sidebar"
>

    <!-- =====================================================
         PROFILE
    ====================================================== -->

    <div class="sidebar-profile">

        <div class="profile-image">
            <img
                src="Katelogo.png"
                alt="Kate's Goodies Logo" style="height:auto; width:100%;"
            >
        </div>

        <div class="profile-info">

            <h3>Kate's Goodies</h3>

            <span>ADMINISTRATOR</span>

        </div>

    </div>


    <!-- =====================================================
         NAVIGATION
    ====================================================== -->

    <nav class="sidebar-nav" style="margin: 33px 0 -50px;">

        <!-- DASHBOARD -->
        <a
            href="admin_dashboard.php"
            class="sidebar-link"
            data-page="admin_dashboard.php"
            data-tooltip="Dashboard" 
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-house"></i>
            </span>

            <span class="sidebar-text">
                Dashboard
            </span>
        </a>


        <!-- MESSAGES -->
        <a
            href="admin_message.php"
            class="sidebar-link"
            data-page="admin_message.php"
            data-tooltip="Messages"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-message"></i>
            </span>

            <span class="sidebar-text">
                Messages
            </span>
        </a>


        <!-- PRODUCTS -->
        <a
            href="manage_products.php"
            class="sidebar-link"
            data-page="manage_products.php"
            data-tooltip="Products"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-box"></i>
            </span>

            <span class="sidebar-text">
                Products
            </span>
        </a>


        <!-- ORDERS -->
        <a
            href="manage_orders.php"
            class="sidebar-link"
            data-page="manage_orders.php"
            data-tooltip="Orders"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </span>

            <span class="sidebar-text">
                Orders
            </span>
        </a>


        <!-- PAYMENTS -->
        <a
            href="admin_payment.php"
            class="sidebar-link"
            data-page="admin_payment.php"
            data-tooltip="Payments"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-credit-card"></i>
            </span>

            <span class="sidebar-text">
                Payments
            </span>
        </a>


        <!-- ANNOUNCEMENTS -->
        <a
            href="admin_announcements.php"
            class="sidebar-link"
            data-page="admin_announcements.php"
            data-tooltip="Announcements"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-bullhorn"></i>
            </span>

            <span class="sidebar-text">
                Announcements
            </span>
        </a>


        <!-- USERS -->
        <a
            href="user.php"
            class="sidebar-link"
            data-page="user.php"
            data-tooltip="Users"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-users"></i>
            </span>

            <span class="sidebar-text">
                Users
            </span>
        </a>


        <!-- SETTINGS -->
        <a
            href="admin_settings.php"
            class="sidebar-link"
            data-page="admin_settings.php"
            data-tooltip="Settings"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-gear"></i>
            </span>

            <span class="sidebar-text">
                Settings
            </span>
        </a>

    </nav>


    <!-- =====================================================
         LOGOUT
    ====================================================== -->

    <div class="sidebar-bottom">

        <button
            class="logout-btn"
            id="logoutBtn"
            type="button"
            data-tooltip="Logout"
        >

            <span class="sidebar-icon">
                <i class="fa-solid fa-right-from-bracket"></i>
            </span>

            <span class="sidebar-text">
                Logout
            </span>

        </button>

    </div>

</aside>

<!-- =========================================================
     MAIN CONTENT
========================================================= -->

<main class="main">


    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <header class="page-header">

        <div>

            <span class="header-small">
                ADMIN PANEL
            </span>

            <h1>
                Announcements
            </h1>

            <p>
                Create and manage announcements for your customers.
            </p>

        </div>

    </header>


    <!-- =====================================================
         ANNOUNCEMENT PAGE
    ====================================================== -->

    <section class="announcement-page">


        <!-- =================================================
             SYSTEM MESSAGE
        ================================================== -->

        <?php if ($message !== ""): ?>

            <div
                class="system-message <?php echo htmlspecialchars($message_type); ?>"
                id="systemMessage"
            >

                <span>

                    <?php
                    echo htmlspecialchars($message);
                    ?>

                </span>


                <button
                    type="button"
                    class="message-close"
                    id="messageClose"
                    aria-label="Close message"
                >

                    <i class="fa-solid fa-xmark"></i>

                </button>

            </div>

        <?php endif; ?>


        <!-- =================================================
             TOP SECTION
        ================================================== -->

        <div class="top-section">


            <!-- =============================================
                 CREATE ANNOUNCEMENT
            ============================================== -->

            <section class="create-announcement">


                <div class="card-heading">

                    <div>

                        <span>
                            CREATE
                        </span>

                        <h2>
                            New Announcement
                        </h2>

                    </div>


                    <div class="heading-icon">

                        <i class="fa-solid fa-bullhorn"></i>

                    </div>

                </div>


                <!-- =========================================
                     FORM
                ========================================== -->

                <form
                    method="POST"
                    class="announcement-form"
                    id="announcementForm"
                >


                    <input
                        type="hidden"
                        name="action"
                        value="add"
                    >


                    <!-- TITLE -->

                    <div class="form-group full">

                        <label for="title">
                            Announcement Title
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            placeholder="Enter announcement title"
                            maxlength="255"
                            autocomplete="off"
                            required
                        >

                    </div>


                    <!-- TYPE -->

                    <div class="form-group">

                        <label for="type">
                            Announcement Type
                        </label>

                        <select
                            id="type"
                            name="type"
                        >

                            <option value="General">
                                General
                            </option>

                            <option value="Promotion">
                                Promotion
                            </option>

                            <option value="Important">
                                Important
                            </option>

                            <option value="Notice">
                                Notice
                            </option>

                            <option value="Update">
                                Update
                            </option>

                        </select>

                    </div>


                    <!-- STATUS -->

                    <div class="form-group">

                        <label>
                            Status
                        </label>

                        <div class="status-display">

                            <span class="status-dot"></span>

                            Published

                        </div>

                    </div>


                    <!-- MESSAGE -->

                    <div class="form-group full">

                        <label for="announcement_message">
                            Announcement Message
                        </label>

                        <textarea
                            id="announcement_message"
                            name="announcement_message"
                            placeholder="Write your announcement here..."
                            maxlength="2000"
                            required
                        ></textarea>


                        <div class="character-counter">

                            <span id="characterCount">
                                0
                            </span>

                            / 2000

                        </div>

                    </div>


                    <!-- BUTTON -->

                    <div class="form-actions">

                        <button
                            type="reset"
                            class="clear-btn"
                            id="clearBtn"
                        >

                            <i class="fa-solid fa-rotate-left"></i>

                            Clear

                        </button>


                        <button
                            type="submit"
                            class="publish-btn"
                        >

                            <i class="fa-solid fa-paper-plane"></i>

                            Create Announcement

                        </button>

                    </div>


                </form>

            </section>


            <!-- =============================================
                 RECENT POSTS
            ============================================== -->

            <aside class="recent-posts">


                <div class="card-heading">

                    <div>

                        <span>
                            UPDATES
                        </span>

                        <h2>
                            Recent Posts
                        </h2>

                    </div>


                    <div class="heading-icon">

                        <i class="fa-regular fa-clock"></i>

                    </div>

                </div>


                <div class="recent-posts-list">


                    <?php if (count($recent_posts) > 0): ?>


                        <?php foreach ($recent_posts as $recent): ?>


                            <?php

                            $recent_date =
                                $recent["created_at"] ?? "";

                            if ($recent_date !== "") {

                                $recent_formatted_date =
                                    date(
                                        "M d, Y",
                                        strtotime($recent_date)
                                    );

                            } else {

                                $recent_formatted_date =
                                    "N/A";

                            }

                            ?>


                            <article class="recent-post-item">


                                <div class="recent-post-icon">

                                    <i class="fa-solid fa-bullhorn"></i>

                                </div>


                                <div class="recent-post-content">


                                    <h3>

                                        <?php
                                        echo htmlspecialchars(
                                            $recent["title"]
                                        );
                                        ?>

                                    </h3>


                                    <p>

                                        <?php

                                        echo htmlspecialchars(
                                            $recent["message"]
                                        );

                                        ?>

                                    </p>


                                    <div class="recent-post-meta">


                                        <span
                                            class="recent-post-type"
                                        >

                                            <?php
                                            echo htmlspecialchars(
                                                $recent["type"]
                                            );
                                            ?>

                                        </span>


                                        <span
                                            class="recent-post-date"
                                        >

                                            <i
                                                class="fa-regular fa-calendar"
                                            ></i>

                                            <?php
                                            echo htmlspecialchars(
                                                $recent_formatted_date
                                            );
                                            ?>

                                        </span>


                                    </div>


                                </div>


                            </article>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <div class="recent-posts-empty">

                            <div class="empty-icon">

                                <i
                                    class="fa-regular fa-bell-slash"
                                ></i>

                            </div>

                            <h3>
                                No announcements yet
                            </h3>

                            <p>
                                Create your first announcement.
                            </p>

                        </div>


                    <?php endif; ?>


                </div>


            </aside>


        </div>


    </section>


</main>


<!-- =========================================================
     LOGOUT MODAL
========================================================= -->

<div
    class="logout-modal"
    id="logoutModal"
>


    <div class="logout-modal-content">


        <div class="logout-modal-icon">

            <i class="fa-solid fa-right-from-bracket"></i>

        </div>


        <h2>
            Logout
        </h2>


        <p>
            Are you sure you want to logout?
        </p>


        <div class="logout-buttons">


            <button
                type="button"
                class="logout-confirm"
                id="confirmLogout"
            >

                Yes, Logout

            </button>


            <button
                type="button"
                class="logout-cancel"
                id="cancelLogout"
            >

                Cancel

            </button>


        </div>


    </div>


</div>


<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script src="admin_announcements.js"></script>


</body>

</html>