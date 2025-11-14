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

// Parâmetros de filtro
$regiao_filtro = $_GET['regiao'] ?? '';
$dificuldade_filtro = $_GET['dificuldade'] ?? '';
$ordenar = $_GET['ordenar'] ?? 'recentes';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todas as Receitas - FlavorWay</title>
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

        .receitas-container {
            max-width: 1400px;
            margin: 100px auto 50px;
            padding: 2rem;
        }

        .receitas-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .filtros-container {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .filtro-group {
            flex: 1;
            min-width: 200px;
        }

        .filtro-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #334155;
        }

        .filtro-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
        }

        .receitas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
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
            height: 200px;
            object-fit: cover;
            background: #e2e8f0;
        }

        .receita-content {
            padding: 1.5rem;
        }

        .receita-titulo {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .receita-descricao {
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .receita-meta {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        .badge-destaque {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
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
                <a href="index.php" class="nav-link">Início</a>
                <a href="receitas.php" class="nav-link active">Receitas</a>
                <a href="ingredientes.php" class="nav-link">Ingredientes</a>
                <a href="tecnicas.php" class="nav-link">Técnicas</a>
                <a href="lista-compras.php" class="nav-link">Lista de Compras</a>
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

<div class="receitas-container">
    <div class="receitas-header">
        <h1><i class="fas fa-book-open"></i> Todas as Receitas</h1>
        <p>Explore nossa coleção completa de receitas de todo o Brasil</p>

        <div class="stats-overview" id="stats-overview">
            <div class="stat-card">
                <div class="stat-number" id="total-receitas">0</div>
                <div class="stat-label">Total de Receitas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="total-destaque">0</div>
                <div class="stat-label">Em Destaque</div>
            </div>
        </div>

        <div class="filtros-container">
            <div class="filtro-group">
                <label for="regiao">Região</label>
                <select id="regiao" onchange="aplicarFiltros()">
                    <option value="">Todas as Regiões</option>
                </select>
            </div>

            <div class="filtro-group">
                <label for="dificuldade">Dificuldade</label>
                <select id="dificuldade" onchange="aplicarFiltros()">
                    <option value="">Todas as Dificuldades</option>
                    <option value="Básico">Básico</option>
                    <option value="Intermediário">Intermediário</option>
                    <option value="Avançado">Avançado</option>
                </select>
            </div>

            <div class="filtro-group">
                <label for="ordenar">Ordenar por</label>
                <select id="ordenar" onchange="aplicarFiltros()">
                    <option value="recentes">Mais Recentes</option>
                    <option value="rating">Melhor Avaliadas</option>
                    <option value="nome">Nome (A-Z)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="receitas-grid" id="receitas-grid">
        <div class="loading">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Carregando receitas...</p>
        </div>
    </div>
</div>

<script>
let todasReceitas = [];
let regioes = [];

document.addEventListener('DOMContentLoaded', () => {
    carregarRegioes();
    carregarReceitas();
});

async function carregarRegioes() {
    try {
        const response = await fetch('../api/get-regiao.php');
        const data = await response.json();
        if (data.success && data.regioes) {
            regioes = data.regioes;
            const select = document.getElementById('regiao');
            data.regioes.forEach(regiao => {
                const option = document.createElement('option');
                option.value = regiao.slug;
                option.textContent = regiao.nome;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Erro ao carregar regiões:', error);
    }
}

async function carregarReceitas() {
    try {
        const response = await fetch('../api/get-receitas-destaque.php');
        const data = await response.json();

        if (data.success && data.receitas) {
            todasReceitas = data.receitas;

            // Atualiza estatísticas
            document.getElementById('total-receitas').textContent = todasReceitas.length;
            const destaque = todasReceitas.filter(r => r.destaque).length;
            document.getElementById('total-destaque').textContent = destaque;

            renderizarReceitas(todasReceitas);
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

function renderizarReceitas(receitas) {
    const container = document.getElementById('receitas-grid');

    if (receitas.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <p>Nenhuma receita encontrada com os filtros selecionados</p>
            </div>
        `;
        return;
    }

    container.innerHTML = receitas.map(receita => `
        <a href="receita.php?id=${receita.id}" class="receita-card">
            <img src="${receita.image}" class="receita-image" alt="${receita.nome}">
            <div class="receita-content">
                <div class="receita-titulo">${receita.nome}</div>
                <div class="receita-descricao">${receita.descricao || ''}</div>
                <div class="receita-meta">
                    ${receita.destaque ? '<span class="badge-destaque">⭐ Destaque</span>' : ''}
                    <span class="meta-item"><i class="fas fa-clock"></i> ${receita.tempo}</span>
                    <span class="meta-item"><i class="fas fa-signal"></i> ${receita.dificuldade}</span>
                    <span class="meta-item"><i class="fas fa-star"></i> ${receita.rating}</span>
                </div>
            </div>
        </a>
    `).join('');
}

function aplicarFiltros() {
    const regiao = document.getElementById('regiao').value;
    const dificuldade = document.getElementById('dificuldade').value;
    const ordenar = document.getElementById('ordenar').value;

    let receitasFiltradas = [...todasReceitas];

    // Filtrar por região
    if (regiao) {
        receitasFiltradas = receitasFiltradas.filter(r =>
            r.culinaria && r.culinaria.toLowerCase().includes(regiao.toLowerCase())
        );
    }

    // Filtrar por dificuldade
    if (dificuldade) {
        receitasFiltradas = receitasFiltradas.filter(r => r.dificuldade === dificuldade);
    }

    // Ordenar
    switch (ordenar) {
        case 'rating':
            receitasFiltradas.sort((a, b) => b.rating - a.rating);
            break;
        case 'nome':
            receitasFiltradas.sort((a, b) => a.nome.localeCompare(b.nome));
            break;
        case 'recentes':
        default:
            // Já vem ordenado por data
            break;
    }

    renderizarReceitas(receitasFiltradas);
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
