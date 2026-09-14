
<?php

header('Content-Type: application/json; charset=UTF-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| LOAD DATABASE CONFIGURATION
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/config.php';

/*
|--------------------------------------------------------------------------
| ONLY GET REQUESTS ARE ALLOWED
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

try {
    $conn = getConnection();
} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| CHECK CONNECTION
|--------------------------------------------------------------------------
*/

if (!$conn instanceof mysqli) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid database connection.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| GET FILTERS
|--------------------------------------------------------------------------
*/

$category = strtolower(
    trim($_GET['category'] ?? 'all')
);

$search = trim(
    $_GET['search'] ?? ''
);

/*
|--------------------------------------------------------------------------
| PRODUCT QUERY
|--------------------------------------------------------------------------
|
| Products table columns:
| id, name, description, category, price, sale_price,
| image, stock, status, created_at, updated_at
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id,
        name,
        description,
        category,
        price,
        sale_price,
        image,
        stock,
        status,
        created_at,
        updated_at
    FROM products
    WHERE stock > 0
    AND (
        LOWER(CAST(status AS CHAR)) = 'available'
        OR LOWER(CAST(status AS CHAR)) = 'active'
        OR CAST(status AS CHAR) = '1'
    )
";

$params = [];
$types = '';

/*
|--------------------------------------------------------------------------
| CATEGORY FILTER
|--------------------------------------------------------------------------
*/

if ($category !== '' && $category !== 'all') {
    $sql .= " AND LOWER(category) = ?";

    $types .= 's';
    $params[] = $category;
}

/*
|--------------------------------------------------------------------------
| SEARCH FILTER
|--------------------------------------------------------------------------
*/

if ($search !== '') {
    $sql .= "
        AND (
            name LIKE ?
            OR description LIKE ?
        )
    ";

    $searchValue = '%' . $search . '%';

    $types .= 'ss';
    $params[] = $searchValue;
    $params[] = $searchValue;
}

/*
|--------------------------------------------------------------------------
| ORDER PRODUCTS
|--------------------------------------------------------------------------
*/

$sql .= " ORDER BY created_at DESC";

/*
|--------------------------------------------------------------------------
| PREPARE QUERY
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to prepare product query.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| BIND PARAMETERS
|--------------------------------------------------------------------------
*/

if ($types !== '') {
    mysqli_stmt_bind_param(
        $stmt,
        $types,
        ...$params
    );
}

/*
|--------------------------------------------------------------------------
| EXECUTE QUERY
|--------------------------------------------------------------------------
*/

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to retrieve products.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| GET RESULTS
|--------------------------------------------------------------------------
*/

$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    mysqli_stmt_close($stmt);

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to read product results.'
    ]);

    exit;
}

$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = [
        'id' => (int) $row['id'],

        'name' => $row['name'] ?? '',

        'description' => $row['description'] ?? '',

        'category' => $row['category'] ?? '',

        'price' => (float) $row['price'],

        'sale_price' => (
            $row['sale_price'] !== null
            && $row['sale_price'] !== ''
        )
            ? (float) $row['sale_price']
            : null,

        'image' => $row['image'] ?? '',

        'stock' => (int) $row['stock'],

        'status' => $row['status'],

        'created_at' => $row['created_at'] ?? null,

        'updated_at' => $row['updated_at'] ?? null
    ];
}

mysqli_stmt_close($stmt);

/*
|--------------------------------------------------------------------------
| SUCCESS RESPONSE
|--------------------------------------------------------------------------
*/

echo json_encode([
    'success' => true,
    'products' => $products
]);

exit;
?>