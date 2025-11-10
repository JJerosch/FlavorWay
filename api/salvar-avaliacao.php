<?php
/**
 * API - Salvar avaliação
 * Permite que usuários adicionem ou editem suas avaliações de receitas
 */

session_start();
header('Content-Type: application/json; charset=utf-8');
require_once '../config/database.php';

try {
    // Verifica se usuário está logado
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Usuário não autenticado');
    }

    // Obtém dados da requisição
    $input = json_decode(file_get_contents('php://input'), true);
    $receita_id = $input['receita_id'] ?? null;
    $nota = $input['nota'] ?? null;
    $comentario = $input['comentario'] ?? '';

    // Validações
    if (!$receita_id || !is_numeric($receita_id)) {
        throw new Exception('ID da receita inválido');
    }

    if (!$nota || !is_numeric($nota) || $nota < 1 || $nota > 5) {
        throw new Exception('Nota deve ser entre 1 e 5');
    }

    // Verifica se a receita existe
    $stmt = $pdo->prepare("SELECT id FROM receitas WHERE id = ?");
    $stmt->execute([$receita_id]);
    if (!$stmt->fetch()) {
        throw new Exception('Receita não encontrada');
    }

    // Verifica se o usuário já avaliou esta receita
    $stmt = $pdo->prepare("
        SELECT id
        FROM avaliacoes
        WHERE usuario_id = ? AND receita_id = ?
    ");
    $stmt->execute([$_SESSION['user_id'], $receita_id]);
    $avaliacao_existente = $stmt->fetch();

    if ($avaliacao_existente) {
        // Atualiza avaliação existente
        $stmt = $pdo->prepare("
            UPDATE avaliacoes
            SET nota = ?, comentario = ?, data_atualizacao = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $stmt->execute([$nota, $comentario, $avaliacao_existente['id']]);
        $acao = 'atualizada';
        $avaliacao_id = $avaliacao_existente['id'];
    } else {
        // Cria nova avaliação
        $stmt = $pdo->prepare("
            INSERT INTO avaliacoes (usuario_id, receita_id, nota, comentario)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $receita_id, $nota, $comentario]);
        $acao = 'criada';
        $avaliacao_id = $pdo->lastInsertId();
    }

    // Recalcula o rating médio da receita
    $stmt = $pdo->prepare("
        SELECT AVG(nota) as media
        FROM avaliacoes
        WHERE receita_id = ?
    ");
    $stmt->execute([$receita_id]);
    $nova_media = $stmt->fetch()['media'];

    // Atualiza o rating da receita
    $stmt = $pdo->prepare("
        UPDATE receitas
        SET rating = ?
        WHERE id = ?
    ");
    $stmt->execute([$nova_media, $receita_id]);

    // Busca as estatísticas atualizadas
    $stmt = $pdo->prepare("
        SELECT
            COUNT(*) as total,
            AVG(nota) as media,
            SUM(CASE WHEN nota = 5 THEN 1 ELSE 0 END) as cinco_estrelas,
            SUM(CASE WHEN nota = 4 THEN 1 ELSE 0 END) as quatro_estrelas,
            SUM(CASE WHEN nota = 3 THEN 1 ELSE 0 END) as tres_estrelas,
            SUM(CASE WHEN nota = 2 THEN 1 ELSE 0 END) as duas_estrelas,
            SUM(CASE WHEN nota = 1 THEN 1 ELSE 0 END) as uma_estrela
        FROM avaliacoes
        WHERE receita_id = ?
    ");
    $stmt->execute([$receita_id]);
    $estatisticas = $stmt->fetch();

    // Retorna resposta
    echo json_encode([
        'success' => true,
        'acao' => $acao,
        'avaliacao_id' => (int)$avaliacao_id,
        'novo_rating' => round((float)$nova_media, 1),
        'estatisticas' => $estatisticas
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
