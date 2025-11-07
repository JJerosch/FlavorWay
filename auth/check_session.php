<?php
session_start();

// ===================================
// FUNÇÕES DE AUTENTICAÇÃO (PHP)
// ===================================

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
    return ($_SESSION['user_type'] ?? '') === 'admin';
}

function getUserName() {
    return $_SESSION['user_name'] ?? 'Usuário';
}

function getUserEmail() {
    return $_SESSION['user_email'] ?? '';
}

function getUserType() {
    return $_SESSION['user_type'] ?? null;
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// ===================================
// SAÍDA PARA JAVASCRIPT (apenas se for HTML)
// ===================================

// Só executa isso se o arquivo for incluído em uma página HTML
if (php_sapi_name() !== 'cli' && strpos($_SERVER['REQUEST_URI'], '.php') !== false) {
    // Garante que não quebre em CLI ou APIs
    echo "<script>
        window.USER = {
            id: " . json_encode($_SESSION['user_id'] ?? null) . ",
            name: " . json_encode($_SESSION['user_name'] ?? null) . ",
            email: " . json_encode($_SESSION['user_email'] ?? null) . ",
            type: " . json_encode($_SESSION['user_type'] ?? null) . ",
            admin_level: " . json_encode($_SESSION['admin_level'] ?? null) . ",
            isLoggedIn: " . (!empty($_SESSION['user_id']) ? 'true' : 'false') . ",
            isAdmin: " . (isAdmin() ? 'true' : 'false') . "
        };

        function getUser() {
            return window.USER;
        }

        function isUserAdmin() {
            return window.USER.isAdmin;
        }

        // Debug (remova em produção)
        console.log('%c Usuário logado:', 'color: #4CAF50; font-weight: bold;', window.USER);
    </script>";
}
?>