<?php
/**
 * Arquivo de teste para verificar a API e o banco de dados
 */

echo "<h1>FlavorWay - Teste de API</h1>";
echo "<hr>";

// Teste 1: Conexão com banco de dados
echo "<h2>1. Teste de Conexão com Banco de Dados</h2>";
try {
    require_once '../config/database.php';
    echo "✅ Conexão com banco de dados estabelecida com sucesso!<br>";
} catch (Exception $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . "<br>";
    exit;
}

// Teste 2: Verificar estrutura da tabela receitas
echo "<h2>2. Estrutura da Tabela 'receitas'</h2>";
try {
    $stmt = $pdo->query("DESCRIBE receitas");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Padrão</th></tr>";

    $has_imagem = false;
    $has_ingredientes = false;
    $has_modo_preparo = false;
    $has_usuario_id = false;

    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";

        if ($col['Field'] === 'imagem') $has_imagem = true;
        if ($col['Field'] === 'ingredientes') $has_ingredientes = true;
        if ($col['Field'] === 'modo_preparo') $has_modo_preparo = true;
        if ($col['Field'] === 'usuario_id') $has_usuario_id = true;
    }
    echo "</table><br>";

    echo "<h3>Verificação de Campos Novos:</h3>";
    echo $has_imagem ? "✅ Campo 'imagem' existe<br>" : "❌ Campo 'imagem' NÃO existe<br>";
    echo $has_ingredientes ? "✅ Campo 'ingredientes' existe<br>" : "❌ Campo 'ingredientes' NÃO existe<br>";
    echo $has_modo_preparo ? "✅ Campo 'modo_preparo' existe<br>" : "❌ Campo 'modo_preparo' NÃO existe<br>";
    echo $has_usuario_id ? "✅ Campo 'usuario_id' existe<br>" : "❌ Campo 'usuario_id' NÃO existe<br>";

    if (!$has_imagem || !$has_ingredientes || !$has_modo_preparo || !$has_usuario_id) {
        echo "<br><strong style='color:red;'>⚠️ ATENÇÃO: Execute o arquivo config/update-receitas-table.php para adicionar os campos faltantes!</strong><br>";
    }

} catch (Exception $e) {
    echo "❌ Erro ao verificar estrutura: " . $e->getMessage() . "<br>";
}

// Teste 3: Contar receitas
echo "<h2>3. Receitas no Banco de Dados</h2>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM receitas");
    $result = $stmt->fetch();
    $total_receitas = $result['total'];

    echo "📊 Total de receitas: <strong>$total_receitas</strong><br>";

    if ($total_receitas > 0) {
        // Contar receitas em destaque
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM receitas WHERE destaque = 1");
        $result = $stmt->fetch();
        $total_destaque = $result['total'];

        echo "⭐ Receitas em destaque: <strong>$total_destaque</strong><br>";

        // Mostrar algumas receitas
        echo "<h3>Últimas 5 receitas:</h3>";
        $stmt = $pdo->query("SELECT id, nome, dificuldade, destaque, created_at FROM receitas ORDER BY created_at DESC LIMIT 5");
        $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Dificuldade</th><th>Destaque</th><th>Criado em</th></tr>";
        foreach ($receitas as $receita) {
            echo "<tr>";
            echo "<td>" . $receita['id'] . "</td>";
            echo "<td>" . $receita['nome'] . "</td>";
            echo "<td>" . $receita['dificuldade'] . "</td>";
            echo "<td>" . ($receita['destaque'] ? '⭐ Sim' : 'Não') . "</td>";
            echo "<td>" . $receita['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "⚠️ Nenhuma receita encontrada no banco de dados<br>";
    }
} catch (Exception $e) {
    echo "❌ Erro ao buscar receitas: " . $e->getMessage() . "<br>";
}

// Teste 4: Testar API get-receitas-destaque.php
echo "<h2>4. Teste da API get-receitas-destaque.php</h2>";
try {
    $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/get-receitas-destaque.php';
    echo "URL da API: <a href='$url' target='_blank'>$url</a><br><br>";

    // Simular chamada da API
    $sql = "SELECT
                r.id,
                r.nome,
                r.descricao,
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

    echo "📋 Receitas que a API retornaria: <strong>" . count($receitas) . "</strong><br>";

    if (count($receitas) > 0) {
        echo "✅ API deve funcionar corretamente!<br>";
        echo "<pre>" . json_encode($receitas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
    } else {
        echo "⚠️ Nenhuma receita em destaque. A API retornará todas as receitas.<br>";

        // Buscar todas as receitas
        $sql = "SELECT
                    r.id,
                    r.nome,
                    r.descricao,
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

        echo "📋 Total de receitas encontradas: <strong>" . count($receitas) . "</strong><br>";
    }
} catch (Exception $e) {
    echo "❌ Erro ao testar API: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h2>Próximos Passos:</h2>";
echo "<ol>";
echo "<li>Se campos estão faltando: Execute <code>php config/update-receitas-table.php</code></li>";
echo "<li>Se não há receitas: Adicione receitas em <a href='../public/adicionar-receita.php'>adicionar-receita.php</a></li>";
echo "<li>Verifique o console do navegador (F12) ao acessar <a href='../public/index.php'>index.php</a></li>";
echo "</ol>";
?>
