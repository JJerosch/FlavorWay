-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07/11/2025 às 21:49
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `flavor_way`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nivel` enum('super_admin','admin','moderador') DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `administradores`
--

INSERT INTO `administradores` (`id`, `usuario_id`, `nivel`) VALUES
(1, 1, 'super_admin');

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `receita_id` int(11) NOT NULL,
  `nota` int(11) NOT NULL CHECK (`nota` >= 1 and `nota` <= 5),
  `comentario` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cultura_regiao`
--

CREATE TABLE `cultura_regiao` (
  `id` int(11) NOT NULL,
  `regiao_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `tipo` enum('influencia','tradicao','historia') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estados_regiao`
--

CREATE TABLE `estados_regiao` (
  `id` int(11) NOT NULL,
  `regiao_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `capital` varchar(100) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `ingrediente_destaque` varchar(255) DEFAULT NULL,
  `especialidades` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`especialidades`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estudantes`
--

CREATE TABLE `estudantes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `progresso` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `estudantes`
--

INSERT INTO `estudantes` (`id`, `usuario_id`, `progresso`) VALUES
(1, 2, 0),
(2, 3, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `estudante_id` int(11) NOT NULL,
  `receita_id` int(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ingredientes`
--

CREATE TABLE `ingredientes` (
  `id` int(11) NOT NULL,
  `receita_id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `categoria` enum('Laticínios','Carnes','Vegetais','Grãos','Temperos','Outros') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ingredientes_regiao`
--

CREATE TABLE `ingredientes_regiao` (
  `id` int(11) NOT NULL,
  `ingrediente_id` int(11) NOT NULL,
  `regiao_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `receitas`
--

CREATE TABLE `receitas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `tempo_preparo` varchar(50) NOT NULL,
  `pessoas` varchar(60) NOT NULL,
  `rating` decimal(2,1) DEFAULT 4.5,
  `dificuldade` enum('Básico','Intermediário','Avançado') NOT NULL,
  `regiao` enum('Nordeste','Sudeste','Sul','Centro-Oeste','Norte') NOT NULL,
  `destaque` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `badge` varchar(50) DEFAULT NULL,
  `tempo_cozimento` varchar(50) DEFAULT NULL,
  `rendimento` varchar(50) DEFAULT NULL,
  `calorias` varchar(50) DEFAULT NULL,
  `proteinas` varchar(50) DEFAULT NULL,
  `carboidratos` varchar(50) DEFAULT NULL,
  `gorduras` varchar(50) DEFAULT NULL,
  `regiao_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `receita_tags`
--

CREATE TABLE `receita_tags` (
  `receita_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `regioes`
--

CREATE TABLE `regioes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `regioes`
--

INSERT INTO `regioes` (`id`, `nome`, `slug`, `descricao`, `ordem`, `ativo`, `created_at`) VALUES
(1, 'Nordeste', 'nordeste', 'Sabores intensos e temperos marcantes', 1, 1, '2025-11-07 20:12:14'),
(2, 'Sudeste', 'sudeste', 'Tradição e modernidade em harmonia', 2, 1, '2025-11-07 20:12:14'),
(3, 'Sul', 'sul', 'Tradições europeias e sabores gaúchos', 3, 1, '2025-11-07 20:12:14'),
(4, 'Norte', 'norte', 'Sabores exóticos da Amazônia', 4, 1, '2025-11-07 20:12:14'),
(5, 'Centro-Oeste', 'centro-oeste', 'Sabores do pantanal e cerrado', 5, 1, '2025-11-07 20:12:14');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tecnicas`
--

CREATE TABLE `tecnicas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `dificuldades_tecnica` enum('Básico','Intermediário','Avançado') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tecnicas_regiao`
--

CREATE TABLE `tecnicas_regiao` (
  `id` int(11) NOT NULL,
  `tecnica_id` int(11) NOT NULL,
  `regiao_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_usuario`
--

CREATE TABLE `tipo_usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`id`, `nome`, `descricao`) VALUES
(1, 'administrador', 'Usuário com acesso administrativo total'),
(2, 'estudante', 'Usuário regular que aprende receitas');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ultimo_acesso` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `nome`, `email`, `senha`, `avatar`, `data_criacao`, `data_atualizacao`, `ultimo_acesso`, `ativo`) VALUES
(1, 'admin', 'Administrador', 'admin@flavorway.com', '$2y$10$aLPExxv9dPWi0ccpC2N31u6MK8kVI1zuPBM03ed8cjnmTTgii2sX6', NULL, '2025-10-09 18:48:23', '2025-11-07 20:29:01', '2025-11-07 19:26:37', 1),
(2, 'jj13679', 'joao', 'jpjerosch@gmail.com', '$2y$10$Xs0cYazsmByTkq5TH5w1AeYKgabQ1EAtg/jL4fQQ85kFN8lP7RDlC', NULL, '2025-10-15 17:12:26', '2025-10-16 17:16:41', '2025-10-16 17:16:41', 1),
(3, 'f123123', 'fol', 'f123123@gmail.com', '$2y$10$vxr55ZGa6j3XJ9ES20VV2.dArNFNaZxcnApwNlIf8t9sDyrNL9k0O', NULL, '2025-10-16 17:15:54', '2025-10-16 17:15:54', NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_tipo_usuario`
--

CREATE TABLE `usuarios_tipo_usuario` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo_usuario_id` int(11) NOT NULL,
  `data_atribuicao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios_tipo_usuario`
--

INSERT INTO `usuarios_tipo_usuario` (`id`, `usuario_id`, `tipo_usuario_id`, `data_atribuicao`) VALUES
(1, 1, 1, '2025-11-07 20:48:45'),
(2, 2, 2, '2025-11-07 20:48:45'),
(3, 3, 2, '2025-11-07 20:48:45');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`),
  ADD KEY `idx_nivel` (`nivel`);

--
-- Índices de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_avaliacao` (`usuario_id`,`receita_id`),
  ADD KEY `fk_avaliacoes_receita` (`receita_id`);

--
-- Índices de tabela `cultura_regiao`
--
ALTER TABLE `cultura_regiao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cultura_regiao` (`regiao_id`);

--
-- Índices de tabela `estados_regiao`
--
ALTER TABLE `estados_regiao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_estados_regiao` (`regiao_id`);

--
-- Índices de tabela `estudantes`
--
ALTER TABLE `estudantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favorito` (`estudante_id`,`receita_id`),
  ADD KEY `fk_favoritos_receita` (`receita_id`);

--
-- Índices de tabela `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nome` (`nome`),
  ADD KEY `fk_ingredientes_receita` (`receita_id`);

--
-- Índices de tabela `ingredientes_regiao`
--
ALTER TABLE `ingredientes_regiao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ingrediente_regiao` (`ingrediente_id`,`regiao_id`),
  ADD KEY `fk_ingredientes_regiao_regiao` (`regiao_id`);

--
-- Índices de tabela `receitas`
--
ALTER TABLE `receitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_destaque` (`destaque`),
  ADD KEY `idx_rating` (`rating`),
  ADD KEY `fk_receitas_regiao` (`regiao_id`);

--
-- Índices de tabela `receita_tags`
--
ALTER TABLE `receita_tags`
  ADD PRIMARY KEY (`receita_id`,`tag_id`),
  ADD KEY `fk_receita_tags_tag` (`tag_id`);

--
-- Índices de tabela `regioes`
--
ALTER TABLE `regioes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`);

--
-- Índices de tabela `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nome` (`nome`);

--
-- Índices de tabela `tecnicas`
--
ALTER TABLE `tecnicas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tecnicas_regiao`
--
ALTER TABLE `tecnicas_regiao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tecnica_regiao` (`tecnica_id`,`regiao_id`),
  ADD KEY `fk_tecnicas_regiao_regiao` (`regiao_id`);

--
-- Índices de tabela `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_ativo` (`ativo`);

--
-- Índices de tabela `usuarios_tipo_usuario`
--
ALTER TABLE `usuarios_tipo_usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_usuario_tipo` (`usuario_id`,`tipo_usuario_id`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_tipo` (`tipo_usuario_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cultura_regiao`
--
ALTER TABLE `cultura_regiao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estados_regiao`
--
ALTER TABLE `estados_regiao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estudantes`
--
ALTER TABLE `estudantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ingredientes`
--
ALTER TABLE `ingredientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ingredientes_regiao`
--
ALTER TABLE `ingredientes_regiao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `receitas`
--
ALTER TABLE `receitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `regioes`
--
ALTER TABLE `regioes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tecnicas`
--
ALTER TABLE `tecnicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tecnicas_regiao`
--
ALTER TABLE `tecnicas_regiao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuarios_tipo_usuario`
--
ALTER TABLE `usuarios_tipo_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `administradores`
--
ALTER TABLE `administradores`
  ADD CONSTRAINT `fk_administradores_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `fk_avaliacoes_receita` FOREIGN KEY (`receita_id`) REFERENCES `receitas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_avaliacoes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `cultura_regiao`
--
ALTER TABLE `cultura_regiao`
  ADD CONSTRAINT `fk_cultura_regiao` FOREIGN KEY (`regiao_id`) REFERENCES `regioes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `estados_regiao`
--
ALTER TABLE `estados_regiao`
  ADD CONSTRAINT `fk_estados_regiao` FOREIGN KEY (`regiao_id`) REFERENCES `regioes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `estudantes`
--
ALTER TABLE `estudantes`
  ADD CONSTRAINT `fk_estudantes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `fk_favoritos_estudante` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favoritos_receita` FOREIGN KEY (`receita_id`) REFERENCES `receitas` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD CONSTRAINT `fk_ingredientes_receita` FOREIGN KEY (`receita_id`) REFERENCES `receitas` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ingredientes_regiao`
--
ALTER TABLE `ingredientes_regiao`
  ADD CONSTRAINT `fk_ingredientes_regiao_ingrediente` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ingredientes_regiao_regiao` FOREIGN KEY (`regiao_id`) REFERENCES `regioes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `receitas`
--
ALTER TABLE `receitas`
  ADD CONSTRAINT `fk_receitas_regiao` FOREIGN KEY (`regiao_id`) REFERENCES `regioes` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `receita_tags`
--
ALTER TABLE `receita_tags`
  ADD CONSTRAINT `fk_receita_tags_receita` FOREIGN KEY (`receita_id`) REFERENCES `receitas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_receita_tags_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tecnicas_regiao`
--
ALTER TABLE `tecnicas_regiao`
  ADD CONSTRAINT `fk_tecnicas_regiao_regiao` FOREIGN KEY (`regiao_id`) REFERENCES `regioes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tecnicas_regiao_tecnica` FOREIGN KEY (`tecnica_id`) REFERENCES `tecnicas` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `usuarios_tipo_usuario`
--
ALTER TABLE `usuarios_tipo_usuario`
  ADD CONSTRAINT `fk_usuarios_tipo_tipo` FOREIGN KEY (`tipo_usuario_id`) REFERENCES `tipo_usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuarios_tipo_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
