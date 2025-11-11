<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-title">Região - FlavorWay</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/public.css/homestyles.css">
    <style>
        :root {
            --primary: #ea580c;
            --secondary: #eab308;
            --accent: #dc2626;
            --dark: #1f2937;
            --gray: #6b7280;
            --light: #f9fafb;
            --white: #ffffff;
        }

        /* Hero Section */
        .region-hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            padding: 120px 20px 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .region-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            opacity: 0.2;
        }

        .region-hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .region-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 15px;
            font-weight: 900;
        }

        .region-hero .subtitle {
            font-size: 1.3rem;
            opacity: 0.95;
            margin-bottom: 30px;
        }

        .region-stats {
            display: flex;
            gap: 40px;
            justify-content: center;
            margin-top: 30px;
        }

        .region-stat {
            text-align: center;
        }

        .region-stat .number {
            font-size: 2.5rem;
            font-weight: bold;
            display: block;
        }

        .region-stat .label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Region Info Section */
        .region-info {
            padding: 60px 20px;
            background: white;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .info-card {
            background: var(--light);
            padding: 30px;
            border-radius: 15px;
            border-left: 4px solid var(--primary);
        }

        .info-card h3 {
            color: var(--primary);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card h3 i {
            font-size: 1.5rem;
        }

        .culture-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .culture-item h4 {
            color: var(--dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .culture-badge {
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 12px;
            background: var(--primary);
            color: white;
            font-weight: 500;
        }

        /* Recipes Section */
        .recipes-section {
            padding: 80px 20px;
            background: var(--light);
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-header h2 {
            font-size: 2.5rem;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .section-header p {
            color: var(--gray);
            font-size: 1.1rem;
        }

        .recipes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .recipe-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .recipe-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .recipe-image {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: white;
            position: relative;
        }

        .recipe-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: white;
            color: var(--primary);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .recipe-content {
            padding: 25px;
        }

        .recipe-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .recipe-description {
            color: var(--gray);
            font-size: 0.95rem;
            margin-bottom: 15px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .recipe-meta {
            display: flex;
            gap: 20px;
            padding-top: 15px;
            border-top: 1px solid var(--light);
            font-size: 0.9rem;
        }

        .recipe-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--gray);
        }

        .recipe-meta-item i {
            color: var(--primary);
        }

        .recipe-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--secondary);
            font-weight: 600;
        }

        .recipe-difficulty {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
            background: var(--light);
            color: var(--primary);
        }

        /* Loading States */
        .loading {
            text-align: center;
            padding: 60px 20px;
        }

        .loading i {
            font-size: 3rem;
            color: var(--primary);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .error-message {
            text-align: center;
            padding: 60px 20px;
            color: var(--accent);
        }

        .error-message i {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .region-hero h1 {
                font-size: 2.5rem;
            }

            .region-stats {
                flex-direction: column;
                gap: 20px;
            }

            .recipes-grid {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">
                    <i class="fas fa-utensils"></i>
                    <div>
                        <h1>FlavorWay</h1>
                        <span>Culinária Brasileira</span>
                    </div>
                </a>
                <nav class="nav">
                    <a href="index.php" class="nav-link">Início</a>
                    <a href="regioes.php" class="nav-link">Regiões</a>
                    <a href="receitas.php" class="nav-link">Receitas</a>
                    <a href="sobre.php" class="nav-link">Sobre</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="region-hero" id="region-hero">
        <div class="region-hero-content">
            <h1 id="region-name">Carregando...</h1>
            <p class="subtitle" id="region-description">Carregando informações da região...</p>
            <div class="region-stats">
                <div class="region-stat">
                    <span class="number" id="total-receitas">-</span>
                    <span class="label">Receitas</span>
                </div>
                <div class="region-stat">
                    <span class="number" id="total-estados">-</span>
                    <span class="label">Estados</span>
                </div>
                <div class="region-stat">
                    <span class="number" id="total-cultura">-</span>
                    <span class="label">Tradições</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Region Info -->
    <section class="region-info">
        <div class="container">
            <div class="info-grid" id="info-grid">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Recipes Section -->
    <section class="recipes-section">
        <div class="container">
            <div class="section-header">
                <h2>Receitas da Região</h2>
                <p>Descubra os sabores autênticos desta região</p>
            </div>
            <div class="recipes-grid" id="recipes-grid">
                <div class="loading">
                    <i class="fas fa-spinner"></i>
                    <p>Carregando receitas...</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Get region slug from URL
        const urlParams = new URLSearchParams(window.location.search);
        const regionSlug = urlParams.get('regiao') || urlParams.get('slug');

        if (!regionSlug) {
            window.location.href = 'index.php';
        }

        // Fetch region data
        async function loadRegionData() {
            try {
                const response = await fetch(`api/regions.php?slug=${regionSlug}`);
                if (!response.ok) {
                    throw new Error('Região não encontrada');
                }

                const region = await response.json();

                // Update page title and hero
                document.getElementById('page-title').textContent = `${region.nome} - FlavorWay`;
                document.getElementById('region-name').textContent = region.nome;
                document.getElementById('region-description').textContent = region.descricao;
                document.getElementById('total-receitas').textContent = region.total_receitas;
                document.getElementById('total-estados').textContent = region.estados?.length || 0;
                document.getElementById('total-cultura').textContent = region.cultura?.length || 0;

                // Populate info cards
                populateInfoCards(region);

                // Load recipes
                loadRecipes(regionSlug);

            } catch (error) {
                console.error('Error loading region:', error);
                document.getElementById('recipes-grid').innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <h3>Erro ao carregar região</h3>
                        <p>${error.message}</p>
                        <a href="index.php" class="btn">Voltar ao Início</a>
                    </div>
                `;
            }
        }

        function populateInfoCards(region) {
            const infoGrid = document.getElementById('info-grid');
            let html = '';

            // Estados card
            if (region.estados && region.estados.length > 0) {
                html += `
                    <div class="info-card">
                        <h3><i class="fas fa-map-marked-alt"></i> Estados</h3>
                        ${region.estados.map(estado => `
                            <div class="culture-item">
                                <h4>${estado.nome}</h4>
                                <p style="font-size: 0.9rem; color: var(--gray); margin-bottom: 5px;">
                                    ${estado.descricao || ''}
                                </p>
                                ${estado.ingrediente_destaque ? `
                                    <p style="font-size: 0.85rem; color: var(--primary);">
                                        <i class="fas fa-star"></i> Destaque: ${estado.ingrediente_destaque}
                                    </p>
                                ` : ''}
                            </div>
                        `).join('')}
                    </div>
                `;
            }

            // Cultura card
            if (region.cultura && region.cultura.length > 0) {
                html += `
                    <div class="info-card">
                        <h3><i class="fas fa-book"></i> Cultura & Tradição</h3>
                        ${region.cultura.map(item => `
                            <div class="culture-item">
                                <h4>
                                    ${item.icon || '📖'} ${item.titulo}
                                    <span class="culture-badge">${item.tipo}</span>
                                </h4>
                                <p style="font-size: 0.9rem; color: var(--gray);">${item.descricao || ''}</p>
                            </div>
                        `).join('')}
                    </div>
                `;
            }

            // Técnicas card
            if (region.tecnicas && region.tecnicas.length > 0) {
                html += `
                    <div class="info-card">
                        <h3><i class="fas fa-fire"></i> Técnicas Culinárias</h3>
                        ${region.tecnicas.map(tecnica => `
                            <div class="culture-item">
                                <h4>${tecnica.nome}</h4>
                                <p style="font-size: 0.9rem; color: var(--gray); margin-bottom: 5px;">
                                    ${tecnica.descricao}
                                </p>
                                <span class="culture-badge">${tecnica.dificuldades_tecnica}</span>
                            </div>
                        `).join('')}
                    </div>
                `;
            }

            infoGrid.innerHTML = html;
        }

        async function loadRecipes(slug) {
            try {
                const response = await fetch(`api/recipes.php?regiao_slug=${slug}`);
                if (!response.ok) {
                    throw new Error('Erro ao carregar receitas');
                }

                const recipes = await response.json();
                displayRecipes(recipes);

            } catch (error) {
                console.error('Error loading recipes:', error);
                document.getElementById('recipes-grid').innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <h3>Erro ao carregar receitas</h3>
                        <p>${error.message}</p>
                    </div>
                `;
            }
        }

        function displayRecipes(recipes) {
            const grid = document.getElementById('recipes-grid');

            if (recipes.length === 0) {
                grid.innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-inbox"></i>
                        <h3>Nenhuma receita encontrada</h3>
                        <p>Em breve teremos receitas desta região!</p>
                    </div>
                `;
                return;
            }

            grid.innerHTML = recipes.map(recipe => `
                <a href="recipe.php?id=${recipe.id}" class="recipe-card">
                    <div class="recipe-image">
                        <i class="fas fa-utensils"></i>
                        ${recipe.badge ? `<span class="recipe-badge">${recipe.badge}</span>` : ''}
                    </div>
                    <div class="recipe-content">
                        <h3 class="recipe-title">${recipe.nome}</h3>
                        <p class="recipe-description">${recipe.descricao}</p>
                        <div class="recipe-meta">
                            <div class="recipe-meta-item">
                                <i class="fas fa-clock"></i>
                                <span>${recipe.tempo_preparo}</span>
                            </div>
                            <div class="recipe-meta-item">
                                <i class="fas fa-users"></i>
                                <span>${recipe.pessoas}</span>
                            </div>
                            <div class="recipe-rating">
                                <i class="fas fa-star"></i>
                                <span>${recipe.media_avaliacoes || recipe.rating}</span>
                            </div>
                        </div>
                        <div style="margin-top: 10px;">
                            <span class="recipe-difficulty">${recipe.dificuldade}</span>
                        </div>
                    </div>
                </a>
            `).join('');
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', loadRegionData);
    </script>
</body>
</html>
