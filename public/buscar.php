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

$query = $_GET['q'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar - FlavorWay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/public.css/homestyles.css">
    <style>
        body { background: #f5f7fa; }

        .busca-container {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 2rem;
        }

        .busca-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .busca-input-container {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .busca-input {
            flex: 1;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
        }

        .busca-input:focus {
            outline: none;
            border-color: #f59e0b;
        }

        .btn-buscar {
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .resultados-stats {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .stat-badge {
            padding: 0.5rem 1rem;
            background: #f59e0b;
            color: white;
            border-radius: 20px;
            font-size: 0.875rem;
        }

        .resultados-section {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .resultado-card {
            display: flex;
            gap: 1.5rem;
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.3s;
        }

        .resultado-card:hover {
            background: #f8fafc;
        }

        .resultado-card:last-child {
            border-bottom: none;
        }

        .resultado-imagem {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
            background: #e2e8f0;
        }

        .resultado-info {
            flex: 1;
        }

        .resultado-titulo {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .resultado-titulo a {
            color: inherit;
            text-decoration: none;
        }

        .resultado-titulo a:hover {
            color: #f59e0b;
        }

        .resultado-descricao {
            color: #64748b;
            margin-bottom: 0.75rem;
        }

        .resultado-meta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .meta-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            background: #f1f5f9;
            border-radius: 20px;
            font-size: 0.875rem;
            color: #475569;
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

        .loading {
            text-align: center;
            padding: 2rem;
            color: #64748b;
        }

        .filters {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-btn.active {
            background: #f59e0b;
            color: white;
            border-color: #f59e0b;
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
                <a href="ingredientes.php" class="nav-link">Ingredientes</a>
                <a href="tecnicas.php" class="nav-link">Técnicas</a>
            </nav>

            <div class="header-actions">
                <span class="user-greeting">Olá, <?= getUserName() ?>!</span>
                <a href="../auth/logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </div>
        </div>
    </div>
</header>

<div class="busca-container">
    <div class="busca-header">
        <h1><i class="fas fa-search"></i> Buscar no FlavorWay</h1>
        <div class="busca-input-container">
            <input type="text" class="busca-input" id="search-input"
                   placeholder="Buscar receitas, ingredientes ou técnicas..."
                   value="<?= htmlspecialchars($query) ?>">
            <button class="btn-buscar" onclick="buscar()">
                <i class="fas fa-search"></i> Buscar
            </button>
        </div>

        <div class="resultados-stats" id="stats-container">
            <!-- Stats serão preenchidas via JS -->
        </div>
    </div>

    <!-- Filtros -->
    <div class="filters" id="filters">
        <button class="filter-btn active" onclick="filtrar('todos')">Todos</button>
        <button class="filter-btn" onclick="filtrar('receitas')">Receitas</button>
        <button class="filter-btn" onclick="filtrar('ingredientes')">Ingredientes</button>
        <button class="filter-btn" onclick="filtrar('tecnicas')">Técnicas</button>
    </div>

    <!-- Receitas -->
    <div class="resultados-section" id="section-receitas" style="display: none;">
        <h2 class="section-title">
            <i class="fas fa-utensils"></i>
            Receitas
        </h2>
        <div id="resultados-receitas"></div>
    </div>

    <!-- Ingredientes -->
    <div class="resultados-section" id="section-ingredientes" style="display: none;">
        <h2 class="section-title">
            <i class="fas fa-carrot"></i>
            Ingredientes
        </h2>
        <div id="resultados-ingredientes"></div>
    </div>

    <!-- Técnicas -->
    <div class="resultados-section" id="section-tecnicas" style="display: none;">
        <h2 class="section-title">
            <i class="fas fa-fire"></i>
            Técnicas
        </h2>
        <div id="resultados-tecnicas"></div>
    </div>
</div>

<script>
let searchData = null;
let filtroAtivo = 'todos';

document.addEventListener('DOMContentLoaded', () => {
    const query = "<?= addslashes($query) ?>";
    if (query) {
        realizarBusca(query);
    }

    // Enter para buscar
    document.getElementById('search-input').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') buscar();
    });
});

function buscar() {
    const query = document.getElementById('search-input').value.trim();
    if (query.length < 2) {
        alert('Digite pelo menos 2 caracteres');
        return;
    }
    window.location.href = `buscar.php?q=${encodeURIComponent(query)}`;
}

async function realizarBusca(query) {
    try {
        const response = await fetch(`../api/buscar.php?q=${encodeURIComponent(query)}`);
        const data = await response.json();

        if (data.success) {
            searchData = data;
            renderizarResultados();
        } else {
            mostrarErro(data.error);
        }
    } catch (error) {
        console.error('Erro:', error);
        mostrarErro('Erro ao buscar');
    }
}

function renderizarResultados() {
    if (!searchData) return;

    // Atualiza stats
    const statsContainer = document.getElementById('stats-container');
    const totais = searchData.totais;
    statsContainer.innerHTML = `
        <div class="stat-badge">${searchData.total} resultados encontrados</div>
        <div class="stat-badge">${totais.receitas} receitas</div>
        <div class="stat-badge">${totais.ingredientes} ingredientes</div>
        <div class="stat-badge">${totais.tecnicas} técnicas</div>
    `;

    // Renderiza receitas
    renderizarReceitas();
    renderizarIngredientes();
    renderizarTecnicas();

    // Aplica filtro
    aplicarFiltro();
}

function renderizarReceitas() {
    const container = document.getElementById('resultados-receitas');
    const section = document.getElementById('section-receitas');
    const receitas = searchData.results.receitas;

    if (receitas.length === 0) {
        section.style.display = 'none';
        return;
    }

    container.innerHTML = receitas.map(r => `
        <div class="resultado-card">
            <img src="${r.imagem}" class="resultado-imagem" alt="${r.nome}">
            <div class="resultado-info">
                <div class="resultado-titulo">
                    <a href="receita.php?id=${r.id}">${r.nome}</a>
                </div>
                <div class="resultado-descricao">${r.descricao}</div>
                <div class="resultado-meta">
                    <span class="meta-badge"><i class="fas fa-clock"></i> ${r.tempo_preparo}</span>
                    <span class="meta-badge"><i class="fas fa-signal"></i> ${r.dificuldade}</span>
                    <span class="meta-badge"><i class="fas fa-star"></i> ${r.rating}</span>
                    ${r.regiao ? `<span class="meta-badge"><i class="fas fa-map-marker-alt"></i> ${r.regiao}</span>` : ''}
                </div>
            </div>
        </div>
    `).join('');
}

function renderizarIngredientes() {
    const container = document.getElementById('resultados-ingredientes');
    const section = document.getElementById('section-ingredientes');
    const ingredientes = searchData.results.ingredientes;

    if (ingredientes.length === 0) {
        section.style.display = 'none';
        return;
    }

    container.innerHTML = ingredientes.map(ing => `
        <div class="resultado-card">
            <div class="resultado-info">
                <div class="resultado-titulo">
                    <a href="ingredientes.php?nome=${encodeURIComponent(ing.nome)}">${ing.nome}</a>
                </div>
                <div class="resultado-meta">
                    <span class="meta-badge"><i class="fas fa-tag"></i> ${ing.categoria}</span>
                    <span class="meta-badge"><i class="fas fa-utensils"></i> ${ing.total_receitas} receitas</span>
                </div>
            </div>
        </div>
    `).join('');
}

function renderizarTecnicas() {
    const container = document.getElementById('resultados-tecnicas');
    const section = document.getElementById('section-tecnicas');
    const tecnicas = searchData.results.tecnicas;

    if (tecnicas.length === 0) {
        section.style.display = 'none';
        return;
    }

    container.innerHTML = tecnicas.map(tec => `
        <div class="resultado-card">
            <div class="resultado-info">
                <div class="resultado-titulo">
                    <a href="tecnicas.php?id=${tec.id}">${tec.nome}</a>
                </div>
                <div class="resultado-descricao">${tec.descricao}</div>
                <div class="resultado-meta">
                    <span class="meta-badge"><i class="fas fa-signal"></i> ${tec.dificuldade}</span>
                </div>
            </div>
        </div>
    `).join('');
}

function filtrar(tipo) {
    filtroAtivo = tipo;

    // Atualiza botões
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    aplicarFiltro();
}

function aplicarFiltro() {
    const sections = {
        'receitas': document.getElementById('section-receitas'),
        'ingredientes': document.getElementById('section-ingredientes'),
        'tecnicas': document.getElementById('section-tecnicas')
    };

    if (filtroAtivo === 'todos') {
        Object.values(sections).forEach(section => {
            if (section && section.querySelector('.resultado-card')) {
                section.style.display = 'block';
            }
        });
    } else {
        Object.keys(sections).forEach(key => {
            const section = sections[key];
            if (section) {
                section.style.display = key === filtroAtivo ? 'block' : 'none';
            }
        });
    }
}

function mostrarErro(mensagem) {
    alert(mensagem);
}
</script>

</body>
</html>
