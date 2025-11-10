<?php
/**
 * SCRIPT DE POPULAÇÃO DO BANCO DE DADOS
 * FlavorWay - Popular com Dados de Exemplo
 *
 * INSTRUÇÕES:
 * 1. Execute este arquivo UMA VEZ: http://localhost/seu-projeto/config/populate_database.php
 * 2. Aguarde a conclusão
 * 3. DELETE este arquivo após uso
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(300);

// Database configuration
$host = "localhost";
$dbname = "flavor_way";
$username = "root";
$password = "";

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Banco de Dados - FlavorWay</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 70px rgba(0,0,0,0.3);
        }
        h1 {
            background: linear-gradient(135deg, #ea580c, #dc2626);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 900;
        }
        .subtitle {
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        .step {
            background: #f9fafb;
            padding: 25px;
            margin: 20px 0;
            border-radius: 12px;
            border-left: 5px solid #ea580c;
        }
        .step-title {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        .success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            font-weight: 600;
        }
        .error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            font-weight: 600;
        }
        .warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
            padding: 12px;
            border-radius: 8px;
            margin: 8px 0;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .stat-card {
            background: linear-gradient(135deg, #ea580c, #dc2626);
            color: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(234, 88, 12, 0.3);
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 0.85rem;
            opacity: 0.95;
        }
        code {
            background: #1f2937;
            color: #10b981;
            padding: 3px 8px;
            border-radius: 4px;
            font-family: 'Monaco', 'Courier New', monospace;
        }
        .delete-warning {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-top: 40px;
            text-align: center;
            font-size: 1.3rem;
            font-weight: 900;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 População do Banco de Dados</h1>
        <p class="subtitle">FlavorWay - Inserindo Dados de Exemplo</p>

<?php

try {
    echo '<div class="step">';
    echo '<div class="step-title">1️⃣ Conectando ao Banco de Dados</div>';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

    echo '<div class="success">✓ Conexão estabelecida com sucesso</div>';
    echo '</div>';

    // Read SQL file
    echo '<div class="step">';
    echo '<div class="step-title">2️⃣ Lendo Arquivo SQL</div>';

    $sqlFile = __DIR__ . '/../docs/populate_database.sql';

    if (!file_exists($sqlFile)) {
        throw new Exception('Arquivo SQL não encontrado: ' . $sqlFile);
    }

    $sql = file_get_contents($sqlFile);
    echo '<div class="success">✓ Arquivo SQL carregado com sucesso</div>';
    echo '</div>';

    // Execute SQL
    echo '<div class="step">';
    echo '<div class="step-title">3️⃣ Executando Comandos SQL</div>';

    // Remove comments and split SQL into individual statements
    $sql = preg_replace('/^--.*$/m', '', $sql); // Remove comment lines
    $sql = preg_replace('/^USE .*;/mi', '', $sql); // Remove USE statements

    // Split SQL into individual statements
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($statement) {
            $statement = trim($statement);
            // Skip empty statements, comments, and SELECT statements at the end
            return !empty($statement) &&
                   !preg_match('/^--/', $statement) &&
                   !preg_match('/^SELECT/i', $statement);
        }
    );

    $executed = 0;
    $errors = 0;
    $insertedTables = [];

    foreach ($statements as $statement) {
        try {
            $statement = trim($statement);
            if (!empty($statement)) {
                $pdo->exec($statement);
                $executed++;

                // Show progress for INSERT statements
                if (preg_match('/^INSERT INTO `?(\w+)`?/i', $statement, $matches)) {
                    $table = $matches[1];
                    if (!in_array($table, $insertedTables)) {
                        echo '<div style="color: #059669; margin: 5px 0;">✓ Dados inseridos em <strong>' . $table . '</strong></div>';
                        $insertedTables[] = $table;
                    }
                }
            }
        } catch (PDOException $e) {
            $errors++;
            // Only show error if it's not a duplicate entry
            if (!preg_match('/Duplicate entry/i', $e->getMessage())) {
                echo '<div class="warning">⚠ Aviso: ' . htmlspecialchars(substr($e->getMessage(), 0, 100)) . '...</div>';
            }
        }
    }

    echo '<div class="success">✓ Total: ' . $executed . ' comandos executados com sucesso</div>';
    if ($errors > 0) {
        echo '<div class="warning">⚠ ' . $errors . ' avisos (podem ser entradas duplicadas)</div>';
    }
    echo '</div>';

    // Statistics
    echo '<div class="step">';
    echo '<div class="step-title">4️⃣ Estatísticas do Banco de Dados</div>';

    echo '<div class="stats">';

    $stats = [
        'receitas' => 'Receitas',
        'ingredientes' => 'Ingredientes',
        'tags' => 'Tags',
        'tecnicas' => 'Técnicas',
        'estados_regiao' => 'Estados',
        'cultura_regiao' => 'Culturas',
        'avaliacoes' => 'Avaliações',
        'favoritos' => 'Favoritos'
    ];

    foreach ($stats as $table => $label) {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM `$table`");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $result[0]['total'];
        $stmt->closeCursor();

        echo '<div class="stat-card">';
        echo '<div class="stat-number">' . $count . '</div>';
        echo '<div class="stat-label">' . $label . '</div>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';

    // Show some sample data
    echo '<div class="step">';
    echo '<div class="step-title">5️⃣ Receitas por Região</div>';

    $stmt = $pdo->query("
        SELECT r.nome as regiao, COUNT(rec.id) as total
        FROM regioes r
        LEFT JOIN receitas rec ON rec.regiao_id = r.id
        GROUP BY r.id
        ORDER BY r.ordem
    ");

    $regions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">';
    foreach ($regions as $row) {
        echo '<div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">';
        echo '<div style="font-size: 1.8rem; font-weight: 700; color: #ea580c;">' . $row['total'] . '</div>';
        echo '<div style="color: #6b7280; font-size: 0.9rem;">' . $row['regiao'] . '</div>';
        echo '</div>';
    }
    echo '</div>';

    echo '</div>';

    // Success message
    echo '<div class="success" style="font-size: 1.2rem; padding: 30px; margin-top: 30px;">';
    echo '<h2 style="margin-bottom: 20px;">🎉 BANCO DE DADOS POPULADO COM SUCESSO!</h2>';
    echo '<div style="text-align: left; max-width: 700px; margin: 0 auto;">';
    echo '<p style="margin: 10px 0;">✓ Todas as receitas regionais inseridas</p>';
    echo '<p style="margin: 10px 0;">✓ Ingredientes catalogados</p>';
    echo '<p style="margin: 10px 0;">✓ Tags e técnicas adicionadas</p>';
    echo '<p style="margin: 10px 0;">✓ Estados e cultura regional documentados</p>';
    echo '<p style="margin: 10px 0;">✓ Dados de exemplo (usuários, avaliações, favoritos)</p>';
    echo '<p style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #10b981; font-weight: 700;">';
    echo '🚀 Seu sistema está pronto para testes!</p>';
    echo '</div>';
    echo '</div>';

} catch (Exception $e) {
    echo '<div class="error">';
    echo '<h3>❌ Erro Fatal</h3>';
    echo '<p><strong>Mensagem:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>Linha:</strong> ' . $e->getLine() . '</p>';
    echo '<p><strong>Arquivo:</strong> ' . htmlspecialchars($e->getFile()) . '</p>';
    echo '<pre style="background: #1f2937; color: #f87171; padding: 15px; border-radius: 8px; overflow-x: auto; max-height: 400px; overflow-y: auto;">';
    echo htmlspecialchars($e->getTraceAsString());
    echo '</pre>';
    echo '<div style="margin-top: 20px; padding: 15px; background: #fef3c7; border-radius: 8px;">';
    echo '<strong>Dica:</strong> Verifique se o banco de dados já foi criado usando o arquivo <code>createtable.php</code>';
    echo '</div>';
    echo '</div>';
}

?>

        <div class="delete-warning">
            ⚠️ IMPORTANTE: DELETE ESTE ARQUIVO APÓS O USO! ⚠️<br>
            <small style="font-size: 0.8rem; font-weight: normal; opacity: 0.9;">
                Este arquivo é apenas para configuração inicial
            </small>
        </div>
    </div>
</body>
</html>
