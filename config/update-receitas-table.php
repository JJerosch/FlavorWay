<?php
/**
 * Script para atualizar a tabela receitas com novos campos
 * Adiciona: imagem, ingredientes, modo_preparo, usuario_id
 */

require_once 'database.php';

try {
    echo "Iniciando atualização da tabela receitas...\n\n";

    // Adicionar campo imagem
    $pdo->exec("ALTER TABLE `receitas`
                ADD COLUMN IF NOT EXISTS `imagem` VARCHAR(500) DEFAULT NULL AFTER `descricao`");
    echo "✓ Campo 'imagem' adicionado\n";

    // Adicionar campo ingredientes
    $pdo->exec("ALTER TABLE `receitas`
                ADD COLUMN IF NOT EXISTS `ingredientes` TEXT DEFAULT NULL AFTER `imagem`");
    echo "✓ Campo 'ingredientes' adicionado\n";

    // Adicionar campo modo_preparo
    $pdo->exec("ALTER TABLE `receitas`
                ADD COLUMN IF NOT EXISTS `modo_preparo` TEXT DEFAULT NULL AFTER `ingredientes`");
    echo "✓ Campo 'modo_preparo' adicionado\n";

    // Adicionar campo usuario_id
    $pdo->exec("ALTER TABLE `receitas`
                ADD COLUMN IF NOT EXISTS `usuario_id` INT(11) DEFAULT NULL AFTER `regiao_id`");
    echo "✓ Campo 'usuario_id' adicionado\n";

    // Adicionar foreign key para usuario_id
    try {
        $pdo->exec("ALTER TABLE `receitas`
                    ADD CONSTRAINT `fk_receitas_usuario`
                    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL");
        echo "✓ Foreign key 'fk_receitas_usuario' adicionada\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate') !== false) {
            echo "⚠ Foreign key 'fk_receitas_usuario' já existe\n";
        } else {
            throw $e;
        }
    }

    // Criar índice no campo usuario_id
    try {
        $pdo->exec("CREATE INDEX `idx_usuario_id` ON `receitas` (`usuario_id`)");
        echo "✓ Índice 'idx_usuario_id' criado\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate') !== false) {
            echo "⚠ Índice 'idx_usuario_id' já existe\n";
        } else {
            throw $e;
        }
    }

    // Criar índice composto para buscar receitas em destaque rapidamente
    try {
        $pdo->exec("CREATE INDEX `idx_destaque_created` ON `receitas` (`destaque`, `created_at` DESC)");
        echo "✓ Índice 'idx_destaque_created' criado\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate') !== false) {
            echo "⚠ Índice 'idx_destaque_created' já existe\n";
        } else {
            throw $e;
        }
    }

    echo "\n✅ Tabela receitas atualizada com sucesso!\n";

} catch (PDOException $e) {
    echo "\n❌ Erro ao atualizar tabela: " . $e->getMessage() . "\n";
    exit(1);
}
