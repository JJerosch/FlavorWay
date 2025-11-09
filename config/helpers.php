<?php
/**
 * FlavorWay - Helper Functions
 * Reusable functions for the entire system
 */

/**
 * Returns standardized JSON response
 * @param bool $success - Whether the operation was successful
 * @param string $message - Message for the user
 * @param mixed $data - Additional data (optional)
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
 * Validates if all required fields are filled
 * @param array $fields - Array with fields to validate
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
 * Validates email format
 * @param string $email
 * @return bool
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validates password strength
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
 * Generates secure password hash
 * @param string $password
 * @return string
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verifies if password matches hash
 * @param string $password
 * @param string $hash
 * @return bool
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Checks if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Checks if user is administrator
 * @return bool
 */
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

/**
 * Redirects to login page if not authenticated
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../public/login.php');
        exit;
    }
}

/**
 * Redirects to login page if not administrator
 */
function requireAdmin() {
    if (!isLoggedIn() || !isAdmin()) {
        header('Location: ../public/login.php');
        exit;
    }
}

/**
 * Sanitizes string to prevent XSS
 * @param string $data
 * @return string
 */
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Checks if email is already registered
 * @param PDO $pdo
 * @param string $email
 * @param int|null $excludeUserId - User ID to exclude from check (for updates)
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
 * Checks if username is already registered
 * @param PDO $pdo
 * @param string $username
 * @param int|null $excludeUserId - User ID to exclude from check (for updates)
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
 * Finds user by email or username
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
 * Updates user's last access timestamp
 * @param PDO $pdo
 * @param int $userId
 */
function updateLastAccess($pdo, $userId) {
    $stmt = $pdo->prepare("UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = ?");
    $stmt->execute([$userId]);
}

/**
 * Starts session securely
 */
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
