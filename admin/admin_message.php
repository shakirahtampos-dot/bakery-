<?php
session_start();
include "config.php";

/* =========================================================
   ADMIN ACCESS CHECK
========================================================= */

if (
    !isset($_SESSION["user_id"]) ||
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] !== "admin"
) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = (int) $_SESSION["user_id"];

/* =========================================================
   SEND MESSAGE
========================================================= */

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["action"]) &&
    $_POST["action"] === "send_message"
) {
    header("Content-Type: application/json");

    $receiver_id = (int) ($_POST["receiver_id"] ?? 0);
    $message = trim($_POST["message"] ?? "");

    if ($receiver_id <= 0 || $message === "") {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid receiver or empty message."
        ]);
        exit();
    }

    $check = mysqli_prepare(
        $conn,
        "SELECT id FROM users WHERE id = ? AND role = 'user'"
    );

    mysqli_stmt_bind_param($check, "i", $receiver_id);
    mysqli_stmt_execute($check);

    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) === 0) {
        mysqli_stmt_close($check);

        echo json_encode([
            "status" => "error",
            "message" => "User does not exist."
        ]);
        exit();
    }

    mysqli_stmt_close($check);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO chats (sender_id, receiver_id, message)
         VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iis",
        $admin_id,
        $receiver_id,
        $message
    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    echo json_encode([
        "status" => $success ? "success" : "error",
        "message" => $success
            ? "Message sent."
            : "Unable to send message."
    ]);

    exit();
}

/* =========================================================
   GET USERS
========================================================= */

$users = mysqli_query(
    $conn,
    "SELECT id, email
     FROM users
     WHERE role = 'user'
     ORDER BY email ASC"
);

/* =========================================================
   SELECTED USER
========================================================= */

$chat_user = isset($_GET["user"])
    ? (int) $_GET["user"]
    : 0;

$chat_user_email = "";

if ($chat_user > 0) {
    $stmt = mysqli_prepare(
        $conn,
        "SELECT email
         FROM users
         WHERE id = ? AND role = 'user'"
    );

    mysqli_stmt_bind_param($stmt, "i", $chat_user);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $chat_user_email = $row["email"];
    }

    mysqli_stmt_close($stmt);
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

    <title>Admin Messages | Kates Goodies</title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <link
        rel="stylesheet"
        href="admin_message.css"
    >
</head>

<body>

<!-- =========================================================
     MOBILE BURGER BUTTON
     THIS BUTTON ALSO BECOMES THE X BUTTON
========================================================= -->

<button
    type="button"
    class="mobile-menu-btn"
    id="mobileMenuBtn"
    aria-label="Open navigation menu"
    aria-expanded="false"
    aria-controls="sidebar"
>
    <i class="fa-solid fa-bars"></i>
    
</button>

<!-- =========================================================
     SIDEBAR OVERLAY
========================================================= -->

<div
    class="sidebar-overlay"
    id="sidebarOverlay"
></div>

<!-- =========================================================
     SIDEBAR
     NO INSIDE X BUTTON
========================================================= -->

<aside
    class="sidebar"
    id="sidebar"
    style="
        background-image: url('sidebarbckgrd.jpg');
        background-size: cover;
        background-repeat: no-repeat;
    "
>

    <!-- PROFILE -->

    <div class="sidebar-profile">

        <div class="profile-image">
            <img
                src="Katelogo.png"
                alt="Kates Goodies Logo" style="border-radius:none; border:none; height:auto; width:100%;"
            >
        </div>

        <div class="profile-info">
            <h3 style="margin: -4.4px;">Kate's Goodies</h3>
            <span style="margin: 7px;">ADMINISTRATOR</span>
        </div>

    </div>

    <!-- NAVIGATION -->

    <nav class="sidebar-nav" style="margin: -55px 0 -50px;">

        <a
            href="admin_dashboard.php"
            class="sidebar-link"
            data-page="admin_dashboard.php"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-house"></i>
            </span>

            <span class="sidebar-text">
                Dashboard
            </span>
        </a>

        <a
            href="admin_message.php"
            class="sidebar-link active"
            data-page="admin_message.php"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-message"></i>
            </span>

            <span class="sidebar-text">
                Messages
            </span>
        </a>

        <a
            href="manage_products.php"
            class="sidebar-link"
            data-page="manage_products.php"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-box"></i>
            </span>

            <span class="sidebar-text">
                Products
            </span>
        </a>

        <a
            href="manage_orders.php"
            class="sidebar-link"
            data-page="manage_orders.php"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </span>

            <span class="sidebar-text">
                Orders
            </span>
        </a>

        <a
            href="admin_payment.php"
            class="sidebar-link"
            data-page="admin_payment.php"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-credit-card"></i>
            </span>

            <span class="sidebar-text">
                Payments
            </span>
        </a>

        <a
            href="admin_announcements.php"
            class="sidebar-link"
            data-page="admin_announcements.php"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-bullhorn"></i>
            </span>

            <span class="sidebar-text">
                Announcements
            </span>
        </a>

        <a
            href="user.php"
            class="sidebar-link"
            data-page="user.php"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-users"></i>
            </span>

            <span class="sidebar-text">
                Users
            </span>
        </a>

        <a
            href="admin_settings.php"
            class="sidebar-link"
            data-page="admin_settings.php"
        >
            <span class="sidebar-icon">
                <i class="fa-solid fa-gear"></i>
            </span>

            <span class="sidebar-text">
                Settings
            </span>
        </a>

    </nav>

    <!-- LOGOUT -->

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

    <header class="page-header">
        <span class="page-label">
            ADMIN PANEL
        </span>

        <h1>Admin Messenger</h1>

        <p>
            Communicate with your customers.
        </p>
    </header>

    <!-- MESSENGER -->

    <section class="messenger">

        <!-- USER LIST -->

        <div class="user-panel">

            <div class="user-panel-header">

                <div class="user-title">
                    <i class="fa-solid fa-users"></i>
                    <span>Users</span>
                </div>

                <span class="user-count">
                    <?php echo mysqli_num_rows($users); ?>
                </span>

            </div>

            <div class="users">

                <?php if (mysqli_num_rows($users) > 0): ?>

                    <?php while ($u = mysqli_fetch_assoc($users)): ?>

                        <a
                            href="admin_message.php?user=<?php echo (int) $u["id"]; ?>"
                            class="user-item <?php echo ($chat_user === (int) $u["id"]) ? "active" : ""; ?>"
                        >

                            <div class="user-avatar">
                                <?php
                                echo htmlspecialchars(
                                    strtoupper(substr($u["email"], 0, 1))
                                );
                                ?>
                            </div>

                            <div class="user-details">

                                <span class="user-email">
                                    <?php
                                    echo htmlspecialchars($u["email"]);
                                    ?>
                                </span>

                                <small>
                                    Customer
                                </small>

                            </div>

                            <i class="fa-solid fa-chevron-right user-arrow"></i>

                        </a>

                    <?php endwhile; ?>

                <?php else: ?>

                    <div class="no-users">
                        <i class="fa-solid fa-user-slash"></i>
                        <p>No users available.</p>
                    </div>

                <?php endif; ?>

            </div>

        </div>

        <!-- CHAT AREA -->

        <div class="chat-area">

            <?php if ($chat_user > 0 && !empty($chat_user_email)): ?>

                <div class="chat-header">

                    <div class="chat-user-avatar">
                        <?php
                        echo htmlspecialchars(
                            strtoupper(substr($chat_user_email, 0, 1))
                        );
                        ?>
                    </div>

                    <div class="chat-user-info">

                        <strong>
                            <?php
                            echo htmlspecialchars($chat_user_email);
                            ?>
                        </strong>

                        <span>
                            <i class="fa-solid fa-circle"></i>
                            Active conversation
                        </span>

                    </div>

                </div>

                <div
                    class="chat-messages"
                    id="chatMessages"
                >
                    <div class="loading-chat">
                        <div class="loading-spinner"></div>
                        <span>Loading messages...</span>
                    </div>
                </div>

                <form
                    id="chatForm"
                    class="chat-form"
                    autocomplete="off"
                >

                    <input
                        type="hidden"
                        name="receiver_id"
                        value="<?php echo $chat_user; ?>"
                    >

                    <div class="message-input-wrapper">

                        <input
                            type="text"
                            id="messageInput"
                            name="message"
                            placeholder="Type your message..."
                            maxlength="1000"
                            required
                        >

                        <button
                            type="submit"
                            class="send-btn"
                            id="sendBtn"
                        >
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Send</span>
                        </button>

                    </div>

                </form>

            <?php else: ?>

                <div class="no-chat">

                    <div class="no-chat-icon">
                        <i class="fa-solid fa-comments"></i>
                    </div>

                    <h2>
                        Welcome to Admin Messenger
                    </h2>

                    <p>
                        Select a customer from the user list
                        to start a conversation.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>

</main>

<!-- =========================================================
     LOGOUT MODAL
========================================================= -->

<div
    class="modal"
    id="logoutModal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="logoutTitle"
    aria-hidden="true"
>

    <div class="modal-content">

        <div class="modal-icon">
            <i class="fa-solid fa-right-from-bracket"></i>
        </div>

        <h3 id="logoutTitle">
            Logout
        </h3>

        <p>
            Are you sure you want to logout?
        </p>

        <div class="modal-buttons">

            <button
                type="button"
                class="confirm"
                id="confirmLogout"
            >
                <i class="fa-solid fa-check"></i>
                Yes, Logout
            </button>

            <button
                type="button"
                class="cancel"
                id="cancelLogout"
            >
                Cancel
            </button>

        </div>

    </div>

</div>

<script src="admin_message.js"></script>

</body>
</html>