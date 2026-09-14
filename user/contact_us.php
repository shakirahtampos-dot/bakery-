<?php
/* =========================================================
   KATES GOODIES
   CONTACT US PAGE
========================================================= */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$brandName = $brandName ?? "Kates Goodies";
$fullname  = $fullname ?? ($_SESSION["fullname"] ?? "Guest User");
$role      = $role ?? ($_SESSION["role"] ?? "Customer");
$username  = $username ?? ($_SESSION["username"] ?? "Guest");
$email     = $email ?? ($_SESSION["email"] ?? "Not available");

$messages = $messages ?? [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&display=swap"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css?family=Poppins"
        rel="stylesheet"
    >

    <!-- CSS -->
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="contact_us.css">
    <link rel="stylesheet" href="menu.css">

    <title>
        <?php echo htmlspecialchars($brandName); ?> - Contact Us
    </title>
</head>

<body>

<!-- =========================================================
     NAVIGATION BAR
========================================================= -->

<header class="navbar">

    <!-- LOGO -->
    <div class="logo">
        <a href="home.php">
            <img src="Katelogo.png" alt="Kates Goodies">
        </a>
    </div>

    <!-- MOBILE BURGER BUTTON -->
    <button
        type="button"
        class="nav-toggle"
        id="navToggle"
        aria-label="Open navigation"
        aria-expanded="false"
        aria-controls="mainNav"
    >
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </button>

    <!-- NAVIGATION MENU -->
    <nav id="mainNav">

        <!-- MOBILE CLOSE (X) BUTTON -->
        <button
            type="button"
            class="nav-close"
            id="navClose"
            aria-label="Close navigation"
        >
            &times;
        </button>

        <a href="home.php" style="font-weight: 600;">
            HOME
        </a>

        <a href="menu.php" style="font-weight: 600;">
            MENU
        </a>

        <a href="order.php" style="font-weight: 600;">
            MY ORDERS
        </a>

        <a href="about_us.php" style="font-weight: 600;">
            ABOUT US
        </a>

        <a
            href="contact_us.php"
            class="active"
            style="font-weight: 600;"
        >
            CONTACT US
        </a>

    </nav>

    <!-- MOBILE OVERLAY -->
    <div
        class="nav-overlay"
        id="navOverlay"
        aria-hidden="true"
    ></div>

    <!-- NAVBAR ACTIONS -->
    <div class="navbar-actions">

        <!-- CART -->
        <a
            href="cart.php"
            class="icon-button cart-button"
            aria-label="Shopping cart"
            title="Shopping Cart"
        >
            <span>🛒</span>
            <span class="cart-count" id="cartCount">0</span>
        </a>

        <!-- ACCOUNT -->
        <div class="account-container">

            <button
                type="button"
                class="account-button"
                id="accountButton"
                aria-expanded="false"
                aria-controls="accountDropdown"
                aria-label="Personal account"
            >
                <span class="account-icon">👤</span>
                
            </button>

            <!-- ACCOUNT DROPDOWN -->
            <div
                class="account-dropdown"
                id="accountDropdown"
            >

                <div class="account-title">

                    <div class="account-icon-small">
                        👤
                    </div>

                    <div>
                        <strong>
                            <?php echo htmlspecialchars($fullname); ?>
                        </strong>

                        <small>
                            <?php echo htmlspecialchars($role); ?>
                        </small>
                    </div>

                </div>

                <div class="account-divider"></div>

                <div class="account-info">

                    <p>
                        <strong>Username:</strong>
                        <?php echo htmlspecialchars($username); ?>
                    </p>

                    <p>
                        <strong>Email:</strong>
                        <?php echo htmlspecialchars($email); ?>
                    </p>

                </div>

                <div class="account-divider"></div>

                <a href="logout.php" class="logout-button" style= "display: flex; align-items: center;gap: 8px;">
    <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
    <span class="account-icon" style="font-size: 0.8rem;">Logout</span>
</a>

            </div>

        </div>

    </div>

</header>


<!-- =========================================================
     CONTACT HERO
========================================================= -->

<section class="contact-hero">

    <span class="section-label">
        GET IN TOUCH
    </span>

    <h1>
        Contact Us
    </h1>

    <p>
        We'd love to hear from you!
    </p>

</section>


<!-- =========================================================
     CONTACT INFORMATION
========================================================= -->

<section class="contact-info-section">

    <div class="contact-info-heading">

        <span class="section-label">
            REACH OUT
        </span>

        <h2>
            Get In Touch
        </h2>

    </div>

    <div class="contact-info-grid">

        <div class="contact-info-card">

            <div class="contact-info-icon">
                📍
            </div>

            <h3>
                Address
            </h3>

            <p>
                Kate's Goodies, Dumaguete City
            </p>

        </div>

        <div class="contact-info-card">

            <div class="contact-info-icon">
                📞
            </div>

            <h3>
                Contact Number
            </h3>

            <p>
                09XX XXX XXXX
            </p>

        </div>

        <div class="contact-info-card">

            <div class="contact-info-icon">
                ✉️
            </div>

            <h3>
                Email
            </h3>

            <p>
                katesgoodies@email.com
            </p>

        </div>

        <div class="contact-info-card">

            <div class="contact-info-icon">
                🕒
            </div>

            <h3>
                Business Hours
            </h3>

            <p>
                Monday – Saturday
                <br>
                9:00 AM – 6:00 PM
            </p>

        </div>

    </div>

</section>


<!-- =========================================================
     CONTACT FORM
========================================================= -->

<section class="contact-form-section">

    <div class="contact-form-heading">

        <span class="section-label">
            DROP US A LINE
        </span>

        <h2>
            Send Us a Message
        </h2>

        <p>
            Have a question, feedback, or a custom order in mind?
            Fill out the form below and we'll get back to you
            as soon as we can.
        </p>

    </div>

    <form
        class="contact-form"
        id="contactForm"
        novalidate
    >

        <div class="form-row">

            <div class="form-group">

                <label for="contactName">
                    Name
                </label>

                <input
                    type="text"
                    id="contactName"
                    name="name"
                    placeholder="Your full name"
                    required
                >

                <span
                    class="form-error"
                    id="nameError"
                ></span>

            </div>

            <div class="form-group">

                <label for="contactEmail">
                    Email
                </label>

                <input
                    type="email"
                    id="contactEmail"
                    name="email"
                    placeholder="you@example.com"
                    required
                >

                <span
                    class="form-error"
                    id="emailError"
                ></span>

            </div>

        </div>

        <div class="form-group">

            <label for="contactSubject">
                Subject
            </label>

            <input
                type="text"
                id="contactSubject"
                name="subject"
                placeholder="What is this about?"
                required
            >

            <span
                class="form-error"
                id="subjectError"
            ></span>

        </div>

        <div class="form-group">

            <label for="contactMessage">
                Message
            </label>

            <textarea
                id="contactMessage"
                name="message"
                rows="6"
                placeholder="Write your message here..."
                required
            ></textarea>

            <span
                class="form-error"
                id="messageError"
            ></span>

        </div>

        <div class="form-submit">

            <button
                type="submit"
                class="send-message-button"
            >
                SEND MESSAGE
            </button>

            <p
                class="form-status"
                id="formStatus"
            ></p>

        </div>

    </form>

</section>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer id="contact">

    <div class="footer-content">

        <div class="footer-brand">

            <img
                src="Katelogo.png"
                alt="Kates Goodies"
            >

            <p>
                Freshly baked every morning with quality
                ingredients and plenty of love.
            </p>

            <p>
                The bakery that feels like home.
            </p>

        </div>

        <div>

            <h3>
                Explore
            </h3>

            <a href="home.php">Home</a>
            <a href="menu.php">Menu</a>
            <a href="order.php">My Orders</a>
            <a href="about_us.php">About Us</a>
            <a href="contact_us.php">Contact Us</a>

        </div>

        <div>

            <h3>
                Categories
            </h3>

            <a href="menu.php?category=cookies">
                Cookies
            </a>

            <a href="menu.php?category=cakes">
                Cakes
            </a>

            <a href="menu.php?category=cupcakes">
                Cupcakes
            </a>

            <a href="menu.php?category=pastries">
                Pastries
            </a>

        </div>

        <div>

            <h3>
                Contact Us
            </h3>

            <p>
                Dumaguete City
            </p>

            <p>
                Monday - Saturday
            </p>

            <p>
                7:00 AM - 6:00 PM
            </p>

            <a href="contact_us.php">
                Send us a message
            </a>

        </div>

    </div>

    <div class="footer-bottom">

        <span>
            © <?php echo date("Y"); ?>
            <?php echo htmlspecialchars($brandName); ?>.
            All rights reserved.
        </span>

        <span>
            Fresh · Sweet · Homemade
        </span>

    </div>

</footer>


<!-- JAVASCRIPT -->
<script src="home.js"></script>
<script src="contact_us.js"></script>
<script src="menu.js"></script>

</body>
</html>