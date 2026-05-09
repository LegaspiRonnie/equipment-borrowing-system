<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function redirect_to_login()
{
    header("Location: ../403.php");
    exit();
}

function redirect_to_forbidden()
{
    header("Location: ../403.php");
    exit();
}

function require_login()
{
    if (empty($_SESSION['user_id'])) {
        redirect_to_login();
    }
}

function require_role($role)
{
    require_login();

    if (empty($_SESSION['role']) || $_SESSION['role'] !== $role) {
        redirect_to_forbidden();
    }
}

function current_user_id()
{
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
}
