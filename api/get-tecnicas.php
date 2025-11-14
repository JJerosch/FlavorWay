<?php
/**
 * API para buscar todas as técnicas
 */

session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

try {
    $sql = "SELECT
                id,
                nome,
                descricao,
                dificuldades_tecnica as dificuldade
            FROM tecnicas
            ORDER BY dificuldades_tecnica ASC, nome ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $tecnicas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'tecnicas' => $tecnicas,
        'total' => count($tecnicas)
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao buscar técnicas: ' . $e->getMessage(),
        'tecnicas' => []
    ]);
}
