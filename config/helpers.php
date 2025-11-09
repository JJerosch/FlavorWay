<?php
/**
 * FlavorWay - Funções Helper
 * Funções reutilizáveis para todo o sistema
 */

/**
 * Retorna resposta JSON padronizada
 * @param bool $success - Se a operação foi bem-sucedida
 * @param string $message - Mensagem para o usuário
 * @param mixed $data - Dados adicionais (opcional)
 */
function jsonResponse($success, $message, $data = null) {
    $response = [
        'success' => $success,
        'message' => $message
    ];

    if ($data !== null) {
        $response['data'] = $data;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

/**
 * Valida se todos os campos obrigatórios estão preenchidos
 * @param array $fields - Array com os campos a validar
 * @return array - ['valid' => bool, 'message' => string]
 */
function validateRequiredFields($fields) {
    foreach ($fields as $fieldName => $fieldValue) {
        if (empty(trim($fieldValue))) {
            return [
                'valid' => false,
                'message' => "O campo '$fieldName' é obrigatório"
            ];
        }
    }

    return ['valid' => true, 'message' => ''];
}

/**
 * Valida formato de email
 * @param string $email
 * @return bool
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida força da senha
 * @param string $password
 * @return array - ['valid' => bool, 'message' => string]
 */
function validatePasswordStrength($password) {
    if (strlen($password) < 6) {
        return [
            'valid' => false,
            'message' => 'A senha deve ter no mínimo 6 caracteres'
        ];
    }

    return ['valid' => true, 'message' => ''];
}

/**
 * Gera hash seguro da senha
 * @param string $password
 * @return string
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verifica se senha corresponde ao hash
 * @param string $password
 * @param string $hash
 * @return bool
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Verifica se usuário está logado
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Verifica se usuário é administrador
 * @return bool
 */
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

/**
 * Redireciona para página de login se não estiver autenticado
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../public/login.php');
        exit;
    }
}

/**
 * Redireciona para página de login se não for administrador
 */
function requireAdmin() {
    if (!isLoggedIn() || !isAdmin()) {
        header('Location: ../public/login.php');
        exit;
    }
}

/**
 * Sanitiza string para prevenir XSS
 * @param string $data
 * @return string
 */
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Verifica se email já está cadastrado
 * @param PDO $pdo
 * @param string $email
 * @param int|null $excludeUserId - ID do usuário a excluir da verificação (para updates)
 * @return bool
 */
function emailExists($pdo, $email, $excludeUserId = null) {
    $query = "SELECT id FROM usuarios WHERE email = ?";
    $params = [$email];

    if ($excludeUserId !== null) {
        $query .= " AND id != ?";
        $params[] = $excludeUserId;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    return $stmt->fetch() !== false;
}

/**
 * Verifica se username já está cadastrado
 * @param PDO $pdo
 * @param string $username
 * @param int|null $excludeUserId - ID do usuário a excluir da verificação (para updates)
 * @return bool
 */
function usernameExists($pdo, $username, $excludeUserId = null) {
    $query = "SELECT id FROM usuarios WHERE username = ?";
    $params = [$username];

    if ($excludeUserId !== null) {
        $query .= " AND id != ?";
        $params[] = $excludeUserId;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    return $stmt->fetch() !== false;
}

/**
 * Busca usuário por email ou username
 * @param PDO $pdo
 * @param string $emailOrUsername
 * @return array|false
 */
function findUserByEmailOrUsername($pdo, $emailOrUsername) {
    $stmt = $pdo->prepare("
        SELECT * FROM usuarios
        WHERE email = ? OR username = ?
    ");
    $stmt->execute([$emailOrUsername, $emailOrUsername]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Atualiza último acesso do usuário
 * @param PDO $pdo
 * @param int $userId
 */
function updateLastAccess($pdo, $userId) {
    $stmt = $pdo->prepare("UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = ?");
    $stmt->execute([$userId]);
}

/**
 * Inicia sessão de forma segura
 */
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
