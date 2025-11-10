<?php
/**
 * API - Buscar dados de uma região específica
 * Retorna informações completas sobre uma região brasileira
 */

header('Content-Type: application/json; charset=utf-8');
require_once '../config/database.php';

try {
    // Obtém o slug da região via GET
    $slug = $_GET['slug'] ?? null;

    if (!$slug) {
        throw new Exception('Slug da região não fornecido');
    }

    // Busca dados da região
    $stmt = $pdo->prepare("
        SELECT
            id,
            nome,
            slug,
            descricao,
            ordem
        FROM regioes
        WHERE slug = ? AND ativo = TRUE
        LIMIT 1
    ");
    $stmt->execute([$slug]);
    $regiao = $stmt->fetch();

    if (!$regiao) {
        throw new Exception('Região não encontrada');
    }

    // Conta quantas receitas essa região tem
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total
        FROM receitas
        WHERE regiao_id = ?
    ");
    $stmt->execute([$regiao['id']]);
    $totalReceitas = $stmt->fetch()['total'];

    // Busca estados da região (se houver)
    $stmt = $pdo->prepare("
        SELECT
            nome,
            slug,
            capital,
            descricao,
            ingrediente_destaque
        FROM estados_regiao
        WHERE regiao_id = ?
        ORDER BY nome
    ");
    $stmt->execute([$regiao['id']]);
    $estados = $stmt->fetchAll();

    // Monta resposta
    $response = [
        'success' => true,
        'regiao' => [
            'id' => (int)$regiao['id'],
            'nome' => $regiao['nome'],
            'slug' => $regiao['slug'],
            'descricao' => $regiao['descricao'],
            'ordem' => (int)$regiao['ordem'],
            'total_receitas' => (int)$totalReceitas,
            'estados' => $estados
        ]
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
