<?php
/**
 * FlavorWay - Logout
 *
 * Destrói completamente a sessão do usuário:
 * 1. Limpa todas as variáveis de sessão
 * 2. Destrói a sessão no servidor
 * 3. Remove o cookie de sessão do navegador
 * 4. Redireciona para página de login
 */

session_start();

// 1. Limpa todas as variáveis da sessão
$_SESSION = [];

// 2. Destrói a sessão no servidor
session_destroy();

// 3. Remove o cookie de sessão do navegador
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000, // Expira no passado
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4. Redireciona para login
header('Location: ../public/login.php');
exit;
