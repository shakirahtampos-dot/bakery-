
<?php

session_start();

include "db.php";


/* =========================================================
   ADMIN ACCESS
========================================================= */

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: admin_login.php");
    exit();
}


/* =========================================================
   PAYMENT DATA
========================================================= */

$totalRevenue = 125450.00;
$completedPayments = 85;
$pendingPayments = 12;
$failedPayments = 5;


/* =========================================================
   RECENT PAYMENTS
========================================================= */

$payments = [

    [
        "id" => "PAY-001",
        "customer" => "Juan",
        "order" => "#1021",
        "amount" => 450.00,
        "method" => "GCash",
        "status" => "Completed",
        "date" => "Sep 13, 2026"
    ],

    [
        "id" => "PAY-002",
        "customer" => "Maria",
        "order" => "#1022",
        "amount" => 620.00,
        "method" => "Cash",
        "status" => "Pending",
        "date" => "Sep 13, 2026"
    ],

    [
        "id" => "PAY-003",
        "customer" => "Alex",
        "order" => "#1023",
        "amount" => 350.00,
        "method" => "Maya",
        "status" => "Completed",
        "date" => "Sep 12, 2026"
    ],

    [
        "id" => "PAY-004",
        "customer" => "Sofia",
        "order" => "#1024",
        "amount" => 780.00,
        "method" => "Card",
        "status" => "Failed",
        "date" => "Sep 12, 2026"
    ],

    [
        "id" => "PAY-005",
        "customer" => "Daniel",
        "order" => "#1025",
        "amount" => 950.00,
        "method" => "GCash",
        "status" => "Completed",
        "date" => "Sep 11, 2026"
    ],

    [
        "id" => "PAY-006",
        "customer" => "Angela",
        "order" => "#1026",
        "amount" => 540.00,
        "method" => "Maya",
        "status" => "Completed",
        "date" => "Sep 11, 2026"
    ],

    [
        "id" => "PAY-007",
        "customer" => "Kevin",
        "order" => "#1027",
        "amount" => 850.00,
        "method" => "Cash",
        "status" => "Pending",
        "date" => "Sep 10, 2026"
    ],

    [
        "id" => "PAY-008",
        "customer" => "Nicole",
        "order" => "#1028",
        "amount" => 420.00,
        "method" => "GCash",
        "status" => "Completed",
        "date" => "Sep 10, 2026"
    ]

];

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
        name="theme-color"
        content="#252324"
    >

    <title>
        Admin Payments
    </title>


    <!-- =====================================================
         GOOGLE FONT
    ====================================================== -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- =====================================================
         FONT AWESOME
    ====================================================== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- =====================================================
         PAYMENT CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="admin_payment.css"
    >

</head>


<body>


<!-- =========================================================
     MOBILE MENU BUTTON
========================================================= -->
<!-- Burger button for mobile -->
<button id="mobileMenuBtn" class="mobile-menu-btn" aria-label="Open navigation">
    <i class="fa fa-bars"></i>
</button>

<!-- Sidebar overlay for mobile -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<!-- Sidebar -->
<aside id="sidebar" class="sidebar">
    

    <!-- PROFILE -->
    <div class="sidebar-profile">

        <div class="profile-image">

            <img
                src="Katelogo.png"
                alt="Kate's Goodies Logo" style="border-radius:none; border:none; height:auto; width:100%;"
            >

        </div>

        <div class="profile-info">

            <h3>Kate's Goodies</h3>

            <span>ADMINISTRATOR</span>

        </div>

    </div>

    <!-- YOUR EXISTING NAVIGATION CONTINUES HERE -->





    <!-- =====================================================
         NAVIGATION
    ====================================================== -->

    <nav class="sidebar-nav" style="margin: -75px 0 -50px;">


        <!-- DASHBOARD -->

        <a
            href="admin_dashboard.php"
            class="sidebar-link"
            data-page="admin_dashboard.php"
            data-tooltip="Dashboard"
        >

            <span class="sidebar-icon">
                <i class="fa-solid fa-house"></i>
            </span>

            <span class="sidebar-text">
                Dashboard
            </span>

        </a>



        <!-- MESSAGES -->

        <a
            href="admin_message.php"
            class="sidebar-link"
            data-page="admin_message.php"
            data-tooltip="Messages"
        >

            <span class="sidebar-icon">
                <i class="fa-solid fa-message"></i>
            </span>

            <span class="sidebar-text">
                Messages
            </span>

        </a>



        <!-- PRODUCTS -->

        <a
            href="manage_products.php"
            class="sidebar-link"
            data-page="manage_products.php"
            data-tooltip="Products"
        >

            <span class="sidebar-icon">
                <i class="fa-solid fa-box"></i>
            </span>

            <span class="sidebar-text">
                Products
            </span>

        </a>



        <!-- ORDERS -->

        <a
            href="manage_orders.php"
            class="sidebar-link"
            data-page="manage_orders.php"
            data-tooltip="Orders"
        >

            <span class="sidebar-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </span>

            <span class="sidebar-text">
                Orders
            </span>

        </a>



        <!-- PAYMENTS -->

        <a
            href="admin_payment.php"
            class="sidebar-link"
            data-page="admin_payment.php"
            data-tooltip="Payments"
        >

            <span class="sidebar-icon">
                <i class="fa-solid fa-credit-card"></i>
            </span>

            <span class="sidebar-text">
                Payments
            </span>

        </a>



        <!-- ANNOUNCEMENTS -->

        <a
            href="admin_announcements.php"
            class="sidebar-link"
            data-page="admin_announcements.php"
            data-tooltip="Announcements"
        >

            <span class="sidebar-icon">
                <i class="fa-solid fa-bullhorn"></i>
            </span>

            <span class="sidebar-text">
                Announcements
            </span>

        </a>



        <!-- USERS -->

        <a
            href="user.php"
            class="sidebar-link"
            data-page="user.php"
            data-tooltip="Users"
        >

            <span class="sidebar-icon">
                <i class="fa-solid fa-users"></i>
            </span>

            <span class="sidebar-text">
                Users
            </span>

        </a>



        <!-- SETTINGS -->

        <a
            href="admin_settings.php"
            class="sidebar-link"
            data-page="admin_settings.php"
            data-tooltip="Settings"
        >

            <span class="sidebar-icon">
                <i class="fa-solid fa-gear"></i>
            </span>

            <span class="sidebar-text">
                Settings
            </span>

        </a>


    </nav>



    <!-- =====================================================
         LOGOUT
    ====================================================== -->

    <div class="sidebar-bottom" style="margin: 23px 10px;">

        <button
            class="logout-btn"
            id="logoutBtn"
            type="button"
            data-tooltip="Logout"
        >

            <span class="sidebar-icon">

                <i class="fa-solid fa-right-from-bracket"></i>

            </span>

            <span class="sidebar-text">
                Logout
            </span>

        </button>

    </div>


</aside>



<!-- =========================================================
     MAIN
========================================================= -->

<main class="main">


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <header class="dashboard-header">

        <div>

            <span class="header-small">
                ADMIN PANEL
            </span>

            <h1>
                Payments
            </h1>

            <p>
                Track and manage customer payments.
            </p>

        </div>

    </header>



    <!-- =====================================================
         PAYMENT CONTENT
    ====================================================== -->

    <section class="payment-content">


        <!-- =================================================
             SUMMARY CARDS
        ================================================== -->

        <div class="payment-summary-grid">


            <!-- TOTAL REVENUE -->

            <div class="payment-stat-card">

                <div
                    class="payment-stat-icon revenue-icon"
                >

                    <i class="fa-solid fa-peso-sign"></i>

                </div>

                <div class="payment-stat-info">

                    <span>
                        Total Revenue
                    </span>

                    <h2>
                        ₱<?php echo number_format($totalRevenue, 2); ?>
                    </h2>

                </div>

            </div>



            <!-- COMPLETED -->

            <div class="payment-stat-card">

                <div
                    class="payment-stat-icon completed-icon"
                >

                    <i class="fa-solid fa-circle-check"></i>

                </div>

                <div class="payment-stat-info">

                    <span>
                        Completed
                    </span>

                    <h2>
                        <?php echo $completedPayments; ?>
                    </h2>

                </div>

            </div>



            <!-- PENDING -->

            <div class="payment-stat-card">

                <div
                    class="payment-stat-icon pending-icon"
                >

                    <i class="fa-solid fa-clock"></i>

                </div>

                <div class="payment-stat-info">

                    <span>
                        Pending
                    </span>

                    <h2>
                        <?php echo $pendingPayments; ?>
                    </h2>

                </div>

            </div>



            <!-- FAILED -->

            <div class="payment-stat-card">

                <div
                    class="payment-stat-icon failed-icon"
                >

                    <i class="fa-solid fa-circle-xmark"></i>

                </div>

                <div class="payment-stat-info">

                    <span>
                        Failed
                    </span>

                    <h2>
                        <?php echo $failedPayments; ?>
                    </h2>

                </div>

            </div>


        </div>



      


        <!-- =================================================
             RECENT PAYMENTS
        ================================================== -->

        <div class="dashboard-box recent-payments-box">


            <div class="payments-table-header">


                <div>

                    <span class="table-small-title">
                        TRANSACTIONS
                    </span>

                    <h2>
                        Recent Payments
                    </h2>

                </div>



                <div class="payment-table-actions">


                    <!-- SEARCH -->

                    <div class="payment-search">

                        <i
                            class="fa-solid fa-magnifying-glass"
                        ></i>

                        <input
                            type="text"
                            id="paymentSearch"
                            placeholder="Search payments..."
                            autocomplete="off"
                        >

                    </div>



                    <!-- FILTER -->

                    <select
                        id="paymentStatusFilter"
                        class="payment-filter"
                    >

                        <option value="all">
                            All Status
                        </option>

                        <option value="completed">
                            Completed
                        </option>

                        <option value="pending">
                            Pending
                        </option>

                        <option value="failed">
                            Failed
                        </option>

                    </select>


                </div>


            </div>



            <!-- =================================================
                 PAYMENT TABLE
            ================================================== -->

            <div class="payment-table-wrapper">

                <table
                    class="payment-table"
                    id="paymentTable"
                >

                    <thead>

                        <tr>

                            <th>
                                Payment ID
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Order
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Method
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Date
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($payments as $payment): ?>

                            <?php

                            $statusClass =
                                strtolower(
                                    $payment["status"]
                                );

                            ?>

                            <tr
                                data-status="<?php echo htmlspecialchars($statusClass); ?>"
                            >

                                <td>

                                    <strong>
                                        <?php
                                        echo htmlspecialchars(
                                            $payment["id"]
                                        );
                                        ?>
                                    </strong>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $payment["customer"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $payment["order"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <strong>
                                        ₱<?php
                                        echo number_format(
                                            $payment["amount"],
                                            2
                                        );
                                        ?>
                                    </strong>

                                </td>


                                <td>

                                    <span class="payment-method">

                                        <?php if ($payment["method"] === "GCash"): ?>

                                            <i class="fa-solid fa-mobile-screen-button"></i>

                                        <?php elseif ($payment["method"] === "Maya"): ?>

                                            <i class="fa-solid fa-wallet"></i>

                                        <?php elseif ($payment["method"] === "Cash"): ?>

                                            <i class="fa-solid fa-money-bill-wave"></i>

                                        <?php else: ?>

                                            <i class="fa-solid fa-credit-card"></i>

                                        <?php endif; ?>


                                        <?php
                                        echo htmlspecialchars(
                                            $payment["method"]
                                        );
                                        ?>

                                    </span>

                                </td>


                                <td>

                                    <span
                                        class="
                                            payment-status
                                            <?php echo $statusClass; ?>
                                        "
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            $payment["status"]
                                        );
                                        ?>

                                    </span>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $payment["date"]
                                    );
                                    ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>



            <!-- =================================================
                 EMPTY RESULT
            ================================================== -->

            <div
                class="payment-empty"
                id="paymentEmpty"
            >

                <i class="fa-solid fa-receipt"></i>

                <p>
                    No payments found.
                </p>

            </div>


        </div>


    </section>


</main>



<!-- =========================================================
     LOGOUT MODAL
========================================================= -->

<div
    class="logout-modal"
    id="logoutModal"
>


    <div class="logout-modal-content">


        <div class="logout-modal-icon">

            <i class="fa-solid fa-right-from-bracket"></i>

        </div>


        <h2>
            Logout
        </h2>


        <p>
            Are you sure you want to logout?
        </p>


        <div class="logout-buttons">


            <button
                type="button"
                class="logout-confirm"
                id="confirmLogout"
            >

                Yes, Logout

            </button>


            <button
                type="button"
                class="logout-cancel"
                id="cancelLogout"
            >

                Cancel

            </button>


        </div>


    </div>


</div>



<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script src="admin_payment.js"></script>


</body>

</html>
```
