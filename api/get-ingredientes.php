<?php
/**
 * API para buscar todos os ingredientes únicos
 */

session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

try {
    // Busca ingredientes únicos com contagem de receitas
    $sql = "SELECT DISTINCT
                i.nome,
                i.categoria,
                COUNT(DISTINCT i.receita_id) as total_receitas
            FROM ingredientes i
            GROUP BY i.nome, i.categoria
            ORDER BY i.categoria, i.nome";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $ingredientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'ingredientes' => $ingredientes,
        'total' => count($ingredientes)
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao buscar ingredientes: ' . $e->getMessage(),
        'ingredientes' => []
    ]);
}
