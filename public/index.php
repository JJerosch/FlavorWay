<?php
session_start();

// === VERIFICA LOGIN ===
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// === CARREGA CONEXÃO (seu arquivo real) ===
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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlavorWay - Explore a Culinária Mundial</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/public.css/homestyles.css">
<script src="../assets/js/public.js/home-main.js"></script>
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

        /* === ESTILOS PARA SEÇÕES DE PREVIEW === */
        .preview-section { padding: 4rem 0; }
        .preview-action { text-align: center; margin-top: 2rem; }
        .loading-spinner { text-align: center; padding: 2rem; color: #64748b; }

        /* Preview de Receitas - Cards Horizontais */
        .preview-scroll-container {
            overflow-x: auto;
            margin: 2rem 0;
            padding-bottom: 1rem;
            scrollbar-width: thin;
            scrollbar-color: #f59e0b #f1f5f9;
        }
        .preview-scroll-container::-webkit-scrollbar {
            height: 8px;
        }
        .preview-scroll-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        .preview-scroll-container::-webkit-scrollbar-thumb {
            background: #f59e0b;
            border-radius: 10px;
        }
        .preview-cards-horizontal {
            display: flex;
            gap: 1.5rem;
            min-width: min-content;
        }
        .preview-recipe-card {
            flex: 0 0 320px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            color: inherit;
        }
        .preview-recipe-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .preview-recipe-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
        }
        .preview-recipe-content {
            padding: 1.25rem;
        }
        .preview-recipe-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .preview-recipe-region {
            font-size: 0.875rem;
            color: #f59e0b;
            font-weight: 500;
            margin-bottom: 0.75rem;
        }
        .preview-recipe-meta {
            display: flex;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: #64748b;
            flex-wrap: wrap;
        }
        .preview-meta-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Preview de Técnicas - Grid Compacto */
        .preview-grid-tecnicas {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .preview-tecnica-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .preview-tecnica-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .preview-tecnica-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 1rem;
        }
        .preview-tecnica-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .preview-tecnica-desc {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Preview de Ingredientes - Tags/Badges */
        .preview-tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            margin: 2rem 0;
        }
        .preview-ingredient-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: white;
            border: 2px solid #f59e0b;
            border-radius: 25px;
            color: #1e293b;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .preview-ingredient-tag:hover {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
        .preview-ingredient-count {
            background: #fef3c7;
            color: #92400e;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: bold;
        }
        .preview-ingredient-tag:hover .preview-ingredient-count {
            background: rgba(255,255,255,0.3);
            color: white;
        }

        @media (max-width: 768px) {
            .preview-section { padding: 2rem 0; }
            .preview-recipe-card { flex: 0 0 280px; }
            .preview-grid-tecnicas {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
            }
        }
    </style>
</head>
<body>

<header class="header" id="header">
    <div class="container">
        <div class="header-content">
            <!-- Logo -->
            <div class="logo">
                <i class="fas fa-utensils"></i>
                <div>
                    <h1>FlavorWay</h1>
                    <span>Sabores do Mundo</span>
                </div>
            </div>

            <!-- Navegação -->
            <nav class="nav" id="nav">
                <a href="#home" class="nav-link active">Início</a>
                <a href="receitas.php" class="nav-link">
                    <i class="fas fa-book-open"></i> Receitas
                </a>
                <a href="ingredientes.php" class="nav-link">
                    <i class="fas fa-carrot"></i> Ingredientes
                </a>
                <a href="tecnicas.php" class="nav-link">
                    <i class="fas fa-fire"></i> Técnicas
                </a>
                <a href="lista-compras.php" class="nav-link">
                    <i class="fas fa-shopping-cart"></i> Lista
                </a>
                <a href="adicionar-receita.php" class="nav-link">
                    <i class="fas fa-plus-circle"></i> Adicionar
                </a>
            </nav>

            <!-- Ações (1 única barra de busca) -->
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

        <!-- Barra de Pesquisa ÚNICA -->
        <div class="search-container" id="searchContainer">
            <div class="search-inner">
                <input type="text" placeholder="Buscar culinárias, receitas, ingredientes..." class="search-input" onkeypress="if(event.key==='Enter') search()">
                <button class="search-close" onclick="toggleSearch()" aria-label="Fechar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Hero -->
<section id="home" class="hero">
    <div class="hero-bg"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-fire"></i>
                <span>Mais de 5.000 receitas autênticas</span>
            </div>
            <h1 class="hero-title">
                Descubra os Sabores
                <span class="gradient-text">do Mundo</span>
            </h1>
            <p class="hero-description">
                Explore culinárias tradicionais de todos os continentes. 
                Das receitas brasileiras aos pratos asiáticos, 
                uma jornada gastronômica completa espera por você.
            </p>
            <div class="hero-buttons">
                <a href="#culinarias" class="btn btn-primary">
                    <i class="fas fa-compass"></i>
                    Explorar Culinárias
                    <i class="fas fa-arrow-right"></i>
                </a>
                <button class="btn btn-outline" onclick="scrollToSection('destaque')">
                    <i class="fas fa-star"></i>
                    Ver Destaques
                </button>
            </div>
            <div class="hero-stats">
                <div class="stat-item"><div class="stat-number">50+</div><div class="stat-label">Países</div></div>
                <div class="stat-item"><div class="stat-number">5.000+</div><div class="stat-label">Receitas</div></div>
                <div class="stat-item"><div class="stat-number">100K+</div><div class="stat-label">Usuários</div></div>
                <div class="stat-item"><div class="stat-number">4.9★</div><div class="stat-label">Avaliação</div></div>
            </div>
        </div>
    </div>
    <div class="scroll-indicator" onclick="scrollToSection('culinarias')">
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<!-- ========== NOVAS SEÇÕES DE PREVIEW ========== -->

<!-- Preview: Receitas em Destaque -->
<section class="preview-section receitas-preview">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Featured</div>
            <h2><i class="fas fa-book-open"></i> Receitas em <span class="highlight">Destaque</span></h2>
            <p>Descubra as receitas mais populares e amadas pela nossa comunidade</p>
        </div>
        <div class="preview-scroll-container">
            <div class="preview-cards-horizontal" id="receitasPreview">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i> Carregando...
                </div>
            </div>
        </div>
        <div class="preview-action">
            <a href="receitas.php" class="btn btn-primary">
                <i class="fas fa-arrow-right"></i> Ver Todas as Receitas
            </a>
        </div>
    </div>
</section>

<!-- Preview: Técnicas Culinárias -->
<section class="preview-section tecnicas-preview" style="background: #f8fafc;">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Skills</div>
            <h2><i class="fas fa-fire"></i> Técnicas <span class="highlight">Culinárias</span></h2>
            <p>Aprimore suas habilidades com técnicas essenciais da cozinha profissional</p>
        </div>
        <div class="preview-grid-tecnicas" id="tecnicasPreview">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i> Carregando...
            </div>
        </div>
        <div class="preview-action">
            <a href="tecnicas.php" class="btn btn-outline">
                <i class="fas fa-arrow-right"></i> Ver Todas as Técnicas
            </a>
        </div>
    </div>
</section>

<!-- Preview: Ingredientes Populares -->
<section class="preview-section ingredientes-preview">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Ingredients</div>
            <h2><i class="fas fa-carrot"></i> Ingredientes <span class="highlight">Populares</span></h2>
            <p>Explore os ingredientes mais utilizados nas receitas do FlavorWay</p>
        </div>
        <div class="preview-tags-container" id="ingredientesPreview">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i> Carregando...
            </div>
        </div>
        <div class="preview-action">
            <a href="ingredientes.php" class="btn btn-primary">
                <i class="fas fa-arrow-right"></i> Ver Todos os Ingredientes
            </a>
        </div>
    </div>
</section>

<!-- Culinárias -->
<section id="culinarias" class="culinarias">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Explore</div>
            <h2>Culinárias <span class="highlight">do Mundo</span></h2>
            <p>Viaje pelos sabores mais autênticos de cada região do planeta</p>
        </div>
        <div class="culinarias-grid" id="culinariasGrid">
            Carregado via JavaScript
        </div>
    </div>
</section>

<!-- Destaques -->
<section id="destaque" class="destaques">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Popular</div>
            <h2>Receitas em <span class="highlight">Destaque</span></h2>
            <p>As receitas mais amadas e preparadas pela nossa comunidade</p>
        </div>
        <div class="destaques-grid" id="destaquesGrid">
            Carregado via JavaScript
        </div>
    </div>
</section>

<!-- Seção Explore -->
<section class="explore-section" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); padding: 4rem 0;">
    <div class="container">
        <div class="section-header" style="color: white;">
            <div class="section-tag" style="background: rgba(255,255,255,0.2); color: white;">Explore</div>
            <h2 style="color: white;">Descubra o <span style="color: #fef3c7;">FlavorWay</span></h2>
            <p style="color: rgba(255,255,255,0.9);">Acesse nosso catálogo completo de conteúdo culinário</p>
        </div>
        <div class="recursos-grid" style="margin-top: 2rem;">
            <a href="receitas.php" class="recurso-card" style="text-decoration: none; cursor: pointer; transition: transform 0.3s;">
                <div class="recurso-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);"><i class="fas fa-book-open"></i></div>
                <h3>Todas as Receitas</h3>
                <p>Explore nosso catálogo completo de receitas brasileiras e internacionais</p>
                <div style="margin-top: 1rem; color: #f59e0b; font-weight: 600;">
                    Ver Receitas <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            <a href="ingredientes.php" class="recurso-card" style="text-decoration: none; cursor: pointer; transition: transform 0.3s;">
                <div class="recurso-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);"><i class="fas fa-carrot"></i></div>
                <h3>Ingredientes</h3>
                <p>Conheça todos os ingredientes utilizados nas receitas e suas categorias</p>
                <div style="margin-top: 1rem; color: #f59e0b; font-weight: 600;">
                    Ver Ingredientes <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            <a href="tecnicas.php" class="recurso-card" style="text-decoration: none; cursor: pointer; transition: transform 0.3s;">
                <div class="recurso-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);"><i class="fas fa-fire"></i></div>
                <h3>Técnicas Culinárias</h3>
                <p>Aprenda técnicas essenciais para aprimorar suas habilidades na cozinha</p>
                <div style="margin-top: 1rem; color: #f59e0b; font-weight: 600;">
                    Ver Técnicas <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            <a href="lista-compras.php" class="recurso-card" style="text-decoration: none; cursor: pointer; transition: transform 0.3s;">
                <div class="recurso-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);"><i class="fas fa-shopping-cart"></i></div>
                <h3>Lista de Compras</h3>
                <p>Gerencie seus ingredientes e organize suas compras de forma prática</p>
                <div style="margin-top: 1rem; color: #f59e0b; font-weight: 600;">
                    Ver Lista <i class="fas fa-arrow-right"></i>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Recursos -->
<section class="recursos">
    <div class="container">
        <div class="section-header">
            <div class="section-tag">Recursos</div>
            <h2>Por que escolher <span class="highlight">FlavorWay</span></h2>
            <p>Tudo o que você precisa para se tornar um chef internacional</p>
        </div>
        <div class="recursos-grid">
            <div class="recurso-card">
                <div class="recurso-icon"><i class="fas fa-book-open"></i></div>
                <h3>Receitas Autênticas</h3>
                <p>Mais de 5.000 receitas tradicionais coletadas diretamente das regiões de origem</p>
            </div>
            <div class="recurso-card">
                <div class="recurso-icon"><i class="fas fa-video"></i></div>
                <h3>Vídeos Passo a Passo</h3>
                <p>Tutoriais detalhados em vídeo para você aprender cada técnica com facilidade</p>
            </div>
            <div class="recurso-card">
                <div class="recurso-icon"><i class="fas fa-users"></i></div>
                <h3>Comunidade Ativa</h3>
                <p>Conecte-se com chefs e entusiastas da culinária de todo o mundo</p>
            </div>
            <div class="recurso-card">
                <div class="recurso-icon"><i class="fas fa-mobile-alt"></i></div>
                <h3>Acesso Multiplataforma</h3>
                <p>Disponível em web, iOS e Android. Cozinhe onde e quando quiser</p>
            </div>
            <div class="recurso-card">
                <div class="recurso

-card">
                <div class="recurso-icon"><i class="fas fa-bookmark"></i></div>
                <h3>Favoritos e Listas</h3>
                <p>Salve suas receitas preferidas e crie listas personalizadas</p>
            </div>
            <div class="recurso-card">
                <div class="recurso-icon"><i class="fas fa-shopping-cart"></i></div>
                <h3>Lista de Compras</h3>
                <p>Gere automaticamente listas de compras baseadas nas suas receitas</p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section id="contato" class="newsletter">
    <div class="container">
        <div class="newsletter-content">
            <div class="newsletter-info">
                <h2>Receba Receitas Exclusivas</h2>
                <p>Cadastre-se e receba semanalmente receitas especiais e dicas culinárias</p>
                <ul class="newsletter-benefits">
                    <li><i class="fas fa-check"></i> Receitas exclusivas toda semana</li>
                    <li><i class="fas fa-check"></i> Dicas de chefs profissionais</li>
                    <li><i class="fas fa-check"></i> Acesso antecipado a novos conteúdos</li>
                </ul>
            </div>
            <div class="newsletter-form">
                <form onsubmit="submitNewsletter(event)">
                    <div class="form-group"><input type="text" placeholder="Seu nome" required></div>
                    <div class="form-group"><input type="email" placeholder="Seu melhor e-mail" required></div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Quero Receber
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
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
                    Sua jornada gastronômica começa aqui. Explore culinárias autênticas de todos os continentes.
                </p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h4>Culinárias</h4>
                <ul>
                    <li><a href="culinaria-brasileira.php">Brasileira</a></li>
                    <li><a href="#">Italiana</a></li>
                    <li><a href="#">Japonesa</a></li>
                    <li><a href="#">Francesa</a></li>
                    <li><a href="#">Mexicana</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Recursos</h4>
                <ul>
                    <li><a href="#">Todas as Receitas</a></li>
                    <li><a href="#">Vídeos</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Comunidade</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Empresa</h4>
                <ul>
                    <li><a href="#">Sobre Nós</a></li>
                    <li><a href="#">Contato</a></li>
                    <li><a href="#">Trabalhe Conosco</a></li>
                    <li><a href="#">Termos de Uso</a></li>
                    <li><a href="#">Privacidade</a></li>
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
// Funções JS embutidas (sem arquivo externo)
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
    if (query && query.length >= 2) {
        window.location.href = `buscar.php?q=${encodeURIComponent(query)}`;
    } else if (query.length < 2) {
        alert('Digite pelo menos 2 caracteres para buscar');
    }
}

function submitNewsletter(e) {
    e.preventDefault();
    alert('Inscrito com sucesso! (simulação)');
}

// ========== CARREGA SEÇÕES DE PREVIEW ==========
document.addEventListener('DOMContentLoaded', () => {
    carregarReceitasPreview();
    carregarTecnicasPreview();
    carregarIngredientesPreview();
});

// Carrega preview de receitas
async function carregarReceitasPreview() {
    try {
        const response = await fetch('../api/get-receitas-destaque.php');
        const data = await response.json();

        if (data.success && data.receitas) {
            const receitas = data.receitas.slice(0, 8); // Limita a 8 receitas
            const container = document.getElementById('receitasPreview');

            if (receitas.length === 0) {
                container.innerHTML = '<p style="text-align:center;color:#64748b;">Nenhuma receita disponível</p>';
                return;
            }

            container.innerHTML = receitas.map(receita => `
                <a href="receita.php?id=${receita.id}" class="preview-recipe-card">
                    <img src="${receita.image}" class="preview-recipe-image" alt="${receita.nome}">
                    <div class="preview-recipe-content">
                        <div class="preview-recipe-title">${receita.nome}</div>
                        <div class="preview-recipe-region">
                            <i class="fas fa-map-marker-alt"></i> ${receita.culinaria || 'Brasil'}
                        </div>
                        <div class="preview-recipe-meta">
                            <span class="preview-meta-item">
                                <i class="fas fa-clock"></i> ${receita.tempo || '30min'}
                            </span>
                            <span class="preview-meta-item">
                                <i class="fas fa-star"></i> ${receita.rating || '4.5'}
                            </span>
                            ${receita.dificuldade ? `<span class="preview-meta-item"><i class="fas fa-signal"></i> ${receita.dificuldade}</span>` : ''}
                        </div>
                    </div>
                </a>
            `).join('');
        }
    } catch (error) {
        console.error('Erro ao carregar receitas preview:', error);
        document.getElementById('receitasPreview').innerHTML =
            '<p style="text-align:center;color:#ef4444;">Erro ao carregar receitas</p>';
    }
}

// Carrega preview de técnicas
async function carregarTecnicasPreview() {
    try {
        const response = await fetch('../api/get-tecnicas.php');
        const data = await response.json();

        if (data.success && data.tecnicas) {
            const tecnicas = data.tecnicas.slice(0, 6); // Limita a 6 técnicas
            const container = document.getElementById('tecnicasPreview');

            if (tecnicas.length === 0) {
                container.innerHTML = '<p style="text-align:center;color:#64748b;">Nenhuma técnica disponível</p>';
                return;
            }

            // Ícones para técnicas
            const icones = {
                'Fritura': 'fa-fire',
                'Assar': 'fa-temperature-high',
                'Cozinhar': 'fa-pot',
                'Grelhar': 'fa-utensils',
                'Refogar': 'fa-pepper-hot',
                'Corte': 'fa-cut',
                'default': 'fa-utensils'
            };

            container.innerHTML = tecnicas.map(tecnica => {
                const icone = icones[tecnica.nome] || icones['default'];
                const descricao = tecnica.descricao || 'Técnica culinária essencial para diversos pratos';

                return `
                    <a href="tecnica.php?id=${tecnica.id}" class="preview-tecnica-card">
                        <div class="preview-tecnica-icon">
                            <i class="fas ${icone}"></i>
                        </div>
                        <div class="preview-tecnica-title">${tecnica.nome}</div>
                        <div class="preview-tecnica-desc">${descricao}</div>
                    </a>
                `;
            }).join('');
        }
    } catch (error) {
        console.error('Erro ao carregar técnicas preview:', error);
        document.getElementById('tecnicasPreview').innerHTML =
            '<p style="text-align:center;color:#ef4444;">Erro ao carregar técnicas</p>';
    }
}

// Carrega preview de ingredientes
async function carregarIngredientesPreview() {
    try {
        const response = await fetch('../api/get-ingredientes.php');
        const data = await response.json();

        if (data.success && data.ingredientes) {
            // Ordena por quantidade de uso e pega os 12 mais populares
            const ingredientes = data.ingredientes
                .sort((a, b) => (b.count || 0) - (a.count || 0))
                .slice(0, 12);

            const container = document.getElementById('ingredientesPreview');

            if (ingredientes.length === 0) {
                container.innerHTML = '<p style="text-align:center;color:#64748b;">Nenhum ingrediente disponível</p>';
                return;
            }

            container.innerHTML = ingredientes.map(ingrediente => `
                <a href="ingrediente.php?nome=${encodeURIComponent(ingrediente.nome)}" class="preview-ingredient-tag">
                    <i class="fas fa-carrot"></i>
                    <span>${ingrediente.nome}</span>
                    ${ingrediente.count ? `<span class="preview-ingredient-count">${ingrediente.count}</span>` : ''}
                </a>
            `).join('');
        }
    } catch (error) {
        console.error('Erro ao carregar ingredientes preview:', error);
        document.getElementById('ingredientesPreview').innerHTML =
            '<p style="text-align:center;color:#ef4444;">Erro ao carregar ingredientes</p>';
    }
}
</script>

</body>
</html>