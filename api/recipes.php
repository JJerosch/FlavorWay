<?php
/**
 * API para buscar receitas
 * Endpoint: /api/recipes.php
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/conexao.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {

        if (isset($_GET['id'])) {
            // Get single recipe with full details
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

            $stmt = $pdo->prepare("
                SELECT
                    r.*,
                    reg.nome as regiao_nome,
                    reg.slug as regiao_slug
                FROM receitas r
                LEFT JOIN regioes reg ON r.regiao_id = reg.id
                WHERE r.id = :id
            ");
            $stmt->execute(['id' => $id]);
            $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$recipe) {
                http_response_code(404);
                echo json_encode(['error' => 'Receita não encontrada']);
                exit;
            }

            // Get ingredients
            $stmt = $pdo->prepare("
                SELECT * FROM ingredientes
                WHERE receita_id = :receita_id
                ORDER BY categoria, nome
            ");
            $stmt->execute(['receita_id' => $id]);
            $recipe['ingredientes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get tags
            $stmt = $pdo->prepare("
                SELECT t.*
                FROM tags t
                INNER JOIN receita_tags rt ON t.id = rt.tag_id
                WHERE rt.receita_id = :receita_id
            ");
            $stmt->execute(['receita_id' => $id]);
            $recipe['tags'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get average rating and count
            $stmt = $pdo->prepare("
                SELECT
                    COUNT(*) as total_avaliacoes,
                    AVG(nota) as media_avaliacoes
                FROM avaliacoes
                WHERE receita_id = :receita_id
            ");
            $stmt->execute(['receita_id' => $id]);
            $rating = $stmt->fetch(PDO::FETCH_ASSOC);
            $recipe['total_avaliacoes'] = (int)$rating['total_avaliacoes'];
            $recipe['media_avaliacoes'] = $rating['media_avaliacoes'] ? round($rating['media_avaliacoes'], 1) : null;

            echo json_encode($recipe, JSON_UNESCAPED_UNICODE);

        } elseif (isset($_GET['regiao_id'])) {
            // Get recipes by region
            $regiao_id = filter_var($_GET['regiao_id'], FILTER_VALIDATE_INT);
            $limit = isset($_GET['limit']) ? filter_var($_GET['limit'], FILTER_VALIDATE_INT) : 20;
            $offset = isset($_GET['offset']) ? filter_var($_GET['offset'], FILTER_VALIDATE_INT) : 0;

            $stmt = $pdo->prepare("
                SELECT
                    r.*,
                    reg.nome as regiao_nome,
                    reg.slug as regiao_slug,
                    COUNT(DISTINCT a.id) as total_avaliacoes,
                    AVG(a.nota) as media_avaliacoes
                FROM receitas r
                LEFT JOIN regioes reg ON r.regiao_id = reg.id
                LEFT JOIN avaliacoes a ON r.id = a.receita_id
                WHERE r.regiao_id = :regiao_id
                GROUP BY r.id
                ORDER BY r.destaque DESC, r.rating DESC, r.created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':regiao_id', $regiao_id, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format ratings
            foreach ($recipes as &$recipe) {
                $recipe['media_avaliacoes'] = $recipe['media_avaliacoes'] ? round($recipe['media_avaliacoes'], 1) : null;
            }

            echo json_encode($recipes, JSON_UNESCAPED_UNICODE);

        } elseif (isset($_GET['regiao_slug'])) {
            // Get recipes by region slug
            $regiao_slug = filter_var($_GET['regiao_slug'], FILTER_SANITIZE_STRING);
            $limit = isset($_GET['limit']) ? filter_var($_GET['limit'], FILTER_VALIDATE_INT) : 20;
            $offset = isset($_GET['offset']) ? filter_var($_GET['offset'], FILTER_VALIDATE_INT) : 0;

            $stmt = $pdo->prepare("
                SELECT
                    r.*,
                    reg.nome as regiao_nome,
                    reg.slug as regiao_slug,
                    COUNT(DISTINCT a.id) as total_avaliacoes,
                    AVG(a.nota) as media_avaliacoes
                FROM receitas r
                INNER JOIN regioes reg ON r.regiao_id = reg.id
                LEFT JOIN avaliacoes a ON r.id = a.receita_id
                WHERE reg.slug = :regiao_slug
                GROUP BY r.id
                ORDER BY r.destaque DESC, r.rating DESC, r.created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':regiao_slug', $regiao_slug, PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format ratings
            foreach ($recipes as &$recipe) {
                $recipe['media_avaliacoes'] = $recipe['media_avaliacoes'] ? round($recipe['media_avaliacoes'], 1) : null;
            }

            echo json_encode($recipes, JSON_UNESCAPED_UNICODE);

        } else {
            // Get all recipes
            $limit = isset($_GET['limit']) ? filter_var($_GET['limit'], FILTER_VALIDATE_INT) : 20;
            $offset = isset($_GET['offset']) ? filter_var($_GET['offset'], FILTER_VALIDATE_INT) : 0;

            $stmt = $pdo->prepare("
                SELECT
                    r.*,
                    reg.nome as regiao_nome,
                    reg.slug as regiao_slug,
                    COUNT(DISTINCT a.id) as total_avaliacoes,
                    AVG(a.nota) as media_avaliacoes
                FROM receitas r
                LEFT JOIN regioes reg ON r.regiao_id = reg.id
                LEFT JOIN avaliacoes a ON r.id = a.receita_id
                GROUP BY r.id
                ORDER BY r.destaque DESC, r.rating DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format ratings
            foreach ($recipes as &$recipe) {
                $recipe['media_avaliacoes'] = $recipe['media_avaliacoes'] ? round($recipe['media_avaliacoes'], 1) : null;
            }

            echo json_encode($recipes, JSON_UNESCAPED_UNICODE);
        }

    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar dados', 'message' => $e->getMessage()]);
}
