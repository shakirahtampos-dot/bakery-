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

    <link
        href="https://fonts.googleapis.com/css?family=Croissant+One"
        rel="stylesheet"
    >

    <!-- EXISTING HOME CSS -->

    <link
        rel="stylesheet"
        href="home.css"
    >

    <link rel="stylesheet"
        href="about_us.css"
    >
    <link rel="stylesheet" href="menu.css">
    <title>
        About Us | <?php echo htmlspecialchars($brandName); ?>
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
        aria-controls="mainNav"
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
            class="active"
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

    </nav>


    <!-- MOBILE OVERLAY -->

    <div
        class="nav-overlay"
        id="navOverlay"
    ></div>


    <!-- NAVBAR ACTIONS -->

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


        <!-- CART -->

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


        <!-- ACCOUNT -->

        <div class="account-container">

            <button
                type="button"
                class="account-button"
                id="accountButton"
                aria-expanded="false"
                aria-label="Personal account"
            >
            <i class="icon-user" style="margin: 0 20px 0 30px; font-family:'Poppins'; font-style:normal;">👤 </i>

                <span class="account-label"></span>

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
                 <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
    <span class="account-icon" style="font-size: 0.8rem;">Logout</span>

                    

                </a>

            </div>

        </div>

    </div>

</header>



<!-- =========================================================
    ABOUT US HERO / OUR STORY
========================================================= -->

<section class="about about-page">

    <!-- IMAGES -->

    <div class="about-images">

        <img
            src="https://images.unsplash.com/photo-1528699633788-424224dc89b5?w=700&h=900&fit=crop"
            alt="Bread and coffee"
        >

        <img
            src="https://images.unsplash.com/photo-1534432182912-63863115e106?w=400&h=300&fit=crop"
            alt="Fresh pastry"
        >

    </div>


    <!-- STORY -->

    <div class="about-text">

        <span
            class="section-label"
            style="font-size: 1.4rem;"
        >
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

            <p>
                We believe that good food brings people together.
                Every product is carefully prepared with quality
                ingredients and plenty of love.
                </p>

        </p>


        <!-- VALUES -->

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

</section>



<!-- =========================================================
    SIMPLE OVERVIEW
========================================================= -->

<section class="about-overview">

    <div class="about-overview-heading">

        <span class="section-label">
            WHAT WE DO
        </span>

        <h2>
            Made Fresh.
            <br>
            Made With Care.
        </h2>

        <p>
            From everyday treats to special celebrations,
            we prepare baked goods that make every moment
            a little sweeter.
        </p>

    </div>


    <div class="about-overview-grid">


        <!-- CARD 1 -->

        <div class="about-overview-card">

            <div class="overview-number">
                01
            </div>

            <h3>
                Freshly Baked
            </h3>

            <p>
                Our baked goods are prepared with care
                to give customers fresh and delicious
                treats.
            </p>

        </div>


        <!-- CARD 2 -->

        <div class="about-overview-card">

            <div class="overview-number">
                02
            </div>

            <h3>
                Quality Ingredients
            </h3>

            <p>
                We choose quality ingredients to create
                products that taste good and feel homemade.
            </p>

        </div>


        <!-- CARD 3 -->

        <div class="about-overview-card">

            <div class="overview-number">
                03
            </div>

            <h3>
                Sweet Celebrations
            </h3>

            <p>
                Our cakes, cupcakes, cookies, and pastries
                are made for ordinary days and special
                celebrations.
            </p>

        </div>


        <!-- CARD 4 -->

        <div class="about-overview-card">

            <div class="overview-number">
                04
            </div>

            <h3>
                Easy Ordering
            </h3>

            <p>
                Browse our menu, choose your favorites,
                add them to your cart, and place your order
                with ease.
            </p>

        </div>

    </div>

</section>



<!-- =========================================================
    WHY CHOOSE KATE'S GOODIES
========================================================= -->

<section class="why-section about-why">

    <div class="why-heading">

        <span class="section-label">
            WHY KATE'S GOODIES
        </span>

        <h2>
            Something Sweet
            <br>
            For Everyone.
        </h2>

        <p>

            We focus on making good food, providing
            simple service, and giving our customers
            something worth coming back for.

        </p>

    </div>


    <div class="why-grid">


        <!-- CARD 1 -->

        <div class="why-card">

            <div class="why-icon">
                ♡
            </div>

            <h3>
                Made With Love
            </h3>

            <p>
                Every product is prepared with care,
                passion, and attention to detail.
            </p>

        </div>


        <!-- CARD 2 -->

        <div class="why-card">

            <div class="why-icon">
                ✦
            </div>

            <h3>
                Quality
            </h3>

            <p>
                We carefully select ingredients for
                delicious and satisfying baked goods.
            </p>

        </div>


        <!-- CARD 3 -->

        <div class="why-card">

            <div class="why-icon">
                ★
            </div>

            <h3>
                Freshness
            </h3>

            <p>
                We value freshness so customers can enjoy
                their favorite treats at their best.
            </p>

        </div>


        <!-- CARD 4 -->

        <div class="why-card">

            <div class="why-icon">
                ✓
            </div>

            <h3>
                Easy Service
            </h3>

            <p>
                Our ordering process makes it simple to
                browse products and place an order.
            </p>

        </div>

    </div>

</section>



<!-- =========================================================
    CUSTOMER LOVE
========================================================= -->

<section class="testimonials">

    <div class="testimonial-heading">

        <span
            class="section-label"
            style="font-size: 1.4rem;"
        >
            CUSTOMER LOVE
        </span>


        <h2>
            Real People.
            Real Moments.
        </h2>

    </div>


    <div class="testimonial-container">


        <!-- TESTIMONIAL 1 -->

        <div class="testimonial">

            <div class="stars">
                ★★★★★
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


        <!-- TESTIMONIAL 2 -->

        <div class="testimonial">

            <div class="stars">
                ★★★★★
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


        <!-- TESTIMONIAL 3 -->

        <div class="testimonial">

            <div class="stars">
                ★★★★★
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

</section>



<!-- =========================================================
    VISIT / ORDER SECTION
========================================================= -->

<section class="about-visit">

    <div class="about-visit-content">

        <span class="section-label">
            COME VISIT US
        </span>

        <h2>
            Your Next
            <span>
                Sweet Moment.
            </span>
        </h2>

        <p>

            Whether you are looking for a quick treat,
            a gift, or something special for a celebration,
            Kate's Goodies is here to make your day sweeter.

        </p>


        <div class="about-buttons">

            <a
                href="menu.php"
                class="about-primary-button"
            >
                VIEW OUR MENU
            </a>


            <a
                href="contact_us.php"
                class="about-secondary-button"
            >
                CONTACT US
            </a>

        </div>

    </div>

</section>



<!-- =========================================================
    FOOTER
========================================================= -->

<footer id="contact">

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


    <!-- FOOTER BOTTOM -->

    <div class="footer-bottom">

        <span>

            ©
            <?php echo date("Y"); ?>

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





<script src="home.js"></script>
<script src="about_us.js"></script>
<script src="menu.js"></script>

</body>

</html>