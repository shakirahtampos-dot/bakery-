<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| DATABASE CONFIGURATION
|--------------------------------------------------------------------------
*/

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'kates_goodies');

/*
|--------------------------------------------------------------------------
| MYSQLI CONNECTION
|--------------------------------------------------------------------------
*/

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(
        DB_HOST,
        DB_USER,
        DB_PASSWORD,
        DB_NAME
    );

    $conn->set_charset('utf8mb4');

} catch (mysqli_sql_exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}