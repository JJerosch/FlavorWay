<?php
/**
 * FlavorWay - Configuração do Banco de Dados
 *
 * Estabelece conexão PDO com MySQL
 * - Cria banco automaticamente se não existir
 * - Configura charset UTF-8 para suporte completo a caracteres
 * - Ativa modo de erro com exceptions
 */

// Credenciais do banco
$host = "localhost";
$dbname = "flavor_way";
$username = "root";
$password = "";

try {
    // Conecta ao MySQL sem especificar banco (para poder criar se necessário)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cria banco de dados se não existir
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Seleciona o banco para uso
    $pdo->exec("USE `$dbname`");

    // Configurações de segurança e performance
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Retorna arrays associativos
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              // Usa prepared statements nativos

} catch(PDOException $e) {
    // Em produção, logar erro e mostrar mensagem genérica
    // Por ora, mostra erro detalhado para debug
    die("Erro na conexão: SQLSTATE[" . $e->getCode() . "] - " . $e->getMessage());
}
