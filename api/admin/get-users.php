<?php
session_start();
header('Content-Type: application/json');

// === VERIFICA LOGIN E PERMISSÕES DE ADMIN ===
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

// === CARREGA CONEXÃO ===
require_once '../../config/database.php';

// === VERIFICA SE É ADMIN ===
try {
    $stmt = $pdo->prepare("SELECT is_admin FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user || !$user['is_admin']) {
        echo json_encode(['success' => false, 'message' => 'Acesso negado']);
        exit;
    }

    // === BUSCA TODOS OS USUÁRIOS ===
    $stmt = $pdo->query("
        SELECT
            id,
            nome,
            email,
            is_admin,
            created_at
        FROM usuarios
        ORDER BY created_at DESC
    ");

    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'usuarios' => $usuarios
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao buscar usuários: ' . $e->getMessage()
    ]);
}
