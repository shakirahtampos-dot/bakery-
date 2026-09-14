<?php

/* =========================================================
   KATES GOODIES
   MY ORDERS PAGE
========================================================= */

$brandName = $brandName ?? "Kates Goodies";
$fullname  = $fullname ?? "Guest User";
$role      = $role ?? "Customer";
$username  = $username ?? "Guest";
$email     = $email ?? "guest@example.com";

$orders = $orders ?? [];

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
        content="Kates Goodies - View your orders and order history."
    >

    <!-- GOOGLE FONTS -->

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

    <!-- ORDER CSS -->

    <link
        rel="stylesheet"
        href="order.css"
    >
      <link
        rel="stylesheet"
        href="menu.css"
    >
    <title>
        My Orders -
        <?php
        echo htmlspecialchars(
            $brandName,
            ENT_QUOTES,
            'UTF-8'
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


        <a href="home.php"  style="font-family: 'Playfair Display', serif;">
            HOME
        </a>


        <a href="menu.php"  style="font-family: 'Playfair Display', serif;">
            MENU
        </a>


        <a
            href="order.php"
            class="active"
         style="font-family: 'Playfair Display', serif;">
            MY ORDERS
        </a>


        <a href="about_us.php" style="font-family: 'Playfair Display', serif;">
            ABOUT US
        </a>


        <a href="contact_us.php" style="font-family: 'Playfair Display', serif;">
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


        <!-- CART -->

        <a
            href="cart.php"
            class="icon-button cart-button"
            aria-label="Shopping cart"
            title="Shopping Cart"
        >

            <i>
              🛒
            </i>

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
                aria-label="Personal account" style="width:45px;"
            >

                <i class="icon-user" style="margin: 0 20px 0 50px; font-family:'Poppins'; font-style:normal;">👤 </i>

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

                        <i>👤 </i>

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

                    <i
                        class="fa-solid fa-right-from-bracket"
                        aria-hidden="true"
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
     PAGE HERO
========================================================= -->

<section class="orders-hero">

    <div class="container">

        <span class="section-label">
            YOUR KATES GOODIES
        </span>

        <h1>
            My Orders
        </h1>

        <p>
            View your current orders and keep track of
            your delicious treats.
        </p>

    </div>

</section>



<!-- =========================================================
     ORDER CONTENT
========================================================= -->

<main class="orders-section">

    <div class="container">


        <!-- PAGE HEADING -->

        <div class="orders-heading">

            <div>

                <span class="section-label">
                    ORDER HISTORY
                </span>

                <h2>
                    Your Orders
                </h2>

            </div>


            <a
                href="menu.php"
                class="new-order-button"
            >

                <i
                    class="fa-solid fa-plus"
                    aria-hidden="true"
                ></i>

                <span>
                    ORDER MORE
                </span>

            </a>

        </div>



        <!-- FILTER BUTTONS -->

        <div class="order-filters">

            <button
                type="button"
                class="filter-button active"
                data-filter="all"
            >
                All Orders
            </button>

            <button
                type="button"
                class="filter-button"
                data-filter="pending"
            >
                Pending
            </button>

            <button
                type="button"
                class="filter-button"
                data-filter="preparing"
            >
                Preparing
            </button>

            <button
                type="button"
                class="filter-button"
                data-filter="ready"
            >
                Ready
            </button>

            <button
                type="button"
                class="filter-button"
                data-filter="completed"
            >
                Completed
            </button>

        </div>



        <!-- =====================================================
             ORDERS
        ====================================================== -->

        <div
            class="orders-list"
            id="ordersList"
        >


            <?php if (empty($orders)): ?>


                <!-- SAMPLE ORDER 1 -->

                <article
                    class="order-card"
                    data-status="preparing"
                >


                    <div class="order-card-top">


                        <div class="order-number">

                            <span>
                                ORDER
                            </span>

                            <strong>
                                #KG-1001
                            </strong>

                        </div>


                        <span class="order-status preparing">
                            Preparing
                        </span>


                    </div>


                    <div class="order-divider"></div>


                    <div class="order-main">


                        <div class="order-products">


                            <div class="order-product">


                                <div class="order-product-image">

                                    <img
                                        src="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=300&h=300&fit=crop"
                                        alt="Chocolate Chip Cookies"
                                    >

                                </div>


                                <div class="order-product-info">

                                    <h3>
                                        Chocolate Chip Cookies
                                    </h3>

                                    <span>
                                        Cookies
                                    </span>

                                    <p>
                                        Quantity:
                                        <strong>2</strong>
                                    </p>

                                </div>


                                <strong class="order-product-price">
                                    ₱240
                                </strong>


                            </div>



                            <div class="order-product">


                                <div class="order-product-image">

                                    <img
                                        src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=300&h=300&fit=crop"
                                        alt="Strawberry Cake"
                                    >

                                </div>


                                <div class="order-product-info">

                                    <h3>
                                        Strawberry Cream Cake
                                    </h3>

                                    <span>
                                        Cakes
                                    </span>

                                    <p>
                                        Quantity:
                                        <strong>1</strong>
                                    </p>

                                </div>


                                <strong class="order-product-price">
                                    ₱650
                                </strong>


                            </div>


                        </div>


                        <!-- ORDER SUMMARY -->

                        <div class="order-summary">


                            <div class="summary-row">

                                <span>
                                    Order Date
                                </span>

                                <strong>
                                    September 12, 2026
                                </strong>

                            </div>


                            <div class="summary-row">

                                <span>
                                    Pickup
                                </span>

                                <strong>
                                    12:00 PM
                                </strong>

                            </div>


                            <div class="summary-row">

                                <span>
                                    Payment
                                </span>

                                <strong>
                                    Cash
                                </strong>

                            </div>


                            <div class="summary-divider"></div>


                            <div class="summary-total">

                                <span>
                                    Total
                                </span>

                                <strong>
                                    ₱890
                                </strong>

                            </div>


                        </div>


                    </div>


                    <div class="order-card-bottom">


                        <div class="order-progress">


                            <div class="progress-step completed">

                                <span class="progress-icon">

                                    <i
                                        class="fa-solid fa-check"
                                        aria-hidden="true"
                                    ></i>

                                </span>

                                <small>
                                    Ordered
                                </small>

                            </div>


                            <div class="progress-line completed"></div>


                            <div class="progress-step completed">

                                <span class="progress-icon">

                                    <i
                                        class="fa-solid fa-check"
                                        aria-hidden="true"
                                    ></i>

                                </span>

                                <small>
                                    Confirmed
                                </small>

                            </div>


                            <div class="progress-line active"></div>


                            <div class="progress-step active">

                                <span class="progress-icon">

                                    <i
                                        class="fa-solid fa-bread-slice"
                                        aria-hidden="true"
                                    ></i>

                                </span>

                                <small>
                                    Preparing
                                </small>

                            </div>


                            <div class="progress-line"></div>


                            <div class="progress-step">

                                <span class="progress-icon">

                                    <i
                                        class="fa-solid fa-bag-shopping"
                                        aria-hidden="true"
                                    ></i>

                                </span>

                                <small>
                                    Ready
                                </small>

                            </div>


                        </div>


                        <button
                            type="button"
                            class="view-order-button"
                            data-order="KG-1001"
                        >

                            VIEW ORDER

                          
                        </button>


                    </div>


                </article>



                <!-- SAMPLE ORDER 2 -->

                <article
                    class="order-card"
                    data-status="completed"
                >


                    <div class="order-card-top">


                        <div class="order-number">

                            <span>
                                ORDER
                            </span>

                            <strong>
                                #KG-0987
                            </strong>

                        </div>


                        <span class="order-status completed">
                            Completed
                        </span>


                    </div>


                    <div class="order-divider"></div>


                    <div class="order-main">


                        <div class="order-products">


                            <div class="order-product">


                                <div class="order-product-image">

                                    <img
                                        src="https://images.unsplash.com/photo-1576618148400-cf6b4b305d6f?w=300&h=300&fit=crop"
                                        alt="Chocolate Cupcake"
                                    >

                                </div>


                                <div class="order-product-info">

                                    <h3>
                                        Chocolate Cupcake
                                    </h3>

                                    <span>
                                        Cupcakes
                                    </span>

                                    <p>
                                        Quantity:
                                        <strong>4</strong>
                                    </p>

                                </div>


                                <strong class="order-product-price">
                                    ₱340
                                </strong>


                            </div>


                            <div class="order-product">


                                <div class="order-product-image">

                                    <img
                                        src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=300&h=300&fit=crop"
                                        alt="Fresh Pastry"
                                    >

                                </div>


                                <div class="order-product-info">

                                    <h3>
                                        Fresh Pastry
                                    </h3>

                                    <span>
                                        Pastries
                                    </span>

                                    <p>
                                        Quantity:
                                        <strong>2</strong>
                                    </p>

                                </div>


                                <strong class="order-product-price">
                                    ₱240
                                </strong>


                            </div>


                        </div>


                        <div class="order-summary">


                            <div class="summary-row">

                                <span>
                                    Order Date
                                </span>

                                <strong>
                                    September 8, 2026
                                </strong>

                            </div>


                            <div class="summary-row">

                                <span>
                                    Pickup
                                </span>

                                <strong>
                                    11:30 AM
                                </strong>

                            </div>


                            <div class="summary-row">

                                <span>
                                    Payment
                                </span>

                                <strong>
                                    Cash
                                </strong>

                            </div>


                            <div class="summary-divider"></div>


                            <div class="summary-total">

                                <span>
                                    Total
                                </span>

                                <strong>
                                    ₱580
                                </strong>

                            </div>


                        </div>


                    </div>


                    <div class="order-card-bottom">


                        <div class="order-progress completed-order">


                            <div class="progress-step completed">

                                <span class="progress-icon">

                                    <i
                                        class="fa-solid fa-check"
                                        aria-hidden="true"
                                    ></i>

                                </span>

                                <small>
                                    Ordered
                                </small>

                            </div>


                            <div class="progress-line completed"></div>


                            <div class="progress-step completed">

                                <span class="progress-icon">

                                    <i
                                        class="fa-solid fa-check"
                                        aria-hidden="true"
                                    ></i>

                                </span>

                                <small>
                                    Confirmed
                                </small>

                            </div>


                            <div class="progress-line completed"></div>


                            <div class="progress-step completed">

                                <span class="progress-icon">

                                    <i
                                        class="fa-solid fa-check"
                                        aria-hidden="true"
                                    ></i>

                                </span>

                                <small>
                                    Preparing
                                </small>

                            </div>


                            <div class="progress-line completed"></div>


                            <div class="progress-step completed">

                                <span class="progress-icon">

                                    <i
                                        class="fa-solid fa-check"
                                        aria-hidden="true"
                                    ></i>

                                </span>

                                <small>
                                    Ready
                                </small>

                            </div>


                        </div>


                        <button
                            type="button"
                            class="view-order-button"
                            data-order="KG-0987"
                        >

                            VIEW ORDER

                           
                        </button>


                    </div>


                </article>


            <?php else: ?>


                <!-- DATABASE ORDERS -->

                <?php foreach ($orders as $order): ?>

                    <?php

                    $status =
                        strtolower(
                            $order["status"] ?? "pending"
                        );

                    ?>

                    <article
                        class="order-card"
                        data-status="<?php echo htmlspecialchars(
                            $status,
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>"
                    >

                        <div class="order-card-top">

                            <div class="order-number">

                                <span>
                                    ORDER
                                </span>

                                <strong>

                                    #<?php

                                    echo htmlspecialchars(
                                        $order["order_number"] ?? "N/A",
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );

                                    ?>

                                </strong>

                            </div>


                            <span
                                class="order-status <?php
                                echo htmlspecialchars(
                                    $status,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    ucfirst($status),
                                    ENT_QUOTES,
                                    'UTF-8'
                                );

                                ?>

                            </span>

                        </div>


                        <div class="order-divider"></div>


                        <div class="order-database-info">

                            <p>
                                Order Date:
                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $order["order_date"] ?? "",
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </strong>
                            </p>


                            <p>
                                Total:
                                <strong>
                                    ₱<?php
                                    echo htmlspecialchars(
                                        $order["total_amount"] ?? "0.00",
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </strong>
                            </p>

                        </div>


                    </article>

                <?php endforeach; ?>


            <?php endif; ?>


            <!-- NO FILTER RESULTS -->

            <div
                class="no-orders"
                id="noOrders"
            >

                <div class="no-orders-icon">

                    <i
                        class="fa-solid fa-receipt"
                        aria-hidden="true"
                    ></i>

                </div>

                <h3>
                    No orders found
                </h3>

                <p>
                    There are no orders in this category.
                </p>

                <a
                    href="menu.php"
                    class="new-order-button"
                >
                    START SHOPPING
                </a>

            </div>


        </div>


    </div>

</main>



<!-- =========================================================
     ORDER DETAILS MODAL
========================================================= -->

<div
    class="order-modal"
    id="orderModal"
    aria-hidden="true"
>


    <div
        class="order-modal-backdrop"
        data-close-order
    ></div>


    <div class="order-modal-content">


        <button
            type="button"
            class="order-modal-close"
            data-close-order
            aria-label="Close order details"
        >

            <i
                class="fa-solid fa-xmark"
                aria-hidden="true"
            ></i>

        </button>


        <span class="section-label">
            ORDER DETAILS
        </span>


        <h2 id="modalOrderNumber">
            Order #KG-1001
        </h2>


        <div class="modal-status">
            Preparing
        </div>


        <div class="modal-divider"></div>


        <div class="modal-detail-grid">


            <div>

                <span>
                    Order Date
                </span>

                <strong id="modalOrderDate">
                    September 12, 2026
                </strong>

            </div>


            <div>

                <span>
                    Pickup Time
                </span>

                <strong>
                    12:00 PM
                </strong>

            </div>


            <div>

                <span>
                    Payment
                </span>

                <strong>
                    Cash
                </strong>

            </div>


            <div>

                <span>
                    Total
                </span>

                <strong id="modalOrderTotal">
                    ₱890
                </strong>

            </div>


        </div>


        <div class="modal-divider"></div>


        <h3>
            Pickup Information
        </h3>


        <div class="pickup-box">

            <i
                class="fa-solid fa-location-dot"
                aria-hidden="true"
            ></i>

            <div>

                <strong>
                    Kates Goodies
                </strong>

                <span>
                    Dumaguete City
                </span>

                <small>
                    Monday - Saturday · 7:00 AM - 6:00 PM
                </small>

            </div>

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


                <a href="contact.php">
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


                <a href="contact.php">
                    Send us a message
                </a>


            </div>


        </div>


        <div class="footer-bottom">


            <span>

                © <?php echo date("Y"); ?>

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


    </div>

</footer>



<!-- ORDER JAVASCRIPT -->

<script src="order.js"></script>


</body>

</html>