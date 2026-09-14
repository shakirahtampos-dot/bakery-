<?php

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
*/

function cleanInput(string $value): string
{
    return htmlspecialchars(
        trim($value),
        ENT_QUOTES,
        'UTF-8'
    );
}


/*
|--------------------------------------------------------------------------
| ESCAPE OUTPUT
|--------------------------------------------------------------------------
*/

function e(string $value): string
{
    return htmlspecialchars(
        $value,
        ENT_QUOTES,
        'UTF-8'
    );
}


/*
|--------------------------------------------------------------------------
| VALIDATE FULL NAME
|--------------------------------------------------------------------------
*/

function validateFullName(string $fullname): ?string
{
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
     * Allows letters, spaces, periods, apostrophes and hyphens.
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
     * Letters, numbers, underscore and period.
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