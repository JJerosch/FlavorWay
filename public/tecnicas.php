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
    <title>Técnicas Culinárias - FlavorWay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/public.css/homestyles.css">
    <style>
        body { background: #f5f7fa; }

        .tecnicas-container {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 2rem;
        }

        .tecnicas-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .tecnicas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        .tecnica-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            color: inherit;
            display: block;
            cursor: pointer;
        }

        .tecnica-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .tecnica-titulo {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tecnica-descricao {
            color: #64748b;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .tecnica-meta {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .dificuldade-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .basico {
            background: #dcfce7;
            color: #166534;
        }

        .intermediario {
            background: #fef3c7;
            color: #92400e;
        }

        .avancado {
            background: #fee2e2;
            color: #991b1b;
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
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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
                <a href="receitas.php" class="nav-link">Receitas</a>
                <a href="ingredientes.php" class="nav-link">Ingredientes</a>
                <a href="tecnicas.php" class="nav-link active">Técnicas</a>
                <a href="lista-compras.php" class="nav-link">Lista de Compras</a>
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

<div class="tecnicas-container">
    <div class="tecnicas-header">
        <h1><i class="fas fa-fire"></i> Técnicas Culinárias</h1>
        <p>Aprenda as técnicas essenciais da culinária brasileira e mundial</p>

        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-number" id="total-tecnicas">0</div>
                <div class="stat-label">Técnicas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="total-basicas">0</div>
                <div class="stat-label">Básicas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="total-avancadas">0</div>
                <div class="stat-label">Avançadas</div>
            </div>
        </div>

        <div class="filtro-container">
            <button class="filtro-btn active" onclick="filtrarDificuldade('')">Todas</button>
            <button class="filtro-btn" onclick="filtrarDificuldade('Básico')">Básicas</button>
            <button class="filtro-btn" onclick="filtrarDificuldade('Intermediário')">Intermediárias</button>
            <button class="filtro-btn" onclick="filtrarDificuldade('Avançado')">Avançadas</button>
        </div>
    </div>

    <div class="tecnicas-grid" id="tecnicas-grid">
        <div class="loading">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Carregando técnicas...</p>
        </div>
    </div>
</div>

<script>
let todasTecnicas = [];
let dificuldadeAtiva = '';

document.addEventListener('DOMContentLoaded', () => {
    carregarTecnicas();
});

async function carregarTecnicas() {
    try {
        const response = await fetch('../api/get-tecnicas.php');
        const data = await response.json();

        if (data.success && data.tecnicas) {
            todasTecnicas = data.tecnicas;
            renderizarTecnicas(todasTecnicas);
            atualizarStats();
        } else {
            mostrarMensagemVazia();
        }
    } catch (error) {
        console.error('Erro ao carregar técnicas:', error);
        mostrarMensagemVazia();
    }
}

function renderizarTecnicas(tecnicas) {
    const container = document.getElementById('tecnicas-grid');

    if (tecnicas.length === 0) {
        mostrarMensagemVazia();
        return;
    }

    container.innerHTML = tecnicas.map(tec => {
        const dificuldadeClass = tec.dificuldade.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

        return `
            <a href="tecnica.php?id=${tec.id}" class="tecnica-card">
                <div class="tecnica-titulo">
                    <i class="fas fa-fire-alt"></i>
                    ${tec.nome}
                </div>
                <div class="tecnica-descricao">${tec.descricao}</div>
                <div class="tecnica-meta">
                    <span class="dificuldade-badge ${dificuldadeClass}">
                        ${tec.dificuldade}
                    </span>
                </div>
            </a>
        `;
    }).join('');
}

function filtrarDificuldade(dificuldade) {
    dificuldadeAtiva = dificuldade;

    document.querySelectorAll('.filtro-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');

    const tecnicasFiltradas = dificuldade
        ? todasTecnicas.filter(t => t.dificuldade === dificuldade)
        : todasTecnicas;

    renderizarTecnicas(tecnicasFiltradas);
}

function atualizarStats() {
    document.getElementById('total-tecnicas').textContent = todasTecnicas.length;

    const basicas = todasTecnicas.filter(t => t.dificuldade === 'Básico').length;
    const avancadas = todasTecnicas.filter(t => t.dificuldade === 'Avançado').length;

    document.getElementById('total-basicas').textContent = basicas;
    document.getElementById('total-avancadas').textContent = avancadas;
}

function mostrarMensagemVazia() {
    document.getElementById('tecnicas-grid').innerHTML = `
        <div class="empty-state">
            <i class="fas fa-fire"></i>
            <p>Nenhuma técnica cadastrada ainda</p>
            <p><a href="populate-tecnicas.php">Clique aqui para popular com técnicas de exemplo</a></p>
        </div>
    `;
}
</script>

</body>
</html>
