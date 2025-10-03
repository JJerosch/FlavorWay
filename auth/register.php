<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validações
if (empty($nome) || empty($username) || empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
    exit;
}

if (strlen($nome) < 3) {
    echo json_encode(['success' => false, 'message' => 'Nome deve ter no mínimo 3 caracteres']);
    exit;
}

if (strlen($username) < 3) {
    echo json_encode(['success' => false, 'message' => 'Username deve ter no mínimo 3 caracteres']);
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    echo json_encode(['success' => false, 'message' => 'Username deve conter apenas letras, números e underscore']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'E-mail inválido']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Senha deve ter no mínimo 6 caracteres']);
    exit;
}

try {
    // Verificar se email já existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'E-mail já cadastrado']);
        exit;
    }

    // Verificar se username já existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Nome de usuário já está em uso']);
        exit;
    }

    // Criar hash da senha
    $senhaHash = password_hash($password, PASSWORD_DEFAULT);

    // Iniciar transação
    $pdo->beginTransaction();

    // Inserir usuário
    $stmt = $pdo->prepare("
        INSERT INTO usuarios (nome, username, email, senha, ativo) 
        VALUES (?, ?, ?, ?, 1)
    ");
    $stmt->execute([$nome, $username, $email, $senhaHash]);
    $usuario_id = $pdo->lastInsertId();

    // Inserir como estudante
    $stmt = $pdo->prepare("INSERT INTO estudantes (usuario_id, progresso) VALUES (?, 0)");
    $stmt->execute([$usuario_id]);

    $pdo->commit();

    echo json_encode([
        'success' => true, 
        'message' => 'Conta criada com sucesso! Redirecionando para login...'
    ]);

} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erro ao criar conta. Tente novamente.']);
}
?>