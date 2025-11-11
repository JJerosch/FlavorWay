<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-title">Receita - FlavorWay</title>
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

        body {
            padding-top: 80px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Recipe Header */
        .recipe-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
            position: relative;
        }

        .recipe-title {
            font-size: 3rem;
            margin-bottom: 15px;
            font-weight: 900;
        }

        .recipe-subtitle {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        .recipe-badges {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
        }

        .recipe-actions {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: white;
            color: var(--primary);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-outline:hover {
            background: white;
            color: var(--primary);
        }

        .btn-favorite {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-favorite.favorited {
            background: white;
            color: var(--accent);
        }

        /* Recipe Content */
        .recipe-content {
            padding: 60px 20px;
        }

        .recipe-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }

        .recipe-main {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .recipe-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .sidebar-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar-card h3 {
            color: var(--dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--light);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--gray);
            font-weight: 500;
        }

        .info-value {
            color: var(--dark);
            font-weight: 600;
        }

        /* Ingredients */
        .ingredients-list {
            list-style: none;
            padding: 0;
        }

        .ingredient-category {
            margin-top: 25px;
        }

        .ingredient-category:first-child {
            margin-top: 0;
        }

        .ingredient-category h4 {
            color: var(--primary);
            font-size: 1.1rem;
            margin-bottom: 12px;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .ingredient-item {
            padding: 10px 15px;
            background: var(--light);
            margin-bottom: 8px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ingredient-item i {
            color: var(--primary);
            font-size: 0.9rem;
        }

        /* Nutrition */
        .nutrition-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .nutrition-item {
            background: var(--light);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }

        .nutrition-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary);
            display: block;
        }

        .nutrition-label {
            color: var(--gray);
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* Tags */
        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .tag {
            padding: 6px 14px;
            background: var(--light);
            color: var(--dark);
            border-radius: 20px;
            font-size: 0.85rem;
            border: 1px solid #e5e7eb;
        }

        /* Ratings Section */
        .ratings-section {
            background: var(--light);
            padding: 60px 20px;
        }

        .section-title {
            font-size: 2rem;
            margin-bottom: 30px;
            text-align: center;
            color: var(--dark);
        }

        .ratings-summary {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .rating-overview {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 40px;
            align-items: center;
        }

        .rating-score {
            text-align: center;
        }

        .rating-number {
            font-size: 4rem;
            font-weight: 900;
            color: var(--primary);
            line-height: 1;
        }

        .rating-stars {
            color: var(--secondary);
            font-size: 1.5rem;
            margin: 10px 0;
        }

        .rating-count {
            color: var(--gray);
        }

        .rating-bars {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .rating-bar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bar-label {
            min-width: 60px;
            font-size: 0.9rem;
            color: var(--gray);
        }

        .bar-fill-container {
            flex: 1;
            height: 8px;
            background: var(--light);
            border-radius: 10px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            background: var(--secondary);
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .bar-count {
            min-width: 40px;
            text-align: right;
            font-size: 0.9rem;
            color: var(--gray);
        }

        /* Add Rating Form */
        .add-rating-form {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .star-rating {
            display: flex;
            gap: 10px;
            font-size: 2rem;
            margin: 20px 0;
        }

        .star {
            color: #d1d5db;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star:hover,
        .star.active {
            color: var(--secondary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            font-family: inherit;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        /* Reviews List */
        .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .review-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .review-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .author-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .author-info h4 {
            margin: 0;
            color: var(--dark);
        }

        .review-date {
            color: var(--gray);
            font-size: 0.85rem;
        }

        .review-rating {
            color: var(--secondary);
        }

        .review-comment {
            color: var(--dark);
            line-height: 1.6;
        }

        /* Loading & Messages */
        .loading {
            text-align: center;
            padding: 40px;
            color: var(--gray);
        }

        .loading i {
            font-size: 2rem;
            color: var(--primary);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .message {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .message-success {
            background: #d1fae5;
            color: #065f46;
        }

        .message-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .message-info {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .recipe-grid {
                grid-template-columns: 1fr;
            }

            .rating-overview {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .recipe-title {
                font-size: 2rem;
            }

            .nutrition-grid {
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
                    <?php if ($isLoggedIn): ?>
                        <a href="perfil.php" class="nav-link">Perfil</a>
                        <a href="logout.php" class="nav-link">Sair</a>
                    <?php else: ?>
                        <a href="login.php" class="nav-link">Entrar</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- Recipe Header -->
    <section class="recipe-header" id="recipe-header">
        <div class="container">
            <h1 class="recipe-title" id="recipe-title">Carregando...</h1>
            <p class="recipe-subtitle" id="recipe-subtitle"></p>
            <div class="recipe-badges" id="recipe-badges"></div>
            <div class="recipe-actions">
                <?php if ($isLoggedIn): ?>
                    <button class="btn btn-favorite" id="btn-favorite">
                        <i class="far fa-heart"></i>
                        <span>Favoritar</span>
                    </button>
                <?php endif; ?>
                <a href="#" class="btn btn-outline" id="btn-region">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Ver Região</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Recipe Content -->
    <section class="recipe-content">
        <div class="container">
            <div class="recipe-grid">
                <!-- Main Content -->
                <div class="recipe-main">
                    <h2><i class="fas fa-list-ul"></i> Ingredientes</h2>
                    <div id="ingredients-container" class="loading">
                        <i class="fas fa-spinner"></i>
                        <p>Carregando ingredientes...</p>
                    </div>

                    <div style="margin-top: 40px;" id="tags-section">
                        <h2><i class="fas fa-tags"></i> Tags</h2>
                        <div class="tags-container" id="tags-container"></div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="recipe-sidebar">
                    <!-- Recipe Info -->
                    <div class="sidebar-card">
                        <h3><i class="fas fa-info-circle"></i> Informações</h3>
                        <div id="recipe-info"></div>
                    </div>

                    <!-- Nutrition Info -->
                    <div class="sidebar-card" id="nutrition-card">
                        <h3><i class="fas fa-heartbeat"></i> Informação Nutricional</h3>
                        <div class="nutrition-grid" id="nutrition-info"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ratings Section -->
    <section class="ratings-section">
        <div class="container">
            <h2 class="section-title">Avaliações e Comentários</h2>

            <!-- Ratings Summary -->
            <div class="ratings-summary" id="ratings-summary">
                <div class="loading">
                    <i class="fas fa-spinner"></i>
                    <p>Carregando avaliações...</p>
                </div>
            </div>

            <!-- Add Rating Form -->
            <?php if ($isLoggedIn): ?>
            <div class="add-rating-form">
                <h3>Deixe sua avaliação</h3>
                <div id="rating-message"></div>
                <form id="rating-form">
                    <div class="form-group">
                        <label>Sua nota:</label>
                        <div class="star-rating" id="star-rating">
                            <i class="fas fa-star star" data-rating="1"></i>
                            <i class="fas fa-star star" data-rating="2"></i>
                            <i class="fas fa-star star" data-rating="3"></i>
                            <i class="fas fa-star star" data-rating="4"></i>
                            <i class="fas fa-star star" data-rating="5"></i>
                        </div>
                        <input type="hidden" id="rating-value" name="nota" required>
                    </div>
                    <div class="form-group">
                        <label for="comentario">Seu comentário (opcional):</label>
                        <textarea
                            id="comentario"
                            name="comentario"
                            class="form-control"
                            placeholder="Compartilhe sua experiência com esta receita..."
                        ></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Enviar Avaliação
                    </button>
                </form>
            </div>
            <?php else: ?>
            <div class="add-rating-form">
                <div class="message message-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Faça login para avaliar esta receita</strong>
                    <p style="margin-top: 10px;">
                        <a href="login.php" style="color: #1e40af; font-weight: 600;">Clique aqui para entrar</a>
                    </p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Reviews List -->
            <div class="reviews-list" id="reviews-list"></div>
        </div>
    </section>

    <script>
        const recipeId = new URLSearchParams(window.location.search).get('id');
        const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        let currentRecipe = null;
        let selectedRating = 0;

        if (!recipeId) {
            window.location.href = 'index.php';
        }

        // Load recipe data
        async function loadRecipe() {
            try {
                const response = await fetch(`api/recipes.php?id=${recipeId}`);
                if (!response.ok) throw new Error('Receita não encontrada');

                currentRecipe = await response.json();
                displayRecipe(currentRecipe);

                if (isLoggedIn) {
                    checkFavoriteStatus();
                }

            } catch (error) {
                console.error('Error loading recipe:', error);
                document.getElementById('recipe-title').textContent = 'Erro ao carregar receita';
            }
        }

        function displayRecipe(recipe) {
            // Update page title
            document.getElementById('page-title').textContent = `${recipe.nome} - FlavorWay`;
            document.getElementById('recipe-title').textContent = recipe.nome;
            document.getElementById('recipe-subtitle').textContent = recipe.descricao;

            // Badges
            const badges = [];
            if (recipe.badge) badges.push(recipe.badge);
            badges.push(recipe.dificuldade);
            if (recipe.regiao_nome) badges.push(recipe.regiao_nome);

            document.getElementById('recipe-badges').innerHTML = badges
                .map(badge => `<span class="badge">${badge}</span>`)
                .join('');

            // Region link
            if (recipe.regiao_slug) {
                document.getElementById('btn-region').href = `region.php?regiao=${recipe.regiao_slug}`;
            }

            // Recipe info
            document.getElementById('recipe-info').innerHTML = `
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-clock"></i> Preparo</span>
                    <span class="info-value">${recipe.tempo_preparo}</span>
                </div>
                ${recipe.tempo_cozimento ? `
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-fire"></i> Cozimento</span>
                        <span class="info-value">${recipe.tempo_cozimento}</span>
                    </div>
                ` : ''}
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-users"></i> Porções</span>
                    <span class="info-value">${recipe.pessoas}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-signal"></i> Dificuldade</span>
                    <span class="info-value">${recipe.dificuldade}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-star"></i> Avaliação</span>
                    <span class="info-value">${recipe.media_avaliacoes || recipe.rating || '-'}</span>
                </div>
            `;

            // Nutrition info
            const hasNutrition = recipe.calorias || recipe.proteinas || recipe.carboidratos || recipe.gorduras;
            if (hasNutrition) {
                document.getElementById('nutrition-info').innerHTML = `
                    ${recipe.calorias ? `
                        <div class="nutrition-item">
                            <span class="nutrition-value">${recipe.calorias}</span>
                            <span class="nutrition-label">Calorias</span>
                        </div>
                    ` : ''}
                    ${recipe.proteinas ? `
                        <div class="nutrition-item">
                            <span class="nutrition-value">${recipe.proteinas}</span>
                            <span class="nutrition-label">Proteínas</span>
                        </div>
                    ` : ''}
                    ${recipe.carboidratos ? `
                        <div class="nutrition-item">
                            <span class="nutrition-value">${recipe.carboidratos}</span>
                            <span class="nutrition-label">Carboidratos</span>
                        </div>
                    ` : ''}
                    ${recipe.gorduras ? `
                        <div class="nutrition-item">
                            <span class="nutrition-value">${recipe.gorduras}</span>
                            <span class="nutrition-label">Gorduras</span>
                        </div>
                    ` : ''}
                `;
            } else {
                document.getElementById('nutrition-card').style.display = 'none';
            }

            // Ingredients by category
            displayIngredients(recipe.ingredientes);

            // Tags
            if (recipe.tags && recipe.tags.length > 0) {
                document.getElementById('tags-container').innerHTML = recipe.tags
                    .map(tag => `<span class="tag"><i class="fas fa-tag"></i> ${tag.nome}</span>`)
                    .join('');
            } else {
                document.getElementById('tags-section').style.display = 'none';
            }
        }

        function displayIngredients(ingredientes) {
            if (!ingredientes || ingredientes.length === 0) {
                document.getElementById('ingredients-container').innerHTML = '<p>Nenhum ingrediente cadastrado.</p>';
                return;
            }

            // Group by category
            const byCategory = {};
            ingredientes.forEach(ing => {
                if (!byCategory[ing.categoria]) {
                    byCategory[ing.categoria] = [];
                }
                byCategory[ing.categoria].push(ing.nome);
            });

            let html = '';
            for (const [categoria, items] of Object.entries(byCategory)) {
                html += `
                    <div class="ingredient-category">
                        <h4>${categoria}</h4>
                        ${items.map(item => `
                            <div class="ingredient-item">
                                <i class="fas fa-check-circle"></i>
                                <span>${item}</span>
                            </div>
                        `).join('')}
                    </div>
                `;
            }

            document.getElementById('ingredients-container').innerHTML = html;
        }

        // Load ratings
        async function loadRatings() {
            try {
                const response = await fetch(`api/ratings.php?receita_id=${recipeId}`);
                if (!response.ok) throw new Error('Erro ao carregar avaliações');

                const data = await response.json();
                displayRatingsSummary(data.resumo);
                displayReviews(data.avaliacoes);

            } catch (error) {
                console.error('Error loading ratings:', error);
                document.getElementById('ratings-summary').innerHTML = '<p class="message message-error">Erro ao carregar avaliações</p>';
            }
        }

        function displayRatingsSummary(resumo) {
            const total = parseInt(resumo.total) || 0;
            const media = parseFloat(resumo.media) || 0;

            const fullStars = Math.floor(media);
            const hasHalfStar = media % 1 >= 0.5;
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

            let starsHtml = '';
            for (let i = 0; i < fullStars; i++) starsHtml += '<i class="fas fa-star"></i>';
            if (hasHalfStar) starsHtml += '<i class="fas fa-star-half-alt"></i>';
            for (let i = 0; i < emptyStars; i++) starsHtml += '<i class="far fa-star"></i>';

            document.getElementById('ratings-summary').innerHTML = `
                <div class="rating-overview">
                    <div class="rating-score">
                        <div class="rating-number">${media.toFixed(1)}</div>
                        <div class="rating-stars">${starsHtml}</div>
                        <div class="rating-count">${total} avaliações</div>
                    </div>
                    <div class="rating-bars">
                        ${[5, 4, 3, 2, 1].map(stars => {
                            const count = parseInt(resumo[`${['uma', 'duas', 'tres', 'quatro', 'cinco'][stars-1]}_estrela${stars === 1 ? '' : 's'}`]) || 0;
                            const percentage = total > 0 ? (count / total * 100) : 0;
                            return `
                                <div class="rating-bar">
                                    <span class="bar-label">${stars} estrelas</span>
                                    <div class="bar-fill-container">
                                        <div class="bar-fill" style="width: ${percentage}%"></div>
                                    </div>
                                    <span class="bar-count">${count}</span>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
            `;
        }

        function displayReviews(avaliacoes) {
            if (!avaliacoes || avaliacoes.length === 0) {
                document.getElementById('reviews-list').innerHTML = `
                    <div class="message message-info">
                        <i class="fas fa-comment"></i>
                        Seja o primeiro a avaliar esta receita!
                    </div>
                `;
                return;
            }

            document.getElementById('reviews-list').innerHTML = avaliacoes.map(review => {
                const initials = review.usuario_nome.split(' ').map(n => n[0]).join('').substring(0, 2);
                const stars = '<i class="fas fa-star"></i>'.repeat(review.nota) +
                             '<i class="far fa-star"></i>'.repeat(5 - review.nota);
                const date = new Date(review.data_criacao).toLocaleDateString('pt-BR');

                return `
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-author">
                                <div class="author-avatar">${initials}</div>
                                <div class="author-info">
                                    <h4>${review.usuario_nome}</h4>
                                    <span class="review-date">${date}</span>
                                </div>
                            </div>
                            <div class="review-rating">${stars}</div>
                        </div>
                        ${review.comentario ? `<p class="review-comment">${review.comentario}</p>` : ''}
                    </div>
                `;
            }).join('');
        }

        // Star rating interaction
        const stars = document.querySelectorAll('.star');
        stars.forEach(star => {
            star.addEventListener('click', () => {
                selectedRating = parseInt(star.dataset.rating);
                document.getElementById('rating-value').value = selectedRating;
                updateStars();
            });

            star.addEventListener('mouseenter', () => {
                const rating = parseInt(star.dataset.rating);
                stars.forEach((s, index) => {
                    s.classList.toggle('active', index < rating);
                });
            });
        });

        document.getElementById('star-rating').addEventListener('mouseleave', updateStars);

        function updateStars() {
            stars.forEach((s, index) => {
                s.classList.toggle('active', index < selectedRating);
            });
        }

        // Submit rating
        document.getElementById('rating-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (selectedRating === 0) {
                showMessage('Por favor, selecione uma nota', 'error');
                return;
            }

            const comentario = document.getElementById('comentario').value;

            try {
                const response = await fetch('api/ratings.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        receita_id: recipeId,
                        nota: selectedRating,
                        comentario: comentario || null
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    showMessage(data.message, 'success');
                    document.getElementById('comentario').value = '';
                    selectedRating = 0;
                    updateStars();
                    loadRatings();
                    loadRecipe(); // Reload to update average rating
                } else {
                    showMessage(data.error || 'Erro ao enviar avaliação', 'error');
                }

            } catch (error) {
                console.error('Error submitting rating:', error);
                showMessage('Erro ao enviar avaliação', 'error');
            }
        });

        function showMessage(text, type) {
            const messageEl = document.getElementById('rating-message');
            messageEl.innerHTML = `<div class="message message-${type}">${text}</div>`;
            setTimeout(() => messageEl.innerHTML = '', 5000);
        }

        // Favorites
        async function checkFavoriteStatus() {
            try {
                const response = await fetch(`api/favorites.php?receita_id=${recipeId}`);
                const data = await response.json();

                const btn = document.getElementById('btn-favorite');
                if (data.is_favorite) {
                    btn.classList.add('favorited');
                    btn.querySelector('i').classList.remove('far');
                    btn.querySelector('i').classList.add('fas');
                    btn.querySelector('span').textContent = 'Favoritado';
                }
            } catch (error) {
                console.error('Error checking favorite:', error);
            }
        }

        document.getElementById('btn-favorite')?.addEventListener('click', async () => {
            const btn = document.getElementById('btn-favorite');
            const isFavorited = btn.classList.contains('favorited');

            try {
                const response = await fetch('api/favorites.php', {
                    method: isFavorited ? 'DELETE' : 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ receita_id: recipeId })
                });

                const data = await response.json();

                if (response.ok) {
                    btn.classList.toggle('favorited');
                    const icon = btn.querySelector('i');
                    const text = btn.querySelector('span');

                    if (isFavorited) {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        text.textContent = 'Favoritar';
                    } else {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        text.textContent = 'Favoritado';
                    }
                } else {
                    alert(data.error || 'Erro ao processar favorito');
                }

            } catch (error) {
                console.error('Error toggling favorite:', error);
                alert('Erro ao processar favorito');
            }
        });

        // Load data on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadRecipe();
            loadRatings();
        });
    </script>
</body>
</html>
