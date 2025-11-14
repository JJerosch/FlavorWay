<?php
/**
 * API para adicionar ingredientes à lista de compras
 */

session_start();
header('Content-Type: application/json');

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Você precisa estar logado para usar a lista de compras'
    ]);
    exit;
}

require_once '../config/database.php';

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'error' => 'Método não permitido'
    ]);
    exit;
}

// Obtém dados JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || !isset($data['ingredientes']) || !is_array($data['ingredientes'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Dados inválidos'
    ]);
    exit;
}

$usuario_id = $_SESSION['user_id'];
$receita_id = $data['receita_id'] ?? null;
$ingredientes = $data['ingredientes'];

if (empty($ingredientes)) {
    echo json_encode([
        'success' => false,
        'error' => 'Nenhum ingrediente para adicionar'
    ]);
    exit;
}

try {
    $pdo->beginTransaction();

    $total_adicionados = 0;

    $stmt = $pdo->prepare("
        INSERT INTO lista_compras (usuario_id, receita_id, item, comprado, created_at)
        VALUES (?, ?, ?, 0, NOW())
    ");

    foreach ($ingredientes as $ingrediente) {
        $item = is_array($ingrediente) ? $ingrediente['nome'] : $ingrediente;
        $item = trim($item);

        if (!empty($item)) {
            // Verifica se o item já existe na lista do usuário (não comprado)
            $check = $pdo->prepare("
                SELECT id FROM lista_compras
                WHERE usuario_id = ? AND item = ? AND comprado = 0
            ");
            $check->execute([$usuario_id, $item]);

            // Só adiciona se não existir
            if (!$check->fetch()) {
                $stmt->execute([$usuario_id, $receita_id, $item]);
                $total_adicionados++;
            }
        }
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'total' => $total_adicionados,
        'message' => "$total_adicionados ingredientes adicionados à lista de compras"
    ]);

} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao adicionar à lista: ' . $e->getMessage()
    ]);
}
