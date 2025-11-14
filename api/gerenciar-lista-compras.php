<?php
/**
 * API para gerenciar lista de compras
 * Operações: marcar como comprado, remover item, limpar lista
 */

session_start();
header('Content-Type: application/json');

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Você precisa estar logado'
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

if (!$data || !isset($data['acao'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Ação não especificada'
    ]);
    exit;
}

$usuario_id = $_SESSION['user_id'];
$acao = $data['acao'];

try {
    switch ($acao) {
        case 'toggle_comprado':
            if (!isset($data['item_id'])) {
                throw new Exception('ID do item não fornecido');
            }

            $item_id = (int)$data['item_id'];

            // Verifica se o item pertence ao usuário
            $stmt = $pdo->prepare("SELECT comprado FROM lista_compras WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$item_id, $usuario_id]);
            $item = $stmt->fetch();

            if (!$item) {
                throw new Exception('Item não encontrado');
            }

            $novo_estado = $item['comprado'] ? 0 : 1;

            $stmt = $pdo->prepare("UPDATE lista_compras SET comprado = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$novo_estado, $item_id]);

            echo json_encode([
                'success' => true,
                'comprado' => (bool)$novo_estado
            ]);
            break;

        case 'remover':
            if (!isset($data['item_id'])) {
                throw new Exception('ID do item não fornecido');
            }

            $item_id = (int)$data['item_id'];

            $stmt = $pdo->prepare("DELETE FROM lista_compras WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$item_id, $usuario_id]);

            echo json_encode([
                'success' => true,
                'message' => 'Item removido da lista'
            ]);
            break;

        case 'limpar_comprados':
            $stmt = $pdo->prepare("DELETE FROM lista_compras WHERE usuario_id = ? AND comprado = 1");
            $stmt->execute([$usuario_id]);
            $total = $stmt->rowCount();

            echo json_encode([
                'success' => true,
                'total' => $total,
                'message' => "$total itens removidos"
            ]);
            break;

        case 'limpar_tudo':
            $stmt = $pdo->prepare("DELETE FROM lista_compras WHERE usuario_id = ?");
            $stmt->execute([$usuario_id]);
            $total = $stmt->rowCount();

            echo json_encode([
                'success' => true,
                'total' => $total,
                'message' => 'Lista limpa'
            ]);
            break;

        default:
            throw new Exception('Ação inválida');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
