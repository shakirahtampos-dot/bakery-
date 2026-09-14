
<?php
session_start();

include "db.php";

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

/* =========================================================
   SAFE QUERY
========================================================= */

function safe_query($conn, $sql) {
    if (!$conn) {
        return false;
    }

    try {
        return mysqli_query($conn, $sql);
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/* =========================================================
   UPDATE ORDER STATUS
========================================================= */

if (
    isset($_POST['update_status']) &&
    isset($_POST['order_id'], $_POST['status'])
) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $allowed_statuses = [
        'Pending',
        'Processing',
        'Delivered',
        'Cancelled'
    ];

    if (in_array($status, $allowed_statuses, true)) {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE orders SET status = ? WHERE id = ?"
        );

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "si",
                $status,
                $order_id
            );

            if (mysqli_stmt_execute($stmt)) {
                $message = "Order #" . $order_id .
                    " updated successfully!";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

/* =========================================================
   GET ALL ORDERS
========================================================= */

$orders = safe_query(
    $conn,
    "SELECT * FROM orders ORDER BY created_at DESC"
);

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Orders | Kate's Goodies</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect"
          href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="manage_orders.css">
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
    aria-controls="sidebar"
    aria-expanded="false">

    <i class="fa-solid fa-bars" id="menuIcon"></i>
</button>

<!-- =========================================================
     SIDEBAR OVERLAY
========================================================= -->

<div
    class="sidebar-overlay"
    id="sidebarOverlay"
    aria-hidden="true">
</div>

<!-- =========================================================
     SIDEBAR
========================================================= -->

<aside class="sidebar" id="sidebar">

    <!-- PROFILE -->

    <div class="sidebar-profile">

        <div class="profile-image">
            <img
                src="Katelogo.png"
                alt="Kate's Goodies Logo" style="border-radius:none; border:none; height:auto; width:100%;">
        </div>

        <div class="profile-info">
            <h3>Kate's Goodies</h3>
            <span>ADMINISTRATOR</span>
        </div>

    </div>

    <!-- NAVIGATION -->

    <nav class="sidebar-nav" aria-label="Admin navigation">

        <a
            href="admin_dashboard.php"
            class="sidebar-link<?php echo ($current_page == 'admin_dashboard.php') ? ' active' : ''; ?>"
            data-page="admin_dashboard.php"
            data-tooltip="Dashboard">

            <span class="sidebar-icon">
                <i class="fa-solid fa-house"></i>
            </span>

            <span class="sidebar-text">Dashboard</span>
        </a>

        <a
            href="admin_message.php"
            class="sidebar-link<?php echo ($current_page == 'admin_message.php') ? ' active' : ''; ?>"
            data-page="admin_message.php"
            data-tooltip="Messages">

            <span class="sidebar-icon">
                <i class="fa-solid fa-message"></i>
            </span>

            <span class="sidebar-text">Messages</span>
        </a>

        <a
            href="manage_products.php"
            class="sidebar-link<?php echo ($current_page == 'manage_products.php') ? ' active' : ''; ?>"
            data-page="manage_products.php"
            data-tooltip="Products">

            <span class="sidebar-icon">
                <i class="fa-solid fa-box"></i>
            </span>

            <span class="sidebar-text">Products</span>
        </a>

        <a
            href="manage_orders.php"
            class="sidebar-link<?php echo ($current_page == 'manage_orders.php') ? ' active' : ''; ?>"
            data-page="manage_orders.php"
            data-tooltip="Orders">

            <span class="sidebar-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </span>

            <span class="sidebar-text">Orders</span>
        </a>

        <a
            href="admin_payment.php"
            class="sidebar-link<?php echo ($current_page == 'admin_payment.php') ? ' active' : ''; ?>"
            data-page="admin_payment.php"
            data-tooltip="Payments">

            <span class="sidebar-icon">
                <i class="fa-solid fa-credit-card"></i>
            </span>

            <span class="sidebar-text">Payments</span>
        </a>

        <a
            href="admin_announcements.php"
            class="sidebar-link<?php echo ($current_page == 'admin_announcements.php') ? ' active' : ''; ?>"
            data-page="admin_announcements.php"
            data-tooltip="Announcements">

            <span class="sidebar-icon">
                <i class="fa-solid fa-bullhorn"></i>
            </span>

            <span class="sidebar-text">Announcements</span>
        </a>

        <a
            href="user.php"
            class="sidebar-link<?php echo ($current_page == 'user.php') ? ' active' : ''; ?>"
            data-page="user.php"
            data-tooltip="Users">

            <span class="sidebar-icon">
                <i class="fa-solid fa-users"></i>
            </span>

            <span class="sidebar-text">Users</span>
        </a>

        <a
            href="admin_settings.php"
            class="sidebar-link<?php echo ($current_page == 'admin_settings.php') ? ' active' : ''; ?>"
            data-page="admin_settings.php"
            data-tooltip="Settings">

            <span class="sidebar-icon">
                <i class="fa-solid fa-gear"></i>
            </span>

            <span class="sidebar-text">Settings</span>
        </a>

    </nav>

    <!-- LOGOUT -->

    <div class="sidebar-bottom">

        <button
            class="logout-btn"
            id="logoutBtn"
            type="button"
            data-tooltip="Logout">

            <span class="sidebar-icon">
                <i class="fa-solid fa-right-from-bracket"></i>
            </span>

            <span class="sidebar-text">Logout</span>
        </button>

    </div>

</aside>

<!-- =========================================================
     MAIN CONTENT
========================================================= -->

<main class="main">

    <header class="dashboard-header">

        <span class="header-small">ORDERS</span>

        <h1>Manage Orders</h1>

        <p>View and update customer orders.</p>

    </header>

    <?php if ($message): ?>

        <div class="msg">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <?php if (!$orders): ?>

        <div class="notice">
            <strong>Database Notice:</strong>
            The <code>orders</code> table does not exist or could
            not be accessed. Please check your database.
        </div>

    <?php else: ?>

        <div class="orders-container">

            <table class="orders-table">

                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Info</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (mysqli_num_rows($orders) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($orders)): ?>

                        <tr>

                            <td>
                                <strong>
                                    #<?php echo htmlspecialchars($row['id']); ?>
                                </strong>
                            </td>

                            <td>
                                <strong>
                                    <?php echo htmlspecialchars($row['name'] ?? 'N/A'); ?>
                                </strong>
                                <br>

                                <?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?>
                                <br>

                                <?php
                                $address =
                                    ($row['address'] ?? '') . ', ' .
                                    ($row['city'] ?? '');

                                echo htmlspecialchars(
                                    trim($address, ', ')
                                );
                                ?>
                            </td>

                            <td>
                                <strong>
                                    ₱<?php
                                    echo number_format(
                                        $row['total_amount']
                                        ?? $row['total']
                                        ?? 0,
                                        2
                                    );
                                    ?>
                                </strong>
                            </td>

                            <td>
                                <?php
                                $current_status =
                                    $row['status'] ?? 'Pending';

                                $status_class = '';

                                if ($current_status == 'Pending') {
                                    $status_class = 'status-pending';
                                } elseif ($current_status == 'Processing') {
                                    $status_class = 'status-processing';
                                } elseif ($current_status == 'Delivered') {
                                    $status_class = 'status-delivered';
                                } elseif ($current_status == 'Cancelled') {
                                    $status_class = 'status-cancelled';
                                }
                                ?>

                                <span class="status-text <?php echo $status_class; ?>">
                                    <?php echo htmlspecialchars($current_status); ?>
                                </span>
                            </td>

                            <td>
                                <?php echo htmlspecialchars(
                                    $row['created_at'] ?? 'N/A'
                                ); ?>
                            </td>

                            <td>
                                <?php
                                $order_id = intval($row['id']);

                                $items = safe_query(
                                    $conn,
                                    "SELECT * FROM order_items
                                     WHERE order_id = $order_id"
                                );
                                ?>

                                <?php if ($items && mysqli_num_rows($items) > 0): ?>

                                    <?php while ($item = mysqli_fetch_assoc($items)): ?>

                                        <div>
                                            <?php
                                            $product_name =
                                                $item['product_title']
                                                ?? $item['name']
                                                ?? 'Product';

                                            $quantity =
                                                intval(
                                                    $item['quantity'] ?? 1
                                                );

                                            echo htmlspecialchars($product_name);
                                            ?>

                                            <strong>
                                                (x<?php echo $quantity; ?>)
                                            </strong>
                                        </div>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <em>No items</em>

                                <?php endif; ?>
                            </td>

                            <td>
                                <form
                                    action=""
                                    method="POST"
                                    class="status-form">

                                    <input
                                        type="hidden"
                                        name="order_id"
                                        value="<?php echo htmlspecialchars($row['id']); ?>">

                                    <select
                                        name="status"
                                        class="status-select">

                                        <option value="Pending"
                                            <?php echo ($current_status == 'Pending') ? 'selected' : ''; ?>>
                                            Pending
                                        </option>

                                        <option value="Processing"
                                            <?php echo ($current_status == 'Processing') ? 'selected' : ''; ?>>
                                            Processing
                                        </option>

                                        <option value="Delivered"
                                            <?php echo ($current_status == 'Delivered') ? 'selected' : ''; ?>>
                                            Delivered
                                        </option>

                                        <option value="Cancelled"
                                            <?php echo ($current_status == 'Cancelled') ? 'selected' : ''; ?>>
                                            Cancelled
                                        </option>

                                    </select>

                                    <button
                                        type="submit"
                                        name="update_status"
                                        class="update-btn">

                                        Update
                                    </button>

                                </form>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7" class="empty-state">
                            No orders found.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>
            </table>

        </div>

    <?php endif; ?>

</main>

<!-- =========================================================
     LOGOUT MODAL
========================================================= -->

<div
    class="logout-modal"
    id="logoutModal"
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

<script src="manage_orders.js"></script>

</body>
</html>