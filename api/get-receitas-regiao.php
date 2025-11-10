<?php
/**
 * API - Buscar receitas de uma região
 * Retorna todas as receitas de uma região específica
 */

header('Content-Type: application/json; charset=utf-8');
require_once '../config/database.php';

try {
    // Obtém o slug da região via GET
    $slug = $_GET['slug'] ?? null;

    if (!$slug) {
        throw new Exception('Slug da região não fornecido');
    }

    // Busca o ID da região
    $stmt = $pdo->prepare("SELECT id FROM regioes WHERE slug = ? AND ativo = TRUE");
    $stmt->execute([$slug]);
    $regiao = $stmt->fetch();

    if (!$regiao) {
        throw new Exception('Região não encontrada');
    }

    $regiao_id = $regiao['id'];

    // Busca todas as receitas da região
    $stmt = $pdo->prepare("
        SELECT
            r.id,
            r.nome,
            r.descricao,
            r.tempo_preparo,
            r.pessoas,
            r.rating,
            r.dificuldade,
            r.badge,
            r.tempo_cozimento,
            r.rendimento,
            r.calorias,
            r.proteinas,
            r.carboidratos,
            r.gorduras,
            r.destaque
        FROM receitas r
        WHERE r.regiao_id = ?
        ORDER BY r.destaque DESC, r.rating DESC, r.nome ASC
    ");
    $stmt->execute([$regiao_id]);
    $receitas = $stmt->fetchAll();

    // Para cada receita, busca os ingredientes e tags
    foreach ($receitas as &$receita) {
        // Busca ingredientes
        $stmt = $pdo->prepare("
            SELECT nome, categoria
            FROM ingredientes
            WHERE receita_id = ?
        ");
        $stmt->execute([$receita['id']]);
        $receita['ingredientes'] = $stmt->fetchAll();

        // Busca tags
        $stmt = $pdo->prepare("
            SELECT t.nome
            FROM tags t
            INNER JOIN receita_tags rt ON t.id = rt.tag_id
            WHERE rt.receita_id = ?
        ");
        $stmt->execute([$receita['id']]);
        $tags = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $receita['tags'] = $tags;

        // Conta avaliações
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as total
            FROM avaliacoes
            WHERE receita_id = ?
        ");
        $stmt->execute([$receita['id']]);
        $receita['total_avaliacoes'] = (int)$stmt->fetch()['total'];

        // Converte tipos numéricos
        $receita['id'] = (int)$receita['id'];
        $receita['rating'] = (float)$receita['rating'];
        $receita['destaque'] = (bool)$receita['destaque'];
    }

    // Retorna resposta
    echo json_encode([
        'success' => true,
        'total' => count($receitas),
        'receitas' => $receitas
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
