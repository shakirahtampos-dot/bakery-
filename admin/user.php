<?php
/* =============================================================
   USER MANAGEMENT — BACKEND LOGIC
   =============================================================
   This block was missing, which is why the page was showing
   "Undefined variable" warnings for $total_users, $active_users,
   $banned_users, $edit_user, $users, $csrf_token, $message, etc.
   All of those are now defined below before the HTML runs.

   ADJUST THIS LINE to match your actual database connection file
   (it must give you a mysqli connection in $conn):
   ============================================================= */

require_once 'db.php';   // <-- change filename if yours differs ($conn = mysqli_connect(...))

session_start();

$message = '';


/* -----------------------------------------------------------
   CSRF TOKEN
   ----------------------------------------------------------- */

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf_token = $_SESSION['csrf_token'];


function csrf_ok($conn, $token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
}


/* -----------------------------------------------------------
   HANDLE POST ACTIONS (create / update / ban / unban / delete)
   ----------------------------------------------------------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $posted_token = $_POST['csrf_token'] ?? '';

    if (!csrf_ok($conn, $posted_token)) {

        $message = '<div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> Invalid request. Please try again.</div>';

    } else {

        /* ---------- CREATE ---------- */
        if (isset($_POST['create'])) {

            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $message = '<div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> Please enter a valid email address.</div>';

            } elseif (strlen($password) < 8) {

                $message = '<div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> Password must be at least 8 characters.</div>';

            } else {

                $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
                mysqli_stmt_bind_param($check, "s", $email);
                mysqli_stmt_execute($check);
                mysqli_stmt_store_result($check);

                if (mysqli_stmt_num_rows($check) > 0) {

                    $message = '<div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> That email is already registered.</div>';

                } else {

                    $hashed = password_hash($password, PASSWORD_DEFAULT);

                    $insert = mysqli_prepare($conn, "INSERT INTO users (email, password, banned) VALUES (?, ?, 0)");
                    mysqli_stmt_bind_param($insert, "ss", $email, $hashed);

                    if (mysqli_stmt_execute($insert)) {
                        $message = '<div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> User created successfully.</div>';
                    } else {
                        $message = '<div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> Could not create user.</div>';
                    }

                    mysqli_stmt_close($insert);
                }

                mysqli_stmt_close($check);
            }
        }

        /* ---------- UPDATE ---------- */
        if (isset($_POST['update'])) {

            $id    = (int)($_POST['id'] ?? 0);
            $email = trim($_POST['email'] ?? '');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $message = '<div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> Please enter a valid email address.</div>';

            } else {

                $update = mysqli_prepare($conn, "UPDATE users SET email = ? WHERE id = ?");
                mysqli_stmt_bind_param($update, "si", $email, $id);

                if (mysqli_stmt_execute($update)) {
                    $message = '<div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> User updated successfully.</div>';
                } else {
                    $message = '<div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> Could not update user.</div>';
                }

                mysqli_stmt_close($update);
            }
        }

        /* ---------- BAN ---------- */
        if (isset($_POST['ban'])) {

            $id = (int)$_POST['ban'];

            $stmt = mysqli_prepare($conn, "UPDATE users SET banned = 1 WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $message = '<div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> User banned.</div>';
        }

        /* ---------- UNBAN ---------- */
        if (isset($_POST['unban'])) {

            $id = (int)$_POST['unban'];

            $stmt = mysqli_prepare($conn, "UPDATE users SET banned = 0 WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $message = '<div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> User unbanned.</div>';
        }

        /* ---------- DELETE ---------- */
        if (isset($_POST['delete'])) {

            $id = (int)$_POST['delete'];

            $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $message = '<div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> User deleted.</div>';
        }
    }
}


/* -----------------------------------------------------------
   EDIT MODE (?edit=ID)
   ----------------------------------------------------------- */

$edit_user = null;

if (isset($_GET['edit'])) {

    $edit_id = (int)$_GET['edit'];

    $stmt = mysqli_prepare($conn, "SELECT id, email FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $edit_user = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
}


/* -----------------------------------------------------------
   STATISTICS
   ----------------------------------------------------------- */

$total_users  = (int)mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users"))[0];
$banned_users = (int)mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE banned = 1"))[0];
$active_users = $total_users - $banned_users;


/* -----------------------------------------------------------
   USER LIST
   ----------------------------------------------------------- */

$users = mysqli_query($conn, "SELECT id, email, banned FROM users ORDER BY id ASC");

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>User Management | Admin</title>

    <!-- GOOGLE FONT -->
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
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- FONT AWESOME -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <!-- YOUR CSS -->
    <link
        rel="stylesheet"
        href="user.css"
    >

</head>


<body>


<!-- =========================================================
     MOBILE MENU / CLOSE BUTTON
     ========================================================= -->

<button
    type="button"
    class="mobile-menu-btn"
    id="mobileMenuBtn"
    aria-label="Open navigation"
    aria-controls="sidebar"
    aria-expanded="false"
>

    <span class="menu-lines">

        <span></span>
        <span></span>
        <span></span>

    </span>

</button>


<!-- =========================================================
     MOBILE OVERLAY
     ========================================================= -->

<div
    class="sidebar-overlay"
    id="sidebarOverlay"
    aria-hidden="true"
></div>


<!-- =========================================================
     SIDEBAR
     ========================================================= -->

<aside
    class="sidebar"
    id="sidebar"
    aria-label="Admin navigation" style="
    background-size: cover;background-repeat: no-repeat;"
>


    <!-- =====================================================
         PROFILE
         ===================================================== -->

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


    <!-- =====================================================
         NAVIGATION
         ===================================================== -->

    <nav class="sidebar-nav" style="margin: 25px 0 -50px;">


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
            class="sidebar-link active"
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
         ===================================================== -->

    <div class="sidebar-bottom">

        <button
            type="button"
            class="logout-btn"
            id="logoutBtn"
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
     MAIN CONTENT
     ========================================================= -->

<main class="main">


    <!-- =====================================================
         PAGE HEADER
         ===================================================== -->

    <header class="page-header">

        <span class="header-small">
            ADMIN PANEL
        </span>

        <h1>
            User Management
        </h1>

        <p>
            Manage customer accounts and account access.
        </p>

    </header>


    <!-- =====================================================
         PHP MESSAGE
         ===================================================== -->

    <?php

    if (!empty($message)) {
        echo $message;
    }

    ?>


    <!-- =====================================================
         USER STATISTICS
         ===================================================== -->

    <section class="user-stats">


        <div class="stat-card">

            <div class="stat-icon">

                <i class="fa-solid fa-users"></i>

            </div>

            <div class="stat-content">

                <span>TOTAL USERS</span>

                <strong>
                    <?php echo $total_users; ?>
                </strong>

                <small>
                    Registered customers
                </small>

            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon active-icon">

                <i class="fa-solid fa-user-check"></i>

            </div>

            <div class="stat-content">

                <span>ACTIVE USERS</span>

                <strong>
                    <?php echo $active_users; ?>
                </strong>

                <small>
                    Accounts currently active
                </small>

            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon banned-icon">

                <i class="fa-solid fa-user-slash"></i>

            </div>

            <div class="stat-content">

                <span>BANNED USERS</span>

                <strong>
                    <?php echo $banned_users; ?>
                </strong>

                <small>
                    Restricted accounts
                </small>

            </div>

        </div>

    </section>


    <!-- =====================================================
         ADD / EDIT USER
         ===================================================== -->

    <section class="form-card">

        <div class="section-heading">

            <div>

                <span>USER ACCOUNT</span>

                <h2>

                    <?php

                    echo $edit_user
                        ? 'Edit User'
                        : 'Add New User';

                    ?>

                </h2>

            </div>


            <?php if ($edit_user): ?>

                <a
                    href="user.php"
                    class="cancel-top"
                >

                    <i class="fa-solid fa-xmark"></i>

                    Cancel

                </a>

            <?php endif; ?>

        </div>


        <form
            method="post"
            action="user.php"
            class="user-form"
        >

            <input
                type="hidden"
                name="csrf_token"
                value="<?php echo htmlspecialchars($csrf_token); ?>"
            >


            <?php if ($edit_user): ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo (int)$edit_user['id']; ?>"
                >


                <div class="form-field">

                    <label for="edit-email">
                        User Email
                    </label>

                    <div class="input-wrapper">

                        <i class="fa-solid fa-envelope"></i>

                        <input
                            type="email"
                            name="email"
                            id="edit-email"
                            value="<?php echo htmlspecialchars($edit_user['email']); ?>"
                            required
                        >

                    </div>

                </div>


                <div class="form-actions">

                    <button
                        type="submit"
                        name="update"
                        class="primary-btn"
                    >

                        <i class="fa-solid fa-floppy-disk"></i>

                        Update User

                    </button>


                    <a
                        href="user.php"
                        class="secondary-btn"
                    >

                        Cancel

                    </a>

                </div>


            <?php else: ?>


                <div class="form-grid">


                    <div class="form-field">

                        <label for="email">
                            Email
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-envelope"></i>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="user@example.com"
                                required
                            >

                        </div>

                    </div>


                    <div class="form-field">

                        <label for="password">
                            Password
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-lock"></i>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Enter password"
                                minlength="8"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                id="passwordToggle"
                                aria-label="Show password"
                            >

                                <i class="fa-solid fa-eye"></i>

                            </button>

                        </div>

                    </div>

                </div>


                <div class="form-actions">

                    <button
                        type="submit"
                        name="create"
                        class="primary-btn"
                    >

                        <i class="fa-solid fa-user-plus"></i>

                        Add User

                    </button>

                </div>


            <?php endif; ?>

        </form>

    </section>


    <!-- =====================================================
         USERS TABLE
         ===================================================== -->

    <section class="users-card">

        <div class="section-heading">

            <div>

                <span>ALL CUSTOMERS</span>

                <h2>
                    User List
                </h2>

            </div>


            <div class="user-count">

                <i class="fa-solid fa-circle-info"></i>

                <?php echo $total_users; ?> total users

            </div>

        </div>


        <div class="table-wrapper">

            <table class="user-table">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Email</th>

                        <th>Status</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    <?php

                    if (
                        $users &&
                        mysqli_num_rows($users) > 0
                    ):

                        $row_number = 1;

                        while (
                            $user =
                            mysqli_fetch_assoc($users)
                        ):

                            $is_banned =
                                (int)$user['banned'] === 1;

                            $safe_email =
                                htmlspecialchars(
                                    $user['email']
                                );

                    ?>

                    <tr>


                        <td
                            class="user-number"
                            data-label="#"
                        >

                            <?php
                            echo $row_number;
                            ?>

                        </td>


                        <td data-label="Email">

                            <div class="email-cell">

                                <div class="user-avatar">

                                    <i class="fa-solid fa-user"></i>

                                </div>

                                <span class="user-email">

                                    <?php
                                    echo $safe_email;
                                    ?>

                                </span>

                            </div>

                        </td>


                        <td data-label="Status">

                            <?php if ($is_banned): ?>

                                <span class="status-badge banned">

                                    <i class="fa-solid fa-ban"></i>

                                    Banned

                                </span>

                            <?php else: ?>

                                <span class="status-badge active">

                                    <i class="fa-solid fa-circle-check"></i>

                                    Active

                                </span>

                            <?php endif; ?>

                        </td>


                        <td
                            class="actions"
                            data-label="Actions"
                        >


                            <a
                                href="user.php?edit=<?php echo (int)$user['id']; ?>"
                                class="action-btn edit"
                            >

                                <i class="fa-solid fa-pen"></i>

                                <span>Edit</span>

                            </a>


                            <?php if ($is_banned): ?>

                                <form
                                    method="post"
                                    action="user.php"
                                    class="inline-action-form"
                                >

                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?php echo htmlspecialchars($csrf_token); ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="unban"
                                        value="<?php echo (int)$user['id']; ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="action-btn unban"
                                    >

                                        <i class="fa-solid fa-user-check"></i>

                                        <span>Unban</span>

                                    </button>

                                </form>

                            <?php else: ?>

                                <form
                                    method="post"
                                    action="user.php"
                                    class="inline-action-form"
                                >

                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?php echo htmlspecialchars($csrf_token); ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="ban"
                                        value="<?php echo (int)$user['id']; ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="action-btn ban"
                                    >

                                        <i class="fa-solid fa-ban"></i>

                                        <span>Ban</span>

                                    </button>

                                </form>

                            <?php endif; ?>


                            <form
                                method="post"
                                action="user.php"
                                class="inline-action-form"
                                data-delete-form
                            >

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?php echo htmlspecialchars($csrf_token); ?>"
                                >

                                <input
                                    type="hidden"
                                    name="delete"
                                    value="<?php echo (int)$user['id']; ?>"
                                >

                                <button
                                    type="submit"
                                    class="action-btn delete"
                                    data-delete-user="<?php echo $safe_email; ?>"
                                >

                                    <i class="fa-solid fa-trash"></i>

                                    <span>Delete</span>

                                </button>

                            </form>


                        </td>

                    </tr>


                    <?php

                            $row_number++;

                        endwhile;

                    else:

                    ?>

                    <tr>

                        <td
                            colspan="4"
                            class="empty-users"
                        >

                            <div>

                                <i class="fa-solid fa-user-slash"></i>

                                <h3>
                                    No users found
                                </h3>

                                <p>
                                    There are no registered customers yet.
                                </p>

                            </div>

                        </td>

                    </tr>

                    <?php endif; ?>

                </tbody>

            </table>

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
            Log Out
        </h2>

        <p>
            Are you sure you want to log out of the admin panel?
        </p>

        <div class="logout-buttons">

            <button
                type="button"
                class="logout-cancel"
                id="cancelLogout"
            >
                Cancel
            </button>

            <button
                type="button"
                class="logout-confirm"
                id="confirmLogout"
            >
                Log Out
            </button>

        </div>

    </div>

</div>


<!-- =========================================================
     DELETE MODAL
     ========================================================= -->

<div
    class="delete-modal"
    id="deleteModal"
>

    <div class="delete-modal-content">

        <div class="delete-modal-icon">

            <i class="fa-solid fa-trash"></i>

        </div>

        <h2>
            Delete User
        </h2>

        <p id="deleteMessage">
            Are you sure you want to delete this user?
        </p>

        <div class="delete-buttons">

            <button
                type="button"
                class="delete-cancel"
                id="cancelDelete"
            >
                Cancel
            </button>

            <button
                type="button"
                class="delete-confirm"
                id="confirmDelete"
            >
                Delete
            </button>

        </div>

    </div>

</div>


<!-- JAVASCRIPT -->
<script src="user.js"></script>

</body>

</html>