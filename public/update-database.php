<?php
/**
 * Script para atualizar a tabela receitas com novos campos
 * Execute este arquivo no navegador: http://localhost/FlavorWay/public/update-database.php
 */

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Atualizar Banco de Dados</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;}h1{color:#333;}.success{color:green;}.error{color:red;}.warning{color:orange;}</style>";
echo "</head><body>";
echo "<h1>🔧 Atualização da Tabela Receitas</h1>";
echo "<hr>";

require_once '../config/database.php';

try {
    echo "<h2>Adicionando campos na tabela receitas...</h2>";

    // Verificar e adicionar campo imagem
    $stmt = $pdo->query("SHOW COLUMNS FROM receitas LIKE 'imagem'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `receitas` ADD COLUMN `imagem` VARCHAR(500) DEFAULT NULL AFTER `descricao`");
        echo "<p class='success'>✓ Campo 'imagem' adicionado com sucesso!</p>";
    } else {
        echo "<p class='warning'>⚠ Campo 'imagem' já existe</p>";
    }

    // Verificar e adicionar campo ingredientes
    $stmt = $pdo->query("SHOW COLUMNS FROM receitas LIKE 'ingredientes'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `receitas` ADD COLUMN `ingredientes` TEXT DEFAULT NULL AFTER `imagem`");
        echo "<p class='success'>✓ Campo 'ingredientes' adicionado com sucesso!</p>";
    } else {
        echo "<p class='warning'>⚠ Campo 'ingredientes' já existe</p>";
    }

    // Verificar e adicionar campo modo_preparo
    $stmt = $pdo->query("SHOW COLUMNS FROM receitas LIKE 'modo_preparo'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `receitas` ADD COLUMN `modo_preparo` TEXT DEFAULT NULL AFTER `ingredientes`");
        echo "<p class='success'>✓ Campo 'modo_preparo' adicionado com sucesso!</p>";
    } else {
        echo "<p class='warning'>⚠ Campo 'modo_preparo' já existe</p>";
    }

    // Verificar e adicionar campo usuario_id
    $stmt = $pdo->query("SHOW COLUMNS FROM receitas LIKE 'usuario_id'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `receitas` ADD COLUMN `usuario_id` INT(11) DEFAULT NULL AFTER `regiao_id`");
        echo "<p class='success'>✓ Campo 'usuario_id' adicionado com sucesso!</p>";
    } else {
        echo "<p class='warning'>⚠ Campo 'usuario_id' já existe</p>";
    }

    // Adicionar foreign key para usuario_id (se não existir)
    try {
        // Verificar se a constraint já existe
        $stmt = $pdo->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                            WHERE TABLE_NAME = 'receitas'
                            AND CONSTRAINT_NAME = 'fk_receitas_usuario'");

        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE `receitas`
                        ADD CONSTRAINT `fk_receitas_usuario`
                        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL");
            echo "<p class='success'>✓ Foreign key 'fk_receitas_usuario' adicionada com sucesso!</p>";
        } else {
            echo "<p class='warning'>⚠ Foreign key 'fk_receitas_usuario' já existe</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='warning'>⚠ Foreign key já existe ou erro: " . $e->getMessage() . "</p>";
    }

    // Criar índice no campo usuario_id (se não existir)
    try {
        $stmt = $pdo->query("SHOW INDEX FROM receitas WHERE Key_name = 'idx_usuario_id'");

        if ($stmt->rowCount() == 0) {
            $pdo->exec("CREATE INDEX `idx_usuario_id` ON `receitas` (`usuario_id`)");
            echo "<p class='success'>✓ Índice 'idx_usuario_id' criado com sucesso!</p>";
        } else {
            echo "<p class='warning'>⚠ Índice 'idx_usuario_id' já existe</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='warning'>⚠ Índice já existe ou erro: " . $e->getMessage() . "</p>";
    }

    // Criar índice composto para buscar receitas em destaque rapidamente (se não existir)
    try {
        $stmt = $pdo->query("SHOW INDEX FROM receitas WHERE Key_name = 'idx_destaque_created'");

        if ($stmt->rowCount() == 0) {
            $pdo->exec("CREATE INDEX `idx_destaque_created` ON `receitas` (`destaque`, `created_at`)");
            echo "<p class='success'>✓ Índice 'idx_destaque_created' criado com sucesso!</p>";
        } else {
            echo "<p class='warning'>⚠ Índice 'idx_destaque_created' já existe</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='warning'>⚠ Índice já existe ou erro: " . $e->getMessage() . "</p>";
    }

    echo "<hr>";
    echo "<h2 class='success'>✅ Atualização concluída com sucesso!</h2>";
    echo "<p><strong>Próximos passos:</strong></p>";
    echo "<ol>";
    echo "<li>Verifique se tudo está OK em: <a href='../api/test-api.php'>test-api.php</a></li>";
    echo "<li>Acesse o index e veja as receitas: <a href='index.php'>index.php</a></li>";
    echo "<li>Ou adicione uma nova receita: <a href='adicionar-receita.php'>adicionar-receita.php</a></li>";
    echo "</ol>";

} catch (PDOException $e) {
    echo "<h2 class='error'>❌ Erro ao atualizar tabela</h2>";
    echo "<p class='error'>Erro: " . $e->getMessage() . "</p>";
    echo "<p>Código do erro: " . $e->getCode() . "</p>";
}

echo "</body></html>";
