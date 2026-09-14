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
| MYSQLI ERROR REPORTING
|--------------------------------------------------------------------------
*/

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

function getConnection(): mysqli
{
    try {

        $conn = new mysqli(
            DB_HOST,
            DB_USER,
            DB_PASSWORD,
            DB_NAME
        );

        $conn->set_charset('utf8mb4');

        return $conn;

    } catch (mysqli_sql_exception $e) {

        error_log(
            'Database connection failed: ' .
            $e->getMessage()
        );

        throw $e;
    }
}