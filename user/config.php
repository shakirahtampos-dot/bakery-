<?php

function getConnection(): PDO
{
    $host = 'localhost';
    $db   = 'kate_goodies';
    $user = 'root';
    $pass = 'root';

    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$db;charset=utf8mb4",
            $user,
            $pass
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $pdo;
    } catch (PDOException $e) {
        // Log the real error, but never echo DB details to the browser.
        error_log("DB connection failed: " . $e->getMessage());
        die("A server error occurred. Please try again later.");
    }
}