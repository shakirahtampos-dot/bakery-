<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

/* THIS IS DATABASE CONNECTION*/

try {
    $conn = getConnection();
} catch (Throwable $e) {
    die('Database connection failed. Please check your config.php.');
}


/* ADMIN ACCESS */

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    header('Location: admin_login.php');
    exit();
}


/* ADMIN INFORMATION */

$adminName = $_SESSION['fullname']
    ?? $_SESSION['full_name']
    ?? $_SESSION['name']
    ?? $_SESSION['username']
    ?? 'Administrator';


/* HELPER FUNCTIONS*/

if (!function_exists('e')) {

    function e($value): string
    {
        return htmlspecialchars(
            (string) ($value ?? ''),
            ENT_QUOTES,
            'UTF-8'
        );
    }

}


/*
| Safe database query.
*/

if (!function_exists('safeQuery')) {

    function safeQuery(mysqli $conn, string $sql): ?mysqli_result
    {
        try {

            $result = $conn->query($sql);

            if ($result instanceof mysqli_result) {
                return $result;
            }

            return null;

        } catch (Throwable $exception) {

            return null;
        }
    }

}


/*
| Get COUNT result.
*/

if (!function_exists('getCount')) {

    function getCount(mysqli $conn, string $sql): int
    {
        $result = safeQuery($conn, $sql);

        if (!$result) {
            return 0;
        }

        $row = $result->fetch_assoc();

        return (int) ($row['total'] ?? 0);
    }

}

/* DEFAULT VALUES */

$totalProducts = 0;
$totalUsers = 0;
$totalOrders = 0;
$totalRevenue = 0.00;

$pendingOrders = 0;
$processingOrders = 0;
$shippedOrders = 0;
$cancelledOrders = 0;
$deliveredOrders = 0;

$newProducts = 0;
$saleProducts = 0;
$newCustomers = 0;
$unreadMessages = 0;

$recentOrders = [];
$recentProducts = [];
$announcements = [];


/* DASHBOARD COUNTS*/

$totalProducts = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM products"
);


$totalUsers = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role IN ('customer', 'user')"
);


$totalOrders = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM orders"
);


/*THIS IS REVENU */

$revenueResult = safeQuery(
    $conn,
    "SELECT COALESCE(SUM(total_amount), 0) AS revenue
     FROM orders
     WHERE status IN ('Completed', 'Delivered')"
);

if ($revenueResult) {

    $row = $revenueResult->fetch_assoc();

    $totalRevenue = (float) (
        $row['revenue'] ?? 0
    );
}


/* ORDER STATUS COUNTS */

$pendingOrders = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE status = 'Pending'"
);


$processingOrders = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE status IN ('Processing', 'Preparing')"
);


$shippedOrders = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE status = 'Shipped'"
);


$cancelledOrders = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE status = 'Cancelled'"
);


$deliveredOrders = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE status IN ('Completed', 'Delivered')"
);


/* NEW PRODUCTS */

$newProducts = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM products
     WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)"
);


/* NEW CUSTOMERS */

$newCustomers = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role IN ('customer', 'user')
     AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)"
);


/* UNREAD MESSAGES */

$userId = (int) ($_SESSION['user_id'] ?? 0);

$unreadMessages = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM chats
     WHERE receiver_id = {$userId}
     AND is_read = 0"
);


/* RECENT ORDERS */

$recentOrdersResult = safeQuery(
    $conn,
    "SELECT
        id,
        order_number,
        total_amount,
        status,
        created_at
     FROM orders
     ORDER BY created_at DESC
     LIMIT 5"
);

if ($recentOrdersResult) {

    while ($row = $recentOrdersResult->fetch_assoc()) {

        $recentOrders[] = $row;
    }
}


/* RECENT PRODUCTS*/

$recentProductsResult = safeQuery(
    $conn,
    "SELECT
        id,
        name,
        price,
        created_at
     FROM products
     ORDER BY created_at DESC
     LIMIT 5"
);

if ($recentProductsResult) {

    while ($row = $recentProductsResult->fetch_assoc()) {

        $recentProducts[] = $row;
    }
}


/* ANNOUNCEMENTS */

$announcementResult = safeQuery(
    $conn,
    "SELECT *
     FROM announcements
     WHERE status = 'active'
     ORDER BY created_at DESC
     LIMIT 5"
);

if ($announcementResult) {

    while ($row = $announcementResult->fetch_assoc()) {

        $announcements[] = $row;
    }
}


/*SALE PRODUCTS */

$saleProducts = getCount(
    $conn,
    "SELECT COUNT(*) AS total
     FROM products
     WHERE sale_price IS NOT NULL
     AND sale_price > 0
     AND sale_price < price"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <title>Admin Dashboard | Kate's Goodies</title>

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="admin_dashboard.css">
</head>

<body>

<!-- MOBILE MENU BUTTON -->

<button
    class="mobile-menu-btn"
    id="mobileMenuBtn"
    type="button"
    aria-label="Open navigation"
    aria-controls="sidebar"
    aria-expanded="false">

    <i class="fa-solid fa-bars"></i>
</button>

<!-- SIDEBAR OVERLAY -->

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- SIDEBAR -->

<aside class="sidebar" id="sidebar">

    <div class="sidebar-profile">

        <div class="profile-image">
            <img src="Katelogo.png"
                 alt="Kate's Goodies Logo">
        </div>

        <div class="profile-info">
            <h3>Kate's Goodies</h3>
            <span>ADMINISTRATOR</span>
        </div>

    </div>

    <nav class="sidebar-nav">

        <a href="admin_dashboard.php"
           class="sidebar-link active">
            <span class="sidebar-icon">
                <i class="fa-solid fa-house"></i>
            </span>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <a href="admin_message.php" class="sidebar-link">
            <span class="sidebar-icon">
                <i class="fa-solid fa-message"></i>
            </span>
            <span class="sidebar-text">Messages</span>
        </a>

        <a href="manage_products.php" class="sidebar-link">
            <span class="sidebar-icon">
                <i class="fa-solid fa-box"></i>
            </span>
            <span class="sidebar-text">Products</span>
        </a>

        <a href="manage_orders.php" class="sidebar-link">
            <span class="sidebar-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </span>
            <span class="sidebar-text">Orders</span>
        </a>

        <a href="admin_payment.php" class="sidebar-link">
            <span class="sidebar-icon">
                <i class="fa-solid fa-credit-card"></i>
            </span>
            <span class="sidebar-text">Payments</span>
        </a>

        <a href="admin_announcements.php" class="sidebar-link">
            <span class="sidebar-icon">
                <i class="fa-solid fa-bullhorn"></i>
            </span>
            <span class="sidebar-text">Announcements</span>
        </a>

        <a href="user.php" class="sidebar-link">
            <span class="sidebar-icon">
                <i class="fa-solid fa-users"></i>
            </span>
            <span class="sidebar-text">Users</span>
        </a>

        <a href="admin_settings.php" class="sidebar-link">
            <span class="sidebar-icon">
                <i class="fa-solid fa-gear"></i>
            </span>
            <span class="sidebar-text">Settings</span>
        </a>

    </nav>

    <div class="sidebar-bottom">

        <button
            class="logout-btn"
            id="logoutBtn"
            type="button">

            <span class="sidebar-icon">
                <i class="fa-solid fa-right-from-bracket"></i>
            </span>

            <span class="sidebar-text">Logout</span>

        </button>

    </div>

</aside>

<!-- MAIN CONTENT -->

<main class="main">

    <header class="dashboard-header">

        <div>
            <span class="header-small">ADMIN PANEL</span>

            <h1>Dashboard Overview</h1>

            <p>
                Welcome back, <?= e($adminName) ?>.
            </p>
        </div>

    </header>

    <!-- OVERVIEW CARDS -->

    <section class="overview-grid">

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">
                    <i class="fa-solid fa-peso-sign"></i>
                </div>
            </div>

            <h4>Total Revenue</h4>

            <div class="stat-number">
                ₱<?= number_format($totalRevenue, 2) ?>
            </div>

            <p>Delivered sales</p>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">
                    <i class="fa-solid fa-box-open"></i>
                </div>
            </div>

            <h4>Total Products</h4>

            <div class="stat-number">
                <?= $totalProducts ?>
            </div>

            <p>Products in catalog</p>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">
                    <i class="fa-solid fa-user-group"></i>
                </div>
            </div>

            <h4>Total Customers</h4>

            <div class="stat-number">
                <?= $totalUsers ?>
            </div>

            <p>Registered customers</p>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>

            <h4>Total Orders</h4>

            <div class="stat-number">
                <?= $totalOrders ?>
            </div>

            <p>Customer orders</p>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>

            <h4>Payments Collected</h4>

            <div class="stat-number">
                ₱<?= number_format($totalRevenue, 2) ?>
            </div>

            <p>From delivered orders</p>
        </div>

    </section>

    <!-- LOWER SECTION -->

    <section class="dashboard-lower-grid">

        <!-- ORDER STATUS -->

        <div class="dashboard-box">

            <div class="section-heading">

                <div>
                    <span>ORDERS</span>
                    <h2>Order Status</h2>
                </div>

                <a href="manage_orders.php" class="view-all">
                    View Orders
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>

            <div class="status-grid">

                <div class="status-card pending">
                    <div class="status-icon">
                        <i class="fa-regular fa-clock"></i>
                    </div>

                    <div>
                        <span>Pending</span>
                        <strong><?= $pendingOrders ?></strong>
                    </div>
                </div>

                <div class="status-card processing">
                    <div class="status-icon">
                        <i class="fa-solid fa-spinner"></i>
                    </div>

                    <div>
                        <span>Processing</span>
                        <strong><?= $processingOrders ?></strong>
                    </div>
                </div>

                <div class="status-card shipped">
                    <div class="status-icon">
                        <i class="fa-solid fa-truck"></i>
                    </div>

                    <div>
                        <span>Shipped</span>
                        <strong><?= $shippedOrders ?></strong>
                    </div>
                </div>

                <div class="status-card cancelled">
                    <div class="status-icon">
                        <i class="fa-solid fa-xmark"></i>
                    </div>

                    <div>
                        <span>Cancelled</span>
                        <strong><?= $cancelledOrders ?></strong>
                    </div>
                </div>

                <div class="status-card delivered">
                    <div class="status-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>

                    <div>
                        <span>Delivered</span>
                        <strong><?= $deliveredOrders ?></strong>
                    </div>
                </div>

            </div>

        </div>

        <!-- ANNOUNCEMENTS -->

        <div class="dashboard-box">

            <div class="section-heading">

                <div>
                    <span>UPDATES</span>
                    <h2>Announcements</h2>
                </div>

                <a href="admin_announcements.php" class="view-all">
                    View All
                </a>

            </div>

            <div class="announcement-list">

                <?php if (!empty($announcements)): ?>

                    <?php foreach ($announcements as $announcement): ?>

                        <div class="announcement-item">

                            <div class="announcement-icon">
                                <i class="fa-solid fa-bullhorn"></i>
                            </div>

                            <div class="announcement-content">

                                <h4>
                                    <?= e(
                                        $announcement['title']
                                        ?? 'Announcement'
                                    ) ?>
                                </h4>

                                <p>
                                    <?= e(
                                        $announcement['message']
                                        ?? $announcement['content']
                                        ?? ''
                                    ) ?>
                                </p>

                                <?php if (
                                    !empty($announcement['created_at'])
                                ): ?>

                                    <small>
                                        <i class="fa-regular fa-calendar"></i>
                                        <?= e(
                                            $announcement['created_at']
                                        ) ?>
                                    </small>

                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="empty-announcements">
                        <i class="fa-regular fa-bell-slash"></i>
                        <p>No announcements available.</p>
                    </div>

                <?php endif; ?>

            </div>

        </div>

    </section>

    <!-- QUICK SUMMARY -->

    <section class="quick-section">

        <div class="section-heading">

            <div>
                <span>SUMMARY</span>
                <h2>Quick Summary</h2>
            </div>

        </div>

        <div class="quick-grid">

            <div class="quick-card">

                <div class="quick-icon">
                    <i class="fa-solid fa-box"></i>
                </div>

                <div class="quick-content">
                    <h3>New Products</h3>
                    <p>Added within 30 days</p>
                </div>

                <strong><?= $newProducts ?></strong>

            </div>

            <div class="quick-card">

                <div class="quick-icon">
                    <i class="fa-solid fa-tag"></i>
                </div>

                <div class="quick-content">
                    <h3>Sale Products</h3>
                    <p>Products currently on sale</p>
                </div>

                <strong><?= $saleProducts ?></strong>

            </div>

            <div class="quick-card">

                <div class="quick-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>

                <div class="quick-content">
                    <h3>Unread Messages</h3>
                    <p>Messages waiting for reply</p>
                </div>

                <strong><?= $unreadMessages ?></strong>

            </div>

            <div class="quick-card">

                <div class="quick-icon">
                    <i class="fa-solid fa-user-plus"></i>
                </div>

                <div class="quick-content">
                    <h3>New Customers</h3>
                    <p>Registered within 30 days</p>
                </div>

                <strong><?= $newCustomers ?></strong>

            </div>

        </div>

    </section>

</main>

<!-- LOGOUT MODAL -->


<!-- LOGOUT MODAL -->

<div
    class="logout-modal"
    id="logoutModal"
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
    aria-labelledby="logoutTitle">

    <div class="logout-modal-content">

        <div class="logout-modal-icon">
            <i class="fa-solid fa-right-from-bracket"></i>
        </div>

        <h2 id="logoutTitle">Logout</h2>

        <p>Are you sure you want to logout?</p>

        <div class="logout-buttons">

            <button
                type="button"
                class="logout-confirm"
                id="confirmLogout">
                Yes, Logout
            </button>

            <button
                type="button"
                class="logout-cancel"
                id="cancelLogout">
                Cancel
            </button>

        </div>

    </div>

</div>

<!-- JAVASCRIPT -->

<script src="admin_dashboard.js"></script>

</body>
</html>