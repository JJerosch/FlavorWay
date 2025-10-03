<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$emailOrUsername = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($emailOrUsername) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
    exit;
}

try {
    // Verificar se é admin
    $stmt = $pdo->prepare("
        SELECT u.*, a.nivel 
        FROM usuarios u
        INNER JOIN administradores a ON u.id = a.usuario_id
        WHERE (u.email = ? OR u.username = ?) AND u.ativo = 1
    ");
    $stmt->execute([$emailOrUsername, $emailOrUsername]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['senha'])) {
        // Login como Admin
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_name'] = $admin['nome'];
        $_SESSION['user_email'] = $admin['email'];
        $_SESSION['user_type'] = 'admin';
        $_SESSION['admin_level'] = $admin['nivel'];
        
        // Atualizar último acesso
        $stmt = $pdo->prepare("UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = ?");
        $stmt->execute([$admin['id']]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Login realizado com sucesso!',
            'redirect' => '../admin/dashboard.php'
        ]);
        exit;
    }

    // Verificar se é estudante
    $stmt = $pdo->prepare("
        SELECT u.*, e.progresso 
        FROM usuarios u
        INNER JOIN estudantes e ON u.id = e.usuario_id
        WHERE (u.email = ? OR u.username = ?) AND u.ativo = 1
    ");
    $stmt->execute([$emailOrUsername, $emailOrUsername]);
    $estudante = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($estudante && password_verify($password, $estudante['senha'])) {
        // Login como Estudante
        $_SESSION['user_id'] = $estudante['id'];
        $_SESSION['user_name'] = $estudante['nome'];
        $_SESSION['user_email'] = $estudante['email'];
        $_SESSION['user_type'] = 'estudante';
        $_SESSION['progresso'] = $estudante['progresso'];
        
        // Atualizar último acesso
        $stmt = $pdo->prepare("UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = ?");
        $stmt->execute([$estudante['id']]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Bem-vindo de volta!',
            'redirect' => '../public/index.php'
        ]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Credenciais inválidas']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no servidor. Tente novamente.']);
}
?>