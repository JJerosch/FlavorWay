<?php
/**
 * FlavorWay - Authentication
 * Processes login for administrators and students
 */

session_start();
require_once '../config/database.php';
require_once '../config/helpers.php';

// Accept only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Método não permitido');
}

// Capture credentials
$emailOrUsername = sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validate fields
$validation = validateRequiredFields([
    'email/username' => $emailOrUsername,
    'senha' => $password
]);

if (!$validation['valid']) {
    jsonResponse(false, 'Preencha todos os campos');
}

try {
    // Try login as ADMINISTRATOR
    $stmt = $pdo->prepare("
        SELECT u.*, a.nivel
        FROM usuarios u
        INNER JOIN administradores a ON u.id = a.usuario_id
        WHERE (u.email = ? OR u.username = ?) AND u.ativo = 1
    ");
    $stmt->execute([$emailOrUsername, $emailOrUsername]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && verifyPassword($password, $admin['senha'])) {
        // Setup admin session
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_name'] = $admin['nome'];
        $_SESSION['user_email'] = $admin['email'];
        $_SESSION['user_type'] = 'admin';
        $_SESSION['admin_level'] = $admin['nivel'];

        // Update last access
        updateLastAccess($pdo, $admin['id']);

        jsonResponse(true, 'Login realizado com sucesso!', [
            'redirect' => '../admin/gerenciar-usuarios.php'
        ]);
    }

    // Try login as STUDENT
    $stmt = $pdo->prepare("
        SELECT u.*, e.progresso
        FROM usuarios u
        INNER JOIN estudantes e ON u.id = e.usuario_id
        WHERE (u.email = ? OR u.username = ?) AND u.ativo = 1
    ");
    $stmt->execute([$emailOrUsername, $emailOrUsername]);
    $estudante = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($estudante && verifyPassword($password, $estudante['senha'])) {
        // Setup student session
        $_SESSION['user_id'] = $estudante['id'];
        $_SESSION['user_name'] = $estudante['nome'];
        $_SESSION['user_email'] = $estudante['email'];
        $_SESSION['user_type'] = 'estudante';
        $_SESSION['progresso'] = $estudante['progresso'];

        // Update last access
        updateLastAccess($pdo, $estudante['id']);

        jsonResponse(true, 'Bem-vindo de volta!', [
            'redirect' => '../public/index.php'
        ]);
    }

    // Invalid credentials
    jsonResponse(false, 'Credenciais inválidas');

} catch (PDOException $e) {
    jsonResponse(false, 'Erro no servidor. Tente novamente.');
}
