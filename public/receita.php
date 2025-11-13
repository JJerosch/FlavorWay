<?php
session_start();

// === VERIFICA LOGIN ===
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// === CARREGA CONEXÃO ===
require_once '../config/database.php';

// === PUXA NOME DO BANCO ===
try {
    $stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $_SESSION['username'] = $user['nome'] ?? 'Usuário';
} catch (Exception $e) {
    $_SESSION['username'] = 'Usuário';
}

// === FUNÇÃO SEGURA ===
function getUserName() {
    return htmlspecialchars($_SESSION['username'] ?? 'Usuário', ENT_QUOTES, 'UTF-8');
}

// === OBTÉM ID DA RECEITA ===
$receita_id = $_GET['id'] ?? '';
if (empty($receita_id) || !is_numeric($receita_id)) {
    header('Location: culinaria-brasileira.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-title">Receita - FlavorWay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/public.css/homestyles.css">
    <link rel="stylesheet" href="../assets/css/public.css/culinariabrasileira.css">
    <link rel="stylesheet" href="../assets/css/public.css/receita.css">
    <style>
        /* Estilos críticos para busca (evita FOUC) */
        .search-container {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .search-container.active { display: block; }
        .search-inner { display: flex; gap: 0.5rem; max-width: 600px; margin: 0 auto; }
        .search-input { flex: 1; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; }
        .search-close { background: none; border: none; font-size: 1.5rem; cursor: pointer; }
        .menu-toggle { display: none; }
        @media (max-width: 768px) {
            .nav { display: none; flex-direction: column; position: absolute; top: 100%; left: 0; right: 0; background: white; padding: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
            .nav.active { display: flex; }
            .menu-toggle { display: block; }
            .header-actions .search-btn { display: none; }
        }
    </style>
</head>
<body class="receita-page">
    <!-- Header -->
    <header class="header" id="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-utensils"></i>
                    <div>
                        <h1>FlavorWay</h1>
                        <span>Sabores do Mundo</span>
                    </div>
                </div>

                <nav class="nav" id="nav">
                    <a href="index.php" class="nav-link">
                        <i class="fas fa-home"></i> Início
                    </a>
                    <a href="culinaria-brasileira.php" class="nav-link">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <a href="#ingredientes" class="nav-link">Ingredientes</a>
                    <a href="#preparo" class="nav-link">Modo de Preparo</a>
                    <a href="#avaliacoes" class="nav-link">Avaliações</a>
                </nav>

                <div class="header-actions">
                    <span class="user-greeting">Olá, <?= getUserName() ?>!</span>
                    <a href="../auth/logout.php" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>
                    <button class="search-btn" onclick="toggleSearch()" aria-label="Buscar">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="menu-toggle" onclick="toggleMenu()" aria-label="Menu">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Barra de Pesquisa -->
            <div class="search-container" id="searchContainer">
                <div class="search-inner">
                    <input type="text" placeholder="Buscar receitas, regiões..." class="search-input" onkeypress="if(event.key==='Enter') search()">
                    <button class="search-close" onclick="toggleSearch()" aria-label="Fechar">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero da Receita -->
    <section class="hero-receita" id="hero-receita">
        <div class="hero-bg-receita"></div>
        <div class="container">
            <div class="hero-content-receita" id="hero-content">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i> Carregando receita...
                </div>
            </div>
        </div>
    </section>

    <!-- Informações da Receita -->
    <section class="receita-info">
        <div class="container">
            <div class="receita-grid">
                <!-- Ingredientes -->
                <div class="receita-section" id="ingredientes">
                    <div class="section-header-small">
                        <h3><i class="fas fa-list"></i> Ingredientes</h3>
                    </div>
                    <div id="ingredientes-list" class="ingredientes-list">
                        <div class="loading-spinner">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                </div>

                <!-- Modo de Preparo -->
                <div class="receita-section full-width" id="preparo">
                    <div class="section-header-small">
                        <h3><i class="fas fa-fire"></i> Modo de Preparo</h3>
                    </div>
                    <div id="preparo-steps" class="preparo-steps">
                        <p class="placeholder-text">Em breve disponível...</p>
                    </div>
                </div>

                <!-- Informações Nutricionais -->
                <div class="receita-section" id="nutricao">
                    <div class="section-header-small">
                        <h3><i class="fas fa-heartbeat"></i> Informações Nutricionais</h3>
                    </div>
                    <div id="nutricao-info" class="nutricao-info">
                        <div class="loading-spinner">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Avaliações -->
    <section id="avaliacoes" class="avaliacoes-section">
        <div class="container">
            <div class="section-header">
                <div class="section-tag brasil">Avaliações</div>
                <h2>O que as pessoas <span class="highlight-brasil">estão dizendo</span></h2>
            </div>

            <!-- Estatísticas de Avaliações -->
            <div class="avaliacoes-stats" id="avaliacoes-stats">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>

            <!-- Formulário de Avaliação -->
            <div class="avaliacao-form-container" id="avaliacao-form-container">
                <h3>Deixe sua avaliação</h3>
                <form id="avaliacao-form" onsubmit="submitAvaliacao(event)">
                    <div class="rating-input">
                        <label>Sua nota:</label>
                        <div class="stars-input" id="stars-input">
                            <i class="far fa-star" data-nota="1"></i>
                            <i class="far fa-star" data-nota="2"></i>
                            <i class="far fa-star" data-nota="3"></i>
                            <i class="far fa-star" data-nota="4"></i>
                            <i class="far fa-star" data-nota="5"></i>
                        </div>
                        <input type="hidden" id="nota-input" name="nota" required>
                    </div>
                    <div class="form-group">
                        <label for="comentario">Comentário (opcional):</label>
                        <textarea id="comentario" name="comentario" rows="4" placeholder="Compartilhe sua experiência..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Enviar Avaliação
                    </button>
                </form>
            </div>

            <!-- Lista de Avaliações -->
            <div class="avaliacoes-list" id="avaliacoes-list">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer footer-brasil">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-utensils"></i>
                        <div>
                            <h3>FlavorWay</h3>
                            <p>Sabores do Mundo</p>
                        </div>
                    </div>
                    <p class="footer-description">
                        Celebrando a riqueza e diversidade da gastronomia mundial.
                    </p>
                </div>

                <div class="footer-section">
                    <h4>Regiões</h4>
                    <ul>
                        <li><a href="regiao.php?regiao=nordeste">Nordeste</a></li>
                        <li><a href="regiao.php?regiao=sudeste">Sudeste</a></li>
                        <li><a href="regiao.php?regiao=sul">Sul</a></li>
                        <li><a href="regiao.php?regiao=norte">Norte</a></li>
                        <li><a href="regiao.php?regiao=centro-oeste">Centro-Oeste</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>FlavorWay</h4>
                    <ul>
                        <li><a href="index.php">Home Principal</a></li>
                        <li><a href="culinaria-brasileira.php">Culinária Brasileira</a></li>
                        <li><a href="#">Contato</a></li>
                        <li><a href="#">Sobre</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 FlavorWay. Todos os direitos reservados.</p>
                <p>Feito com <i class="fas fa-heart"></i> para amantes da culinária</p>
            </div>
        </div>
    </footer>

    <script>
        // Passa o ID da receita para o JavaScript
        const RECEITA_ID = <?= (int)$receita_id ?>;
    </script>
    <script src="../assets/js/public.js/receita.js"></script>
    <script>
    // Funções auxiliares (consistência com index.php)
    function toggleSearch() {
        const container = document.getElementById('searchContainer');
        container.classList.toggle('active');
        if (container.classList.contains('active')) {
            container.querySelector('input').focus();
        }
    }

    function toggleMenu() {
        const nav = document.getElementById('nav');
        nav.classList.toggle('active');
    }

    function scrollToSection(id) {
        document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
    }

    function search() {
        const query = document.querySelector('.search-input').value.trim();
        if (query) {
            alert('Buscando por: ' + query);
            // Aqui você conecta com PHP/AJAX depois
        }
    }
    </script>
</body>
</html>
