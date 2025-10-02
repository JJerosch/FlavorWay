<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlavorWay - Explore a Culinária Mundial</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/homestyles.css">
</head>
<body> 
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
                    <a href="#home" class="nav-link active">Início</a>
                    <a href="#culinarias" class="nav-link">Culinárias</a>
                    <a href="#destaque" class="nav-link">Destaques</a>
                    <a href="#contato" class="nav-link">Contato</a>
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
                <input type="text" placeholder="Buscar culinárias, receitas..." class="search-input">
                <button class="search-close" onclick="toggleSearch()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </header>

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
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Países</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">5.000+</div>
                        <div class="stat-label">Receitas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100K+</div>
                        <div class="stat-label">Usuários</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">4.9★</div>
                        <div class="stat-label">Avaliação</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="scroll-indicator" onclick="scrollToSection('culinarias')">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

     Culinárias do Mundo 
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

    <section class="recursos">
        <div class="container">
            <div class="section-header">
                <div class="section-tag">Recursos</div>
                <h2>Por que escolher <span class="highlight">FlavorWay</span></h2>
                <p>Tudo o que você precisa para se tornar um chef internacional</p>
            </div>
            
            <div class="recursos-grid">
                <div class="recurso-card">
                    <div class="recurso-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>Receitas Autênticas</h3>
                    <p>Mais de 5.000 receitas tradicionais coletadas diretamente das regiões de origem</p>
                </div>
                
                <div class="recurso-card">
                    <div class="recurso-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3>Vídeos Passo a Passo</h3>
                    <p>Tutoriais detalhados em vídeo para você aprender cada técnica com facilidade</p>
                </div>
                
                <div class="recurso-card">
                    <div class="recurso-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Comunidade Ativa</h3>
                    <p>Conecte-se com chefs e entusiastas da culinária de todo o mundo</p>
                </div>
                
                <div class="recurso-card">
                    <div class="recurso-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Acesso Multiplataforma</h3>
                    <p>Disponível em web, iOS e Android. Cozinhe onde e quando quiser</p>
                </div>
                
                <div class="recurso-card">
                    <div class="recurso-icon">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <h3>Favoritos e Listas</h3>
                    <p>Salve suas receitas preferidas e crie listas personalizadas</p>
                </div>
                
                <div class="recurso-card">
                    <div class="recurso-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3>Lista de Compras</h3>
                    <p>Gere automaticamente listas de compras baseadas nas suas receitas</p>
                </div>
            </div>
        </div>
    </section>
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
                        <div class="form-group">
                            <input type="text" placeholder="Seu nome" required>
                        </div>
                        <div class="form-group">
                            <input type="email" placeholder="Seu melhor e-mail" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Quero Receber
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
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
                        Sua jornada gastronômica começa aqui. 
                        Explore culinárias autênticas de todos os continentes.
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

    <script src="../assets/js/home-main.js"></script>
</body>
</html>
