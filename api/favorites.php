<?php
/**
 * API para sistema de favoritos
 * Endpoint: /api/favorites.php
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/conexao.php';
session_start();

$method = $_SERVER['REQUEST_METHOD'];

try {
    // Check if user is authenticated
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Usuário não autenticado']);
        exit;
    }

    $usuario_id = $_SESSION['user_id'];

    // Get estudante_id
    $stmt = $pdo->prepare("SELECT id FROM estudantes WHERE usuario_id = :usuario_id");
    $stmt->execute(['usuario_id' => $usuario_id]);
    $estudante = $stmt->fetch();

    if (!$estudante) {
        http_response_code(403);
        echo json_encode(['error' => 'Usuário não é estudante']);
        exit;
    }

    $estudante_id = $estudante['id'];

    if ($method === 'GET') {
        // Get user's favorites
        if (isset($_GET['receita_id'])) {
            // Check if specific recipe is favorited
            $receita_id = filter_var($_GET['receita_id'], FILTER_VALIDATE_INT);

            $stmt = $pdo->prepare("
                SELECT COUNT(*) as is_favorite
                FROM favoritos
                WHERE estudante_id = :estudante_id AND receita_id = :receita_id
            ");
            $stmt->execute([
                'estudante_id' => $estudante_id,
                'receita_id' => $receita_id
            ]);
            $result = $stmt->fetch();

            echo json_encode([
                'is_favorite' => (bool)$result['is_favorite']
            ], JSON_UNESCAPED_UNICODE);

        } else {
            // Get all favorites
            $stmt = $pdo->prepare("
                SELECT
                    r.*,
                    reg.nome as regiao_nome,
                    reg.slug as regiao_slug,
                    f.data_criacao as favoritado_em
                FROM favoritos f
                INNER JOIN receitas r ON f.receita_id = r.id
                LEFT JOIN regioes reg ON r.regiao_id = reg.id
                WHERE f.estudante_id = :estudante_id
                ORDER BY f.data_criacao DESC
            ");
            $stmt->execute(['estudante_id' => $estudante_id]);
            $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($favorites, JSON_UNESCAPED_UNICODE);
        }

    } elseif ($method === 'POST') {
        // Add to favorites
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['receita_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'receita_id é obrigatório']);
            exit;
        }

        $receita_id = filter_var($data['receita_id'], FILTER_VALIDATE_INT);

        // Check if already favorited
        $stmt = $pdo->prepare("
            SELECT id FROM favoritos
            WHERE estudante_id = :estudante_id AND receita_id = :receita_id
        ");
        $stmt->execute([
            'estudante_id' => $estudante_id,
            'receita_id' => $receita_id
        ]);

        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Receita já está nos favoritos']);
            exit;
        }

        // Add favorite
        $stmt = $pdo->prepare("
            INSERT INTO favoritos (estudante_id, receita_id)
            VALUES (:estudante_id, :receita_id)
        ");
        $stmt->execute([
            'estudante_id' => $estudante_id,
            'receita_id' => $receita_id
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Receita adicionada aos favoritos'
        ], JSON_UNESCAPED_UNICODE);

    } elseif ($method === 'DELETE') {
        // Remove from favorites
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['receita_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'receita_id é obrigatório']);
            exit;
        }

        $receita_id = filter_var($data['receita_id'], FILTER_VALIDATE_INT);

        $stmt = $pdo->prepare("
            DELETE FROM favoritos
            WHERE estudante_id = :estudante_id AND receita_id = :receita_id
        ");
        $stmt->execute([
            'estudante_id' => $estudante_id,
            'receita_id' => $receita_id
        ]);

        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(['error' => 'Favorito não encontrado']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Receita removida dos favoritos'
        ], JSON_UNESCAPED_UNICODE);

    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao processar favorito', 'message' => $e->getMessage()]);
}
