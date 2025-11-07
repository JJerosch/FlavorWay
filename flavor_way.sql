-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07/11/2025 às 21:03
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
  `tipo` enum('receita','regiao') NOT NULL,
  `item_id` int(11) NOT NULL,
  `nota` int(11) NOT NULL CHECK (`nota` >= 1 and `nota` <= 5),
  `comentario` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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

-- --------------------------------------------------------

--
-- Estrutura para tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('receita','regiao','ingrediente') NOT NULL,
  `item_id` int(11) NOT NULL,
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
  `gorduras` varchar(50) DEFAULT NULL
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
  `dificuldade` enum('Básico','Intermediário','Avançado') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nivel` enum('admin','user') DEFAULT 'user',
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

INSERT INTO `usuarios` (`id`, `username`, `nome`, `email`, `nivel`, `senha`, `avatar`, `data_criacao`, `data_atualizacao`, `ultimo_acesso`, `ativo`) VALUES
(1, 'admin', 'Administrador', 'admin@flavorway.com', 'user', '$2y$10$9s6SOC6fwmVq7aFmgAbpreSO/eu58u/PGZdojnbiky145cQGdv/cq', NULL, '2025-10-24 17:38:17', '2025-11-07 20:02:45', '2025-11-07 20:02:45', 1),
(11, '', 'FOLLAS', 'FOLLY@GMAIL.COM', 'user', '$2y$10$oQTnZM8Ea1TqTW83WgfjnuR7f9C533fUjlvDVhJO8N7RBluh1mobq', NULL, '2025-11-07 19:45:46', '2025-11-07 19:45:46', NULL, 1),
(13, '1', 'FOLLYS', 'FOLLYS@GMAIL.COM', 'user', '$2y$10$ZieF/UGd93hXknMUKqXnPObIRp1rt2tw0D2xc9TXOrVu04kwL9yOa', NULL, '2025-11-07 19:50:35', '2025-11-07 19:50:35', NULL, 1),
(14, 'guilherme', 'guilherme', 'guilherme@gmail.com', 'user', '$2y$10$N/55NSM8Y8OEj6UnfKfiC.nIzY21iQSQcpWxzR00HtYceQ62du4R2', NULL, '2025-11-07 19:51:03', '2025-11-07 19:51:03', NULL, 1),
(15, 'CANELEIRA123', 'caneleira maximus', 'CANELEIRA@gmail.com', 'user', '$2y$10$DRStu1LWgaqxNGcW4ZWlruKS4oUEHuQNL.YF7cB9aNEyYtW7ewN1K', NULL, '2025-11-07 19:53:56', '2025-11-07 19:53:56', NULL, 1);

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
  ADD UNIQUE KEY `unique_avaliacao` (`usuario_id`,`tipo`,`item_id`);

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
  ADD UNIQUE KEY `unique_favorito` (`usuario_id`,`tipo`,`item_id`);

--
-- Índices de tabela `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receita_id` (`receita_id`),
  ADD KEY `idx_categoria` (`categoria`);

--
-- Índices de tabela `receitas`
--
ALTER TABLE `receitas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `receita_tags`
--
ALTER TABLE `receita_tags`
  ADD PRIMARY KEY (`receita_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Índices de tabela `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `tecnicas`
--
ALTER TABLE `tecnicas`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT de tabela `estudantes`
--
ALTER TABLE `estudantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT de tabela `receitas`
--
ALTER TABLE `receitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `administradores`
--
ALTER TABLE `administradores`
  ADD CONSTRAINT `administradores_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `avaliacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `estudantes`
--
ALTER TABLE `estudantes`
  ADD CONSTRAINT `estudantes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD CONSTRAINT `ingredientes_ibfk_1` FOREIGN KEY (`receita_id`) REFERENCES `receitas` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `receita_tags`
--
ALTER TABLE `receita_tags`
  ADD CONSTRAINT `receita_tags_ibfk_1` FOREIGN KEY (`receita_id`) REFERENCES `receitas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `receita_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
