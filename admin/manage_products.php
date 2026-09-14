
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