<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function csrf_token_rizkia()
{
    if (empty($_SESSION['csrf_token_rizkia'])) {
        $_SESSION['csrf_token_rizkia'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token_rizkia'];
}

function csrf_validate_rizkia($token)
{
    if (!isset($_SESSION['csrf_token_rizkia'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token_rizkia'], (string)$token);
}

function csrf_input_rizkia()
{
    $token = htmlspecialchars(csrf_token_rizkia(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="csrf_token_rizkia" value="' . $token . '">';
}
