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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingredientes - FlavorWay</title>
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

        .ingredientes-container {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 2rem;
        }

        .ingredientes-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .categoria-section {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .categoria-titulo {
            font-size: 1.5rem;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .ingredientes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }

        .ingrediente-card {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .ingrediente-card:hover {
            border-color: #f59e0b;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .ingrediente-nome {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .ingrediente-info {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        .filtro-container {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .filtro-btn {
            padding: 0.5rem 1rem;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filtro-btn.active {
            background: #f59e0b;
            color: white;
            border-color: #f59e0b;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
            padding: 1.5rem;
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
                <a href="receitas.php" class="nav-link">Receitas</a>
                <a href="ingredientes.php" class="nav-link active">Ingredientes</a>
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

<div class="ingredientes-container">
    <div class="ingredientes-header">
        <h1><i class="fas fa-carrot"></i> Ingredientes</h1>
        <p>Explore todos os ingredientes utilizados em nossas receitas</p>

        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-number" id="total-ingredientes">0</div>
                <div class="stat-label">Total de Ingredientes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="total-categorias">0</div>
                <div class="stat-label">Categorias</div>
            </div>
        </div>

        <div class="filtro-container" id="filtros">
            <button class="filtro-btn active" onclick="filtrarCategoria('')">Todos</button>
        </div>
    </div>

    <div id="ingredientes-content">
        <div class="loading">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Carregando ingredientes...</p>
        </div>
    </div>
</div>

<script>
let todosIngredientes = {};
let categoriaAtiva = '';

document.addEventListener('DOMContentLoaded', () => {
    carregarIngredientes();
});

async function carregarIngredientes() {
    try {
        const response = await fetch('../api/buscar.php?q=');
        // Como a busca vazia não funciona, vamos buscar todos os ingredientes únicos
        const sql_response = await fetch('../config/database.php');

        // Vamos fazer uma requisição mais específica
        const response2 = await fetch('../api/get-ingredientes.php');
        const data = await response2.json();

        if (data.success) {
            todosIngredientes = agruparPorCategoria(data.ingredientes);
            renderizarIngredientes();
            criarFiltros();
            atualizarStats(data.ingredientes);
        }
    } catch (error) {
        console.error('Erro ao carregar ingredientes:', error);
        document.getElementById('ingredientes-content').innerHTML = `
            <div class="empty-state" style="text-align:center;padding:3rem;color:#64748b;">
                <i class="fas fa-exclamation-circle" style="font-size:3rem;margin-bottom:1rem;"></i>
                <p>Erro ao carregar ingredientes</p>
                <p>Os ingredientes serão exibidos assim que você adicionar receitas.</p>
            </div>
        `;
    }
}

function agruparPorCategoria(ingredientes) {
    const grupos = {};
    ingredientes.forEach(ing => {
        const cat = ing.categoria || 'Outros';
        if (!grupos[cat]) grupos[cat] = [];
        grupos[cat].push(ing);
    });
    return grupos;
}

function renderizarIngredientes() {
    const container = document.getElementById('ingredientes-content');
    let html = '';

    const categorias = categoriaAtiva
        ? { [categoriaAtiva]: todosIngredientes[categoriaAtiva] }
        : todosIngredientes;

    for (const [categoria, ingredientes] of Object.entries(categorias)) {
        html += `
            <div class="categoria-section">
                <h2 class="categoria-titulo">
                    <i class="fas fa-tag"></i>
                    ${categoria}
                </h2>
                <div class="ingredientes-grid">
                    ${ingredientes.map(ing => `
                        <a href="ingrediente.php?nome=${encodeURIComponent(ing.nome)}" class="ingrediente-card">
                            <div class="ingrediente-nome">${ing.nome}</div>
                            <div class="ingrediente-info">
                                <span><i class="fas fa-utensils"></i> ${ing.count || ing.total_receitas || 0} receitas</span>
                            </div>
                        </a>
                    `).join('')}
                </div>
            </div>
        `;
    }

    container.innerHTML = html || '<div class="empty-state">Nenhum ingrediente encontrado</div>';
}

function criarFiltros() {
    const container = document.getElementById('filtros');
    const categorias = Object.keys(todosIngredientes);

    categorias.forEach(cat => {
        const btn = document.createElement('button');
        btn.className = 'filtro-btn';
        btn.textContent = cat;
        btn.onclick = () => filtrarCategoria(cat);
        container.appendChild(btn);
    });

    document.getElementById('total-categorias').textContent = categorias.length;
}

function filtrarCategoria(categoria) {
    categoriaAtiva = categoria;

    document.querySelectorAll('.filtro-btn').forEach(btn => {
        btn.classList.remove('active');
        if ((categoria === '' && btn.textContent === 'Todos') ||
            btn.textContent === categoria) {
            btn.classList.add('active');
        }
    });

    renderizarIngredientes();
}

function atualizarStats(ingredientes) {
    document.getElementById('total-ingredientes').textContent = ingredientes.length;
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
