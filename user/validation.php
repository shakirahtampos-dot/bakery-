<?php

function validateRequired(string $value, string $label): ?string
{
    return trim($value) === '' ? "$label is required." : null;
}

function validateLength(string $value, string $label, int $min, int $max): ?string
{
    $len = mb_strlen(trim($value));
    if ($len < $min || $len > $max) {
        return "$label must be between $min and $max characters.";
    }
    return null;
}

function validateUsernameFormat(string $value): ?string
{
    if ($value === '') {
        return null; // let validateRequired handle the empty case
    }
    return preg_match('/^[a-zA-Z0-9_]{3,30}$/', $value)
        ? null
        : "Username must be 3-30 characters and contain only letters, numbers, and underscores.";
}

function validateEmailFormat(string $value): ?string
{
    if ($value === '') {
        return null; // let validateRequired handle the empty case
    }
    if (strlen($value) > 255) {
        return "Email address is too long.";
    }
    return filter_var($value, FILTER_VALIDATE_EMAIL) ? null : "Enter a valid email address.";
}

function validatePasswordFormat(string $value): ?string
{
    if ($value === '') {
        return null; // let validateRequired handle the empty case
    }
    if (strlen($value) < 8) {
        return "Password must be at least 8 characters.";
    }
    if (!preg_match('/[A-Za-z]/', $value) || !preg_match('/[0-9]/', $value)) {
        return "Password must contain at least one letter and one number.";
    }
    return null;
}

function validatePasswordsMatch(string $password, string $confirm): ?string
{
    if ($confirm === '') {
        return null; // let validateRequired handle the empty case
    }
    return $password === $confirm ? null : "Passwords do not match.";
}

/**
 * Validates the registration form's POST data.
 * Returns ['errors' => string[], 'data' => ['full_name'=>, 'username'=>, 'email'=>]]
 * Note: password is deliberately NOT echoed back in 'data'.
 */
function validateRegistrationInput(array $post): array
{
    $full_name         = trim($post['full_name'] ?? '');
    $username          = trim($post['username'] ?? '');
    $email             = strtolower(trim($post['email'] ?? ''));
    $password          = $post['password'] ?? '';
    $confirm_password  = $post['confirm_password'] ?? '';

    $errors = array_filter([
        validateRequired($full_name, 'Full name'),
        validateLength($full_name, 'Full name', 1, 100),
        validateRequired($username, 'Username'),
        validateUsernameFormat($username),
        validateRequired($email, 'Email address'),
        validateEmailFormat($email),
        validateRequired($password, 'Password'),
        validatePasswordFormat($password),
        validateRequired($confirm_password, 'Password confirmation'),
        validatePasswordsMatch($password, $confirm_password),
    ]);

    return [
        'errors' => array_values($errors),
        'data' => [
            'full_name' => $full_name,
            'username'  => $username,
            'email'     => $email,
        ],
    ];
}