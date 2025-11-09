<?php
/**
 * FlavorWay - Registro de Novos Usuários
 * Processa o cadastro de estudantes
 */

session_start();
require_once '../config/database.php';
require_once '../config/helpers.php';

// Aceita apenas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Método não permitido');
}

// Captura e sanitiza dados do formulário
$nome = sanitizeInput($_POST['nome'] ?? '');
$username = sanitizeInput($_POST['username'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Valida campos obrigatórios
$validation = validateRequiredFields([
    'nome' => $nome,
    'username' => $username,
    'email' => $email,
    'senha' => $password
]);

if (!$validation['valid']) {
    jsonResponse(false, 'Preencha todos os campos');
}

// Validações específicas
if (strlen($nome) < 3) {
    jsonResponse(false, 'Nome deve ter no mínimo 3 caracteres');
}

if (strlen($username) < 3) {
    jsonResponse(false, 'Username deve ter no mínimo 3 caracteres');
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    jsonResponse(false, 'Username deve conter apenas letras, números e underscore');
}

if (!isValidEmail($email)) {
    jsonResponse(false, 'E-mail inválido');
}

$passwordValidation = validatePasswordStrength($password);
if (!$passwordValidation['valid']) {
    jsonResponse(false, $passwordValidation['message']);
}

try {
    // Verifica duplicações
    if (emailExists($pdo, $email)) {
        jsonResponse(false, 'E-mail já cadastrado');
    }

    if (usernameExists($pdo, $username)) {
        jsonResponse(false, 'Nome de usuário já está em uso');
    }

    // Inicia transação para garantir integridade
    $pdo->beginTransaction();

    // Insere novo usuário
    $stmt = $pdo->prepare("
        INSERT INTO usuarios (nome, username, email, senha, ativo)
        VALUES (?, ?, ?, ?, 1)
    ");
    $stmt->execute([$nome, $username, $email, hashPassword($password)]);
    $usuario_id = $pdo->lastInsertId();

    // Registra como estudante
    $stmt = $pdo->prepare("INSERT INTO estudantes (usuario_id, progresso) VALUES (?, 0)");
    $stmt->execute([$usuario_id]);

    // Confirma transação
    $pdo->commit();

    jsonResponse(true, 'Conta criada com sucesso! Redirecionando para login...');

} catch (PDOException $e) {
    // Reverte em caso de erro
    $pdo->rollBack();
    jsonResponse(false, 'Erro ao criar conta. Tente novamente.');
}
