<?php
/**
 * API de Busca Global
 * Busca em receitas, ingredientes e técnicas
 */

session_start();
header('Content-Type: application/json');

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Você precisa estar logado para buscar'
    ]);
    exit;
}

require_once '../config/database.php';

// Obtém o termo de busca
$query = $_GET['q'] ?? '';
$query = trim($query);

if (empty($query) || strlen($query) < 2) {
    echo json_encode([
        'success' => false,
        'error' => 'Digite pelo menos 2 caracteres para buscar'
    ]);
    exit;
}

try {
    $search_term = "%{$query}%";
    $results = [
        'receitas' => [],
        'ingredientes' => [],
        'tecnicas' => []
    ];

    // Verifica se campo imagem existe
    $stmt = $pdo->query("SHOW COLUMNS FROM receitas LIKE 'imagem'");
    $has_imagem = $stmt->rowCount() > 0;
    $imagem_field = $has_imagem ? 'r.imagem,' : '';

    // Busca em RECEITAS
    $sql = "SELECT
                r.id,
                r.nome,
                r.descricao,
                $imagem_field
                r.tempo_preparo,
                r.dificuldade,
                r.rating,
                r.destaque,
                reg.nome as regiao_nome
            FROM receitas r
            LEFT JOIN regioes reg ON r.regiao_id = reg.id
            WHERE r.nome LIKE ? OR r.descricao LIKE ?
            ORDER BY r.destaque DESC, r.rating DESC
            LIMIT 10";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$search_term, $search_term]);
    $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($receitas as $receita) {
        $results['receitas'][] = [
            'id' => (int)$receita['id'],
            'nome' => $receita['nome'],
            'descricao' => substr($receita['descricao'], 0, 150) . '...',
            'imagem' => $receita['imagem'] ?? '/placeholder.svg?height=100&width=100&text=' . urlencode($receita['nome']),
            'tempo_preparo' => $receita['tempo_preparo'],
            'dificuldade' => $receita['dificuldade'],
            'rating' => (float)$receita['rating'],
            'regiao' => $receita['regiao_nome'],
            'destaque' => (bool)$receita['destaque'],
            'tipo' => 'receita'
        ];
    }

    // Busca em INGREDIENTES (únicos da tabela ingredientes)
    $sql = "SELECT DISTINCT
                i.nome,
                i.categoria,
                COUNT(DISTINCT i.receita_id) as total_receitas
            FROM ingredientes i
            WHERE i.nome LIKE ?
            GROUP BY i.nome, i.categoria
            ORDER BY total_receitas DESC
            LIMIT 10";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$search_term]);
    $ingredientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($ingredientes as $ing) {
        $results['ingredientes'][] = [
            'nome' => $ing['nome'],
            'categoria' => $ing['categoria'],
            'total_receitas' => (int)$ing['total_receitas'],
            'tipo' => 'ingrediente'
        ];
    }

    // Busca em TÉCNICAS
    $sql = "SELECT
                t.id,
                t.nome,
                t.descricao,
                t.dificuldades_tecnica as dificuldade
            FROM tecnicas t
            WHERE t.nome LIKE ? OR t.descricao LIKE ?
            ORDER BY t.nome ASC
            LIMIT 10";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$search_term, $search_term]);
    $tecnicas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tecnicas as $tec) {
        $results['tecnicas'][] = [
            'id' => (int)$tec['id'],
            'nome' => $tec['nome'],
            'descricao' => substr($tec['descricao'], 0, 150) . '...',
            'dificuldade' => $tec['dificuldade'],
            'tipo' => 'tecnica'
        ];
    }

    // Conta totais
    $total = count($results['receitas']) + count($results['ingredientes']) + count($results['tecnicas']);

    echo json_encode([
        'success' => true,
        'query' => $query,
        'results' => $results,
        'total' => $total,
        'totais' => [
            'receitas' => count($results['receitas']),
            'ingredientes' => count($results['ingredientes']),
            'tecnicas' => count($results['tecnicas'])
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao buscar: ' . $e->getMessage()
    ]);
}
