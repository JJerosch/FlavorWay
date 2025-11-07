<?php
// api/auth.php - VERSÃO FINAL 100% FUNCIONANDO
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Suporte a requisições OPTIONS (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ========================================
// FUNÇÕES DE AUTENTICAÇÃO
// ========================================
function login($email, $senha) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = ? AND ativo = 1 LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_type'] = 'admin'; // ou mude conforme sua lógica

            // Atualiza último acesso
            $pdo->prepare("UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = ?")->execute([$user['id']]);

            return [
                'success' => true,
                'message' => 'Login realizado com sucesso!',
                'user' => [
                    'id' => $user['id'],
                    'nome' => $user['nome'],
                    'email' => $user['email']
                ]
            ];
        }
        return ['success' => false, 'message' => 'E-mail ou senha incorretos'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Erro no servidor'];
    }
}

function registrar($nome, $email, $senha) {
    global $pdo;
    try {
        // Verifica se já existe
        $check = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            return ['success' => false, 'message' => 'E-mail já cadastrado'];
        }

        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, ativo, data_criacao) VALUES (?, ?, ?, 1, NOW())");
        $stmt->execute([$nome, $email, $hash]);

        return ['success' => true, 'message' => 'Cadastro realizado com sucesso!'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Erro ao cadastrar'];
    }
}

function logout() {
    session_destroy();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUsuario() {
    if (!isLoggedIn()) return null;
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, nome, email FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// ========================================
// ROTEAMENTO
// ========================================
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$action = $input['action'] ?? $_GET['action'] ?? '';

if ($method === 'POST') {
    switch ($action) {
        case 'login':
            $result = login($input['email'] ?? '', $input['senha'] ?? '');
            echo json_encode($result);
            break;

        case 'register':
            $result = registrar(
                $input['nome'] ?? '',
                $input['email'] ?? '',
                $input['senha'] ?? ''
            );
            echo json_encode($result);
            break;

        case 'logout':
            logout();
            echo json_encode(['success' => true, 'message' => 'Deslogado com sucesso']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Ação inválida']);
    }
} elseif ($method === 'GET' && $action === 'check') {
    echo json_encode([
        'loggedIn' => isLoggedIn(),
        'usuario' => getUsuario()
    ]);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}
?>