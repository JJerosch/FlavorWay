<?php
/**
 * API para buscar lista de compras do usuário
 */

session_start();
header('Content-Type: application/json');

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Você precisa estar logado'
    ]);
    exit;
}

require_once '../config/database.php';

try {
    $usuario_id = $_SESSION['user_id'];

    // Busca itens não comprados
    $stmt = $pdo->prepare("
        SELECT
            lc.id,
            lc.item,
            lc.quantidade,
            lc.comprado,
            lc.receita_id,
            r.nome as receita_nome,
            lc.created_at
        FROM lista_compras lc
        LEFT JOIN receitas r ON lc.receita_id = r.id
        WHERE lc.usuario_id = ? AND lc.comprado = 0
        ORDER BY lc.created_at DESC
    ");
    $stmt->execute([$usuario_id]);
    $itens_pendentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Busca itens comprados
    $stmt = $pdo->prepare("
        SELECT
            lc.id,
            lc.item,
            lc.quantidade,
            lc.comprado,
            lc.receita_id,
            r.nome as receita_nome,
            lc.created_at
        FROM lista_compras lc
        LEFT JOIN receitas r ON lc.receita_id = r.id
        WHERE lc.usuario_id = ? AND lc.comprado = 1
        ORDER BY lc.updated_at DESC
        LIMIT 20
    ");
    $stmt->execute([$usuario_id]);
    $itens_comprados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Conta totais
    $total_pendentes = count($itens_pendentes);
    $total_comprados = count($itens_comprados);

    echo json_encode([
        'success' => true,
        'itens_pendentes' => $itens_pendentes,
        'itens_comprados' => $itens_comprados,
        'total_pendentes' => $total_pendentes,
        'total_comprados' => $total_comprados
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao buscar lista: ' . $e->getMessage()
    ]);
}
