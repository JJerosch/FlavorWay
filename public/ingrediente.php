<?php
session_start();

// === VERIFICA LOGIN ===
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// === CARREGA CONEX�O ===
require_once '../config/database.php';

// === PUXA NOME DO BANCO ===
try {
    $stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $_SESSION['username'] = $user['nome'] ?? 'Usu�rio';
} catch (Exception $e) {
    $_SESSION['username'] = 'Usu�rio';
}

// === FUN��O SEGURA ===
function getUserName() {
    return htmlspecialchars($_SESSION['username'] ?? 'Usu�rio', ENT_QUOTES, 'UTF-8');
}

// === OBT�M NOME DO INGREDIENTE ===
$ingrediente_nome = $_GET['nome'] ?? '';
if (empty($ingrediente_nome)) {
    header('Location: ingredientes.php');
    exit;
}
$ingrediente_nome_safe = htmlspecialchars($ingrediente_nome, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $ingrediente_nome_safe ?> - FlavorWay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/public.css/homestyles.css">
    <style>
        body { background: #f5f7fa; }

        /* === BARRA DE PESQUISA === */
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
        .search-input-global { flex: 1; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; }
        .search-close { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b; }
        .search-close:hover { color: #1e293b; }
        .search-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            transition: opacity 0.3s;
        }
        .search-btn:hover { opacity: 0.8; }
        .menu-toggle { display: none; background: none; border: none; color: white; font-size: 1.25rem; cursor: pointer; padding: 0.5rem; }

        @media (max-width: 768px) {
            .nav { display: none; flex-direction: column; position: absolute; top: 100%; left: 0; right: 0; background: white; padding: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
            .nav.active { display: flex; }
            .menu-toggle { display: block; }
            .header-actions .search-btn { display: none; }
        }

        .ingrediente-container {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 2rem;
        }

        .ingrediente-hero {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 3rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
        }

        .ingrediente-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .ingrediente-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
        }

        .ingrediente-meta {
            display: flex;
            gap: 2rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.125rem;
        }

        .meta-item i {
            font-size: 1.5rem;
        }

        .content-section {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.75rem;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-title i {
            color: #10b981;
        }

        .receitas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .receita-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .receita-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .receita-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .receita-content {
            padding: 1.25rem;
        }

        .receita-titulo {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .receita-meta {
            display: flex;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: #64748b;
            flex-wrap: wrap;
        }

        .receita-meta-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .loading {
            text-align: center;
            padding: 3rem;
            color: #64748b;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #64748b;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .info-box {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #10b981;
            margin-bottom: 1.5rem;
        }

        .info-box h3 {
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .info-box p {
            color: #64748b;
            line-height: 1.6;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1rem;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #059669;
        }
    </style>
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
                <a href="index.php" class="nav-link">In�cio</a>
                <a href="receitas.php" class="nav-link">Receitas</a>
                <a href="ingredientes.php" class="nav-link active">Ingredientes</a>
                <a href="tecnicas.php" class="nav-link">T�cnicas</a>
                <a href="lista-compras.php" class="nav-link">Lista de Compras</a>
            </nav>

            <div class="header-actions">
                <span class="user-greeting">Ol�, <?= getUserName() ?>!</span>
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
    </div>
        <!-- Barra de Pesquisa -->
        <div class="search-container" id="searchContainer">
            <div class="search-inner">
                <input type="text" placeholder="Buscar receitas, ingredientes, técnicas..." class="search-input-global" onkeypress="if(event.key==='Enter') searchGlobal()">
                <button class="search-close" onclick="toggleSearch()" aria-label="Fechar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<div class="ingrediente-container">
    <a href="ingredientes.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Voltar para Ingredientes
    </a>

    <div class="ingrediente-hero" id="ingrediente-hero">
        <div class="loading">
            <i class="fas fa-spinner fa-spin"></i> Carregando informa��es...
        </div>
    </div>

    <div class="content-section">
        <h2 class="section-title">
            <i class="fas fa-book-open"></i>
            Receitas que usam <?= $ingrediente_nome_safe ?>
        </h2>
        <div class="receitas-grid" id="receitas-grid">
            <div class="loading">
                <i class="fas fa-spinner fa-spin"></i> Carregando receitas...
            </div>
        </div>
    </div>
</div>

<script>
const INGREDIENTE_NOME = <?= json_encode($ingrediente_nome) ?>;

document.addEventListener('DOMContentLoaded', () => {
    carregarIngredienteInfo();
    carregarReceitasComIngrediente();
});

async function carregarIngredienteInfo() {
    try {
        const response = await fetch('../api/get-ingredientes.php');
        const data = await response.json();

        if (data.success && data.ingredientes) {
            const ingrediente = data.ingredientes.find(ing =>
                ing.nome.toLowerCase() === INGREDIENTE_NOME.toLowerCase()
            );

            if (ingrediente) {
                renderizarIngredienteHero(ingrediente);
            } else {
                renderizarIngredienteHero({
                    nome: INGREDIENTE_NOME,
                    categoria: 'N�o categorizado',
                    count: 0
                });
            }
        }
    } catch (error) {
        console.error('Erro ao carregar informa��es do ingrediente:', error);
        renderizarIngredienteHero({
            nome: INGREDIENTE_NOME,
            categoria: 'N�o categorizado',
            count: 0
        });
    }
}

function renderizarIngredienteHero(ingrediente) {
    const heroContainer = document.getElementById('ingrediente-hero');

    heroContainer.innerHTML = `
        <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
            <div class="ingrediente-icon">
                <i class="fas fa-carrot"></i>
            </div>
            <div style="flex: 1;">
                <h1>${ingrediente.nome}</h1>
                <div class="ingrediente-meta">
                    ${ingrediente.categoria ? `
                        <div class="meta-item">
                            <i class="fas fa-tag"></i>
                            <span>${ingrediente.categoria}</span>
                        </div>
                    ` : ''}
                    <div class="meta-item">
                        <i class="fas fa-book"></i>
                        <span>${ingrediente.count || 0} receitas</span>
                    </div>
                </div>
            </div>
        </div>
    `;
}

async function carregarReceitasComIngrediente() {
    try {
        // Busca todas as receitas que cont�m este ingrediente
        const response = await fetch(`../api/buscar.php?q=${encodeURIComponent(INGREDIENTE_NOME)}`);
        const data = await response.json();

        const container = document.getElementById('receitas-grid');

        if (data.success && data.receitas && data.receitas.length > 0) {
            // Filtra receitas que realmente cont�m o ingrediente nos ingredientes
            const receitasFiltradas = data.receitas.filter(receita => {
                if (!receita.ingredientes) return false;
                const ingredientesArray = receita.ingredientes.toLowerCase().split(',');
                return ingredientesArray.some(ing =>
                    ing.trim().includes(INGREDIENTE_NOME.toLowerCase())
                );
            });

            if (receitasFiltradas.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <p>Nenhuma receita encontrada com este ingrediente</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = receitasFiltradas.map(receita => `
                <a href="receita.php?id=${receita.id}" class="receita-card">
                    <img src="${receita.image || '/assets/images/placeholder.jpg'}" class="receita-image" alt="${receita.nome}">
                    <div class="receita-content">
                        <div class="receita-titulo">${receita.nome}</div>
                        <div class="receita-meta">
                            ${receita.tempo ? `
                                <span class="receita-meta-item">
                                    <i class="fas fa-clock"></i> ${receita.tempo}
                                </span>
                            ` : ''}
                            ${receita.dificuldade ? `
                                <span class="receita-meta-item">
                                    <i class="fas fa-signal"></i> ${receita.dificuldade}
                                </span>
                            ` : ''}
                            ${receita.rating ? `
                                <span class="receita-meta-item">
                                    <i class="fas fa-star"></i> ${receita.rating}
                                </span>
                            ` : ''}
                        </div>
                    </div>
                </a>
            `).join('');
        } else {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>Nenhuma receita encontrada com este ingrediente</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Erro ao carregar receitas:', error);
        document.getElementById('receitas-grid').innerHTML = `
            <div class="empty-state">
                <i class="fas fa-exclamation-circle"></i>
                <p>Erro ao carregar receitas</p>
            </div>
        `;
    }
}

// === FUNÇÕES DE BUSCA GLOBAL ===
function toggleSearch() {
    const container = document.getElementById('searchContainer');
    container.classList.toggle('active');
    if (container.classList.contains('active')) {
        document.querySelector('.search-input-global').focus();
    }
}

function toggleMenu() {
    const nav = document.getElementById('nav');
    nav.classList.toggle('active');
}

function searchGlobal() {
    const query = document.querySelector('.search-input-global').value.trim();
    if (query && query.length >= 2) {
        window.location.href = `buscar.php?q=${encodeURIComponent(query)}`;
    } else if (query.length < 2) {
        alert('Digite pelo menos 2 caracteres para buscar');
    }
}
</script>

</body>
</html>
