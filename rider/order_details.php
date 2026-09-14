<?php
session_start();

include "db.php";

$update_message = '';
$order_id = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
$rider_id = $_SESSION['rider_id'];

if (!$order_id) {
    die("Invalid Order ID.");
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle status update
    if (isset($_POST['new_status'])) {
        $new_status = $_POST['new_status'];
        $allowed_statuses = ['out_for_delivery', 'delivered', 'delivery_failed'];
        if (in_array($new_status, $allowed_statuses)) {
            $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $update_stmt->bind_param("si", $new_status, $order_id);
            if ($update_stmt->execute()) {
                $order['status'] = $new_status; // Update status for display
                $update_message = "Status updated successfully!";
            } else {
                $update_message = "Error updating status.";
            }
        }
    }

    // Handle proof of delivery upload
    if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['proof_image'];
        $upload_dir = '../uploads/proofs/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($file_extension), $allowed_extensions)) {
            $new_filename = 'proof_' . $order_id . '_' . time() . '.' . $file_extension;
            $destination = $upload_dir . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                // Save file path to database
                $stmt_upload = $conn->prepare("UPDATE orders SET proof_of_delivery = ? WHERE id = ?");
                $stmt_upload->bind_param("si", $new_filename, $order_id);
                if ($stmt_upload->execute()) {
                    $order['proof_of_delivery'] = $new_filename; // Update for display
                    $update_message .= " Proof of delivery uploaded successfully.";
                } else {
                    $update_message .= " Error updating database with proof.";
                }
            } else {
                $update_message .= " Error moving uploaded file.";
            }
        } else {
            $update_message .= " Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } elseif (isset($_POST['new_status']) && $_POST['new_status'] === 'delivered' && (!isset($_FILES['proof_image']) || $_FILES['proof_image']['error'] !== UPLOAD_ERR_OK)) {
        // If setting to delivered, but no file is uploaded, show a warning if no proof exists yet.
        if (empty($order['proof_of_delivery'])) {
             $update_message .= " Please upload a proof of delivery image.";
        }
    }
}

// Fetch order details, ensuring it's assigned to the current rider
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND rider_id = ?");
$stmt->bind_param("ii", $order_id, $rider_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Order not found or not assigned to you.");
}
$order = $result->fetch_assoc();


// Possible statuses for the rider to select
$possible_statuses = [
    'out_for_delivery' => 'Out for Delivery',
    'delivered' => 'Delivered',
    'delivery_failed' => 'Delivery Failed'
];

$page_title = "Order Details #" . htmlspecialchars($order['id']);
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

    .container { max-width: 100%; margin: 0; padding: 0; background-color: transparent; box-shadow: none; }
    .dashboard-layout { display: flex; min-height: calc(100vh - 58px); }
    .sidebar { width: 260px; background-color: var(--medium-dark-bg); padding: 2rem 1rem; display: flex; flex-direction: column; border-right: 1px solid var(--border-color); }
    .sidebar-header h3 { color: var(--primary-green); text-transform: uppercase; letter-spacing: 2px; font-weight: 700; text-align: center; margin-bottom: 2.5rem; }
    .sidebar-nav ul { list-style: none; padding: 0; margin: 0; }
    .sidebar-nav .nav-link { display: block; padding: 1rem; color: var(--light-text); text-decoration: none; border-radius: 8px; margin-bottom: 0.5rem; transition: background-color 0.3s, color 0.3s; font-weight: 600; }
    .sidebar-nav .nav-link:hover { background-color: #333; }
    .sidebar-nav .nav-link.active { background-color: var(--primary-green); color: var(--dark-text); box-shadow: 0 0 15px rgba(68, 214, 44, 0.4); }
    .main-content { flex-grow: 1; padding: 2rem 3rem; }

    h2 { color: var(--light-text); border-bottom: 1px solid var(--border-color); padding-bottom: 10px; margin-top: 0; margin-bottom: 2rem; }
    .back-link { display: inline-block; margin-bottom: 2rem; color: var(--primary-green); text-decoration: none; font-weight: 600; }
    .back-link:hover { text-decoration: underline; }

    .update-message { padding: 1rem; background-color: rgba(68, 214, 44, 0.1); color: var(--primary-green); border: 1px solid var(--primary-green); border-radius: 8px; margin-bottom: 2rem; }

    .order-card { background-color: var(--content-bg); border-radius: 8px; padding: 2rem; box-shadow: 0 0 25px rgba(68, 214, 44, 0.1); }
    .detail-grid { display: grid; grid-template-columns: 180px 1fr; gap: 1rem; font-size: 1.1rem; }
    .detail-grid strong { color: #aaa; font-weight: 600; }
    .detail-grid span { font-weight: 600; }
    .detail-grid .status-badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 1rem; font-weight: 700; }
    .detail-grid .status-badge.processing { background-color: #ffc107; color: var(--dark-text); }
    .detail-grid .status-badge.out_for_delivery { background-color: #17a2b8; color: var(--light-text); }
    .detail-grid .status-badge.delivered { background-color: var(--primary-green); color: var(--dark-text); }
    .detail-grid .status-badge.delivery_failed { background-color: #dc3545; color: var(--light-text); }
    .proof-image { max-width: 100%; max-height: 400px; border-radius: 5px; margin-top: 15px; }
    .detail-grid .status-badge.paid, .detail-grid .status-badge.pending { background-color: #6c757d; color: var(--light-text); }

    .status-update { margin-top: 2.5rem; background-color: var(--content-bg); border-radius: 8px; padding: 2rem; }
    .status-update h3 { margin-top: 0; margin-bottom: 1.5rem; }
    .status-update form { display: flex; align-items: center; gap: 1rem; }
    .status-update select {
        flex-grow: 1;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 5px;
        font-family: 'Montserrat', sans-serif;
        background-color: #333;
        color: #fff;
        font-size: 1rem;
        font-weight: 600;
    }
    .status-update button {
        padding: 12px 24px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 700;
        background-color: var(--primary-green);
        color: var(--dark-text);
        transition: background-color 0.3s;
    }
    .status-update button:hover { background-color: #59f441; }
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
        <a href="my_active_deliveries.php" class="back-link">&laquo; Back to Active Deliveries</a>
        <h2>Order Details #<?= htmlspecialchars($order['id']) ?></h2>

        <?php if ($update_message): ?>
            <p class="update-message"><?= htmlspecialchars($update_message) ?></p>
        <?php endif; ?>

        <div class="order-card">
            <div class="detail-grid">
                <strong>Status:</strong>
                <span>
                    <span class="status-badge <?= htmlspecialchars($order['status']) ?>">
                        <?= htmlspecialchars(ucwords(str_replace('_', ' ', $order['status']))) ?>
                    </span>
                </span>

                <strong>Customer Name:</strong>
                <span><?= htmlspecialchars($order['full_name']) ?></span>

                <strong>Address:</strong>
                <span><?= htmlspecialchars($order['address']) ?></span>

                <strong>Phone:</strong>
                <span><?= htmlspecialchars($order['number'] ?? 'N/A') ?></span>

                <strong>Payment Method:</strong>
                <span><?= htmlspecialchars(strtoupper($order['payment_method'])) ?></span>

                <strong>Amount to Collect:</strong>
                <span>
                    <?php if (strtolower($order['payment_method']) === 'cod'): ?>
                        $<?= htmlspecialchars(number_format($order['total_price'], 2)) ?>
                    <?php else: echo 'N/A (Pre-Paid)'; endif; ?>
                </span>
            </div>

            <?php if (!empty($order['proof_of_delivery'])): ?>
                <div style="margin-top: 2rem;">
                    <strong>Proof of Delivery:</strong>
                    <div style="margin-top: 1rem;">
                        <a href="../uploads/proofs/<?= htmlspecialchars($order['proof_of_delivery']) ?>" target="_blank">
                            <img src="../uploads/proofs/<?= htmlspecialchars($order['proof_of_delivery']) ?>" alt="Proof of Delivery" class="proof-image">
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="status-update">
            <h3>Update Delivery Status</h3>
            <?php if (!in_array($order['status'], ['delivered', 'delivery_failed'])): ?>
                <form action="order_details.php?order_id=<?= $order_id ?>" method="POST" enctype="multipart/form-data" id="statusForm">
                    <select name="new_status">
                        <?php foreach ($possible_statuses as $value => $label): ?>
                            <?php // Only show options that are a logical next step
                            if ($order['status'] === 'processing' && $value !== 'out_for_delivery') continue;
                            if ($order['status'] === 'out_for_delivery' && !in_array($value, ['delivered', 'delivery_failed'])) continue;
                            ?>
                            <option value="<?= $value ?>" <?= $order['status'] === $value ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="proofUploadContainer" style="display: none;">
                        <label for="proof_image" class="form-label" style="display: block; margin-bottom: 5px;">Upload Proof:</label>
                        <input class="form-control" type="file" id="proof_image" name="proof_image" style="color: var(--light-text);">
                    </div>
                    <button type="submit">Update Status</button>
                </form>
            <?php else: ?>
                <p>This order has been completed and can no longer be updated.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.querySelector('select[name="new_status"]');
    const proofContainer = document.getElementById('proofUploadContainer');

    function toggleProofUpload() {
        if (statusSelect.value === 'delivered') {
            proofContainer.style.display = 'block';
        } else {
            proofContainer.style.display = 'none';
        }
    }

    if (statusSelect) {
        statusSelect.addEventListener('change', toggleProofUpload);
        toggleProofUpload(); // Initial check on page load
    }
});
</script>
<?php
include 'footer.php';
?>