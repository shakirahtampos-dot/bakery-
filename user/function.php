<?php

/*
|--------------------------------------------------------------------------
| KATES GOODIES - FUNCTION FILE
|--------------------------------------------------------------------------
| Common helper functions used by:
| - admin_registration.php
| - admin_login.php
| - admin_dashboard.php
| - other admin/customer pages
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| START SESSION SAFELY
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| CLEAN INPUT
|--------------------------------------------------------------------------
| Removes unnecessary spaces from user input.
|--------------------------------------------------------------------------
*/

if (!function_exists('cleanInput')) {

    function cleanInput(string $value): string
    {
        return trim($value);
    }

}


/*
|--------------------------------------------------------------------------
| ESCAPE HTML OUTPUT
|--------------------------------------------------------------------------
| Prevents HTML/XSS when displaying database or form values.
|--------------------------------------------------------------------------
*/

if (!function_exists('e')) {

    function e($value): string
    {
        return htmlspecialchars(
            (string) $value,
            ENT_QUOTES,
            'UTF-8'
        );
    }

}


/*
|--------------------------------------------------------------------------
| REDIRECT
|--------------------------------------------------------------------------
| Redirects the user to another PHP page.
|--------------------------------------------------------------------------
*/

if (!function_exists('redirect')) {

    function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit();
    }

}


/*
|--------------------------------------------------------------------------
| CHECK IF USER IS LOGGED IN
|--------------------------------------------------------------------------
*/

if (!function_exists('isLoggedIn')) {

    function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

}


/*
|--------------------------------------------------------------------------
| CHECK IF USER IS ADMIN
|--------------------------------------------------------------------------
*/

if (!function_exists('isAdmin')) {

    function isAdmin(): bool
    {
        return (
            isset($_SESSION['user_id']) &&
            isset($_SESSION['role']) &&
            $_SESSION['role'] === 'admin'
        );
    }

}


/*
|--------------------------------------------------------------------------
| REQUIRE ADMIN LOGIN
|--------------------------------------------------------------------------
| Prevents non-admin users from accessing admin pages.
|--------------------------------------------------------------------------
*/

if (!function_exists('requireAdmin')) {

    function requireAdmin(
        string $loginPage = 'login.php'
    ): void {

        if (!isAdmin()) {
            redirect($loginPage);
        }

    }

}


/*
|--------------------------------------------------------------------------
| GET CURRENT USER ID
|--------------------------------------------------------------------------
*/

if (!function_exists('getUserId')) {

    function getUserId(): ?int
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        return (int) $_SESSION['user_id'];
    }

}


/*
|--------------------------------------------------------------------------
| GET CURRENT USER ROLE
|--------------------------------------------------------------------------
*/

if (!function_exists('getUserRole')) {

    function getUserRole(): string
    {
        return $_SESSION['role'] ?? '';
    }

}


/*
|--------------------------------------------------------------------------
| GET CURRENT USER FULL NAME
|--------------------------------------------------------------------------
*/

if (!function_exists('getUserFullName')) {

    function getUserFullName(): string
    {
        return $_SESSION['fullname'] ?? '';
    }

}


/*
|--------------------------------------------------------------------------
| GET CURRENT USERNAME
|--------------------------------------------------------------------------
*/

if (!function_exists('getUsername')) {

    function getUsername(): string
    {
        return $_SESSION['username'] ?? '';
    }

}


/*
|--------------------------------------------------------------------------
| GET CURRENT USER EMAIL
|--------------------------------------------------------------------------
*/

if (!function_exists('getUserEmail')) {

    function getUserEmail(): string
    {
        return $_SESSION['email'] ?? '';
    }

}


/*
|--------------------------------------------------------------------------
| SET FLASH MESSAGE
|--------------------------------------------------------------------------
| Stores a temporary message in the session.
|--------------------------------------------------------------------------
*/

if (!function_exists('setFlashMessage')) {

    function setFlashMessage(
        string $message,
        string $type = 'success'
    ): void {

        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_message_type'] = $type;

    }

}


/*
|--------------------------------------------------------------------------
| GET FLASH MESSAGE
|--------------------------------------------------------------------------
| Returns the flash message and removes it from the session.
|--------------------------------------------------------------------------
*/

if (!function_exists('getFlashMessage')) {

    function getFlashMessage(): ?array
    {
        if (!isset($_SESSION['flash_message'])) {
            return null;
        }

        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_message_type'] ?? 'success';

        unset(
            $_SESSION['flash_message'],
            $_SESSION['flash_message_type']
        );

        return [
            'message' => $message,
            'type' => $type
        ];
    }

}


/*
|--------------------------------------------------------------------------
| CHECK DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

if (!function_exists('isValidConnection')) {

    function isValidConnection($conn): bool
    {
        return $conn instanceof mysqli;
    }

}


/*
|--------------------------------------------------------------------------
| SAFE DATABASE CLOSE
|--------------------------------------------------------------------------
*/

if (!function_exists('closeConnection')) {

    function closeConnection($conn): void
    {
        if ($conn instanceof mysqli) {
            $conn->close();
        }
    }

}


/*
|--------------------------------------------------------------------------
| SAFE STATEMENT CLOSE
|--------------------------------------------------------------------------
*/

if (!function_exists('closeStatement')) {

    function closeStatement($stmt): void
    {
        if ($stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
    }

}


/*
|--------------------------------------------------------------------------
| CHECK IF DATABASE TABLE EXISTS
|--------------------------------------------------------------------------
*/

if (!function_exists('tableExists')) {

    function tableExists(
        mysqli $conn,
        string $table
    ): bool {

        $table = $conn->real_escape_string($table);

        $result = $conn->query(
            "SHOW TABLES LIKE '{$table}'"
        );

        return $result !== false && $result->num_rows > 0;
    }

}


/*
|--------------------------------------------------------------------------
| CHECK IF DATABASE COLUMN EXISTS
|--------------------------------------------------------------------------
*/

if (!function_exists('columnExists')) {

    function columnExists(
        mysqli $conn,
        string $table,
        string $column
    ): bool {

        $table = str_replace('`', '', $table);
        $column = str_replace('`', '', $column);

        $sql = "
            SHOW COLUMNS
            FROM `{$table}`
            LIKE ?
        ";

        try {

            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                return false;
            }

            $stmt->bind_param('s', $column);
            $stmt->execute();
            $stmt->store_result();

            $exists = $stmt->num_rows > 0;

            $stmt->close();

            return $exists;

        } catch (Throwable $e) {

            error_log(
                'Column check error: ' . $e->getMessage()
            );

            return false;
        }
    }

}


/*
|--------------------------------------------------------------------------
| GET DATABASE COUNT
|--------------------------------------------------------------------------
| Example:
| getCount($conn, 'users');
|--------------------------------------------------------------------------
*/

if (!function_exists('getCount')) {

    function getCount(
        mysqli $conn,
        string $table,
        string $condition = ''
    ): int {

        $table = str_replace('`', '', $table);

        $sql = "SELECT COUNT(*) AS total FROM `{$table}`";

        if ($condition !== '') {
            $sql .= " WHERE " . $condition;
        }

        try {

            $result = $conn->query($sql);

            if (!$result) {
                return 0;
            }

            $row = $result->fetch_assoc();

            return (int) ($row['total'] ?? 0);

        } catch (Throwable $e) {

            error_log(
                'Count query error: ' . $e->getMessage()
            );

            return 0;
        }
    }

}


/*
|--------------------------------------------------------------------------
| GET DATABASE SUM
|--------------------------------------------------------------------------
| Example:
| getSum($conn, 'orders', 'total_amount');
|--------------------------------------------------------------------------
*/

if (!function_exists('getSum')) {

    function getSum(
        mysqli $conn,
        string $table,
        string $column,
        string $condition = ''
    ): float {

        $table = str_replace('`', '', $table);
        $column = str_replace('`', '', $column);

        $sql = "
            SELECT COALESCE(SUM(`{$column}`), 0) AS total
            FROM `{$table}`
        ";

        if ($condition !== '') {
            $sql .= " WHERE " . $condition;
        }

        try {

            $result = $conn->query($sql);

            if (!$result) {
                return 0.0;
            }

            $row = $result->fetch_assoc();

            return (float) ($row['total'] ?? 0);

        } catch (Throwable $e) {

            error_log(
                'Sum query error: ' . $e->getMessage()
            );

            return 0.0;
        }
    }

}


/*
|--------------------------------------------------------------------------
| LOGOUT USER
|--------------------------------------------------------------------------
*/

if (!function_exists('logoutUser')) {

    function logoutUser(
        string $redirectPage = 'login.php'
    ): void {

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

        header('Location: ' . $redirectPage);
        exit();
    }

}


/*
|--------------------------------------------------------------------------
| PASSWORD HASH
|--------------------------------------------------------------------------
*/

if (!function_exists('hashPassword')) {

    function hashPassword(string $password): string
    {
        return password_hash(
            $password,
            PASSWORD_DEFAULT
        );
    }

}


/*
|--------------------------------------------------------------------------
| VERIFY PASSWORD
|--------------------------------------------------------------------------
*/

if (!function_exists('verifyPassword')) {

    function verifyPassword(
        string $password,
        string $hashedPassword
    ): bool {

        return password_verify(
            $password,
            $hashedPassword
        );
    }

}


/*
|--------------------------------------------------------------------------
| AUTHENTICATE ADMIN
|--------------------------------------------------------------------------
| Checks admin credentials using:
| - email
| - password
|
| Returns:
|   array = successful login
|   null  = failed login
|--------------------------------------------------------------------------
*/

if (!function_exists('authenticateAdmin')) {

    function authenticateAdmin(
        mysqli $conn,
        string $email,
        string $password
    ): ?array {

        $sql = "
            SELECT
                id,
                fullname,
                username,
                email,
                password,
                role
            FROM users
            WHERE email = ?
            LIMIT 1
        ";

        try {

            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                return null;
            }

            $stmt->bind_param(
                's',
                $email
            );

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows !== 1) {

                $stmt->close();

                return null;
            }

            $user = $result->fetch_assoc();

            $stmt->close();


            /*
            |--------------------------------------------------------------------------
            | CHECK ADMIN ROLE
            |--------------------------------------------------------------------------
            */

            if (
                !isset($user['role']) ||
                $user['role'] !== 'admin'
            ) {
                return null;
            }


            /*
            |--------------------------------------------------------------------------
            | VERIFY PASSWORD
            |--------------------------------------------------------------------------
            */

            if (
                !isset($user['password']) ||
                !password_verify(
                    $password,
                    $user['password']
                )
            ) {
                return null;
            }


            /*
            |--------------------------------------------------------------------------
            | REMOVE PASSWORD BEFORE RETURNING
            |--------------------------------------------------------------------------
            */

            unset($user['password']);

            return $user;

        } catch (Throwable $e) {

            error_log(
                'Admin authentication error: ' .
                $e->getMessage()
            );

            return null;
        }
    }

}


/*
|--------------------------------------------------------------------------
| AUTHENTICATE USER (ANY ROLE, BY USERNAME)
|--------------------------------------------------------------------------
| Used by login.php. Checks credentials using:
| - username
| - password
|
| Returns:
|   array = successful login
|   null  = failed login
|--------------------------------------------------------------------------
*/

if (!function_exists('authenticateUser')) {

    function authenticateUser(
        mysqli $conn,
        string $username,
        string $password
    ): ?array {

        $sql = "
            SELECT id, fullname, username, email, password, role
            FROM users
            WHERE username = ?
            LIMIT 1
        ";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('s', $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            $stmt->close();
            return null;
        }

        $user = $result->fetch_assoc();

        $stmt->close();

        if (
            !isset($user['password']) ||
            !password_verify($password, $user['password'])
        ) {
            return null;
        }

        unset($user['password']);

        return $user;
    }

}


/*
|--------------------------------------------------------------------------
| LOGIN ADMIN SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('loginAdmin')) {

    function loginAdmin(array $user): void
    {
        session_regenerate_id(true);

        $_SESSION['user_id'] =
            (int) ($user['id'] ?? 0);

        $_SESSION['fullname'] =
            $user['fullname'] ?? '';

        $_SESSION['username'] =
            $user['username'] ?? '';

        $_SESSION['email'] =
            $user['email'] ?? '';

        $_SESSION['role'] =
            $user['role'] ?? 'admin';
    }

}


/*
|--------------------------------------------------------------------------
| CHECK IF USERNAME OR EMAIL ALREADY EXISTS
|--------------------------------------------------------------------------
| Used by registration.php to prevent duplicate accounts.
|--------------------------------------------------------------------------
*/

if (!function_exists('usernameOrEmailExists')) {

    function usernameOrEmailExists(
        mysqli $conn,
        string $username,
        string $email
    ): bool {

        $sql = "
            SELECT id
            FROM users
            WHERE username = ? OR email = ?
            LIMIT 1
        ";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $stmt->store_result();

        $exists = $stmt->num_rows > 0;

        $stmt->close();

        return $exists;
    }

}


/*
|--------------------------------------------------------------------------
| REGISTER A NEW USER
|--------------------------------------------------------------------------
| Inserts a new customer account. Returns the new user's id on
| success, or null on failure. New accounts default to
| role = 'customer'.
|--------------------------------------------------------------------------
*/

if (!function_exists('registerUser')) {

    function registerUser(
        mysqli $conn,
        string $fullName,
        string $username,
        string $email,
        string $password
    ): ?int {

        $sql = "
            INSERT INTO users (fullname, username, email, password, role)
            VALUES (?, ?, ?, ?, 'customer')
        ";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $hashedPassword = hashPassword($password);

        $stmt->bind_param(
            'ssss',
            $fullName,
            $username,
            $email,
            $hashedPassword
        );

        $success = $stmt->execute();

        $newId = $success ? $conn->insert_id : null;

        $stmt->close();

        return $newId;
    }

}