<?php

require_once __DIR__ . '/../includes/functions.php';

require_admin();

if (!$conn) {
    json_error('Database unavailable.', 500);
}

$UPLOAD_DIR = __DIR__ . '/../uploads/products/';
$UPLOAD_URL = 'uploads/products/';

if (!is_dir($UPLOAD_DIR)) {
    mkdir($UPLOAD_DIR, 0755, true);
}

function handle_image_upload(string $uploadDir, string $uploadUrl): ?string
{
    if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $file = $_FILES['image'];
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset($allowed[$mime])) {
        json_error('Only JPG, PNG, and GIF images are allowed.');
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        json_error('Image must be smaller than 5MB.');
    }

    $filename = bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
    $destination = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        json_error('Could not save the uploaded image.', 500);
    }

    return $uploadUrl . $filename;
}

/*
|--------------------------------------------------------------------------
| LIST (GET)
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = mysqli_query(
        $conn,
        "SELECT id, title, price, category, image_url, is_active
         FROM products ORDER BY created_at DESC"
    );

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['id'] = (int) $row['id'];
        $row['price'] = (float) $row['price'];
        $products[] = $row;
    }

    json_success(['products' => $products]);
}

$action = $_POST['action'] ?? '';

/*
|--------------------------------------------------------------------------
| ADD
|--------------------------------------------------------------------------
*/
if ($action === 'add') {
    $title = trim($_POST['title'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $category = strtolower(trim($_POST['category'] ?? 'cookies'));
    $description = trim($_POST['description'] ?? '');

    if ($title === '' || $price <= 0) {
        json_error('Product title and a valid price are required.');
    }

    $imageUrl = handle_image_upload($UPLOAD_DIR, $UPLOAD_URL);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO products (title, description, price, category, image_url) VALUES (?, ?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "ssdss", $title, $description, $price, $category, $imageUrl);
    mysqli_stmt_execute($stmt);
    $newId = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    json_success(['message' => 'Product added.', 'id' => $newId]);
}

/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/
if ($action === 'edit') {
    $id = (int) ($_POST['productId'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $category = strtolower(trim($_POST['category'] ?? 'cookies'));
    $description = trim($_POST['description'] ?? '');

    if ($id <= 0 || $title === '' || $price <= 0) {
        json_error('Invalid product data.');
    }

    $imageUrl = handle_image_upload($UPLOAD_DIR, $UPLOAD_URL);

    if ($imageUrl !== null) {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE products SET title=?, description=?, price=?, category=?, image_url=? WHERE id=?"
        );
        mysqli_stmt_bind_param($stmt, "ssdssi", $title, $description, $price, $category, $imageUrl, $id);
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE products SET title=?, description=?, price=?, category=? WHERE id=?"
        );
        mysqli_stmt_bind_param($stmt, "ssdsi", $title, $description, $price, $category, $id);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    json_success(['message' => 'Product updated.']);
}

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/
if ($action === 'delete') {
    $id = (int) ($_POST['productId'] ?? 0);

    if ($id <= 0) {
        json_error('Invalid product.');
    }

    // Soft delete keeps historical order_items intact
    $stmt = mysqli_prepare($conn, "UPDATE products SET is_active = 0 WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    json_success(['message' => 'Product deleted.']);
}

json_error('Unknown action.');
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Products | Kate's Goodies Admin</title>

    <!-- GOOGLE FONT -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- FONT AWESOME -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <!-- MANAGE PRODUCTS CSS -->
    <link rel="stylesheet" href="manage_products.css">

</head>

<body>

    <!-- =====================================================
         MOBILE MENU BUTTON
    ====================================================== -->

    <button
        class="mobile-menu-btn"
        id="mobileMenuBtn"
        type="button"
        aria-label="Open navigation"
        aria-expanded="false"
        aria-controls="sidebar"
    >
        <i class="fa-solid fa-bars" id="menuIcon"></i>
    </button>


    <!-- =====================================================
         SIDEBAR OVERLAY
    ====================================================== -->

    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
        aria-hidden="true"
    ></div>


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="sidebar" id="sidebar" style="background-size: cover;
    background-repeat: no-repeat;">

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


        <!-- NAVIGATION -->

        <nav class="sidebar-nav" aria-label="Admin navigation">

            <a
                href="admin_dashboard.php"
                class="sidebar-link"
                data-page="admin_dashboard.php"
                data-tooltip="Dashboard"
            >
                <span class="sidebar-icon">
                    <i class="fa-solid fa-house"></i>
                </span>

                <span class="sidebar-text">Dashboard</span>
            </a>


            <a
                href="admin_message.php"
                class="sidebar-link"
                data-page="admin_message.php"
                data-tooltip="Messages"
            >
                <span class="sidebar-icon">
                    <i class="fa-solid fa-message"></i>
                </span>

                <span class="sidebar-text">Messages</span>
            </a>


            <a
                href="manage_products.php"
                class="sidebar-link"
                data-page="manage_products.php"
                data-tooltip="Products"
            >
                <span class="sidebar-icon">
                    <i class="fa-solid fa-box"></i>
                </span>

                <span class="sidebar-text">Products</span>
            </a>


            <a
                href="manage_orders.php"
                class="sidebar-link"
                data-page="manage_orders.php"
                data-tooltip="Orders"
            >
                <span class="sidebar-icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                </span>

                <span class="sidebar-text">Orders</span>
            </a>


            <a
                href="admin_payment.php"
                class="sidebar-link"
                data-page="admin_payment.php"
                data-tooltip="Payments"
            >
                <span class="sidebar-icon">
                    <i class="fa-solid fa-credit-card"></i>
                </span>

                <span class="sidebar-text">Payments</span>
            </a>


            <a
                href="admin_announcements.php"
                class="sidebar-link"
                data-page="admin_announcements.php"
                data-tooltip="Announcements"
            >
                <span class="sidebar-icon">
                    <i class="fa-solid fa-bullhorn"></i>
                </span>

                <span class="sidebar-text">Announcements</span>
            </a>


            <a
                href="user.php"
                class="sidebar-link"
                data-page="user.php"
                data-tooltip="Users"
            >
                <span class="sidebar-icon">
                    <i class="fa-solid fa-users"></i>
                </span>

                <span class="sidebar-text">Users</span>
            </a>


            <a
                href="admin_settings.php"
                class="sidebar-link"
                data-page="admin_settings.php"
                data-tooltip="Settings"
            >
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
                data-tooltip="Logout"
            >

                <span class="sidebar-icon">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </span>

                <span class="sidebar-text">Logout</span>

            </button>

        </div>

    </aside>


    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <main class="main">

        <!-- HEADER -->

        <header class="dashboard-header">

            <div>

                <span class="header-small">ADMIN PANEL</span>

                <h1>Products</h1>

                <p>Manage your store products</p>

            </div>

            <div class="header-right">

                <div class="product-count">
                    Total Products:
                    <span id="productCount">0</span>
                </div>

            </div>

        </header>


        <!-- FORM SECTION -->

        <section
            class="form-section"
            id="productFormSection"
        >

            <h2 id="formTitle">Add New Product</h2>

            <form
                id="productForm"
                enctype="multipart/form-data"
            >

                <input
                    type="hidden"
                    id="productId"
                    name="productId"
                >


                <div class="form-group">

                    <label for="title">Product Title</label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="Enter product name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="price">Price</label>

                    <input
                        type="number"
                        id="price"
                        name="price"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="image">Product Image</label>

                    <div
                        class="upload-area"
                        id="uploadArea"
                        tabindex="0"
                        role="button"
                        aria-label="Upload product image"
                    >

                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/png,image/jpeg,image/jpg,image/gif"
                            hidden
                        >


                        <div
                            class="upload-content"
                            id="uploadContent"
                        >

                            <i class="fas fa-cloud-upload-alt"></i>

                            <p>Click or drag to upload image</p>

                            <small>
                                JPG, PNG, GIF | Max 5MB
                            </small>

                        </div>


                        <div
                            class="image-preview"
                            id="imagePreview"
                        >

                            <img
                                src=""
                                alt="Product image preview"
                                id="previewImage"
                            >

                            <button
                                type="button"
                                id="removeImage"
                                aria-label="Remove image"
                            >
                                <i class="fas fa-times"></i>
                            </button>

                        </div>

                    </div>

                </div>


                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="submitBtn"
                    >
                        Add Product
                    </button>

                    <button
                        type="button"
                        class="btn btn-secondary"
                        id="cancelBtn"
                    >
                        Cancel
                    </button>

                </div>

            </form>

        </section>


        <!-- PRODUCTS LIST -->

        <section
            class="products-section"
            id="productsSection"
        >

            <h2>Existing Products</h2>

            <div class="table-container">

                <table
                    class="product-table"
                    id="productsTable"
                >

                    <thead>

                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>

                    </thead>

                    <tbody id="productsBody">

                        <!-- Products appear here -->

                    </tbody>

                </table>

            </div>

        </section>

    </main>


    <!-- =====================================================
         DELETE MODAL
    ====================================================== -->

    <div
        class="modal"
        id="deleteModal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="deleteTitle"
    >

        <div class="modal-content">

            <h3 id="deleteTitle">Delete Product?</h3>

            <p id="deleteMessage">Are you sure?</p>

            <div class="modal-actions">

                <button
                    type="button"
                    class="btn btn-secondary"
                    id="cancelDelete"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="btn btn-danger"
                    id="confirmDelete"
                >
                    Delete
                </button>

            </div>

        </div>

    </div>


    <!-- =====================================================
         LOGOUT MODAL
    ====================================================== -->

    <div
        class="logout-modal"
        id="logoutModal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="logoutTitle"
    >

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


    <!-- JAVASCRIPT -->

    <script src="manage_products.js"></script>

</body>
</html>