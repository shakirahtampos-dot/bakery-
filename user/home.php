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
| SAFE OUTPUT
|--------------------------------------------------------------------------
*/

$fullname = e($fullname);
$username = e($username);
$email    = e($email);
$role     = e($role);


/*
|--------------------------------------------------------------------------
| BRAND NAME FALLBACK
|--------------------------------------------------------------------------
*/

$brandName = $brandName ?? "Kates Goodies";

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
        content="Kates Goodies - Freshly baked cookies, cakes, cupcakes and pastries."
    >

    <!-- GOOGLE FONTS -->

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
        href="https://fonts.googleapis.com/css?family=Croissant+One"
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

    <!-- HOME CSS -->

    <link
        rel="stylesheet"
        href="home.css"
    >

    <link
        rel="stylesheet"
        href="menu.css"
    >

    <title>
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
     NAVIGATION BAR
========================================================= -->

<header class="navbar">


    <!-- LOGO -->

    <div class="logo">

        <a href="home.php">

            <img
                src="Katelogo.png"
                alt="Kates Goodies"
            >

        </a>

    </div>


    <!-- MOBILE MENU BUTTON -->

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


    <!-- NAVIGATION -->

    <nav id="mainNav">


        <!-- CLOSE BUTTON -->

        <button
            type="button"
            class="nav-close"
            id="navClose"
            aria-label="Close navigation"
        >

            <span class="x-bar"></span>
            <span class="x-bar"></span>

        </button>


        <a
            href="home.php"
            class="active"
        >
            HOME
        </a>


        <a href="menu.php">
            MENU
        </a>


        <a href="order.php">
            MY ORDERS
        </a>


        <a href="about_us.php">
            ABOUT US
        </a>


        <a href="contact_us.php">
            CONTACT US
        </a>


    </nav>


    <!-- MOBILE OVERLAY -->

    <div
        class="nav-overlay"
        id="navOverlay"
    ></div>


    <!-- =====================================================
         NAVBAR ACTIONS
    ====================================================== -->

    <div class="navbar-actions">


        <!-- =================================================
             NOTIFICATION BUTTON
        ================================================== -->

        <a
            href="notifications.php"
            class="icon-button notification-button"
            id="notificationButton"
            aria-label="Notifications"
            title="Notifications"
        >

            <i style="font-style:none;">
               🔔
            </i>


            <span
                class="notification-count"
                id="notificationCount"
            >
                0
            </span>

        </a>


        <!-- =================================================
             CART
        ================================================== -->

        <a
            href="cart.php"
            class="icon-button cart-button"
            aria-label="Shopping cart"
            title="Shopping Cart"
        >

            <i>🛒
                
            </i>


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

        <div class="account-container">


            <button
                type="button"
                class="account-button"
                id="accountButton"
                aria-expanded="false"
                aria-label="Personal account"
            >

                <i class="icon-user">
                    👤
                </i>

                <span class="account-logout-label"></span>

                <span class="account-label"></span>

            </button>


            <!-- ACCOUNT DROPDOWN -->

            <div
                class="account-dropdown"
                id="accountDropdown"
            >


                <div class="account-title">


                    <div class="account-icon-small">

                        <span>
                            👤
                        </span>

                    </div>


                    <div>

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
                            "UTF-8"
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
                            "UTF-8"
                        );
                        ?>

                    </p>

                </div>


                <div class="account-divider"></div>


                <a
                    href="logout.php"
                    class="logout-button"
                >

                    <i
                        class="fa-solid fa-right-from-bracket"
                        aria-hidden="true"
                    ></i>

                    <span>
                        Logout
                    </span>

                </a>


            </div>

        </div>


    </div>


</header>


<!-- =========================================================
     HERO
========================================================= -->

<section
    id="home"
    class="hero"
>

    <div class="container hero-container">


        <div class="hero-text">

            <div class="hero-small-title">
                WELCOME TO KATES GOODIES
            </div>


            <h1>

                Freshly Baked.

                <br>

                <span>
                    Made With Love.
                </span>

            </h1>


            <p>

                Delicious cookies, cakes, cupcakes and pastries
                freshly prepared with quality ingredients for
                every sweet moment.

            </p>


            <div class="hero-buttons">

                <a
                    href="#featured"
                    class="primary-button"
                >
                    EXPLORE PRODUCTS
                </a>


                <a
                    href="#categories"
                    class="secondary-button"
                >
                    VIEW CATEGORIES
                </a>

            </div>


        </div>


        <div class="hero-image">

            <img
                src="Coffeeshop.jpg"
                alt="Freshly baked cake"
            >

        </div>


    </div>

</section>


<!-- =========================================================
     FEATURED PRODUCTS
========================================================= -->

<section
    id="featured"
    class="featured-section"
>

    <div class="container">


        <div class="section-heading">


            <div>

                <span class="section-label">
                    OUR FAVORITES
                </span>


                <h2>
                    Featured Products
                </h2>

            </div>


            <div class="product-search">

                <i
                    class="fa-solid fa-magnifying-glass search-icon"
                    aria-hidden="true"
                ></i>


                <input
                    type="search"
                    id="productSearch"
                    placeholder="Search products..."
                    aria-label="Search products"
                    autocomplete="off"
                >

            </div>


        </div>


        <div
            class="featured-products"
            id="featuredProducts"
        >


            <article
                class="featured-product"
                data-name="Chocolate Chip Cookies"
            >

                <div class="product-image">

                    <img
                        src="1.jpg"
                        alt="Chocolate Chip Cookies"
                        loading="lazy"
                    >

                    <span class="product-badge">
                        BESTSELLER
                    </span>

                </div>


                <div class="featured-info">

                    <span class="product-category">
                        COOKIES
                    </span>


                    <h3>
                        Chocolate Chip Cookies
                    </h3>


                    <p>
                        Soft, chewy and filled with delicious
                        chocolate chips.
                    </p>


                    <strong class="price">
                        ₱120
                    </strong>

                </div>

            </article>


            <article
                class="featured-product"
                data-name="Strawberry Cream Cake"
            >

                <div class="product-image">

                    <img
                        src="2.jpg"
                        alt="Strawberry Cream Cake"
                        loading="lazy"
                    >

                    <span class="product-badge">
                        FAVORITE
                    </span>

                </div>


                <div class="featured-info">

                    <span class="product-category">
                        CAKES
                    </span>


                    <h3>
                        Strawberry Cream Cake
                    </h3>


                    <p>
                        Light and creamy cake topped with
                        fresh strawberries.
                    </p>


                    <strong class="price">
                        ₱650
                    </strong>

                </div>

            </article>


            <article
                class="featured-product"
                data-name="Chocolate Cupcake"
            >

                <div class="product-image">

                    <img
                        src="3.jpg"
                        alt="Chocolate Cupcake"
                        loading="lazy"
                    >

                    <span class="product-badge">
                        NEW
                    </span>

                </div>


                <div class="featured-info">

                    <span class="product-category">
                        CUPCAKES
                    </span>


                    <h3>
                        Chocolate Cupcake
                    </h3>


                    <p>
                        Moist chocolate cupcake finished with
                        smooth creamy frosting.
                    </p>


                    <strong class="price">
                        ₱85
                    </strong>

                </div>

            </article>


            <div
                class="no-results"
                id="noResults"
            >
                No products found.
            </div>


        </div>


        <div class="see-more-container">

            <a
                href="menu.php"
                class="see-more-button"
            >

                SEE MORE

                <span>
                    ...
                </span>

            </a>

        </div>


    </div>

</section>


<!-- =========================================================
     CATEGORIES
========================================================= -->

<section
    id="categories"
    class="categories-section"
>

    <div class="container">


        <div class="category-heading">

            <span class="section-label">
                SHOP BY CATEGORY
            </span>


            <h2>
                What are you craving?
            </h2>


            <p>
                Choose your favorite treats and discover
                something delicious.
            </p>

        </div>


        <div class="categories-grid">


            <a
                href="menu.php?category=cookies"
                class="category-card"
            >

                <div class="category-image">

                    <img
                        src="1.jpg"
                        alt="Cookies"
                        loading="lazy"
                    >

                </div>


                <div class="category-content">

                    <h3>
                        Cookies
                    </h3>


                    <span>
                        Explore
                    </span>

                </div>

            </a>


            <a
                href="menu.php?category=cakes"
                class="category-card"
            >

                <div class="category-image">

                    <img
                        src="13.jpg"
                        alt="Cakes"
                        loading="lazy"
                    >

                </div>


                <div class="category-content">

                    <h3>
                        Cakes
                    </h3>


                    <span>
                        Explore
                    </span>

                </div>

            </a>


            <a
                href="menu.php?category=cupcakes"
                class="category-card"
            >

                <div class="category-image">

                    <img
                        src="12.jpg"
                        alt="Cupcakes"
                        loading="lazy"
                    >

                </div>


                <div class="category-content">

                    <h3>
                        Cupcakes
                    </h3>


                    <span>
                        Explore
                    </span>

                </div>

            </a>


            <a
                href="menu.php?category=pastries"
                class="category-card"
            >

                <div class="category-image">

                    <img
                        src="14.jpg"
                        alt="Pastries"
                        loading="lazy"
                    >

                </div>


                <div class="category-content">

                    <h3>
                        Pastries
                    </h3>


                    <span>
                        Explore
                    </span>

                </div>

            </a>


        </div>

    </div>

</section>


<!-- =========================================================
     PRE-ORDER
========================================================= -->

<section
    id="order"
    class="order-section"
>

    <div class="container">

        <div class="order-content">

            <span class="section-label">
                PLAN AHEAD
            </span>


            <h2>

                Order Before 10am,

                <br>

                <span>
                    Pick Up by Noon.
                </span>

            </h2>


            <p>

                Pre-order your favourites and skip the queue.
                Your freshly baked treats will be ready when
                you arrive.

            </p>


            <a
                href="menu.php"
                class="start-order-button"
            >
                START YOUR ORDER
            </a>

        </div>

    </div>

</section>


<!-- =========================================================
     WHY CHOOSE US
========================================================= -->

<section class="why-section">

    <div class="container">


        <div class="why-heading">

            <span class="section-label">
                THE KATES GOODIES DIFFERENCE
            </span>


            <h2>
                Why Choose Us?
            </h2>


            <p>
                We make every order special from the first
                ingredient to the final bite.
            </p>

        </div>


        <div class="why-grid">


            <div class="why-card">

                <div class="why-icon">

                    <i
                        class="fa-solid fa-bread-slice"
                        aria-hidden="true"
                    ></i>

                </div>


                <h3>
                    Freshly Baked
                </h3>


                <p>
                    Our treats are freshly prepared and baked
                    to give you the best taste and texture.
                </p>

            </div>


            <div class="why-card">

                <div class="why-icon">

                    <i
                        class="fa-solid fa-wheat-awn"
                        aria-hidden="true"
                    ></i>

                </div>


                <h3>
                    Quality Ingredients
                </h3>


                <p>
                    We carefully choose quality ingredients
                    for delicious and satisfying baked goods.
                </p>

            </div>


            <div class="why-card">

                <div class="why-icon">

                    <i
                        class="fa-solid fa-heart"
                        aria-hidden="true"
                    ></i>

                </div>


                <h3>
                    Made With Love
                </h3>


                <p>
                    Every product is prepared with care,
                    passion and a little extra love.
                </p>

            </div>


            <div class="why-card">

                <div class="why-icon">

                    <i
                        class="fa-solid fa-bag-shopping"
                        aria-hidden="true"
                    ></i>

                </div>


                <h3>
                    Easy Ordering
                </h3>


                <p>
                    Browse our products, choose your favorites
                    and place your order with ease.
                </p>

            </div>


        </div>

    </div>

</section>


<!-- =========================================================
     ABOUT
========================================================= -->

<section class="about">

    <div class="container about-container">


        <div class="about-images">

            <img
                src="https://images.unsplash.com/photo-1528699633788-424224dc89b5?w=700&h=900&fit=crop"
                alt="Bread and coffee"
                loading="lazy"
            >


            <img
                src="https://images.unsplash.com/photo-1534432182912-63863115e106?w=400&h=300&fit=crop"
                alt="Fresh pastry"
                loading="lazy"
            >

        </div>


        <div class="about-text">

            <span class="section-label">
                OUR STORY
            </span>


            <h2>

                The Bakery

                <br>

                That Feels

                <br>

                <span>
                    Like Home.
                </span>

            </h2>


            <p>

                We believe that good food brings people together.
                Every product is carefully prepared with quality
                ingredients and plenty of love.

            </p>


            <div class="values">


                <div>

                    <h3>
                        Honesty
                    </h3>

                    <p>
                        Simple ingredients and honest food.
                    </p>

                </div>


                <div>

                    <h3>
                        Warmth
                    </h3>

                    <p>
                        Made with the same care we give our family.
                    </p>

                </div>


                <div>

                    <h3>
                        Craft
                    </h3>

                    <p>
                        Carefully prepared and freshly baked.
                    </p>

                </div>


                <div>

                    <h3>
                        Community
                    </h3>

                    <p>
                        Proud to serve our local community.
                    </p>

                </div>


            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     TESTIMONIALS
========================================================= -->

<section class="testimonials">

    <div class="container">


        <div class="testimonial-heading">

            <span class="section-label">
                CUSTOMER LOVE
            </span>


            <h2>
                Real People. Real Moments.
            </h2>

        </div>


        <div class="testimonial-container">


            <div class="testimonial">

                <div class="stars">

                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>

                </div>


                <p>
                    "Every pastry tastes fresh and homemade.
                    I always come back for more."
                </p>


                <strong>
                    Sarah M.
                </strong>


                <span>
                    Regular Customer
                </span>

            </div>


            <div class="testimonial">

                <div class="stars">

                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>

                </div>


                <p>
                    "The cakes are beautiful and delicious.
                    Perfect for family celebrations."
                </p>


                <strong>
                    Daniel R.
                </strong>


                <span>
                    Local Customer
                </span>

            </div>


            <div class="testimonial">

                <div class="stars">

                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>

                </div>


                <p>
                    "Ordering is easy and the pastries are
                    always fresh when I pick them up."
                </p>


                <strong>
                    Priya K.
                </strong>


                <span>
                    Customer
                </span>

            </div>


        </div>

    </div>

</section>


<!-- =========================================================
     MESSAGES MODAL
========================================================= -->

<div
    class="info-modal"
    id="messagesModal"
    aria-hidden="true"
>

    <div
        class="info-modal-backdrop"
        data-close-messages
    ></div>


    <div class="info-modal-content">

        <button
            type="button"
            class="modal-close"
            data-close-messages
            aria-label="Close messages"
        >

            <i class="fa-solid fa-xmark"></i>

        </button>


        <h3>
            Messages
        </h3>


        <div class="info-list">

            <p class="info-empty">
                You have no messages yet.
            </p>

        </div>

    </div>

</div>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer>

    <div class="container">


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


                <a href="#home">
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

                ©
                <?php echo date("Y"); ?>

                <?php
                echo htmlspecialchars(
                    $brandName,
                    ENT_QUOTES,
                    "UTF-8"
                );
                ?>.

                All rights reserved.

            </span>


            <span>
                Fresh · Sweet · Homemade
            </span>

        </div>


    </div>

</footer>


<script src="home.js"></script>

</body>
</html>