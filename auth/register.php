<?php
/**
 * FlavorWay - New User Registration
 * Processes student registration
 */

session_start();
require_once '../config/database.php';
require_once '../config/helpers.php';

// Accept only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Método não permitido');
}

// Capture and sanitize form data
$nome = sanitizeInput($_POST['nome'] ?? '');
$username = sanitizeInput($_POST['username'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validate required fields
$validation = validateRequiredFields([
    'nome' => $nome,
    'username' => $username,
    'email' => $email,
    'senha' => $password
]);

if (!$validation['valid']) {
    jsonResponse(false, 'Preencha todos os campos');
}

// Specific validations
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
    // Check for duplicates
    if (emailExists($pdo, $email)) {
        jsonResponse(false, 'E-mail já cadastrado');
    }

    if (usernameExists($pdo, $username)) {
        jsonResponse(false, 'Nome de usuário já está em uso');
    }

    // Start transaction to ensure integrity
    $pdo->beginTransaction();

    // Insert new user
    $stmt = $pdo->prepare("
        INSERT INTO usuarios (nome, username, email, senha, ativo)
        VALUES (?, ?, ?, ?, 1)
    ");
    $stmt->execute([$nome, $username, $email, hashPassword($password)]);
    $usuario_id = $pdo->lastInsertId();

    // Register as student
    $stmt = $pdo->prepare("INSERT INTO estudantes (usuario_id, progresso) VALUES (?, 0)");
    $stmt->execute([$usuario_id]);

    // Commit transaction
    $pdo->commit();

    jsonResponse(true, 'Conta criada com sucesso! Redirecionando para login...');

} catch (PDOException $e) {
    // Rollback on error
    $pdo->rollBack();
    jsonResponse(false, 'Erro ao criar conta. Tente novamente.');
}
