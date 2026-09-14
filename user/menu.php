<?php
session_start();

/* =========================================================
   KATE'S GOODIES
   MENU PAGE
========================================================= */

/* Default account values */
$brandName = "Kate's Goodies";

$fullname = $_SESSION['fullname'] ?? "Guest Customer";
$username = $_SESSION['username'] ?? "guest";
$email    = $_SESSION['email'] ?? "guest@example.com";
$role     = $_SESSION['role'] ?? "Customer";

/* Optional category from URL */
$selectedCategory = isset($_GET['category'])
    ? strtolower(trim($_GET['category']))
    : "all";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&family=Poppins:wght@300;400;500;600&display=swap"
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

    <!-- MENU CSS -->
    <link
        rel="stylesheet"
        href="menu.css"
    >
    
    <title>
        <?php echo htmlspecialchars($brandName); ?> | Menu
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
                alt="Kate's Goodies Logo"
            >

        </a>

    </div>


    <!-- MOBILE BURGER -->
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

        <!-- CLOSE BUTTON -->
        <button
            type="button"
            class="nav-close"
            id="navClose"
            aria-label="Close navigation"
        >
            &times;
        </button>


        <a href="home.php">
            HOME
        </a>

        <a
            href="menu.php"
            class="active"
        >
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


        <!--
            ACCOUNT IS MOVED HERE BY JAVASCRIPT
            WHEN SCREEN IS MOBILE
        -->

    </nav>


    <!-- MOBILE OVERLAY -->
    <div
        class="nav-overlay"
        id="navOverlay"
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

            <i>🛒</i>

            <span
                class="cart-count"
                id="cartCount"
            >
                0
            </span>

        </a>


        <!-- ACCOUNT -->
        <div class="account-container">

            <button
                type="button"
                class="account-button"
                id="accountButton"
                aria-expanded="false"
                aria-label="Personal account"
                style="display:flex; align-items:center; gap:6px;"
            >

               <i class="icon-user" style="margin: 0 20px 0 47px; font-family:'Poppins'; font-style:normal;">👤</i>

               <span
                    class="account-logout-label"
                    style="font-family:'Poppins'; font-size:0.9rem; color:var(--cream); margin-right:20px;"
               >
                    
               </span>

            </button>


            <!-- ACCOUNT DROPDOWN -->
            <div
                class="account-dropdown"
                id="accountDropdown"
            >

                <div class="account-title">

                    <div class="account-icon-small"> 
                        

                        

                    </div>


                    <div>

                        <strong>
                            <?php
                            echo htmlspecialchars($fullname);
                            ?>
                        </strong>

                        <small>
                            <?php
                            echo htmlspecialchars($role);
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
                        echo htmlspecialchars($username);
                        ?>

                    </p>


                    <p>

                        <strong>
                            Email:
                        </strong>

                        <?php
                        echo htmlspecialchars($email);
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
                        aria-hidden="true" style="color:var(--dark);"
                    ></i>
                    <span class="account-icon" style="font-size: 0.8rem;">
                         Logout
                    </span>

                </a>

            </div>

        </div>

    </div>

</header>



<!-- =========================================================
     MENU HERO
========================================================= -->

<section class="menu-hero">

    <div class="small-title">
        Kate's Goodies
    </div>


    <h1>
        Our Menu
    </h1>


    <p>
        Discover our freshly baked cookies, cakes, cupcakes,
        brownies, and other delicious treats made with love.
    </p>

</section>



<!-- =========================================================
     SEARCH
========================================================= -->

<div class="menu-controls">

    <div class="search-box">

        <i class="fa-solid fa-magnifying-glass"></i>

        <input
            type="text"
            id="searchInput"
            placeholder="Search products..."
            autocomplete="off"
        >

    </div>

</div>



<!-- =========================================================
     CATEGORIES
========================================================= -->

<div class="categories">

    <button
        class="category-btn active"
        data-category="all"
        type="button"
    >
        All
    </button>


    <button
        class="category-btn"
        data-category="cookies"
        type="button"
    >
        Cookies
    </button>


    <button
        class="category-btn"
        data-category="cakes"
        type="button"
    >
        Cakes
    </button>


    <button
        class="category-btn"
        data-category="cupcakes"
        type="button"
    >
        Cupcakes
    </button>


    <button
        class="category-btn"
        data-category="brownies"
        type="button"
    >
        Brownies
    </button>


    <button
        class="category-btn"
        data-category="pastries"
        type="button"
    >
        Pastries
    </button>

</div>



<!-- =========================================================
     PRODUCTS
========================================================= -->

<section class="products-section">

    <div
        class="products-grid"
        id="productsGrid"
    >


        <!-- =================================================
             PRODUCT 1
        ================================================== -->

        <article
            class="product-card"
            data-category="cookies"
            data-name="Chocolate Chip Cookies"
        >

            <div class="product-image">

                <img
                    src="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=700&q=80"
                    alt="Chocolate Chip Cookies"
                >

                <span class="product-category">
                    Cookies
                </span>

            </div>


            <div class="product-info">

                <h3>
                    Chocolate Chip Cookies
                </h3>


                <p>
                    Soft and chewy cookies filled with
                    rich chocolate chips.
                </p>


                <div class="product-bottom">

                    <span class="price">
                        ₱120
                    </span>


                    <div class="product-buttons">

                        <button
                            class="add-cart"
                            type="button"
                            onclick="addToCart('Chocolate Chip Cookies', 120)"
                            aria-label="Add Chocolate Chip Cookies to cart"
                        >

                            <i class="fa-solid fa-cart-plus"></i>

                        </button>


                        <button
                            class="order-now"
                            type="button"
                            onclick="orderNow('Chocolate Chip Cookies', 120)"
                        >
                            Order
                        </button>

                    </div>

                </div>

            </div>

        </article>



        <!-- =================================================
             PRODUCT 2
        ================================================== -->

        <article
            class="product-card"
            data-category="cookies"
            data-name="Red Velvet Cookies"
        >

            <div class="product-image">

                <img
                    src="https://images.unsplash.com/photo-1558961363-fa8fdf82db35?auto=format&fit=crop&w=700&q=80"
                    alt="Red Velvet Cookies"
                >

                <span class="product-category">
                    Cookies
                </span>

            </div>


            <div class="product-info">

                <h3>
                    Red Velvet Cookies
                </h3>


                <p>
                    Soft red velvet cookies with a
                    delicious creamy center.
                </p>


                <div class="product-bottom">

                    <span class="price">
                        ₱130
                    </span>


                    <div class="product-buttons">

                        <button
                            class="add-cart"
                            type="button"
                            onclick="addToCart('Red Velvet Cookies', 130)"
                            aria-label="Add Red Velvet Cookies to cart"
                        >

                            <i class="fa-solid fa-cart-plus"></i>

                        </button>


                        <button
                            class="order-now"
                            type="button"
                            onclick="orderNow('Red Velvet Cookies', 130)"
                        >
                            Order
                        </button>

                    </div>

                </div>

            </div>

        </article>



        <!-- =================================================
             PRODUCT 3
        ================================================== -->

        <article
            class="product-card"
            data-category="cakes"
            data-name="Chocolate Cake"
        >

            <div class="product-image">

                <img
                    src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=700&q=80"
                    alt="Chocolate Cake"
                >

                <span class="product-category">
                    Cakes
                </span>

            </div>


            <div class="product-info">

                <h3>
                    Chocolate Cake
                </h3>


                <p>
                    Moist chocolate cake covered with
                    smooth chocolate frosting.
                </p>


                <div class="product-bottom">

                    <span class="price">
                        ₱450
                    </span>


                    <div class="product-buttons">

                        <button
                            class="add-cart"
                            type="button"
                            onclick="addToCart('Chocolate Cake', 450)"
                            aria-label="Add Chocolate Cake to cart"
                        >

                            <i class="fa-solid fa-cart-plus"></i>

                        </button>


                        <button
                            class="order-now"
                            type="button"
                            onclick="orderNow('Chocolate Cake', 450)"
                        >
                            Order
                        </button>

                    </div>

                </div>

            </div>

        </article>



        <!-- =================================================
             PRODUCT 4
        ================================================== -->

        <article
            class="product-card"
            data-category="cupcakes"
            data-name="Vanilla Cupcakes"
        >

            <div class="product-image">

                <img
                    src="https://images.unsplash.com/photo-1587668178277-295251f900ce?auto=format&fit=crop&w=700&q=80"
                    alt="Vanilla Cupcakes"
                >

                <span class="product-category">
                    Cupcakes
                </span>

            </div>


            <div class="product-info">

                <h3>
                    Vanilla Cupcakes
                </h3>


                <p>
                    Light and fluffy vanilla cupcakes
                    topped with creamy frosting.
                </p>


                <div class="product-bottom">

                    <span class="price">
                        ₱180
                    </span>


                    <div class="product-buttons">

                        <button
                            class="add-cart"
                            type="button"
                            onclick="addToCart('Vanilla Cupcakes', 180)"
                            aria-label="Add Vanilla Cupcakes to cart"
                        >

                            <i class="fa-solid fa-cart-plus"></i>

                        </button>


                        <button
                            class="order-now"
                            type="button"
                            onclick="orderNow('Vanilla Cupcakes', 180)"
                        >
                            Order
                        </button>

                    </div>

                </div>

            </div>

        </article>



        <!-- =================================================
             PRODUCT 5
        ================================================== -->

        <article
            class="product-card"
            data-category="brownies"
            data-name="Fudge Brownies"
        >

            <div class="product-image">

                <img
                    src="https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=700&q=80"
                    alt="Fudge Brownies"
                >

                <span class="product-category">
                    Brownies
                </span>

            </div>


            <div class="product-info">

                <h3>
                    Fudge Brownies
                </h3>


                <p>
                    Rich, chocolatey, and fudgy brownies
                    baked to perfection.
                </p>


                <div class="product-bottom">

                    <span class="price">
                        ₱150
                    </span>


                    <div class="product-buttons">

                        <button
                            class="add-cart"
                            type="button"
                            onclick="addToCart('Fudge Brownies', 150)"
                            aria-label="Add Fudge Brownies to cart"
                        >

                            <i class="fa-solid fa-cart-plus"></i>

                        </button>


                        <button
                            class="order-now"
                            type="button"
                            onclick="orderNow('Fudge Brownies', 150)"
                        >
                            Order
                        </button>

                    </div>

                </div>

            </div>

        </article>



        <!-- =================================================
             PRODUCT 6
        ================================================== -->

        <article
            class="product-card"
            data-category="cookies"
            data-name="Matcha Cookies"
        >

            <div class="product-image">

                <img
                    src="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=700&q=80"
                    alt="Matcha Cookies"
                >

                <span class="product-category">
                    Cookies
                </span>

            </div>


            <div class="product-info">

                <h3>
                    Matcha Cookies
                </h3>


                <p>
                    Delicious soft cookies with a
                    gentle matcha flavor.
                </p>


                <div class="product-bottom">

                    <span class="price">
                        ₱140
                    </span>


                    <div class="product-buttons">

                        <button
                            class="add-cart"
                            type="button"
                            onclick="addToCart('Matcha Cookies', 140)"
                            aria-label="Add Matcha Cookies to cart"
                        >

                            <i class="fa-solid fa-cart-plus"></i>

                        </button>


                        <button
                            class="order-now"
                            type="button"
                            onclick="orderNow('Matcha Cookies', 140)"
                        >
                            Order
                        </button>

                    </div>

                </div>

            </div>

        </article>



        <!-- =================================================
             PRODUCT 7
        ================================================== -->

        <article
            class="product-card"
            data-category="pastries"
            data-name="Butter Croissant"
        >

            <div class="product-image">

                <img
                    src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=700&q=80"
                    alt="Butter Croissant"
                >

                <span class="product-category">
                    Pastries
                </span>

            </div>


            <div class="product-info">

                <h3>
                    Butter Croissant
                </h3>


                <p>
                    Flaky and buttery croissants with
                    a light golden crust.
                </p>


                <div class="product-bottom">

                    <span class="price">
                        ₱95
                    </span>


                    <div class="product-buttons">

                        <button
                            class="add-cart"
                            type="button"
                            onclick="addToCart('Butter Croissant', 95)"
                            aria-label="Add Butter Croissant to cart"
                        >

                            <i class="fa-solid fa-cart-plus"></i>

                        </button>


                        <button
                            class="order-now"
                            type="button"
                            onclick="orderNow('Butter Croissant', 95)"
                        >
                            Order
                        </button>

                    </div>

                </div>

            </div>

        </article>



        <!-- =================================================
             PRODUCT 8
        ================================================== -->

        <article
            class="product-card"
            data-category="cakes"
            data-name="Strawberry Shortcake"
        >

            <div class="product-image">

                <img
                    src="https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?auto=format&fit=crop&w=700&q=80"
                    alt="Strawberry Shortcake"
                >

                <span class="product-category">
                    Cakes
                </span>

            </div>


            <div class="product-info">

                <h3>
                    Strawberry Shortcake
                </h3>


                <p>
                    Soft vanilla cake layered with
                    strawberries and whipped cream.
                </p>


                <div class="product-bottom">

                    <span class="price">
                        ₱480
                    </span>


                    <div class="product-buttons">

                        <button
                            class="add-cart"
                            type="button"
                            onclick="addToCart('Strawberry Shortcake', 480)"
                            aria-label="Add Strawberry Shortcake to cart"
                        >

                            <i class="fa-solid fa-cart-plus"></i>

                        </button>


                        <button
                            class="order-now"
                            type="button"
                            onclick="orderNow('Strawberry Shortcake', 480)"
                        >
                            Order
                        </button>

                    </div>

                </div>

            </div>

        </article>


    </div>



    <!-- =====================================================
         NO RESULTS
    ====================================================== -->

    <div
        class="no-results"
        id="noResults"
    >

        <i class="fa-solid fa-cookie-bite"></i>

        <h3>
            No products found
        </h3>

        <p>
            Try searching for another cookie or baked treat.
        </p>

    </div>

</section>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer>

    <div class="footer-content">


        <!-- BRAND -->
        <div class="footer-brand">

            <img
                src="Katelogo.png"
                alt="Kate's Goodies"
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


            <a href="menu.php?category=brownies">
                Brownies
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



    <!-- FOOTER BOTTOM -->
    <div class="footer-bottom">

        <span>

            © <?php echo date("Y"); ?>

            <?php
            echo htmlspecialchars($brandName);
            ?>.

            All rights reserved.

        </span>


        <span>
            Fresh · Sweet · Homemade
        </span>

    </div>

</footer>



<!-- =========================================================
     TOAST
========================================================= -->

<div
    class="toast"
    id="toast"
></div>



<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script src="menu.js"></script>

</body>
</html>