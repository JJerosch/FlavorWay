<?php
/**
 * API - Buscar dados completos de uma receita
 * Retorna informações detalhadas incluindo ingredientes, avaliações e favoritos
 */

session_start();
header('Content-Type: application/json; charset=utf-8');
require_once '../config/database.php';

try {
    // Obtém o ID da receita via GET
    $receita_id = $_GET['id'] ?? null;

    if (!$receita_id || !is_numeric($receita_id)) {
        throw new Exception('ID da receita inválido');
    }

    // Verifica se campo imagem existe
    $stmt = $pdo->query("SHOW COLUMNS FROM receitas LIKE 'imagem'");
    $has_imagem = $stmt->rowCount() > 0;

    $stmt = $pdo->query("SHOW COLUMNS FROM receitas LIKE 'ingredientes'");
    $has_ingredientes_text = $stmt->rowCount() > 0;

    $stmt = $pdo->query("SHOW COLUMNS FROM receitas LIKE 'modo_preparo'");
    $has_modo_preparo = $stmt->rowCount() > 0;

    // Constrói query dinamicamente
    $imagem_field = $has_imagem ? 'r.imagem,' : '';
    $ingredientes_field = $has_ingredientes_text ? 'r.ingredientes as ingredientes_text,' : '';
    $modo_preparo_field = $has_modo_preparo ? 'r.modo_preparo,' : '';

    // Busca dados da receita
    $stmt = $pdo->prepare("
        SELECT
            r.id,
            r.nome,
            r.descricao,
            $imagem_field
            $ingredientes_field
            $modo_preparo_field
            r.tempo_preparo,
            r.pessoas,
            r.rating,
            r.dificuldade,
            r.regiao,
            r.regiao_id,
            r.badge,
            r.tempo_cozimento,
            r.rendimento,
            r.calorias,
            r.proteinas,
            r.carboidratos,
            r.gorduras,
            r.destaque,
            reg.nome as regiao_nome,
            reg.slug as regiao_slug
        FROM receitas r
        LEFT JOIN regioes reg ON r.regiao_id = reg.id
        WHERE r.id = ?
        LIMIT 1
    ");
    $stmt->execute([$receita_id]);
    $receita = $stmt->fetch();

    if (!$receita) {
        throw new Exception('Receita não encontrada');
    }

    // Busca ingredientes da tabela ingredientes
    $stmt = $pdo->prepare("
        SELECT nome, categoria
        FROM ingredientes
        WHERE receita_id = ?
        ORDER BY categoria, nome
    ");
    $stmt->execute([$receita_id]);
    $ingredientes_tabela = $stmt->fetchAll();

    // Se não houver ingredientes na tabela, usa o campo TEXT
    if (empty($ingredientes_tabela) && isset($receita['ingredientes_text']) && !empty($receita['ingredientes_text'])) {
        // Converte texto em array de ingredientes
        $linhas = explode("\n", $receita['ingredientes_text']);
        $ingredientes_array = [];
        foreach ($linhas as $linha) {
            $linha = trim($linha);
            if (!empty($linha)) {
                $ingredientes_array[] = [
                    'nome' => $linha,
                    'categoria' => 'Ingredientes'
                ];
            }
        }
        $receita['ingredientes'] = $ingredientes_array;
    } else {
        $receita['ingredientes'] = $ingredientes_tabela;
    }

    // Busca tags
    $stmt = $pdo->prepare("
        SELECT t.id, t.nome
        FROM tags t
        INNER JOIN receita_tags rt ON t.id = rt.tag_id
        WHERE rt.receita_id = ?
    ");
    $stmt->execute([$receita_id]);
    $receita['tags'] = $stmt->fetchAll();

    // Busca avaliações
    $stmt = $pdo->prepare("
        SELECT
            a.id,
            a.nota,
            a.comentario,
            a.data_criacao,
            u.nome as usuario_nome,
            u.avatar
        FROM avaliacoes a
        INNER JOIN usuarios u ON a.usuario_id = u.id
        WHERE a.receita_id = ?
        ORDER BY a.data_criacao DESC
    ");
    $stmt->execute([$receita_id]);
    $avaliacoes = $stmt->fetchAll();
    $receita['avaliacoes'] = $avaliacoes;

    // Calcula estatísticas de avaliações
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
        WHERE receita_id = ?
    ");
    $stmt->execute([$receita_id]);
    $estatisticas_avaliacoes = $stmt->fetch();
    $receita['estatisticas_avaliacoes'] = $estatisticas_avaliacoes;

    // Verifica se usuário logado já avaliou
    $receita['usuario_avaliacao'] = null;
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("
            SELECT id, nota, comentario
            FROM avaliacoes
            WHERE receita_id = ? AND usuario_id = ?
        ");
        $stmt->execute([$receita_id, $_SESSION['user_id']]);
        $receita['usuario_avaliacao'] = $stmt->fetch();

        // Verifica se está nos favoritos do usuário
        $stmt = $pdo->prepare("
            SELECT e.id
            FROM estudantes e
            WHERE e.usuario_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $estudante = $stmt->fetch();

        if ($estudante) {
            $stmt = $pdo->prepare("
                SELECT id
                FROM favoritos
                WHERE estudante_id = ? AND receita_id = ?
            ");
            $stmt->execute([$estudante['id'], $receita_id]);
            $receita['is_favorito'] = (bool)$stmt->fetch();
        } else {
            $receita['is_favorito'] = false;
        }
    } else {
        $receita['is_favorito'] = false;
    }

    // Conta total de favoritos
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total
        FROM favoritos
        WHERE receita_id = ?
    ");
    $stmt->execute([$receita_id]);
    $receita['total_favoritos'] = (int)$stmt->fetch()['total'];

    // Converte tipos
    $receita['id'] = (int)$receita['id'];
    $receita['rating'] = (float)$receita['rating'];
    $receita['destaque'] = (bool)$receita['destaque'];
    $receita['regiao_id'] = $receita['regiao_id'] ? (int)$receita['regiao_id'] : null;

    // Retorna resposta
    echo json_encode([
        'success' => true,
        'receita' => $receita
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
