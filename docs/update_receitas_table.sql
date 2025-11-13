-- Adicionar campo de imagem e outros campos úteis na tabela receitas
ALTER TABLE `receitas`
ADD COLUMN `imagem` VARCHAR(500) DEFAULT NULL AFTER `descricao`,
ADD COLUMN `ingredientes` TEXT DEFAULT NULL AFTER `imagem`,
ADD COLUMN `modo_preparo` TEXT DEFAULT NULL AFTER `ingredientes`,
ADD COLUMN `usuario_id` INT(11) DEFAULT NULL AFTER `regiao_id`,
ADD CONSTRAINT `fk_receitas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

-- Criar índice no campo usuario_id para melhor performance
CREATE INDEX `idx_usuario_id` ON `receitas` (`usuario_id`);

-- Criar índice composto para buscar receitas em destaque rapidamente
CREATE INDEX `idx_destaque_created` ON `receitas` (`destaque`, `created_at` DESC);
