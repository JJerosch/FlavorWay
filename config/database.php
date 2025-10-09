<?php
// Configurações do banco de dados
$host = "localhost";
$dbname = "flavor_way";
$username = "root";
$password = "";

try {
    // Tenta conexão sem especificar o banco primeiro
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Cria o banco se não existir
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Agora conecta ao banco específico
    $pdo->exec("USE `$dbname`");
    
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
} catch(PDOException $e) {
    // Mostra erro detalhado para debug
    die("Erro na conexão: SQLSTATE[" . $e->getCode() . "] - " . $e->getMessage());
}
?>