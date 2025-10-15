<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Culinária Brasileira - FlavorWay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/public.css/homestyles.css">
    <link rel="stylesheet" href="../assets/css/public.css/culinariabrasileira.css">
</head>
<body class="culinaria-page">
    <header class="header brasil-header" id="header">
        <div class="container">
            <div class="header-content">
                
                <div class="logo">
                    <i class="fas fa-utensils"></i>
                    <div>
                        <h1>FlavorWay</h1>
                        <span>Culinária Brasileira</span>
                    </div>
                </div>
                
                <nav class="nav" id="nav">
                    <a href="index.php" class="nav-link">
                        <i class="fas fa-home"></i> Início
                    </a>
                    <a href="#regioes" class="nav-link">Regiões</a>
                    <a href="#receitas" class="nav-link">Receitas</a>
                    <a href="#ingredientes" class="nav-link">Ingredientes</a>
                    <a href="#cultura" class="nav-link">Cultura</a>
                </nav>
                
                <div class="header-actions">
                    <button class="search-btn" onclick="toggleSearch()">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="menu-toggle" onclick="toggleMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
            <div class="search-container" id="searchContainer">
                <input type="text" placeholder="Buscar receitas, regiões..." class="search-input">
                <button class="search-close" onclick="toggleSearch()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </header>
    <section class="hero-brasil">
        <div class="hero-bg-brasil"></div>
        <div class="container">
            <div class="hero-content">
                
                <h1 class="hero-title">
                    Sabores do
                    <span class="gradient-text-brasil">Brasil</span>
                </h1>
                
                <p class="hero-subtitle">
                    Uma viagem gastronômica pelas cinco regiões brasileiras
                </p>
                
                <p class="hero-description">
                    Do acarajé baiano ao churrasco gaúcho, do tacacá paraense à feijoada carioca. 
                    Descubra a riqueza e diversidade da culinária que é patrimônio do nosso país.
                </p>
                
                <div class="hero-buttons">
                    <button class="btn btn-primary" onclick="scrollToSection('regioes')">
                        <i class="fas fa-map-marked-alt"></i>
                        Explorar Regiões
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <button class="btn btn-outline-white" onclick="scrollToSection('receitas')">
                        <i class="fas fa-book-open"></i>
                        Ver Receitas
                    </button>
                </div>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">5</div>
                        <div class="stat-label">Regiões</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">27</div>
                        <div class="stat-label">Estados</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Receitas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Ingredientes</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="scroll-indicator" onclick="scrollToSection('regioes')">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>
    <section id="regioes" class="regioes-brasil">
        <div class="container">
            <div class="section-header">
                <div class="section-tag brasil">Explore</div>
                <h2>Regiões <span class="highlight-brasil">Brasileiras</span></h2>
                <p>Conheça as características únicas de cada região gastronômica do Brasil</p>
            </div>
            
            <div class="regioes-grid" id="regioesGrid">
                 Carregado via JavaScript 
            </div>
        </div>
    </section>
    <section id="receitas" class="receitas-populares">
        <div class="container">
            <div class="section-header">
                <div class="section-tag brasil">Receitas</div>
                <h2>Pratos <span class="highlight-brasil">Tradicionais</span></h2>
                <p>As receitas mais amadas e preparadas em todo o Brasil</p>
            </div>
            <div class="filtros-receitas">
                <button class="filtro-btn active" data-regiao="todas">Todas as Regiões</button>
                <button class="filtro-btn" data-regiao="nordeste">Nordeste</button>
                <button class="filtro-btn" data-regiao="sudeste">Sudeste</button>
                <button class="filtro-btn" data-regiao="sul">Sul</button>
                <button class="filtro-btn" data-regiao="norte">Norte</button>
                <button class="filtro-btn" data-regiao="centro-oeste">Centro-Oeste</button>
            </div>
            
            <div class="receitas-grid" id="receitasGrid">
                 Carregado via JavaScript 
            </div>
        </div>
    </section>
    <section id="ingredientes" class="ingredientes-tipicos">
        <div class="container">
            <div class="section-header">
                <div class="section-tag brasil">Ingredientes</div>
                <h2>Sabores <span class="highlight-brasil">Autênticos</span></h2>
                <p>Ingredientes que definem a identidade gastronômica brasileira</p>
            </div>
            
            <div class="ingredientes-grid" id="ingredientesGrid">
                 Carregado via JavaScript 
            </div>
        </div>
    </section>
    <section id="cultura" class="cultura-gastronomica">
        <div class="container">
            <div class="section-header">
                <div class="section-tag brasil">História</div>
                <h2>Cultura <span class="highlight-brasil">Gastronômica</span></h2>
                <p>A rica história que moldou nossa culinária</p>
            </div>
            
            <div class="cultura-grid">
                <div class="cultura-card">
                    <div class="cultura-icon">🏛️</div>
                    <h3>Herança Indígena</h3>
                    <p>
                        Os povos originários nos presentearam com mandioca, milho, frutas tropicais 
                        e técnicas ancestrais de preparo que são base da nossa culinária.
                    </p>
                </div>
                
                <div class="cultura-card">
                    <div class="cultura-icon">⛵</div>
                    <h3>Influência Portuguesa</h3>
                    <p>
                        A colonização trouxe temperos, técnicas de conservação e receitas que se 
                        misturaram aos ingredientes locais criando novos sabores.
                    </p>
                </div>
                
                <div class="cultura-card">
                    <div class="cultura-icon">🌍</div>
                    <h3>Contribuição Africana</h3>
                    <p>
                        O dendê, o azeite de palma, e pratos emblemáticos como acarajé e vatapá 
                        são herança direta da rica cultura africana.
                    </p>
                </div>
                
                <div class="cultura-card">
                    <div class="cultura-icon">🌎</div>
                    <h3>Imigração Europeia</h3>
                    <p>
                        Italianos, alemães, poloneses e outros imigrantes trouxeram suas 
                        tradições que enriqueceram ainda mais nossa gastronomia.
                    </p>
                </div>
                
                <div class="cultura-card">
                    <div class="cultura-icon">🌏</div>
                    <h3>Influência Asiática</h3>
                    <p>
                        A imigração japonesa e de outros povos asiáticos trouxe novos ingredientes 
                        e técnicas que se integraram à nossa culinária.
                    </p>
                </div>
                
                <div class="cultura-card">
                    <div class="cultura-icon">🎭</div>
                    <h3>Fusão Contemporânea</h3>
                    <p>
                        Hoje, chefs brasileiros reinventam tradições, criando uma nova 
                        gastronomia que respeita as raízes e inova com orgulho.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="cta-brasil">
        <div class="container">
            <div class="cta-content">
                <h2>Pronto para Cozinhar?</h2>
                <p>Escolha uma região e comece sua jornada gastronômica pelo Brasil</p>
                <div class="cta-buttons">
                    <button class="btn btn-primary" onclick="scrollToSection('regioes')">
                        <i class="fas fa-map-marked-alt"></i>
                        Explorar Regiões
                    </button>
                    <a href="index.php" class="btn btn-outline-white">
                        <i class="fas fa-globe"></i>
                        Ver Outras Culinárias
                    </a>
                </div>
            </div>
        </div>
    </section>
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
                        Das praias do Nordeste aos pampas do Sul.
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
                    <h4>Pratos Típicos</h4>
                    <ul id="footerPratos">
                        <li><a href="#">Feijoada</a></li>
                        <li><a href="#">Acarajé</a></li>
                        <li><a href="#">Pão de Queijo</a></li>
                        <li><a href="#">Churrasco</a></li>
                        <li><a href="#">Tacacá</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>FlavorWay</h4>
                    <ul>
                        <li><a href="index.php">Home Principal</a></li>
                        <li><a href="#">Todas as Culinárias</a></li>
                        <li><a href="#">Blog</a></li>
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

    <script src="../assets/js/public.js/culinaria-brasileira.js"></script>
</body>
</html>
