<?php
/**
 * API para buscar informações de regiões
 * Endpoint: /api/regions.php
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/conexao.php';

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        // Check if slug or ID is provided
        if (isset($_GET['slug'])) {
            // Get single region by slug
            $slug = filter_var($_GET['slug'], FILTER_SANITIZE_STRING);

            $stmt = $pdo->prepare("
                SELECT
                    r.*,
                    COUNT(DISTINCT rec.id) as total_receitas
                FROM regioes r
                LEFT JOIN receitas rec ON rec.regiao_id = r.id
                WHERE r.slug = :slug AND r.ativo = 1
                GROUP BY r.id
            ");
            $stmt->execute(['slug' => $slug]);
            $region = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$region) {
                http_response_code(404);
                echo json_encode(['error' => 'Região não encontrada']);
                exit;
            }

            // Get states
            $stmt = $pdo->prepare("
                SELECT * FROM estados_regiao
                WHERE regiao_id = :regiao_id
                ORDER BY nome
            ");
            $stmt->execute(['regiao_id' => $region['id']]);
            $region['estados'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get cultural info
            $stmt = $pdo->prepare("
                SELECT * FROM cultura_regiao
                WHERE regiao_id = :regiao_id
                ORDER BY tipo, id
            ");
            $stmt->execute(['regiao_id' => $region['id']]);
            $region['cultura'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get techniques
            $stmt = $pdo->prepare("
                SELECT t.*
                FROM tecnicas t
                INNER JOIN tecnicas_regiao tr ON t.id = tr.tecnica_id
                WHERE tr.regiao_id = :regiao_id
            ");
            $stmt->execute(['regiao_id' => $region['id']]);
            $region['tecnicas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($region, JSON_UNESCAPED_UNICODE);

        } elseif (isset($_GET['id'])) {
            // Get single region by ID
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

            $stmt = $pdo->prepare("
                SELECT
                    r.*,
                    COUNT(DISTINCT rec.id) as total_receitas
                FROM regioes r
                LEFT JOIN receitas rec ON rec.regiao_id = r.id
                WHERE r.id = :id AND r.ativo = 1
                GROUP BY r.id
            ");
            $stmt->execute(['id' => $id]);
            $region = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$region) {
                http_response_code(404);
                echo json_encode(['error' => 'Região não encontrada']);
                exit;
            }

            echo json_encode($region, JSON_UNESCAPED_UNICODE);

        } else {
            // Get all regions
            $stmt = $pdo->query("
                SELECT
                    r.*,
                    COUNT(DISTINCT rec.id) as total_receitas
                FROM regioes r
                LEFT JOIN receitas rec ON rec.regiao_id = r.id
                WHERE r.ativo = 1
                GROUP BY r.id
                ORDER BY r.ordem
            ");
            $regions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($regions, JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar dados', 'message' => $e->getMessage()]);
}
