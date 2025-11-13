<?php
/**
 * API para salvar receitas no banco de dados
 * Permite que usuários autenticados adicionem receitas
 */

session_start();
header('Content-Type: application/json');

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Você precisa estar logado para adicionar receitas'
    ]);
    exit;
}

require_once '../config/database.php';

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido'
    ]);
    exit;
}

// Validação dos campos obrigatórios
$required_fields = ['nome', 'descricao', 'imagem', 'ingredientes', 'modo_preparo', 'tempo_preparo', 'pessoas', 'dificuldade', 'regiao_id'];
$errors = [];

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $errors[] = "O campo '$field' é obrigatório";
    }
}

if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'message' => implode(', ', $errors)
    ]);
    exit;
}

// Sanitiza e prepara os dados
$nome = trim($_POST['nome']);
$descricao = trim($_POST['descricao']);
$imagem = trim($_POST['imagem']);
$ingredientes = trim($_POST['ingredientes']);
$modo_preparo = trim($_POST['modo_preparo']);
$tempo_preparo = trim($_POST['tempo_preparo']);
$pessoas = trim($_POST['pessoas']);
$dificuldade = trim($_POST['dificuldade']);
$regiao_id = intval($_POST['regiao_id']);
$destaque = isset($_POST['destaque']) && $_POST['destaque'] == '1' ? 1 : 0;
$usuario_id = $_SESSION['user_id'];

// Validações adicionais
if (!filter_var($imagem, FILTER_VALIDATE_URL)) {
    echo json_encode([
        'success' => false,
        'message' => 'URL da imagem inválida'
    ]);
    exit;
}

if (!in_array($dificuldade, ['Básico', 'Intermediário', 'Avançado'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Dificuldade inválida'
    ]);
    exit;
}

// Busca o nome da região para preencher o campo 'regiao' (enum legado)
try {
    $stmt = $pdo->prepare("SELECT nome FROM regioes WHERE id = ?");
    $stmt->execute([$regiao_id]);
    $regiao = $stmt->fetch();

    if (!$regiao) {
        echo json_encode([
            'success' => false,
            'message' => 'Região inválida'
        ]);
        exit;
    }

    $regiao_nome = $regiao['nome'];
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao validar região: ' . $e->getMessage()
    ]);
    exit;
}

// Insere a receita no banco de dados
try {
    $sql = "INSERT INTO receitas (
        nome, descricao, imagem, ingredientes, modo_preparo,
        tempo_preparo, pessoas, rating, dificuldade, regiao,
        regiao_id, destaque, usuario_id, created_at
    ) VALUES (
        :nome, :descricao, :imagem, :ingredientes, :modo_preparo,
        :tempo_preparo, :pessoas, 4.5, :dificuldade, :regiao,
        :regiao_id, :destaque, :usuario_id, NOW()
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':imagem' => $imagem,
        ':ingredientes' => $ingredientes,
        ':modo_preparo' => $modo_preparo,
        ':tempo_preparo' => $tempo_preparo,
        ':pessoas' => $pessoas,
        ':dificuldade' => $dificuldade,
        ':regiao' => $regiao_nome,
        ':regiao_id' => $regiao_id,
        ':destaque' => $destaque,
        ':usuario_id' => $usuario_id
    ]);

    $receita_id = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Receita adicionada com sucesso!',
        'receita_id' => $receita_id
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao salvar receita: ' . $e->getMessage()
    ]);
}
