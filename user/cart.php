<?php

session_start();

/* =========================================================
   KATES GOODIES
   CART PAGE
========================================================= */

/* =========================================================
   ACCOUNT INFORMATION
========================================================= */

$brandName = $_SESSION['brandName'] ?? "Kates Goodies";
$fullname  = $_SESSION['fullname'] ?? $_SESSION['name'] ?? "Guest User";
$username  = $_SESSION['username'] ?? "Guest";
$email     = $_SESSION['email'] ?? "guest@example.com";
$role      = $_SESSION['role'] ?? "Customer";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?php echo htmlspecialchars($brandName); ?> - My Cart
    </title>

    <!-- =====================================================
         GOOGLE FONTS
    ====================================================== -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

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

    <link
        href="https://fonts.googleapis.com/css?family=Croissant+One"
        rel="stylesheet"
    >

    <!-- FONT AWESOME -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <!-- CART CSS -->

    <link
        rel="stylesheet"
        href="cart.css"
    >

</head>


<body>


<!-- =========================================================
     NAVIGATION BAR
========================================================= -->

<header class="navbar">


    <!-- =====================================================
         LOGO
    ====================================================== -->

    <div class="logo">

        <a href="home.php">

            <img
                src="Katelogo.png"
                alt="Kates Goodies"
            >

        </a>

    </div>


    <!-- =====================================================
         MOBILE BURGER BUTTON
    ====================================================== -->

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


    <!-- =====================================================
         MAIN NAVIGATION
    ====================================================== -->

    <nav id="mainNav">

        <!-- CLOSE BUTTON -->

        <button
            type="button"
            class="nav-close"
            id="navClose"
            aria-label="Close navigation"
        >

            &times;

        </button>


        <a
            href="home.php"
            style="font-weight: 600;"
        >
            HOME
        </a>


        <a
            href="menu.php"
            style="font-weight: 600;"
        >
            MENU
        </a>


        <a
            href="order.php"
            style="font-weight: 600;"
        >
            MY ORDERS
        </a>


        <a
            href="about_us.php"
            style="font-weight: 600;"
        >
            ABOUT US
        </a>


        <a
            href="contact_us.php"
            style="font-weight: 600;"
        >
            CONTACT US
        </a>


        <!-- =================================================
             ACCOUNT
             THIS IS MOVED HERE ON MOBILE BY JAVASCRIPT
        ================================================== -->

        <div class="mobile-account-placeholder" id="mobileAccountPlaceholder"></div>

    </nav>


    <!-- =====================================================
         MOBILE OVERLAY
    ====================================================== -->

    <div
        class="nav-overlay"
        id="navOverlay"
    ></div>


    <!-- =====================================================
         NAVBAR ACTIONS
    ====================================================== -->

    <div class="navbar-actions">


        <!-- =================================================
             CART
        ================================================== -->

        <a
            href="cart.php"
            class="icon-button cart-button"
            aria-label="Shopping cart"
            title="Shopping Cart"
        >

            <span>🛒</span>

            <span
                class="cart-count"
                id="cartCount"
            >
                0
            </span>

        </a>


        <!-- =================================================
             ACCOUNT
        ================================================== -->

        <div
            class="account-container"
            id="accountContainer"
        >


            <button
                type="button"
                class="account-button"
                id="accountButton"
                aria-expanded="false"
                aria-label="Personal account"
            >

                <span class="account-icon">
                    👤
                </span>

            </button>


            <!-- =============================================
                 ACCOUNT DROPDOWN
            ============================================== -->

            <div
                class="account-dropdown"
                id="accountDropdown"
            >


                <div class="account-title">


                    <div class="account-icon-small">

                        <i class="fa-solid fa-user"></i>

                    </div>


                    <div>

                        <strong>

                            <?php
                            echo htmlspecialchars(
                                $fullname,
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                        </strong>


                        <small>

                            <?php
                            echo htmlspecialchars(
                                $role,
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                        </small>

                    </div>


                </div>


                <div class="account-divider"></div>


                <div class="account-info">


                    <p>

                        <strong>
                            Username:
                        </strong>

                        <?php
                        echo htmlspecialchars(
                            $username,
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>

                    </p>


                    <p>

                        <strong>
                            Email:
                        </strong>

                        <?php
                        echo htmlspecialchars(
                            $email,
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>

                    </p>


                </div>


                <div class="account-divider"></div>


                <a
                    href="logout.php"
                    class="logout-button"
                >

                    <i class="fa-solid fa-right-from-bracket"></i>

                    <span>
                        Logout
                    </span>

                </a>


            </div>


        </div>


    </div>


</header>



<!-- =========================================================
     CART PAGE
========================================================= -->

<main class="cart-page">


    <!-- =====================================================
         CART HEADER
    ====================================================== -->

    <section class="cart-header">


        <span class="section-label">
            KATE'S GOODIES
        </span>


        <h1>
            My Cart
        </h1>


        <p>
            Review your selected treats before checking out.
        </p>


    </section>



    <!-- =====================================================
         CART CONTENT
    ====================================================== -->

    <section class="cart-layout">


        <!-- =================================================
             CART ITEMS
        ================================================== -->

        <div class="cart-items-section">


            <div class="cart-items-header">

                <h2>
                    Your Items
                </h2>

                <span>
                    Freshly baked with love
                </span>

            </div>



            <!-- =================================================
                 CART ITEM 1
            ================================================== -->

            <article
                class="cart-item"
                data-price="120"
            >


                <div class="cart-item-image">

                    <img
                        src="images/chocolate-chip-cookie.jpg"
                        alt="Chocolate Chip Cookies"
                    >

                </div>


                <div class="cart-item-details">


                    <span class="cart-item-category">
                        COOKIES
                    </span>


                    <h3>
                        Chocolate Chip Cookies
                    </h3>


                    <p>
                        Soft, chewy cookies filled with delicious
                        chocolate chips.
                    </p>


                    <strong class="cart-item-price">
                        ₱120
                    </strong>


                </div>


                <div class="cart-item-actions">


                    <div class="quantity-control">


                        <button
                            type="button"
                            class="quantity-btn decrease"
                            aria-label="Decrease quantity"
                        >
                            −
                        </button>


                        <span class="quantity">
                            1
                        </span>


                        <button
                            type="button"
                            class="quantity-btn increase"
                            aria-label="Increase quantity"
                        >
                            +
                        </button>


                    </div>


                    <button
                        type="button"
                        class="remove-btn"
                    >

                        <i class="fa-solid fa-trash"></i>

                        Remove

                    </button>


                </div>


            </article>



            <!-- =================================================
                 CART ITEM 2
            ================================================== -->

            <article
                class="cart-item"
                data-price="150"
            >


                <div class="cart-item-image">

                    <img
                        src="images/red-velvet-cupcake.jpg"
                        alt="Red Velvet Cupcake"
                    >

                </div>


                <div class="cart-item-details">


                    <span class="cart-item-category">
                        CUPCAKES
                    </span>


                    <h3>
                        Red Velvet Cupcake
                    </h3>


                    <p>
                        Moist red velvet cupcake topped with
                        creamy frosting.
                    </p>


                    <strong class="cart-item-price">
                        ₱150
                    </strong>


                </div>


                <div class="cart-item-actions">


                    <div class="quantity-control">


                        <button
                            type="button"
                            class="quantity-btn decrease"
                            aria-label="Decrease quantity"
                        >
                            −
                        </button>


                        <span class="quantity">
                            1
                        </span>


                        <button
                            type="button"
                            class="quantity-btn increase"
                            aria-label="Increase quantity"
                        >
                            +
                        </button>


                    </div>


                    <button
                        type="button"
                        class="remove-btn"
                    >

                        <i class="fa-solid fa-trash"></i>

                        Remove

                    </button>


                </div>


            </article>



            <!-- =================================================
                 CART ITEM 3
            ================================================== -->

            <article
                class="cart-item"
                data-price="350"
            >


                <div class="cart-item-image">

                    <img
                        src="images/chocolate-cake.jpg"
                        alt="Classic Chocolate Cake"
                    >

                </div>


                <div class="cart-item-details">


                    <span class="cart-item-category">
                        CAKES
                    </span>


                    <h3>
                        Classic Chocolate Cake
                    </h3>


                    <p>
                        Rich and moist chocolate cake perfect
                        for every celebration.
                    </p>


                    <strong class="cart-item-price">
                        ₱350
                    </strong>


                </div>


                <div class="cart-item-actions">


                    <div class="quantity-control">


                        <button
                            type="button"
                            class="quantity-btn decrease"
                            aria-label="Decrease quantity"
                        >
                            −
                        </button>


                        <span class="quantity">
                            1
                        </span>


                        <button
                            type="button"
                            class="quantity-btn increase"
                            aria-label="Increase quantity"
                        >
                            +
                        </button>


                    </div>


                    <button
                        type="button"
                        class="remove-btn"
                    >

                        <i class="fa-solid fa-trash"></i>

                        Remove

                    </button>


                </div>


            </article>



            <!-- =================================================
                 EMPTY CART
            ================================================== -->

            <div
                class="empty-cart"
                id="emptyCart"
            >


                <div class="empty-cart-icon">
                    🛒
                </div>


                <h2>
                    Your cart is empty
                </h2>


                <p>
                    Looks like you haven't added any goodies yet.
                </p>


                <a
                    href="menu.php"
                    class="continue-shopping"
                >
                    Browse Menu
                </a>


            </div>


        </div>



        <!-- =================================================
             ORDER SUMMARY
        ================================================== -->

        <aside class="cart-summary">


            <div class="summary-card">


                <span class="summary-label">
                    ORDER SUMMARY
                </span>


                <h2>
                    Your Order
                </h2>


                <div class="summary-divider"></div>


                <div class="summary-row">

                    <span>
                        Subtotal
                    </span>

                    <strong id="subtotal">
                        ₱620
                    </strong>

                </div>


                <div class="summary-row">

                    <span>
                        Delivery Fee
                    </span>

                    <strong id="deliveryFee">
                        ₱50
                    </strong>

                </div>


                <div class="summary-divider"></div>


                <div class="summary-total">

                    <span>
                        Total
                    </span>

                    <strong id="total">
                        ₱670
                    </strong>

                </div>


                <button
                    type="button"
                    class="checkout-button"
                    id="checkoutButton"
                >

                    Proceed to Checkout

                    

                </button>


                <a
                    href="menu.php"
                    class="continue-link"
                >

                     Continue Shopping

                </a>


                <div class="secure-message">

                    <i class="fa-solid fa-lock"></i>

                    Secure and convenient ordering

                </div>


            </div>


        </aside>


    </section>


</main>



<!-- =========================================================
     REMOVE MODAL
========================================================= -->

<div
    class="remove-modal"
    id="removeModal"
    aria-hidden="true"
>


    <div
        class="remove-modal-backdrop"
        id="removeModalBackdrop"
    ></div>


    <div class="remove-modal-content">


        <button
            type="button"
            class="modal-close"
            id="removeModalClose"
            aria-label="Close"
        >

            &times;

        </button>


        <div class="modal-icon">
            🗑️
        </div>


        <h3>
            Remove Item?
        </h3>


        <p>
            Are you sure you want to remove this item
            from your cart?
        </p>


        <div class="modal-actions">


            <button
                type="button"
                class="cancel-remove"
                id="cancelRemove"
            >
                Cancel
            </button>


            <button
                type="button"
                class="confirm-remove"
                id="confirmRemove"
            >
                Remove
            </button>


        </div>


    </div>


</div>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer>


    <div class="footer-content">


        <!-- BRAND -->

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


        <!-- EXPLORE -->

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


            <a href="about_us.php">
                About Us
            </a>


            <a href="contact_us.php">
                Contact Us
            </a>


        </div>


        <!-- CATEGORIES -->

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


        <!-- CONTACT -->

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

            ©
            <?php echo date("Y"); ?>

            <?php
            echo htmlspecialchars(
                $brandName,
                ENT_QUOTES,
                'UTF-8'
            );
            ?>.

            All rights reserved.

        </span>


        <span>
            Fresh · Sweet · Homemade
        </span>


    </div>


</footer>



<!-- =========================================================
     CART JAVASCRIPT
========================================================= -->

<script src="cart.js"></script>


</body>

</html>