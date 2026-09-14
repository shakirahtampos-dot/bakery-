<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Croissant One' rel='stylesheet'>  
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="checkout.css">
    <title><?php echo htmlspecialchars($brandName); ?> - Checkout</title>



</head>

<body>

<!-- =========================================================
     NAVIGATION BAR
========================================================= -->

<header class="navbar">

    <!-- LOGO -->
    <div class="logo">

        <a href="home_index.php">

            <img src="Katelogo.png" alt="Kates Goodies">

        </a>

    </div>


    <!-- MOBILE MENU BUTTON -->

    <button
        type="button"
        class="nav-toggle"
        id="navToggle"
        aria-label="Open navigation"
        aria-expanded="false"
    >

        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>

    </button>


    <!-- NAVIGATION -->

    <nav id="mainNav">

        <button
            type="button"
            class="nav-close"
            id="navClose"
            aria-label="Close navigation"
        >
            &times;
        </button>

        <a href="home.php" style="font-weight: 600;">HOME</a>

        <a href="menu.php" style="font-weight: 600;">MENU</a>

        <a href="order.php" style="font-weight: 600;">MY ORDERS</a>

        <a href="about_us.php" style="font-weight: 600;">ABOUT US</a>

        <a href="contact_us.php" style="font-weight: 600;">CONTACT US</a>

    </nav>


    <!-- MOBILE OVERLAY -->

    <div class="nav-overlay" id="navOverlay"></div>


    <!-- NAVBAR ICONS -->

    <div class="navbar-actions">

        <!-- ACCOUNT -->

        <div class="account-container">

            <button
                type="button"
                class="account-button"
                id="accountButton"
                aria-expanded="false"
                aria-label="Personal account"
            >

                <span class="account-icon">👤</span>

            </button>


            <!-- ACCOUNT DROPDOWN -->

            <div class="account-dropdown" id="accountDropdown">

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


                <a href="logout.php"
                class="logout-button">

                    Logout
                </a>

            </div>

        </div>

    </div>

</header>


<!-- =========================================================
     CHECKOUT
========================================================= -->

<section class="checkout-section">

    <div class="checkout-header">

        <span class="checkout-brand">
            Kate's Goodies
        </span>

        <h1>
            Complete your order
        </h1>

        <p class="checkout-subtitle">
            Just a few details and your treats will be
            on their way.
        </p>

    </div>


    <form class="checkout-form" id="checkoutForm">

        <div class="checkout-layout">

            <!-- LEFT COLUMN -->

            <div class="checkout-main">

                <!-- CUSTOMER INFORMATION -->

                <div class="checkout-block">

                    <h2 class="checkout-block-title">
                        <span class="step-marker">1</span>
                        Customer Information
                    </h2>

                    <div class="form-group">

                        <label for="fullName">
                            Full Name
                        </label>

                        <input
                            type="text"
                            id="fullName"
                            name="fullName"
                            placeholder="Enter your full name"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="contactNumber">
                            Contact Number
                        </label>

                        <input
                            type="tel"
                            id="contactNumber"
                            name="contactNumber"
                            placeholder="Enter your contact number"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="address">
                            Address
                        </label>

                        <input
                            type="text"
                            id="address"
                            name="address"
                            placeholder="Enter your delivery address"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="orderNotes">
                            Order Notes
                        </label>

                        <textarea
                            id="orderNotes"
                            name="orderNotes"
                            rows="3"
                            placeholder="Any special instructions? (optional)"
                        ></textarea>

                    </div>

                </div>


                <!-- DELIVERY OPTION -->

                <div class="checkout-block">

                    <h2 class="checkout-block-title">
                        <span class="step-marker">2</span>
                        Delivery Option
                    </h2>

                    <div class="tile-group">

                        <label class="option-tile">

                            <input
                                type="radio"
                                name="deliveryOption"
                                value="Home Delivery"
                                checked
                            >

                            <span class="tile-icon">🛵</span>

                            <span class="tile-text">

                                <span class="tile-title">
                                    Home Delivery
                                </span>

                                <span class="tile-note">
                                    Delivered to your door
                                </span>

                            </span>

                            <span class="tile-check">✓</span>

                        </label>

                        <label class="option-tile">

                            <input
                                type="radio"
                                name="deliveryOption"
                                value="Store Pickup"
                            >

                            <span class="tile-icon">🏬</span>

                            <span class="tile-text">

                                <span class="tile-title">
                                    Store Pickup
                                </span>

                                <span class="tile-note">
                                    Skip the delivery fee
                                </span>

                            </span>

                            <span class="tile-check">✓</span>

                        </label>

                    </div>

                </div>


                <!-- PAYMENT METHOD -->

                <div class="checkout-block">

                    <h2 class="checkout-block-title">
                        <span class="step-marker">3</span>
                        Payment Method
                    </h2>

                    <div class="tile-group tile-group-compact">

                        <label class="option-tile option-tile-compact">

                            <input
                                type="radio"
                                name="paymentMethod"
                                value="Cash on Delivery"
                                checked
                            >

                            <span class="tile-icon">💵</span>

                            <span class="tile-title">
                                Cash
                            </span>

                            <span class="tile-check">✓</span>

                        </label>

                        <label class="option-tile option-tile-compact">

                            <input
                                type="radio"
                                name="paymentMethod"
                                value="GCash"
                            >

                            <span class="tile-icon">📱</span>

                            <span class="tile-title">
                                GCash
                            </span>

                            <span class="tile-check">✓</span>

                        </label>

                        <label class="option-tile option-tile-compact">

                            <input
                                type="radio"
                                name="paymentMethod"
                                value="PayPal"
                            >

                            <span class="tile-icon">🅿️</span>

                            <span class="tile-title">
                                PayPal
                            </span>

                            <span class="tile-check">✓</span>

                        </label>

                        <label class="option-tile option-tile-compact">

                            <input
                                type="radio"
                                name="paymentMethod"
                                value="Maya"
                            >

                            <span class="tile-icon">🍃</span>

                            <span class="tile-title">
                                Maya
                            </span>

                            <span class="tile-check">✓</span>

                        </label>

                    </div>

                </div>

            </div>


            <!-- RIGHT COLUMN -->

            <div class="checkout-sidebar">

                <div class="receipt">

                    <div class="receipt-hole receipt-hole-left"></div>
                    <div class="receipt-hole receipt-hole-right"></div>

                    <div class="receipt-top">

                        <span class="receipt-brand">
                            Kate's Goodies
                        </span>

                        <span class="receipt-label">
                            Order Ticket
                        </span>

                    </div>

                    <div class="receipt-rule"></div>

                    <div class="order-items" id="orderItems">

                        <div class="order-item">

                            <span class="order-item-name">
                                Chocolate Chip Cookies
                                <span class="order-item-qty">×2</span>
                            </span>

                            <span class="order-item-price">
                                ₱200
                            </span>

                        </div>

                        <div class="order-item">

                            <span class="order-item-name">
                                Red Velvet Cupcake
                                <span class="order-item-qty">×1</span>
                            </span>

                            <span class="order-item-price">
                                ₱120
                            </span>

                        </div>

                        <div class="order-item">

                            <span class="order-item-name">
                                Chocolate Cake
                                <span class="order-item-qty">×1</span>
                            </span>

                            <span class="order-item-price">
                                ₱450
                            </span>

                        </div>

                    </div>


                    <div class="receipt-rule receipt-rule-dashed"></div>


                    <div class="order-totals">

                        <div class="order-total-row">

                            <span>
                                Subtotal
                            </span>

                            <span id="subtotalValue">
                                ₱770
                            </span>

                        </div>

                        <div class="order-total-row">

                            <span>
                                Delivery Fee
                            </span>

                            <span id="deliveryFeeValue">
                                ₱50
                            </span>

                        </div>

                    </div>

                    <div class="receipt-rule"></div>

                    <div class="order-total-row order-grand-total">

                        <span>
                            Total
                        </span>

                        <span id="totalValue">
                            ₱820
                        </span>

                    </div>


                    <button
                        type="submit"
                        class="place-order-button"
                    >
                        Place Order
                    </button>

                    <div class="receipt-edge"></div>

                </div>

            </div>

        </div>

    </form>

</section>


<!-- =========================================================
     ORDER RECEIPT MODAL (pop up after Place Order)
========================================================= -->

<div
    class="info-modal"
    id="orderConfirmModal"
    aria-hidden="true"
>

    <div
        class="info-modal-backdrop"
        data-close-confirm
    ></div>


    <div class="info-modal-content confirm-modal-content">

        <button
            type="button"
            class="modal-close"
            data-close-confirm
            aria-label="Close receipt"
        >
            &times;
        </button>

        <!--
            The full generated receipt (order number, date,
            customer info, items, totals, print/done buttons)
            is injected here by checkout.js right after the
            order is placed.
        -->

        <div id="generatedReceipt"></div>

    </div>

</div>


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

            <a href="home.php">
                Home
            </a>

            <a href="menu.php">
                Menu
            </a>

            <a href="order.php">
                My Orders
            </a>

            <a href="aboutus.php">
                About Us
            </a>

             <a href="contact_us.php">
                Contact Us
            </a>

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


<script src="home.js"></script>
<script src="checkout.js"></script>

</body>

</html>