<?php
session_start();

include "db.php";

if (!isset($_SESSION['rider_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$rider_id = $_SESSION['rider_id'];

// Fetch active/accepted orders for this rider
$active_orders_stmt = $conn->prepare(
    "SELECT id, full_name, address, status, payment_method, total_price
     FROM orders
     WHERE rider_id = ? AND status IN ('processing', 'out_for_delivery')
     ORDER BY field(status, 'out_for_delivery', 'processing'), created_at DESC"
);
$active_orders_stmt->bind_param("i", $rider_id);
$active_orders_stmt->execute();
$active_orders_result = $active_orders_stmt->get_result();
$active_orders = $active_orders_result->fetch_all(MYSQLI_ASSOC);

$page_title = 'My Active Deliveries';
include 'header.php';
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-green: #44d62c;
        --dark-bg: #111111;
        --medium-dark-bg: #1a1a1a;
        --content-bg: #222222;
        --light-text: #f0f0f0;
        --dark-text: #111111;
        --border-color: #333;
    }

    body {
        background-color: var(--dark-bg);
        color: var(--light-text);
        font-family: 'Montserrat', sans-serif;
    }

    .container {
        max-width: 100%;
        margin: 0;
        padding: 0;
        background-color: transparent;
        box-shadow: none;
    }

    .dashboard-layout {
        display: flex;
        min-height: calc(100vh - 58px);
    }

    .sidebar {
        width: 260px;
        background-color: var(--medium-dark-bg);
        padding: 2rem 1rem;
        display: flex;
        flex-direction: column;
        border-right: 1px solid var(--border-color);
    }

    .sidebar-header h3 {
        color: var(--primary-green);
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .sidebar-nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-nav .nav-link {
        display: block;
        padding: 1rem;
        color: var(--light-text);
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: background-color 0.3s, color 0.3s;
        font-weight: 600;
    }

    .sidebar-nav .nav-link:hover {
        background-color: #333;
    }

    .sidebar-nav .nav-link.active {
        background-color: var(--primary-green);
        color: var(--dark-text);
        box-shadow: 0 0 15px rgba(68, 214, 44, 0.4);
    }

    .main-content {
        flex-grow: 1;
        padding: 2rem 3rem;
    }

    h2 {
        color: var(--light-text);
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 10px;
        margin-top: 0;
        margin-bottom: 2rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 0 25px rgba(68, 214, 44, 0.2);
        background-color: var(--content-bg);
        border-radius: 8px;
        overflow: hidden;
    }

    thead tr {
        background-color: var(--primary-green);
        color: var(--dark-text);
        text-align: left;
        font-weight: 600;
    }

    th, td {
        padding: 18px 20px;
    }

    tbody tr {
        border-bottom: 1px solid var(--border-color);
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    tbody tr:last-of-type {
        border-bottom: none;
    }

    tbody tr:hover {
        background-color: #3c3c3c;
        transform: scale(1.01);
    }

    .action-btn {
        display: inline-block;
        padding: 8px 16px;
        color: var(--primary-green);
        border: 1px solid var(--primary-green);
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .action-btn:hover {
        background-color: var(--primary-green);
        color: var(--dark-text);
    }

    .no-deliveries {
        text-align: center;
        padding: 40px;
        font-size: 1.2em;
        color: #888;
    }
</style>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-header">
            <h3>Dashboard</h3>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="new_order_requests.php" class="nav-link">New Order Requests</a></li>
                <li><a href="my_active_deliveries.php" class="nav-link active">My Active Deliveries</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <h2>My Active Deliveries</h2>
        <?php if (empty($active_orders)): ?>
            <p class="no-deliveries">You have no active deliveries.</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Payment Method</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($active_orders as $order): ?>
                <tr>
                    <td>#<?= htmlspecialchars($order['id']) ?></td>
                    <td><?= htmlspecialchars($order['full_name']) ?></td>
                    <td><?= htmlspecialchars($order['address']) ?></td>
                    <td><?= htmlspecialchars(ucwords(str_replace('_', ' ', $order['status']))) ?></td>
                    <td><?= htmlspecialchars(strtoupper($order['payment_method'])) ?></td>
                    <td><a href="order_details.php?order_id=<?= $order['id'] ?>" class="action-btn">View Details</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </main>
</div>
<?php
include 'footer.php';
?>