-- ============================================
-- FLAVORWAY - SCHEMA MESCLADO
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ============================================
-- TABELA DE USUÁRIOS (users + usuarios mesclados)
-- ============================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ultimo_acesso TIMESTAMP NULL,
    ativo TINYINT(1) DEFAULT 1,
    
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA DE ADMINISTRADORES
-- ============================================
CREATE TABLE IF NOT EXISTS administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL UNIQUE,
    nivel ENUM('super_admin', 'admin', 'moderador') DEFAULT 'admin',
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_nivel (nivel)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA DE ESTUDANTES
-- ============================================
CREATE TABLE IF NOT EXISTS estudantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL UNIQUE,
    progresso INT DEFAULT 0,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir primeiro usuário admin (senha: admin123)
INSERT INTO usuarios (username, nome, email, senha, ativo) VALUES
('admin', 'Administrador', 'admin@flavorway.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

INSERT INTO administradores (usuario_id, nivel) VALUES
(1, 'super_admin');

-- ============================================
-- TABELA DE RECEITAS
-- ============================================
CREATE TABLE IF NOT EXISTS receitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    tempo_preparo VARCHAR(50) NOT NULL,
    pessoas VARCHAR(60) NOT NULL,
    rating DECIMAL(2,1) DEFAULT 4.5,
    dificuldade ENUM('Básico', 'Intermediário', 'Avançado') NOT NULL,
    regiao ENUM('Nordeste', 'Sudeste', 'Sul', 'Centro-Oeste', 'Norte') NOT NULL,
    destaque BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    badge VARCHAR(50) DEFAULT NULL,
    tempo_cozimento VARCHAR(50),
    rendimento VARCHAR(50),
    calorias VARCHAR(50),
    proteinas VARCHAR(50),
    carboidratos VARCHAR(50),
    gorduras VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA DE TAGS
-- ============================================
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA RECEITA_TAGS
-- ============================================
CREATE TABLE IF NOT EXISTS receita_tags (
    receita_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (receita_id, tag_id),
    FOREIGN KEY (receita_id) REFERENCES receitas(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA DE INGREDIENTES
-- ============================================
CREATE TABLE IF NOT EXISTS ingredientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receita_id INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    categoria ENUM('Laticínios', 'Carnes', 'Vegetais', 'Grãos', 'Temperos', 'Outros') NOT NULL,
    FOREIGN KEY (receita_id) REFERENCES receitas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA DE TÉCNICAS
-- ============================================
CREATE TABLE IF NOT EXISTS tecnicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    dificuldades_tecnica ENUM('Básico', 'Intermediário', 'Avançado') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA DE FAVORITOS
-- ============================================
CREATE TABLE IF NOT EXISTS favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo ENUM('receita', 'regiao', 'ingrediente') NOT NULL,
    item_id INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorito (usuario_id, tipo, item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA DE AVALIAÇÕES
-- ============================================
CREATE TABLE IF NOT EXISTS avaliacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo ENUM('receita', 'regiao') NOT NULL,
    item_id INT NOT NULL,
    nota INT NOT NULL CHECK (nota >= 1 AND nota <= 5),
    comentario TEXT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_avaliacao (usuario_id, tipo, item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- FIM DO SCHEMA
-- ============================================