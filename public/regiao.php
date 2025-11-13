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

// === OBTÉM REGIÃO DA URL ===
$regiao_slug = $_GET['regiao'] ?? '';
if (empty($regiao_slug)) {
    header('Location: culinaria-brasileira.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-title">Região - FlavorWay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/public.css/homestyles.css">
    <link rel="stylesheet" href="../assets/css/public.css/culinariabrasileira.css">
    <link rel="stylesheet" href="../assets/css/public.css/regiao.css">
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
<body class="regiao-page">
    <!-- Header -->
    <header class="header regiao-header" id="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-utensils"></i>
                    <div>
                        <h1>FlavorWay</h1>
                        <span id="header-regiao-nome">Carregando...</span>
                    </div>
                </div>

                <nav class="nav" id="nav">
                    <a href="index.php" class="nav-link">
                        <i class="fas fa-home"></i> Início
                    </a>
                    <a href="culinaria-brasileira.php" class="nav-link">
                        <i class="fas fa-arrow-left"></i> Culinária Brasileira
                    </a>
                    <a href="#receitas" class="nav-link">Receitas</a>
                    <a href="#sobre" class="nav-link">Sobre a Região</a>
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

    <!-- Hero da Região -->
    <section class="hero-regiao" id="hero-regiao">
        <div class="hero-bg-regiao"></div>
        <div class="container">
            <div class="hero-content" id="hero-content">
                <!-- Preenchido via JavaScript -->
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i> Carregando...
                </div>
            </div>
        </div>

        <div class="scroll-indicator" onclick="scrollToSection('receitas')">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- Receitas da Região -->
    <section id="receitas" class="receitas-regiao">
        <div class="container">
            <div class="section-header">
                <div class="section-tag brasil">Receitas</div>
                <h2>Pratos <span class="highlight-brasil" id="regiao-nome-titulo">Tradicionais</span></h2>
                <p id="receitas-subtitle">Descubra os sabores autênticos da região</p>
            </div>

            <div class="receitas-grid" id="receitasGrid">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i> Carregando receitas...
                </div>
            </div>
        </div>
    </section>

    <!-- Sobre a Região -->
    <section id="sobre" class="sobre-regiao">
        <div class="container">
            <div class="section-header">
                <div class="section-tag brasil">Conheça</div>
                <h2>Sobre a <span class="highlight-brasil" id="sobre-regiao-nome">Região</span></h2>
            </div>

            <div class="sobre-content" id="sobreContent">
                <!-- Preenchido via JavaScript -->
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-brasil">
        <div class="container">
            <div class="cta-content">
                <h2>Gostou das receitas?</h2>
                <p>Explore outras regiões e descubra mais sabores do Brasil</p>
                <div class="cta-buttons">
                    <a href="culinaria-brasileira.php" class="btn btn-primary">
                        <i class="fas fa-map-marked-alt"></i>
                        Ver Todas as Regiões
                    </a>
                    <a href="index.php" class="btn btn-outline-white">
                        <i class="fas fa-globe"></i>
                        Outras Culinárias
                    </a>
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
                            <p>Culinária Brasileira</p>
                        </div>
                    </div>
                    <p class="footer-description">
                        Celebrando a riqueza e diversidade da gastronomia brasileira.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    </div>
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
                <p>&copy; 2025 FlavorWay. Celebrando a culinária brasileira.</p>
                <p>Feito com <i class="fas fa-heart"></i> por brasileiros para o mundo</p>
            </div>
        </div>
    </footer>

    <script>
        // Passa o slug da região para o JavaScript
        const REGIAO_SLUG = '<?= htmlspecialchars($regiao_slug, ENT_QUOTES, 'UTF-8') ?>';
    </script>
    <script src="../assets/js/public.js/regiao.js"></script>
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
