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

// === OBT�M ID DA T�CNICA ===
$tecnica_id = $_GET['id'] ?? '';
if (empty($tecnica_id) || !is_numeric($tecnica_id)) {
    header('Location: tecnicas.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-title">T�cnica Culin�ria - FlavorWay</title>
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

        .tecnica-container {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 2rem;
        }

        .tecnica-hero {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
            padding: 3rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.3);
        }

        .tecnica-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .tecnica-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
        }

        .tecnica-meta {
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

        .dificuldade-badge {
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.3);
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
            color: #f59e0b;
        }

        .descricao-completa {
            font-size: 1.125rem;
            line-height: 1.8;
            color: #475569;
            margin-bottom: 1.5rem;
        }

        .passos-lista {
            list-style: none;
            padding: 0;
            counter-reset: step-counter;
        }

        .passos-lista li {
            counter-increment: step-counter;
            position: relative;
            padding-left: 4rem;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .passos-lista li::before {
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.25rem;
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
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
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

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #f59e0b;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1rem;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #f97316;
        }

        .dicas-box {
            background: #fef3c7;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
            margin-top: 2rem;
        }

        .dicas-box h3 {
            color: #92400e;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dicas-box p {
            color: #78350f;
            line-height: 1.6;
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
                <a href="ingredientes.php" class="nav-link">Ingredientes</a>
                <a href="tecnicas.php" class="nav-link active">T�cnicas</a>
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

<div class="tecnica-container">
    <a href="tecnicas.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Voltar para T�cnicas
    </a>

    <div class="tecnica-hero" id="tecnica-hero">
        <div class="loading">
            <i class="fas fa-spinner fa-spin"></i> Carregando informa��es...
        </div>
    </div>

    <div class="content-section" id="descricao-section" style="display: none;">
        <h2 class="section-title">
            <i class="fas fa-info-circle"></i>
            Sobre a T�cnica
        </h2>
        <div id="descricao-completa" class="descricao-completa"></div>
    </div>

    <div class="content-section" id="passos-section" style="display: none;">
        <h2 class="section-title">
            <i class="fas fa-list-ol"></i>
            Como Fazer
        </h2>
        <ul id="passos-lista" class="passos-lista"></ul>
    </div>

    <div class="content-section">
        <h2 class="section-title">
            <i class="fas fa-book-open"></i>
            Receitas que usam esta t�cnica
        </h2>
        <div class="receitas-grid" id="receitas-grid">
            <div class="loading">
                <i class="fas fa-spinner fa-spin"></i> Carregando receitas...
            </div>
        </div>
    </div>
</div>

<script>
const TECNICA_ID = <?= (int)$tecnica_id ?>;

document.addEventListener('DOMContentLoaded', () => {
    carregarTecnicaInfo();
    carregarReceitasComTecnica();
});

async function carregarTecnicaInfo() {
    try {
        const response = await fetch('../api/get-tecnicas.php');
        const data = await response.json();

        if (data.success && data.tecnicas) {
            const tecnica = data.tecnicas.find(t => t.id == TECNICA_ID);

            if (tecnica) {
                renderizarTecnicaHero(tecnica);
                renderizarDescricao(tecnica);
                renderizarPassos(tecnica);
                document.getElementById('page-title').textContent = `${tecnica.nome} - FlavorWay`;
            } else {
                window.location.href = 'tecnicas.php';
            }
        }
    } catch (error) {
        console.error('Erro ao carregar informa��es da t�cnica:', error);
    }
}

function renderizarTecnicaHero(tecnica) {
    const heroContainer = document.getElementById('tecnica-hero');

    heroContainer.innerHTML = `
        <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
            <div class="tecnica-icon">
                <i class="fas fa-fire-alt"></i>
            </div>
            <div style="flex: 1;">
                <h1>${tecnica.nome}</h1>
                <div class="tecnica-meta">
                    ${tecnica.dificuldade ? `
                        <div class="meta-item">
                            <span class="dificuldade-badge">${tecnica.dificuldade}</span>
                        </div>
                    ` : ''}
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span>${tecnica.tempo || 'Vari�vel'}</span>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function renderizarDescricao(tecnica) {
    if (tecnica.descricao) {
        document.getElementById('descricao-section').style.display = 'block';
        document.getElementById('descricao-completa').textContent = tecnica.descricao;
    }
}

function renderizarPassos(tecnica) {
    if (tecnica.passos) {
        const passos = tecnica.passos.split('\n').filter(p => p.trim());
        if (passos.length > 0) {
            document.getElementById('passos-section').style.display = 'block';
            document.getElementById('passos-lista').innerHTML = passos.map(passo =>
                `<li>${passo}</li>`
            ).join('');
        }
    }
}

async function carregarReceitasComTecnica() {
    try {
        // Primeiro, pega o nome da t�cnica
        const tecResponse = await fetch('../api/get-tecnicas.php');
        const tecData = await tecResponse.json();

        if (tecData.success && tecData.tecnicas) {
            const tecnica = tecData.tecnicas.find(t => t.id == TECNICA_ID);

            if (!tecnica) {
                mostrarMensagemVazia('T�cnica n�o encontrada');
                return;
            }

            // Busca receitas que mencionam esta t�cnica
            const response = await fetch(`../api/buscar.php?q=${encodeURIComponent(tecnica.nome)}`);
            const data = await response.json();

            const container = document.getElementById('receitas-grid');

            if (data.success && data.receitas && data.receitas.length > 0) {
                // Filtra receitas que realmente usam esta t�cnica
                const receitasFiltradas = data.receitas.filter(receita => {
                    if (!receita.tecnicas) return false;
                    const tecnicasArray = receita.tecnicas.toLowerCase().split(',');
                    return tecnicasArray.some(tec =>
                        tec.trim().includes(tecnica.nome.toLowerCase())
                    );
                });

                if (receitasFiltradas.length === 0) {
                    mostrarMensagemVazia('Nenhuma receita encontrada com esta t�cnica');
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
                mostrarMensagemVazia('Nenhuma receita encontrada com esta t�cnica');
            }
        }
    } catch (error) {
        console.error('Erro ao carregar receitas:', error);
        mostrarMensagemVazia('Erro ao carregar receitas');
    }
}

function mostrarMensagemVazia(mensagem) {
    document.getElementById('receitas-grid').innerHTML = `
        <div class="empty-state">
            <i class="fas fa-search"></i>
            <p>${mensagem}</p>
        </div>
    `;
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
