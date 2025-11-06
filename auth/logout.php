<?php
// includes/auth/logout.php

session_start();

// Limpa a sessão
$_SESSION = [];

// Destrói a sessão
session_destroy();

// Remove o cookie da sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// REDIRECIONA CORRETAMENTE
header('Location: ../public/login.php');
exit;
?>