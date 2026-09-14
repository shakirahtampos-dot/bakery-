
<?php

/*
|--------------------------------------------------------------------------
| START SESSION
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| LOAD FILES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/function.php';
require_once __DIR__ . '/validation.php';

/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

$conn = null;

try {
    $conn = getConnection();
} catch (Throwable $e) {
    $conn = null;
}

/*
|--------------------------------------------------------------------------
| BASIC INFORMATION
|--------------------------------------------------------------------------
*/

$brandName = "Kates Goodies";

/*
|--------------------------------------------------------------------------
| SESSION USER
|--------------------------------------------------------------------------
*/

$userId = isset($_SESSION['user_id'])
    ? (int) $_SESSION['user_id']
    : 0;

$fullname = $_SESSION['full_name']
    ?? $_SESSION['fullname']
    ?? $_SESSION['name']
    ?? 'Guest';

$username = $_SESSION['username'] ?? '';
$email = $_SESSION['email'] ?? '';
$role = $_SESSION['role'] ?? 'Customer';

/*
|--------------------------------------------------------------------------
| GUEST CART SESSION
|--------------------------------------------------------------------------
*/

if (empty($_SESSION['cart_session_id'])) {
    $_SESSION['cart_session_id'] = session_id();
}

$sessionId = $_SESSION['cart_session_id'];

/*
|--------------------------------------------------------------------------
| JSON RESPONSE FUNCTIONS
|--------------------------------------------------------------------------
*/

function cart_json_success(array $data = []): never
{
    header('Content-Type: application/json; charset=UTF-8');

    echo json_encode(
        array_merge(['success' => true], $data)
    );

    exit;
}

function cart_json_error(
    string $message,
    int $statusCode = 400
): never {
    http_response_code($statusCode);

    header('Content-Type: application/json; charset=UTF-8');

    echo json_encode([
        'success' => false,
        'message' => $message
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| GET REQUEST INPUT
|--------------------------------------------------------------------------
*/

function cart_get_input(): array
{
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if (stripos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');

        if (!$raw) {
            return [];
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    return $_POST;
}

/*
|--------------------------------------------------------------------------
| CART OWNER CONDITION
|--------------------------------------------------------------------------
*/

function cart_owner_where(
    int $userId,
    string $sessionId
): array {
    if ($userId > 0) {
        return [
            'ci.user_id = ?',
            'i',
            $userId
        ];
    }

    return [
        'ci.session_id = ?',
        's',
        $sessionId
    ];
}

/*
|--------------------------------------------------------------------------
| GET CART ITEMS
|--------------------------------------------------------------------------
|
| PRODUCTS TABLE:
| id, name, description, category, price, sale_price,
| image, stock, status, created_at, updated_at
|
| sale_price is used when it is greater than zero.
| Otherwise, the regular price is used.
|--------------------------------------------------------------------------
*/

function getCartItems(
    mysqli $conn,
    int $userId,
    string $sessionId
): array {
    if (!$conn) {
        return [];
    }

    [$where, $type, $value] = cart_owner_where(
        $userId,
        $sessionId
    );

    $sql = "
        SELECT
            ci.id AS cart_item_id,
            ci.quantity,

            p.id AS product_id,
            p.name AS title,
            p.description,
            p.category,
            p.image AS image_url,
            p.stock,
            p.status,

            CASE
                WHEN p.sale_price IS NOT NULL
                     AND p.sale_price > 0
                THEN p.sale_price
                ELSE p.price
            END AS price

        FROM cart_items ci

        INNER JOIN products p
            ON p.id = ci.product_id

        WHERE $where

        ORDER BY ci.id DESC
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return [];
    }

    mysqli_stmt_bind_param($stmt, $type, $value);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return [];
    }

    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        mysqli_stmt_close($stmt);
        return [];
    }

    $items = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $row['cart_item_id'] = (int) $row['cart_item_id'];
        $row['product_id'] = (int) $row['product_id'];
        $row['quantity'] = (int) $row['quantity'];
        $row['price'] = (float) $row['price'];
        $row['stock'] = (int) $row['stock'];

        if (empty($row['title'])) {
            $row['title'] = 'Kates Goodies Product';
        }

        if (empty($row['category'])) {
            $row['category'] = 'Goodies';
        }

        $items[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $items;
}

/*
|--------------------------------------------------------------------------
| CALCULATE CART TOTALS
|--------------------------------------------------------------------------
*/

function calculateCartTotals(array $items): array
{
    $subtotal = 0;
    $count = 0;

    foreach ($items as $item) {
        $price = (float) ($item['price'] ?? 0);
        $quantity = (int) ($item['quantity'] ?? 0);

        $subtotal += $price * $quantity;
        $count += $quantity;
    }

    $deliveryFee = $count > 0 ? 50 : 0;

    return [
        'subtotal' => round($subtotal, 2),
        'delivery' => round($deliveryFee, 2),
        'total' => round($subtotal + $deliveryFee, 2),
        'count' => $count
    ];
}

/*
|--------------------------------------------------------------------------
| CHECK PRODUCT AVAILABILITY
|--------------------------------------------------------------------------
|
| Uses the actual products table:
| stock and status
|
| This accepts common available status values:
| available, active, and 1.
|--------------------------------------------------------------------------
*/

function cart_product_exists(
    mysqli $conn,
    int $productId
): bool {
    $sql = "
        SELECT id
        FROM products
        WHERE id = ?
        AND stock > 0
        AND (
            status = 'available'
            OR status = 'Available'
            OR status = 'active'
            OR status = 'Active'
            OR status = 1
        )
        LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, 'i', $productId);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);

    $exists = mysqli_stmt_num_rows($stmt) > 0;

    mysqli_stmt_close($stmt);

    return $exists;
}

/*
|--------------------------------------------------------------------------
| GET CURRENT PRODUCT STOCK
|--------------------------------------------------------------------------
*/

function cart_get_product_stock(
    mysqli $conn,
    int $productId
): int {
    $stmt = mysqli_prepare(
        $conn,
        "
        SELECT stock
        FROM products
        WHERE id = ?
        LIMIT 1
        "
    );

    if (!$stmt) {
        return 0;
    }

    mysqli_stmt_bind_param($stmt, 'i', $productId);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $row = $result
        ? mysqli_fetch_assoc($result)
        : null;

    mysqli_stmt_close($stmt);

    return $row ? (int) $row['stock'] : 0;
}

/*
|--------------------------------------------------------------------------
| GET CART INPUT
|--------------------------------------------------------------------------
*/

$input = cart_get_input();

$action = $input['action']
    ?? $_GET['action']
    ?? null;

/*
|--------------------------------------------------------------------------
| AJAX / API ACTIONS
|--------------------------------------------------------------------------
*/

if ($action !== null) {
    if (!$conn) {
        cart_json_error(
            'Database connection failed.',
            500
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GET CART
    |--------------------------------------------------------------------------
    */

    if ($action === 'get') {
        $items = getCartItems(
            $conn,
            $userId,
            $sessionId
        );

        $totals = calculateCartTotals($items);

        cart_json_success([
            'items' => $items,
            'subtotal' => $totals['subtotal'],
            'delivery' => $totals['delivery'],
            'total' => $totals['total'],
            'count' => $totals['count']
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADD ITEM
    |--------------------------------------------------------------------------
    */

    if ($action === 'add') {
        $productId = (int) (
            $input['product_id'] ?? 0
        );

        $quantity = (int) (
            $input['quantity'] ?? 1
        );

        if ($productId <= 0) {
            cart_json_error('Invalid product.');
        }

        if ($quantity <= 0) {
            $quantity = 1;
        }

        if (!cart_product_exists($conn, $productId)) {
            cart_json_error(
                'Product is unavailable or out of stock.',
                404
            );
        }

        $stock = cart_get_product_stock(
            $conn,
            $productId
        );

        if ($quantity > $stock) {
            cart_json_error(
                'The requested quantity exceeds available stock.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOGGED-IN USER
        |--------------------------------------------------------------------------
        */

        if ($userId > 0) {
            $stmt = mysqli_prepare(
                $conn,
                "
                SELECT id, quantity
                FROM cart_items
                WHERE user_id = ?
                AND product_id = ?
                LIMIT 1
                "
            );

            if (!$stmt) {
                cart_json_error(
                    'Unable to check cart.',
                    500
                );
            }

            mysqli_stmt_bind_param(
                $stmt,
                'ii',
                $userId,
                $productId
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $existing = $result
                ? mysqli_fetch_assoc($result)
                : null;

            mysqli_stmt_close($stmt);

            if ($existing) {
                $newQuantity =
                    (int) $existing['quantity']
                    + $quantity;

                if ($newQuantity > $stock) {
                    cart_json_error(
                        'You cannot add more than the available stock.'
                    );
                }

                $stmt = mysqli_prepare(
                    $conn,
                    "
                    UPDATE cart_items
                    SET quantity = ?
                    WHERE user_id = ?
                    AND product_id = ?
                    "
                );

                if (!$stmt) {
                    cart_json_error(
                        'Unable to update cart.',
                        500
                    );
                }

                mysqli_stmt_bind_param(
                    $stmt,
                    'iii',
                    $newQuantity,
                    $userId,
                    $productId
                );
            } else {
                $stmt = mysqli_prepare(
                    $conn,
                    "
                    INSERT INTO cart_items
                    (user_id, product_id, quantity)
                    VALUES (?, ?, ?)
                    "
                );

                if (!$stmt) {
                    cart_json_error(
                        'Unable to add item to cart.',
                        500
                    );
                }

                mysqli_stmt_bind_param(
                    $stmt,
                    'iii',
                    $userId,
                    $productId,
                    $quantity
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | GUEST USER
        |--------------------------------------------------------------------------
        */

        else {
            $stmt = mysqli_prepare(
                $conn,
                "
                SELECT id, quantity
                FROM cart_items
                WHERE session_id = ?
                AND product_id = ?
                LIMIT 1
                "
            );

            if (!$stmt) {
                cart_json_error(
                    'Unable to check cart.',
                    500
                );
            }

            mysqli_stmt_bind_param(
                $stmt,
                'si',
                $sessionId,
                $productId
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $existing = $result
                ? mysqli_fetch_assoc($result)
                : null;

            mysqli_stmt_close($stmt);

            if ($existing) {
                $newQuantity =
                    (int) $existing['quantity']
                    + $quantity;

                if ($newQuantity > $stock) {
                    cart_json_error(
                        'You cannot add more than the available stock.'
                    );
                }

                $stmt = mysqli_prepare(
                    $conn,
                    "
                    UPDATE cart_items
                    SET quantity = ?
                    WHERE session_id = ?
                    AND product_id = ?
                    "
                );

                if (!$stmt) {
                    cart_json_error(
                        'Unable to update cart.',
                        500
                    );
                }

                mysqli_stmt_bind_param(
                    $stmt,
                    'isi',
                    $newQuantity,
                    $sessionId,
                    $productId
                );
            } else {
                $stmt = mysqli_prepare(
                    $conn,
                    "
                    INSERT INTO cart_items
                    (session_id, product_id, quantity)
                    VALUES (?, ?, ?)
                    "
                );

                if (!$stmt) {
                    cart_json_error(
                        'Unable to add item to cart.',
                        500
                    );
                }

                mysqli_stmt_bind_param(
                    $stmt,
                    'sii',
                    $sessionId,
                    $productId,
                    $quantity
                );
            }
        }

        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            cart_json_error(
                'Unable to add item to cart.',
                500
            );
        }

        mysqli_stmt_close($stmt);

        $items = getCartItems(
            $conn,
            $userId,
            $sessionId
        );

        $totals = calculateCartTotals($items);

        cart_json_success([
            'message' => 'Item added to cart.',
            'count' => $totals['count']
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE QUANTITY
    |--------------------------------------------------------------------------
    */

    if ($action === 'update') {
        $cartItemId = (int) (
            $input['cart_item_id'] ?? 0
        );

        $quantity = (int) (
            $input['quantity'] ?? 0
        );

        if ($cartItemId <= 0) {
            cart_json_error('Invalid cart item.');
        }

        /*
        |--------------------------------------------------------------------------
        | OWNER
        |--------------------------------------------------------------------------
        */

        if ($userId > 0) {
            $ownerField = 'user_id = ?';
            $ownerType = 'i';
            $ownerValue = $userId;
        } else {
            $ownerField = 'session_id = ?';
            $ownerType = 's';
            $ownerValue = $sessionId;
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE IF ZERO
        |--------------------------------------------------------------------------
        */

        if ($quantity <= 0) {
            $stmt = mysqli_prepare(
                $conn,
                "
                DELETE FROM cart_items
                WHERE id = ?
                AND $ownerField
                "
            );

            if (!$stmt) {
                cart_json_error(
                    'Unable to remove cart item.',
                    500
                );
            }

            mysqli_stmt_bind_param(
                $stmt,
                'i' . $ownerType,
                $cartItemId,
                $ownerValue
            );
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE QUANTITY
        |--------------------------------------------------------------------------
        */

        else {
            /*
            | Get product ID for this cart item.
            */

            $lookup = mysqli_prepare(
                $conn,
                "
                SELECT product_id
                FROM cart_items
                WHERE id = ?
                AND $ownerField
                LIMIT 1
                "
            );

            if (!$lookup) {
                cart_json_error(
                    'Unable to check cart item.',
                    500
                );
            }

            mysqli_stmt_bind_param(
                $lookup,
                'i' . $ownerType,
                $cartItemId,
                $ownerValue
            );

            mysqli_stmt_execute($lookup);

            $lookupResult = mysqli_stmt_get_result($lookup);

            $lookupRow = $lookupResult
                ? mysqli_fetch_assoc($lookupResult)
                : null;

            mysqli_stmt_close($lookup);

            if (!$lookupRow) {
                cart_json_error(
                    'Cart item not found.',
                    404
                );
            }

            $stock = cart_get_product_stock(
                $conn,
                (int) $lookupRow['product_id']
            );

            if ($quantity > $stock) {
                cart_json_error(
                    'The requested quantity exceeds available stock.'
                );
            }

            $stmt = mysqli_prepare(
                $conn,
                "
                UPDATE cart_items
                SET quantity = ?
                WHERE id = ?
                AND $ownerField
                "
            );

            if (!$stmt) {
                cart_json_error(
                    'Unable to update cart.',
                    500
                );
            }

            mysqli_stmt_bind_param(
                $stmt,
                'ii' . $ownerType,
                $quantity,
                $cartItemId,
                $ownerValue
            );
        }

        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            cart_json_error(
                'Unable to update cart.',
                500
            );
        }

        mysqli_stmt_close($stmt);

        $items = getCartItems(
            $conn,
            $userId,
            $sessionId
        );

        $totals = calculateCartTotals($items);

        cart_json_success([
            'message' => 'Cart updated.',
            'count' => $totals['count']
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE ITEM
    |--------------------------------------------------------------------------
    */

    if ($action === 'remove') {
        $cartItemId = (int) (
            $input['cart_item_id'] ?? 0
        );

        if ($cartItemId <= 0) {
            cart_json_error('Invalid cart item.');
        }

        if ($userId > 0) {
            $ownerField = 'user_id = ?';
            $ownerType = 'i';
            $ownerValue = $userId;
        } else {
            $ownerField = 'session_id = ?';
            $ownerType = 's';
            $ownerValue = $sessionId;
        }

        $stmt = mysqli_prepare(
            $conn,
            "
            DELETE FROM cart_items
            WHERE id = ?
            AND $ownerField
            "
        );

        if (!$stmt) {
            cart_json_error(
                'Unable to remove item.',
                500
            );
        }

        mysqli_stmt_bind_param(
            $stmt,
            'i' . $ownerType,
            $cartItemId,
            $ownerValue
        );

        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            cart_json_error(
                'Unable to remove item.',
                500
            );
        }

        mysqli_stmt_close($stmt);

        $items = getCartItems(
            $conn,
            $userId,
            $sessionId
        );

        $totals = calculateCartTotals($items);

        cart_json_success([
            'message' => 'Item removed.',
            'count' => $totals['count']
        ]);
    }

    cart_json_error('Unknown cart action.');
}

/*
|--------------------------------------------------------------------------
| LOAD CART FOR PAGE
|--------------------------------------------------------------------------
*/

$cartItems = [];

$cartTotals = [
    'subtotal' => 0,
    'delivery' => 0,
    'total' => 0,
    'count' => 0
];

if ($conn) {
    $cartItems = getCartItems(
        $conn,
        $userId,
        $sessionId
    );

    $cartTotals = calculateCartTotals($cartItems);
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
        <?php
        echo htmlspecialchars(
            $brandName,
            ENT_QUOTES,
            'UTF-8'
        );
        ?>
        - My Cart
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&display=swap"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css?family=Poppins"
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

    <link rel="stylesheet" href="cart.css">
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
                alt="Kates Goodies"
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

        <a
            href="home.php"
            style="font-family:'Playfair Display';font-weight:bold;"
        >
            HOME
        </a>

        <a
            href="menu.php"
            style="font-family:'Playfair Display';font-weight:bold;"
        >
            MENU
        </a>

        <a
            href="order.php"
            style="font-family:'Playfair Display';font-weight:bold;"
        >
            MY ORDERS
        </a>

        <a
            href="about_us.php"
            style="font-family:'Playfair Display';font-weight:bold;"
        >
            ABOUT US
        </a>

        <a
            href="contact_us.php"
            style="font-family:'Playfair Display';font-weight:bold;"
        >
            CONTACT US
        </a>

    </nav>

    <div class="nav-overlay" id="navOverlay"></div>

    <!-- NAVBAR ACTIONS -->

    <div class="navbar-actions">

        <!-- CART -->

        <a
            href="cart.php"
            class="icon-button cart-button"
            aria-label="Shopping cart"
            title="Shopping Cart"
        >
            <span>🛒</span>

            <span class="cart-count" id="cartCount">
                <?php
                echo (int) $cartTotals['count'];
                ?>
            </span>
        </a>

        <!-- ACCOUNT -->

        <div
            class="account-container"
            id="accountContainer"
        >

            <button
                type="button"
                class="account-button"
                id="accountButton"
                aria-expanded="false"
                aria-label="Personal account"
            >
                <span class="account-icon">👤</span>
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
                            <?php
                            echo htmlspecialchars(
                                $fullname,
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>
                        </strong>

                        <small>
                            <?php
                            echo htmlspecialchars(
                                $role,
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>
                        </small>
                    </div>

                </div>

                <div class="account-divider"></div>

                <div class="account-info">

                    <p>
                        <strong>Username:</strong>
                        <?php
                        echo htmlspecialchars(
                            $username,
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>
                    </p>

                    <p>
                        <strong>Email:</strong>
                        <?php
                        echo htmlspecialchars(
                            $email,
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>
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
     CART PAGE
========================================================= -->

<main class="cart-page">

    <section class="cart-header">

        <span class="section-label">
            KATE'S GOODIES
        </span>

        <h1>My Cart</h1>

        <p>
            Review your selected treats before checking out.
        </p>

    </section>

    <section class="cart-layout">

        <!-- CART ITEMS -->

        <div class="cart-items-section">

            <div class="cart-items-header">

                <h2>Your Items</h2>

                <span>Freshly baked with love</span>

            </div>

            <div
                id="cartItems"
                class="cart-items"
            >

                <?php if (!empty($cartItems)): ?>

                    <?php foreach ($cartItems as $item): ?>

                        <?php
                        $image = trim(
                            (string) (
                                $item['image_url'] ?? ''
                            )
                        );

                        if ($image === '') {
                            $image = 'Katelogo.png';
                        }

                        $itemTotal =
                            (float) $item['price']
                            * (int) $item['quantity'];
                        ?>

                        <div
                            class="cart-item"
                            data-cart-item-id="<?php
                            echo (int) $item['cart_item_id'];
                            ?>"
                        >

                            <div class="cart-item-image">

                                <img
                                    src="<?php
                                    echo htmlspecialchars(
                                        $image,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>"
                                    alt="<?php
                                    echo htmlspecialchars(
                                        $item['title'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>"
                                    onerror="this.src='Katelogo.png';"
                                >

                            </div>

                            <div class="cart-item-details">

                                <span class="cart-item-category">
                                    <?php
                                    echo htmlspecialchars(
                                        $item['category'] ?? 'Goodies',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </span>

                                <h3>
                                    <?php
                                    echo htmlspecialchars(
                                        $item['title'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </h3>

                                <p class="cart-item-price">
                                    ₱<?php
                                    echo number_format(
                                        (float) $item['price'],
                                        2
                                    );
                                    ?>
                                </p>

                                <div class="quantity-control">

                                    <button
                                        type="button"
                                        class="quantity-btn decrease-btn"
                                        data-action="decrease"
                                        aria-label="Decrease quantity"
                                    >
                                        −
                                    </button>

                                    <span class="quantity">
                                        <?php
                                        echo (int) $item['quantity'];
                                        ?>
                                    </span>

                                    <button
                                        type="button"
                                        class="quantity-btn increase-btn"
                                        data-action="increase"
                                        aria-label="Increase quantity"
                                    >
                                        +
                                    </button>

                                </div>

                            </div>

                            <div class="cart-item-right">

                                <strong class="cart-item-total">
                                    ₱<?php
                                    echo number_format(
                                        $itemTotal,
                                        2
                                    );
                                    ?>
                                </strong>

                                <button
                                    type="button"
                                    class="remove-item"
                                    data-cart-item-id="<?php
                                    echo (int) $item['cart_item_id'];
                                    ?>"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                    Remove
                                </button>

                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

            <!-- EMPTY CART -->

            <div
                class="empty-cart"
                id="emptyCart"
                style="<?php
                echo empty($cartItems)
                    ? 'display:flex;'
                    : 'display:none;';
                ?>"
            >

                <div class="empty-cart-icon">🛒</div>

                <h2>Your cart is empty</h2>

                <p>
                    Looks like you haven't added any goodies yet.
                </p>

                <a
                    href="menu.php"
                    class="continue-shopping"
                >
                    Browse Menu
                </a>

            </div>

        </div>

        <!-- ORDER SUMMARY -->

        <aside class="cart-summary">

            <div class="summary-card">

                <span class="summary-label">
                    ORDER SUMMARY
                </span>

                <h2>Your Order</h2>

                <div class="summary-divider"></div>

                <div class="summary-row">

                    <span>Subtotal</span>

                    <strong id="subtotal">
                        ₱<?php
                        echo number_format(
                            $cartTotals['subtotal'],
                            2
                        );
                        ?>
                    </strong>

                </div>

                <div class="summary-row">

                    <span>Delivery Fee</span>

                    <strong id="deliveryFee">
                        ₱<?php
                        echo number_format(
                            $cartTotals['delivery'],
                            2
                        );
                        ?>
                    </strong>

                </div>

                <div class="summary-divider"></div>

                <div class="summary-total">

                    <span>Total</span>

                    <strong id="total">
                        ₱<?php
                        echo number_format(
                            $cartTotals['total'],
                            2
                        );
                        ?>
                    </strong>

                </div>

                <button
                    type="button"
                    class="checkout-button"
                    id="checkoutButton"
                    <?php
                    echo empty($cartItems)
                        ? 'disabled'
                        : '';
                    ?>
                >
                    Proceed to Checkout
                </button>

                <a
                    href="menu.php"
                    class="continue-link"
                >
                    Continue Shopping
                </a>

                <div class="secure-message">

                    <i class="fa-solid fa-lock"></i>

                    Secure and convenient ordering

                </div>

            </div>

        </aside>

    </section>

</main>

<!-- =========================================================
     REMOVE MODAL
========================================================= -->

<div
    class="remove-modal"
    id="removeModal"
    aria-hidden="true"
>

    <div
        class="remove-modal-backdrop"
        id="removeModalBackdrop"
    ></div>

    <div class="remove-modal-content">

        <button
            type="button"
            class="modal-close"
            id="removeModalClose"
            aria-label="Close"
        >
            &times;
        </button>

        <div class="modal-icon">🗑️</div>

        <h3>Remove Item?</h3>

        <p>
            Are you sure you want to remove this item
            from your cart?
        </p>

        <div class="modal-actions">

            <button
                type="button"
                class="cancel-remove"
                id="cancelRemove"
            >
                Cancel
            </button>

            <button
                type="button"
                class="confirm-remove"
                id="confirmRemove"
            >
                Remove
            </button>

        </div>

    </div>

</div>

<!-- =========================================================
     FOOTER
========================================================= -->

<footer>

    <div class="footer-content">

        <div class="footer-brand">

            <img
                src="Katelogo.png"
                alt="Kates Goodies"
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
            ©
            <?php echo date('Y'); ?>

            <?php
            echo htmlspecialchars(
                $brandName,
                ENT_QUOTES,
                'UTF-8'
            );
            ?>.

            All rights reserved.
        </span>

        <span>
            Fresh · Sweet · Homemade
        </span>

    </div>

</footer>

<script src="cart.js"></script>

</body>
</html>