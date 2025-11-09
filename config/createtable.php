<?php
/**
 * SCRIPT DE CRIAÇÃO COMPLETA DO BANCO DE DADOS
 * FlavorWay - Estrutura Final
 * 
 * INSTRUÇÕES:
 * 1. Execute este arquivo UMA VEZ: http://localhost/seu-projeto/config/createtable.php
 * 2. Aguarde a conclusão
 * 3. DELETE este arquivo após uso
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(300);

// Configurações
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
    <title>Criar Banco de Dados - FlavorWay</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1400px;
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
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.95;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background: linear-gradient(135deg, #374151, #1f2937);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        td {
            border-bottom: 1px solid #e5e7eb;
        }
        tr:hover td {
            background: #f9fafb;
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
        <h1>🚀 Criação do Banco de Dados</h1>
        <p class="subtitle">FlavorWay - Instalação Completa do Zero</p>

<?php

try {
    echo '<div class="step">';
    echo '<div class="step-title">1️⃣ Conectando ao MySQL</div>';
    
    // Conecta SEM especificar o banco
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo '<div class="success">✓ Conexão estabelecida com sucesso</div>';
    echo '</div>';
    
    // ========== PASSO 2: Criar banco ==========
    echo '<div class="step">';
    echo '<div class="step-title">2️⃣ Criando banco de dados</div>';
    
    $pdo->exec("DROP DATABASE IF EXISTS `$dbname`");
    echo '<div class="warning">⚠ Banco anterior removido (se existia)</div>';
    
    $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo '<div class="success">✓ Banco de dados <code>flavor_way</code> criado</div>';
    
    $pdo->exec("USE `$dbname`");
    echo '<div class="success">✓ Banco selecionado</div>';
    echo '</div>';
    
    // ========== PASSO 3: Criar tabelas ==========
    echo '<div class="step">';
    echo '<div class="step-title">3️⃣ Criando estrutura de tabelas</div>';
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    $tabelas = [
        'tipo_usuario' => "
            CREATE TABLE `tipo_usuario` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `nome` VARCHAR(50) NOT NULL UNIQUE,
                `descricao` VARCHAR(255),
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'usuarios' => "
            CREATE TABLE `usuarios` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `username` VARCHAR(50) NOT NULL UNIQUE,
                `nome` VARCHAR(100) NOT NULL,
                `email` VARCHAR(100) NOT NULL UNIQUE,
                `senha` VARCHAR(255) NOT NULL,
                `avatar` VARCHAR(255) DEFAULT NULL,
                `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `data_atualizacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `ultimo_acesso` TIMESTAMP NULL DEFAULT NULL,
                `ativo` TINYINT(1) DEFAULT 1,
                INDEX idx_email (`email`),
                INDEX idx_username (`username`),
                INDEX idx_ativo (`ativo`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'usuarios_tipo_usuario' => "
            CREATE TABLE `usuarios_tipo_usuario` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `usuario_id` INT NOT NULL,
                `tipo_usuario_id` INT NOT NULL,
                `data_atribuicao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY `unique_usuario_tipo` (`usuario_id`, `tipo_usuario_id`),
                INDEX idx_usuario (`usuario_id`),
                INDEX idx_tipo (`tipo_usuario_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'administradores' => "
            CREATE TABLE `administradores` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `usuario_id` INT NOT NULL UNIQUE,
                `nivel` ENUM('super_admin', 'admin', 'moderador') DEFAULT 'admin',
                INDEX idx_nivel (`nivel`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'estudantes' => "
            CREATE TABLE `estudantes` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `usuario_id` INT NOT NULL UNIQUE,
                `progresso` INT DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'regioes' => "
            CREATE TABLE `regioes` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `nome` VARCHAR(100) NOT NULL,
                `slug` VARCHAR(100) NOT NULL UNIQUE,
                `descricao` TEXT,
                `ordem` INT DEFAULT 0,
                `ativo` BOOLEAN DEFAULT TRUE,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_slug (`slug`),
                INDEX idx_ativo (`ativo`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'receitas' => "
            CREATE TABLE `receitas` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `nome` VARCHAR(255) NOT NULL,
                `descricao` TEXT NOT NULL,
                `tempo_preparo` VARCHAR(50) NOT NULL,
                `pessoas` VARCHAR(60) NOT NULL,
                `rating` DECIMAL(2,1) DEFAULT 4.5,
                `dificuldade` ENUM('Básico', 'Intermediário', 'Avançado') NOT NULL,
                `regiao` ENUM('Nordeste', 'Sudeste', 'Sul', 'Centro-Oeste', 'Norte'),
                `regiao_id` INT DEFAULT NULL,
                `destaque` TINYINT(1) DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `badge` VARCHAR(50) DEFAULT NULL,
                `tempo_cozimento` VARCHAR(50) DEFAULT NULL,
                `rendimento` VARCHAR(50) DEFAULT NULL,
                `calorias` VARCHAR(50) DEFAULT NULL,
                `proteinas` VARCHAR(50) DEFAULT NULL,
                `carboidratos` VARCHAR(50) DEFAULT NULL,
                `gorduras` VARCHAR(50) DEFAULT NULL,
                INDEX idx_regiao_id (`regiao_id`),
                INDEX idx_dificuldade (`dificuldade`),
                INDEX idx_destaque (`destaque`),
                INDEX idx_rating (`rating`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'ingredientes' => "
            CREATE TABLE `ingredientes` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `receita_id` INT NOT NULL,
                `nome` VARCHAR(255) NOT NULL,
                `categoria` ENUM('Laticínios', 'Carnes', 'Vegetais', 'Grãos', 'Temperos', 'Outros') NOT NULL,
                INDEX idx_receita (`receita_id`),
                INDEX idx_categoria (`categoria`),
                INDEX idx_nome (`nome`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'tags' => "
            CREATE TABLE `tags` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `nome` VARCHAR(100) NOT NULL UNIQUE,
                INDEX idx_nome (`nome`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'receita_tags' => "
            CREATE TABLE `receita_tags` (
                `receita_id` INT NOT NULL,
                `tag_id` INT NOT NULL,
                PRIMARY KEY (`receita_id`, `tag_id`),
                INDEX idx_tag (`tag_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'tecnicas' => "
            CREATE TABLE `tecnicas` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `nome` VARCHAR(255) NOT NULL,
                `descricao` TEXT NOT NULL,
                `dificuldades_tecnica` ENUM('Básico', 'Intermediário', 'Avançado') NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'avaliacoes' => "
            CREATE TABLE `avaliacoes` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `usuario_id` INT NOT NULL,
                `receita_id` INT NOT NULL,
                `nota` INT NOT NULL CHECK (`nota` >= 1 AND `nota` <= 5),
                `comentario` TEXT,
                `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `data_atualizacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY `unique_avaliacao` (`usuario_id`, `receita_id`),
                INDEX idx_receita (`receita_id`),
                INDEX idx_nota (`nota`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'favoritos' => "
            CREATE TABLE `favoritos` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `estudante_id` INT NOT NULL,
                `receita_id` INT NOT NULL,
                `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY `unique_favorito` (`estudante_id`, `receita_id`),
                INDEX idx_receita (`receita_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'ingredientes_regiao' => "
            CREATE TABLE `ingredientes_regiao` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `ingrediente_id` INT NOT NULL,
                `regiao_id` INT NOT NULL,
                UNIQUE KEY `unique_ingrediente_regiao` (`ingrediente_id`, `regiao_id`),
                INDEX idx_ingrediente (`ingrediente_id`),
                INDEX idx_regiao (`regiao_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'tecnicas_regiao' => "
            CREATE TABLE `tecnicas_regiao` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `tecnica_id` INT NOT NULL,
                `regiao_id` INT NOT NULL,
                UNIQUE KEY `unique_tecnica_regiao` (`tecnica_id`, `regiao_id`),
                INDEX idx_tecnica (`tecnica_id`),
                INDEX idx_regiao (`regiao_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'estados_regiao' => "
            CREATE TABLE `estados_regiao` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `regiao_id` INT NOT NULL,
                `nome` VARCHAR(100) NOT NULL,
                `slug` VARCHAR(100) NOT NULL,
                `capital` VARCHAR(100),
                `descricao` TEXT,
                `ingrediente_destaque` VARCHAR(255),
                `especialidades` JSON,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_regiao (`regiao_id`),
                INDEX idx_slug (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'cultura_regiao' => "
            CREATE TABLE `cultura_regiao` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `regiao_id` INT NOT NULL,
                `titulo` VARCHAR(255) NOT NULL,
                `descricao` TEXT,
                `icon` VARCHAR(50),
                `tipo` ENUM('influencia', 'tradicao', 'historia') NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_regiao (`regiao_id`),
                INDEX idx_tipo (`tipo`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        "
    ];
    
    $tabelasCriadas = 0;
    foreach ($tabelas as $nome => $sql) {
        try {
            $pdo->exec($sql);
            $tabelasCriadas++;
            echo '<div style="color: #059669; margin: 5px 0;">✓ Tabela <strong>' . $nome . '</strong> criada</div>';
        } catch (PDOException $e) {
            echo '<div class="error">✗ Erro ao criar ' . $nome . ': ' . $e->getMessage() . '</div>';
        }
    }
    
    echo '<div class="success">✓ Total: ' . $tabelasCriadas . ' tabelas criadas</div>';
    echo '</div>';
    
    // ========== PASSO 4: Criar Foreign Keys ==========
    echo '<div class="step">';
    echo '<div class="step-title">4️⃣ Configurando Foreign Keys</div>';
    
    $foreignKeys = [
        "ALTER TABLE `usuarios_tipo_usuario` ADD CONSTRAINT `fk_usuarios_tipo_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `usuarios_tipo_usuario` ADD CONSTRAINT `fk_usuarios_tipo_tipo` FOREIGN KEY (`tipo_usuario_id`) REFERENCES `tipo_usuario`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `administradores` ADD CONSTRAINT `fk_administradores_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `estudantes` ADD CONSTRAINT `fk_estudantes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `receitas` ADD CONSTRAINT `fk_receitas_regiao` FOREIGN KEY (`regiao_id`) REFERENCES `regioes`(`id`) ON DELETE SET NULL",
        "ALTER TABLE `ingredientes` ADD CONSTRAINT `fk_ingredientes_receita` FOREIGN KEY (`receita_id`) REFERENCES `receitas`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `receita_tags` ADD CONSTRAINT `fk_receita_tags_receita` FOREIGN KEY (`receita_id`) REFERENCES `receitas`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `receita_tags` ADD CONSTRAINT `fk_receita_tags_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `avaliacoes` ADD CONSTRAINT `fk_avaliacoes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `avaliacoes` ADD CONSTRAINT `fk_avaliacoes_receita` FOREIGN KEY (`receita_id`) REFERENCES `receitas`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `favoritos` ADD CONSTRAINT `fk_favoritos_estudante` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `favoritos` ADD CONSTRAINT `fk_favoritos_receita` FOREIGN KEY (`receita_id`) REFERENCES `receitas`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `ingredientes_regiao` ADD CONSTRAINT `fk_ingredientes_regiao_ingrediente` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredientes`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `ingredientes_regiao` ADD CONSTRAINT `fk_ingredientes_regiao_regiao` FOREIGN KEY (`regiao_id`) REFERENCES `regioes`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `tecnicas_regiao` ADD CONSTRAINT `fk_tecnicas_regiao_tecnica` FOREIGN KEY (`tecnica_id`) REFERENCES `tecnicas`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `tecnicas_regiao` ADD CONSTRAINT `fk_tecnicas_regiao_regiao` FOREIGN KEY (`regiao_id`) REFERENCES `regioes`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `estados_regiao` ADD CONSTRAINT `fk_estados_regiao` FOREIGN KEY (`regiao_id`) REFERENCES `regioes`(`id`) ON DELETE CASCADE",
        "ALTER TABLE `cultura_regiao` ADD CONSTRAINT `fk_cultura_regiao` FOREIGN KEY (`regiao_id`) REFERENCES `regioes`(`id`) ON DELETE CASCADE"
    ];
    
    $fkCriadas = 0;
    foreach ($foreignKeys as $fk) {
        try {
            $pdo->exec($fk);
            $fkCriadas++;
        } catch (PDOException $e) {
            echo '<div class="warning">⚠ FK já existe ou erro: ' . substr($e->getMessage(), 0, 100) . '</div>';
        }
    }
    
    echo '<div class="success">✓ Total: ' . $fkCriadas . ' Foreign Keys configuradas</div>';
    echo '</div>';
    
    // ========== PASSO 5: Inserir dados iniciais ==========
    echo '<div class="step">';
    echo '<div class="step-title">5️⃣ Inserindo dados iniciais</div>';
    
    // Tipos de usuário
    $pdo->exec("
        INSERT INTO `tipo_usuario` (`id`, `nome`, `descricao`) VALUES
        (1, 'administrador', 'Usuário com acesso administrativo total'),
        (2, 'estudante', 'Usuário regular que aprende receitas')
    ");
    echo '<div class="success">✓ Tipos de usuário inseridos</div>';
    
    // Regiões
    $pdo->exec("
        INSERT INTO `regioes` (`nome`, `slug`, `descricao`, `ordem`) VALUES
        ('Nordeste', 'nordeste', 'Sabores intensos e temperos marcantes', 1),
        ('Sudeste', 'sudeste', 'Tradição e modernidade em harmonia', 2),
        ('Sul', 'sul', 'Tradições europeias e sabores gaúchos', 3),
        ('Norte', 'norte', 'Sabores exóticos da Amazônia', 4),
        ('Centro-Oeste', 'centro-oeste', 'Sabores do pantanal e cerrado', 5)
    ");
    echo '<div class="success">✓ 5 regiões brasileiras inseridas</div>';
    
    // Admin padrão
    $pdo->exec("
        INSERT INTO `usuarios` (`username`, `nome`, `email`, `senha`, `ativo`) VALUES
        ('admin', 'Administrador', 'admin@flavorway.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1)
    ");
    
    $pdo->exec("INSERT INTO `administradores` (`usuario_id`, `nivel`) VALUES (1, 'super_admin')");
    $pdo->exec("INSERT INTO `usuarios_tipo_usuario` (`usuario_id`, `tipo_usuario_id`) VALUES (1, 1)");
    
    echo '<div class="success">✓ Usuário admin criado (admin@flavorway.com / admin123)</div>';
    echo '</div>';
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // ========== ESTATÍSTICAS ==========
    echo '<div class="stats">';
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$dbname'");
    echo '<div class="stat-card"><div class="stat-number">' . $stmt->fetch()['total'] . '</div><div class="stat-label">Tabelas</div></div>';
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = '$dbname' AND REFERENCED_TABLE_NAME IS NOT NULL");
    echo '<div class="stat-card"><div class="stat-number">' . $stmt->fetch()['total'] . '</div><div class="stat-label">Foreign Keys</div></div>';
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM regioes");
    echo '<div class="stat-card"><div class="stat-number">' . $stmt->fetch()['total'] . '</div><div class="stat-label">Regiões</div></div>';
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
    echo '<div class="stat-card"><div class="stat-number">' . $stmt->fetch()['total'] . '</div><div class="stat-label">Usuários</div></div>';
    
    echo '</div>';
    
    // ========== VERIFICAÇÃO FINAL ==========
    echo '<div class="step">';
    echo '<div class="step-title">6️⃣ Verificação Final - Todas as Tabelas</div>';
    
    $stmt = $pdo->query("SHOW TABLES");
    echo '<table>';
    echo '<tr><th>#</th><th>Nome da Tabela</th><th>Status</th></tr>';
    
    $i = 1;
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo '<tr>';
        echo '<td>' . $i++ . '</td>';
        echo '<td><strong>' . $row[0] . '</strong></td>';
        echo '<td><span style="color: #10b981;">✓ Ativa</span></td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</div>';
    
    // ========== SUCESSO ==========
    echo '<div class="success" style="font-size: 1.2rem; padding: 30px; margin-top: 30px;">';
    echo '<h2 style="margin-bottom: 20px;">🎉 BANCO DE DADOS CRIADO COM SUCESSO!</h2>';
    echo '<div style="text-align: left; max-width: 700px; margin: 0 auto;">';
    echo '<p style="margin: 10px 0;">✓ Banco de dados <code>flavor_way</code> criado</p>';
    echo '<p style="margin: 10px 0;">✓ ' . count($tabelas) . ' tabelas estruturadas</p>';
    echo '<p style="margin: 10px 0;">✓ ' . $fkCriadas . ' Foreign Keys configuradas</p>';
    echo '<p style="margin: 10px 0;">✓ Dados iniciais inseridos</p>';
    echo '<p style="margin: 10px 0;">✓ Usuário admin: <code>admin@flavorway.com</code> / <code>admin123</code></p>';
    echo '<p style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #10b981; font-weight: 700;">';
    echo '🚀 Seu sistema está pronto para uso!</p>';
    echo '</div>';
    echo '</div>';
    
} catch (Exception $e) {
    echo '<div class="error">';
    echo '<h3>❌ Erro Fatal</h3>';
    echo '<p><strong>Mensagem:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>Linha:</strong> ' . $e->getLine() . '</p>';
    echo '<pre style="background: #1f2937; color: #10b981; padding: 15px; border-radius: 8px; overflow-x: auto;">';
    echo htmlspecialchars($e->getTraceAsString());
    echo '</pre>';
    echo '</div>';
}

?>

        <div class="delete-warning">
            ⚠️ IMPORTANTE: DELETE ESTE ARQUIVO AGORA! ⚠️<br>
            <small style="font-size: 0.8rem; font-weight: normal; opacity: 0.9;">
                Manter este arquivo pode comprometer a segurança do sistema
            </small>
        </div>
    </div>
</body>
</html>