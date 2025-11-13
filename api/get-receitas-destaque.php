<?php
/**
 * API para buscar receitas em destaque
 * Retorna as receitas marcadas como destaque, ordenadas por data de criação
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../config/database.php';

try {
    // Primeiro, verifica se a coluna 'imagem' existe
    $stmt = $pdo->query("SHOW COLUMNS FROM receitas LIKE 'imagem'");
    $has_imagem = $stmt->rowCount() > 0;

    // Constrói a query dinamicamente baseado nos campos disponíveis
    $imagem_field = $has_imagem ? 'r.imagem,' : '';

    // Busca receitas marcadas como destaque
    $sql = "SELECT
                r.id,
                r.nome,
                r.descricao,
                $imagem_field
                r.tempo_preparo as tempo,
                r.dificuldade,
                r.rating,
                reg.nome as culinaria,
                r.created_at
            FROM receitas r
            LEFT JOIN regioes reg ON r.regiao_id = reg.id
            WHERE r.destaque = 1
            ORDER BY r.created_at DESC
            LIMIT 12";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Se não houver receitas em destaque, retorna receitas aleatórias
    if (empty($receitas)) {
        $sql = "SELECT
                    r.id,
                    r.nome,
                    r.descricao,
                    $imagem_field
                    r.tempo_preparo as tempo,
                    r.dificuldade,
                    r.rating,
                    reg.nome as culinaria,
                    r.created_at
                FROM receitas r
                LEFT JOIN regioes reg ON r.regiao_id = reg.id
                ORDER BY r.created_at DESC
                LIMIT 12";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Formata os dados para o frontend
    $receitas_formatadas = array_map(function($receita) use ($has_imagem) {
        return [
            'id' => (int)$receita['id'],
            'nome' => $receita['nome'],
            'descricao' => $receita['descricao'] ?? '',
            'culinaria' => $receita['culinaria'] ?? 'Brasileira',
            'tempo' => $receita['tempo'] ?? '30 min',
            'dificuldade' => $receita['dificuldade'] ?? 'Intermediário',
            'rating' => (float)($receita['rating'] ?? 4.5),
            'image' => ($has_imagem && isset($receita['imagem']))
                ? $receita['imagem']
                : '/placeholder.svg?height=180&width=280&text=' . urlencode($receita['nome'])
        ];
    }, $receitas);

    echo json_encode([
        'success' => true,
        'receitas' => $receitas_formatadas,
        'total' => count($receitas_formatadas),
        'debug' => [
            'has_imagem_field' => $has_imagem,
            'total_db' => count($receitas)
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao buscar receitas: ' . $e->getMessage(),
        'receitas' => [],
        'debug' => [
            'error' => $e->getMessage(),
            'code' => $e->getCode()
        ]
    ], JSON_UNESCAPED_UNICODE);
}
