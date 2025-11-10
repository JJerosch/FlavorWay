<?php
/**
 * API - Adicionar/Remover favorito
 * Permite que estudantes marquem ou desmarquem receitas como favoritas
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

    if (!$receita_id || !is_numeric($receita_id)) {
        throw new Exception('ID da receita inválido');
    }

    // Verifica se a receita existe
    $stmt = $pdo->prepare("SELECT id FROM receitas WHERE id = ?");
    $stmt->execute([$receita_id]);
    if (!$stmt->fetch()) {
        throw new Exception('Receita não encontrada');
    }

    // Busca ou cria o estudante
    $stmt = $pdo->prepare("SELECT id FROM estudantes WHERE usuario_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $estudante = $stmt->fetch();

    if (!$estudante) {
        // Cria novo estudante
        $stmt = $pdo->prepare("INSERT INTO estudantes (usuario_id) VALUES (?)");
        $stmt->execute([$_SESSION['user_id']]);
        $estudante_id = $pdo->lastInsertId();
    } else {
        $estudante_id = $estudante['id'];
    }

    // Verifica se já está nos favoritos
    $stmt = $pdo->prepare("
        SELECT id
        FROM favoritos
        WHERE estudante_id = ? AND receita_id = ?
    ");
    $stmt->execute([$estudante_id, $receita_id]);
    $favorito_existente = $stmt->fetch();

    if ($favorito_existente) {
        // Remove dos favoritos
        $stmt = $pdo->prepare("
            DELETE FROM favoritos
            WHERE estudante_id = ? AND receita_id = ?
        ");
        $stmt->execute([$estudante_id, $receita_id]);

        $acao = 'removido';
        $is_favorito = false;
    } else {
        // Adiciona aos favoritos
        $stmt = $pdo->prepare("
            INSERT INTO favoritos (estudante_id, receita_id)
            VALUES (?, ?)
        ");
        $stmt->execute([$estudante_id, $receita_id]);

        $acao = 'adicionado';
        $is_favorito = true;
    }

    // Conta total de favoritos da receita
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total
        FROM favoritos
        WHERE receita_id = ?
    ");
    $stmt->execute([$receita_id]);
    $total_favoritos = (int)$stmt->fetch()['total'];

    // Retorna resposta
    echo json_encode([
        'success' => true,
        'acao' => $acao,
        'is_favorito' => $is_favorito,
        'total_favoritos' => $total_favoritos
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
