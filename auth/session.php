<?php
// auth/session.php
session_start();
require_once __DIR__ . '/../config/database.php';

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../public/login.php');
        exit;
    }
}

function checkAdmin() {
    checkLogin();
    if ($_SESSION['user_type'] !== 'admin') {
        header('Location: ../public/login.php');
        exit;
    }
}

function getUser() {
    if (!isset($_SESSION['user_id'])) return null;

    global $pdo;
    $stmt = $pdo->prepare("SELECT id, nome, email, nivel, ativo FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Disponibiliza usuário no JavaScript também
echo "<script>
    window.USER = {
        id: " . json_encode($_SESSION['user_id'] ?? null) . ",
        nome: " . json_encode($_SESSION['user_name'] ?? null) . ",
        email: " . json_encode($_SESSION['user_email'] ?? null) . ",
        tipo: " . json_encode($_SESSION['user_type'] ?? null) . ",
        isAdmin: " . ($_SESSION['user_type'] === 'admin' ? 'true' : 'false') . "
    };
    function getUserJS() { return window.USER; }
</script>";
?>