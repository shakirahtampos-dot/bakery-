<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =========================================================
   ESCAPE HTML
========================================================= */

function e(mixed $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

/* =========================================================
   REDIRECT
========================================================= */

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/* =========================================================
   FLASH MESSAGES
========================================================= */

function setFlash(
    string $type,
    string $message
): void {
    $_SESSION['flash'][$type] = $message;
}

function getFlash(string $type): ?string
{
    if (!isset($_SESSION['flash'][$type])) {
        return null;
    }

    $message = $_SESSION['flash'][$type];

    unset($_SESSION['flash'][$type]);

    return $message;
}

/* =========================================================
   ADMIN ACCESS
========================================================= */

function requireAdmin(
    string $loginPage = 'admin_login.php'
): void {
    if (
        !isset($_SESSION['user_id']) ||
        !isset($_SESSION['role']) ||
        $_SESSION['role'] !== 'admin'
    ) {
        redirect($loginPage);
    }
}

/* =========================================================
   LOGIN ACCESS
========================================================= */

function requireLogin(
    string $loginPage = 'admin_login.php'
): void {
    if (!isset($_SESSION['user_id'])) {
        redirect($loginPage);
    }
}

/* =========================================================
   CURRENT USER ID
========================================================= */

function currentUserId(): ?int
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    return (int) $_SESSION['user_id'];
}

/* =========================================================
   PREPARED STATEMENT HELPER
========================================================= */

function executeStatement(
    mysqli $conn,
    string $sql,
    string $types = '',
    array $params = []
): mysqli_stmt {
    $stmt = $conn->prepare($sql);

    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();

    return $stmt;
}

/* =========================================================
   DATABASE TRANSACTIONS
========================================================= */

function beginTransaction(mysqli $conn): void
{
    $conn->begin_transaction();
}

function commitTransaction(mysqli $conn): void
{
    $conn->commit();
}

function rollbackTransaction(mysqli $conn): void
{
    $conn->rollback();
}

/* =========================================================
   CURRENCY
========================================================= */

function formatPeso(float|int $amount): string
{
    return '₱' . number_format(
        (float) $amount,
        2,
        '.',
        ','
    );
}

/* =========================================================
   CSRF TOKEN
========================================================= */

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(
            random_bytes(32)
        );
    }

    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' .
        e(csrfToken()) .
        '">';
}

function verifyCsrfToken(?string $token): bool
{
    return is_string($token) &&
        isset($_SESSION['csrf_token']) &&
        hash_equals($_SESSION['csrf_token'], $token);
}

/* =========================================================
   LOGOUT
========================================================= */

function logoutUser(): never
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();

    redirect('admin_login.php');
}
?>