<?php

/*
|--------------------------------------------------------------------------
| SESSION
|--------------------------------------------------------------------------
| Start the session only if it is not already active.
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| VALIDATE FULL NAME
|--------------------------------------------------------------------------
*/

function validateFullName(string $fullname): ?string
{
    $fullname = trim($fullname);

    if ($fullname === '') {
        return 'Full Name is required.';
    }

    if (strlen($fullname) < 2) {
        return 'Full Name must be at least 2 characters.';
    }

    if (strlen($fullname) > 100) {
        return 'Full Name must not exceed 100 characters.';
    }

    /*
     * Allows:
     * Letters
     * Spaces
     * Periods
     * Apostrophes
     * Hyphens
     */
    if (!preg_match("/^[a-zA-ZÀ-ÿ\s.'-]+$/", $fullname)) {
        return 'Full Name contains invalid characters.';
    }

    return null;
}


/*
|--------------------------------------------------------------------------
| VALIDATE EMAIL
|--------------------------------------------------------------------------
*/

function validateEmail(string $email): ?string
{
    $email = trim($email);

    if ($email === '') {
        return 'Email address is required.';
    }

    if (strlen($email) > 255) {
        return 'Email address must not exceed 255 characters.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Please enter a valid email address.';
    }

    return null;
}


/*
|--------------------------------------------------------------------------
| VALIDATE USERNAME
|--------------------------------------------------------------------------
*/

function validateUsername(string $username): ?string
{
    $username = trim($username);

    if ($username === '') {
        return 'Username is required.';
    }

    if (strlen($username) < 3) {
        return 'Username must be at least 3 characters.';
    }

    if (strlen($username) > 50) {
        return 'Username must not exceed 50 characters.';
    }

    /*
     * Allows:
     * Letters
     * Numbers
     * Underscore
     * Period
     */
    if (!preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
        return 'Username can only contain letters, numbers, underscores, and periods.';
    }

    return null;
}


/*
|--------------------------------------------------------------------------
| VALIDATE PASSWORD
|--------------------------------------------------------------------------
*/

function validatePassword(string $password): ?string
{
    if ($password === '') {
        return 'Password is required.';
    }

    if (strlen($password) < 8) {
        return 'Password must be at least 8 characters.';
    }

    return null;
}


/*
|--------------------------------------------------------------------------
| VALIDATE CONFIRM PASSWORD
|--------------------------------------------------------------------------
*/

function validateConfirmPassword(
    string $password,
    string $confirmPassword
): ?string {

    if ($confirmPassword === '') {
        return 'Please confirm your password.';
    }

    if ($password !== $confirmPassword) {
        return 'Passwords do not match.';
    }

    return null;
}


/*
|--------------------------------------------------------------------------
| VALIDATE REGISTRATION INPUT
|--------------------------------------------------------------------------
| Optional helper function.
|--------------------------------------------------------------------------
*/

function validateRegistrationInput(
    string $fullname,
    string $email,
    string $username,
    string $password,
    string $confirmPassword,
    bool $terms
): array {

    $errors = [];


    /*
     * Full Name
     */
    $error = validateFullName($fullname);

    if ($error !== null) {
        $errors[] = $error;
    }


    /*
     * Email
     */
    $error = validateEmail($email);

    if ($error !== null) {
        $errors[] = $error;
    }


    /*
     * Username
     */
    $error = validateUsername($username);

    if ($error !== null) {
        $errors[] = $error;
    }


    /*
     * Password
     */
    $error = validatePassword($password);

    if ($error !== null) {
        $errors[] = $error;
    }


    /*
     * Confirm Password
     */
    $error = validateConfirmPassword(
        $password,
        $confirmPassword
    );

    if ($error !== null) {
        $errors[] = $error;
    }


    /*
     * Terms
     */
    if (!$terms) {
        $errors[] =
            'You must agree to the Terms and Conditions.';
    }


    return $errors;
}