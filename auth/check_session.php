<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../public/login.php');
        exit;
    }
}

function checkAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
        header('Location: ../public/login.php');
        exit;
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

function getUserName() {
    return $_SESSION['user_name'] ?? 'Usuário';
}

function getUserType() {
    return $_SESSION['user_type'] ?? null;
}
?>