<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Settings</title>

    <!-- GOOGLE FONT -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- FONT AWESOME -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <link
        rel="stylesheet"
        href="admin_settings.css"
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

    <span class="menu-lines">

        <span class="menu-line line-one"></span>

        <span class="menu-line line-two"></span>

        <span class="menu-line line-three"></span>

    </span>

</button>


<!-- =========================================================
     MOBILE OVERLAY
========================================================= -->

<div
    class="sidebar-overlay"
    id="sidebarOverlay"
    aria-hidden="true"
></div>


<!-- =========================================================
     SIDEBAR
========================================================= -->

<aside
    class="sidebar"
    id="sidebar"
    aria-label="Admin navigation"
>
    <button
        class="sidebar-close-btn"
        id="sidebarCloseBtn"
        type="button"
        aria-label="Close navigation"
    >
        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
    </button>


    <!-- =====================================================
         PROFILE
    ====================================================== -->

    <div class="sidebar-profile">

        <div class="profile-image">

            <img
                src="Katelogo.png"
                alt="Kate's Goodies Logo" style="border-radius:none; border:none; height:auto; width:100%;"
            >

        </div>


        <div class="profile-info">

            <h3>
                Kate's Goodies
            </h3>

            <span>
                ADMINISTRATOR
            </span>

        </div>

    </div>


    <!-- =====================================================
         NAVIGATION
    ====================================================== -->

    <nav class="sidebar-nav" style="margin: -39px 0 ;">


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


    <header class="settings-header">

        <div>

            <span class="header-small">
                ADMIN PANEL
            </span>

            <h1>
                Admin Settings
            </h1>

            <p>
                Manage your administrator account and system preferences.
            </p>

        </div>

    </header>


    <!-- =====================================================
         YOUR EXISTING SETTINGS CONTENT
    ====================================================== -->

    <section class="settings-grid">


        <!-- PROFILE SETTINGS -->

        <div class="settings-card">

            <div class="card-heading">

                <div class="card-icon">

                    <i class="fa-solid fa-user"></i>

                </div>

                <div>

                    <h2>
                        Profile Settings
                    </h2>

                    <p>
                        Update your administrator account information.
                    </p>

                </div>

            </div>


            <form
                method="POST"
                class="settings-form"
            >

                <div class="form-group">

                    <label for="name">
                        Full Name
                    </label>

                    <div class="input-wrapper">

                        <i class="fa-solid fa-user"></i>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="<?php echo htmlspecialchars($admin_name ?? "", ENT_QUOTES, "UTF-8"); ?>"
                            placeholder="Enter your full name"
                            required
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <div class="input-wrapper">

                        <i class="fa-solid fa-envelope"></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?php echo htmlspecialchars($admin_email ?? "", ENT_QUOTES, "UTF-8"); ?>"
                            placeholder="Enter your email"
                            required
                        >

                    </div>

                </div>


                <button
                    type="submit"
                    name="save_profile"
                    class="save-btn"
                >

                    <i class="fa-solid fa-floppy-disk"></i>

                    Save Changes

                </button>

            </form>

        </div>


        <!-- ACCOUNT INFORMATION -->

        <div class="settings-card">

            <div class="card-heading">

                <div class="card-icon">

                    <i class="fa-solid fa-id-card"></i>

                </div>

                <div>

                    <h2>
                        Account Information
                    </h2>

                    <p>
                        Information about your administrator account.
                    </p>

                </div>

            </div>


            <div class="information-list">

                <div class="information-row">

                    <div class="information-icon">

                        <i class="fa-solid fa-shield-halved"></i>

                    </div>

                    <div class="information-content">

                        <span>
                            Account Type
                        </span>

                        <strong>
                            Administrator
                        </strong>

                    </div>

                </div>


                <div class="information-row">

                    <div class="information-icon">

                        <i class="fa-solid fa-fingerprint"></i>

                    </div>

                    <div class="information-content">

                        <span>
                            Account ID
                        </span>

                        <strong>
                            <?php echo htmlspecialchars($admin_id ?? "Not available", ENT_QUOTES, "UTF-8"); ?>
                        </strong>

                    </div>

                </div>


                <div class="information-row">

                    <div class="information-icon">

                        <i class="fa-solid fa-circle-check"></i>

                    </div>

                    <div class="information-content">

                        <span>
                            Account Status
                        </span>

                        <strong class="status-active">
                            Active
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        <!-- SYSTEM PREFERENCES -->

        <div class="settings-card full-width">

            <div class="card-heading">

                <div class="card-icon">

                    <i class="fa-solid fa-sliders"></i>

                </div>

                <div>

                    <h2>
                        System Preferences
                    </h2>

                    <p>
                        Customize how the admin panel behaves.
                    </p>

                </div>

            </div>


            <div class="preference-list">


                <div class="preference-row">

                    <div class="preference-left">

                        <div class="preference-icon">

                            <i class="fa-solid fa-envelope"></i>

                        </div>

                        <div>

                            <h3>
                                Email Notifications
                            </h3>

                            <p>
                                Receive notifications about new orders and messages.
                            </p>

                        </div>

                    </div>


                    <label class="switch">

                        <input
                            type="checkbox"
                            id="emailNotifications"
                            checked
                        >

                        <span class="slider"></span>

                    </label>

                </div>


                <div class="preference-row">

                    <div class="preference-left">

                        <div class="preference-icon">

                            <i class="fa-solid fa-cart-shopping"></i>

                        </div>

                        <div>

                            <h3>
                                New Order Alerts
                            </h3>

                            <p>
                                Show an alert when a new customer order is received.
                            </p>

                        </div>

                    </div>


                    <label class="switch">

                        <input
                            type="checkbox"
                            id="orderAlerts"
                            checked
                        >

                        <span class="slider"></span>

                    </label>

                </div>


                <div class="preference-row">

                    <div class="preference-left">

                        <div class="preference-icon">

                            <i class="fa-solid fa-message"></i>

                        </div>

                        <div>

                            <h3>
                                Message Notifications
                            </h3>

                            <p>
                                Notify the administrator when customers send messages.
                            </p>

                        </div>

                    </div>


                    <label class="switch">

                        <input
                            type="checkbox"
                            id="messageNotifications"
                            checked
                        >

                        <span class="slider"></span>

                    </label>

                </div>


                <div class="preference-row">

                    <div class="preference-left">

                        <div class="preference-icon">

                            <i class="fa-solid fa-moon"></i>

                        </div>

                        <div>

                            <h3>
                                Dark Mode
                            </h3>

                            <p>
                                Change the appearance of the admin settings page.
                            </p>

                        </div>

                    </div>


                    <label class="switch">

                        <input
                            type="checkbox"
                            id="darkMode"
                        >

                        <span class="slider"></span>

                    </label>

                </div>


            </div>

        </div>


        <!-- DANGER ZONE -->

        <div class="settings-card danger-zone full-width">

            <div class="card-heading">

                <div class="danger-icon">

                    <i class="fa-solid fa-triangle-exclamation"></i>

                </div>

                <div>

                    <h2>
                        Danger Zone
                    </h2>

                    <p>
                        Be careful when performing account-related actions.
                    </p>

                </div>

            </div>


            <div class="danger-content">

                <div>

                    <h3>
                        Logout from your account
                    </h3>

                    <p>
                        End your current administrator session.
                    </p>

                </div>


                <button
                    type="button"
                    class="danger-btn"
                    id="logoutAllBtn"
                >

                    <i class="fa-solid fa-right-from-bracket"></i>

                    Logout Account

                </button>

            </div>

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


<script src="admin_settings.js"></script>

</body>

</html>