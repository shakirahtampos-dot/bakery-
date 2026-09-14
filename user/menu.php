<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| KATES GOODIES - MENU PAGE
|--------------------------------------------------------------------------
*/

$brandName = "Kate's Goodies";

$fullname = $_SESSION['fullname']
    ?? $_SESSION['full_name']
    ?? $_SESSION['name']
    ?? "Guest Customer";

$username = $_SESSION['username'] ?? "guest";

$email = $_SESSION['email']
    ?? "guest@example.com";

$role = $_SESSION['role'] ?? "Customer";

$selectedCategory = isset($_GET['category'])
    ? strtolower(trim($_GET['category']))
    : "all";

$allowedCategories = [
    'all',
    'cookies',
    'cakes',
    'cupcakes',
    'brownies',
    'pastries'
];

if (!in_array($selectedCategory, $allowedCategories, true)) {
    $selectedCategory = 'all';
}

function menu_escape($value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

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
        <?php echo menu_escape($brandName); ?> | Menu
    </title>

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

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <link
        rel="stylesheet"
        href="menu.css"
    >

</head>

<body>

<!-- =========================================================
     NAVIGATION
========================================================= -->

<header class="navbar">

    <div class="logo">

        <a href="home.php">

            <img
                src="Katelogo.png"
                alt="Kate's Goodies Logo"
            >

        </a>

    </div>

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

    <nav id="mainNav">

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

    </nav>

    <div
        class="nav-overlay"
        id="navOverlay"
    ></div>

    <div class="navbar-actions">

        <!-- NOTIFICATIONS -->

        <a
            href="notifications.php"
            class="icon-button notification-button"
            aria-label="Notifications"
            title="Notifications"
        >

            <span>🔔</span>

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

                <span class="icon-user">
                    👤
                </span>

            </button>

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
                            <?php echo menu_escape($fullname); ?>
                        </strong>

                        <small>
                            <?php echo menu_escape($role); ?>
                        </small>

                    </div>

                </div>

                <div class="account-divider"></div>

                <div class="account-info">

                    <p>
                        <strong>Username:</strong>
                        <?php echo menu_escape($username); ?>
                    </p>

                    <p>
                        <strong>Email:</strong>
                        <?php echo menu_escape($email); ?>
                    </p>

                </div>

                <div class="account-divider"></div>

                <a
                    href="logout.php"
                    class="logout-button"
                >

                    <i class="fa-solid fa-right-from-bracket"></i>

                    <span>Logout</span>

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
        class="category-btn <?php echo $selectedCategory === 'all' ? 'active' : ''; ?>"
        data-category="all"
        type="button"
    >
        All
    </button>

    <button
        class="category-btn <?php echo $selectedCategory === 'cookies' ? 'active' : ''; ?>"
        data-category="cookies"
        type="button"
    >
        Cookies
    </button>

    <button
        class="category-btn <?php echo $selectedCategory === 'cakes' ? 'active' : ''; ?>"
        data-category="cakes"
        type="button"
    >
        Cakes
    </button>

    <button
        class="category-btn <?php echo $selectedCategory === 'cupcakes' ? 'active' : ''; ?>"
        data-category="cupcakes"
        type="button"
    >
        Cupcakes
    </button>

    <button
        class="category-btn <?php echo $selectedCategory === 'brownies' ? 'active' : ''; ?>"
        data-category="brownies"
        type="button"
    >
        Brownies
    </button>

    <button
        class="category-btn <?php echo $selectedCategory === 'pastries' ? 'active' : ''; ?>"
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
    ></div>

    <div
        class="no-results"
        id="noResults"
        style="display:none;"
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

        <div>

            <h3>Explore</h3>

            <a href="home.php">Home</a>

            <a href="menu.php">Menu</a>

            <a href="order.php">My Orders</a>

            <a href="about_us.php">About Us</a>

            <a href="contact_us.php">Contact Us</a>

        </div>

        <div>

            <h3>Categories</h3>

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

        <div>

            <h3>Contact Us</h3>

            <p>Dumaguete City</p>

            <p>Monday - Saturday</p>

            <p>7:00 AM - 6:00 PM</p>

            <a href="contact_us.php">
                Send us a message
            </a>

        </div>

    </div>

    <div class="footer-bottom">

        <span>
            © <?php echo date("Y"); ?>
            <?php echo menu_escape($brandName); ?>.
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
    role="status"
    aria-live="polite"
></div>

<script>

    /*
    |--------------------------------------------------------------------------
    | INITIAL CATEGORY
    |--------------------------------------------------------------------------
    */

    const initialCategory =
        <?php echo json_encode($selectedCategory); ?>;

</script>

<script src="menu.js"></script>

</body>
</html>