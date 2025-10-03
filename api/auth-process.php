<?php
require_once '../config/database.php';
require_once '../config/auth.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch ($action) {
        case 'login':
            $email = $data['email'] ?? '';
            $senha = $data['senha'] ?? '';
            
            if (empty($email) || empty($senha)) {
                echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
                exit;
            }
            
            $result = login($email, $senha);
            echo json_encode($result);
            break;
            
        case 'register':
            $nome = $data['nome'] ?? '';
            $email = $data['email'] ?? '';
            $senha = $data['senha'] ?? '';
            $confirmarSenha = $data['confirmar_senha'] ?? '';
            
            if (empty($nome) || empty($email) || empty($senha)) {
                echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
                exit;
            }
            
            if ($senha !== $confirmarSenha) {
                echo json_encode(['success' => false, 'message' => 'As senhas não coincidem']);
                exit;
            }
            
            if (strlen($senha) < 6) {
                echo json_encode(['success' => false, 'message' => 'A senha deve ter no mínimo 6 caracteres']);
                exit;
            }
            
            $result = registrar($nome, $email, $senha);
            echo json_encode($result);
            break;
            
        case 'logout':
            logout();
            echo json_encode(['success' => true]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Ação inválida']);
    }
} elseif ($method === 'GET' && $action === 'check') {
    // Verificar se está logado
    $usuario = getUsuario();
    echo json_encode([
        'loggedIn' => isLoggedIn(),
        'usuario' => $usuario
    ]);
}
?>
