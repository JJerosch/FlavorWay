<?php
/**
 * API para sistema de avaliações
 * Endpoint: /api/ratings.php
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/conexao.php';
session_start();

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        // Get ratings for a recipe
        if (!isset($_GET['receita_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'receita_id é obrigatório']);
            exit;
        }

        $receita_id = filter_var($_GET['receita_id'], FILTER_VALIDATE_INT);
        $limit = isset($_GET['limit']) ? filter_var($_GET['limit'], FILTER_VALIDATE_INT) : 10;
        $offset = isset($_GET['offset']) ? filter_var($_GET['offset'], FILTER_VALIDATE_INT) : 0;

        $stmt = $pdo->prepare("
            SELECT
                a.*,
                u.nome as usuario_nome,
                u.avatar as usuario_avatar
            FROM avaliacoes a
            INNER JOIN usuarios u ON a.usuario_id = u.id
            WHERE a.receita_id = :receita_id
            ORDER BY a.data_criacao DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':receita_id', $receita_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get summary
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
            WHERE receita_id = :receita_id
        ");
        $stmt->execute(['receita_id' => $receita_id]);
        $summary = $stmt->fetch(PDO::FETCH_ASSOC);
        $summary['media'] = $summary['media'] ? round($summary['media'], 1) : 0;

        echo json_encode([
            'avaliacoes' => $ratings,
            'resumo' => $summary
        ], JSON_UNESCAPED_UNICODE);

    } elseif ($method === 'POST') {
        // Create or update rating
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Usuário não autenticado']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['receita_id']) || !isset($data['nota'])) {
            http_response_code(400);
            echo json_encode(['error' => 'receita_id e nota são obrigatórios']);
            exit;
        }

        $receita_id = filter_var($data['receita_id'], FILTER_VALIDATE_INT);
        $nota = filter_var($data['nota'], FILTER_VALIDATE_INT);
        $comentario = isset($data['comentario']) ? trim($data['comentario']) : null;
        $usuario_id = $_SESSION['user_id'];

        // Validate nota (1-5)
        if ($nota < 1 || $nota > 5) {
            http_response_code(400);
            echo json_encode(['error' => 'Nota deve ser entre 1 e 5']);
            exit;
        }

        // Check if user already rated this recipe
        $stmt = $pdo->prepare("
            SELECT id FROM avaliacoes
            WHERE usuario_id = :usuario_id AND receita_id = :receita_id
        ");
        $stmt->execute([
            'usuario_id' => $usuario_id,
            'receita_id' => $receita_id
        ]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Update existing rating
            $stmt = $pdo->prepare("
                UPDATE avaliacoes
                SET nota = :nota, comentario = :comentario, data_atualizacao = NOW()
                WHERE id = :id
            ");
            $stmt->execute([
                'nota' => $nota,
                'comentario' => $comentario,
                'id' => $existing['id']
            ]);
            $message = 'Avaliação atualizada com sucesso';
        } else {
            // Create new rating
            $stmt = $pdo->prepare("
                INSERT INTO avaliacoes (usuario_id, receita_id, nota, comentario)
                VALUES (:usuario_id, :receita_id, :nota, :comentario)
            ");
            $stmt->execute([
                'usuario_id' => $usuario_id,
                'receita_id' => $receita_id,
                'nota' => $nota,
                'comentario' => $comentario
            ]);
            $message = 'Avaliação criada com sucesso';
        }

        // Update recipe rating
        $stmt = $pdo->prepare("
            UPDATE receitas
            SET rating = (
                SELECT AVG(nota)
                FROM avaliacoes
                WHERE receita_id = :receita_id
            )
            WHERE id = :receita_id
        ");
        $stmt->execute(['receita_id' => $receita_id]);

        echo json_encode([
            'success' => true,
            'message' => $message
        ], JSON_UNESCAPED_UNICODE);

    } elseif ($method === 'DELETE') {
        // Delete rating
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Usuário não autenticado']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['receita_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'receita_id é obrigatório']);
            exit;
        }

        $receita_id = filter_var($data['receita_id'], FILTER_VALIDATE_INT);
        $usuario_id = $_SESSION['user_id'];

        $stmt = $pdo->prepare("
            DELETE FROM avaliacoes
            WHERE usuario_id = :usuario_id AND receita_id = :receita_id
        ");
        $stmt->execute([
            'usuario_id' => $usuario_id,
            'receita_id' => $receita_id
        ]);

        // Update recipe rating
        $stmt = $pdo->prepare("
            UPDATE receitas
            SET rating = COALESCE((
                SELECT AVG(nota)
                FROM avaliacoes
                WHERE receita_id = :receita_id
            ), 0)
            WHERE id = :receita_id
        ");
        $stmt->execute(['receita_id' => $receita_id]);

        echo json_encode([
            'success' => true,
            'message' => 'Avaliação removida com sucesso'
        ], JSON_UNESCAPED_UNICODE);

    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao processar avaliação', 'message' => $e->getMessage()]);
}
