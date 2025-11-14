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
    <title>Minha Lista de Compras - FlavorWay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/public.css/homestyles.css">
    <style>
        body {
            background: #f5f7fa;
        }

        .lista-compras-container {
            max-width: 900px;
            margin: 100px auto 50px;
            padding: 2rem;
        }

        .lista-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .lista-header h1 {
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .lista-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
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

        .lista-section {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            color: #1e293b;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .item-lista {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.3s;
        }

        .item-lista:hover {
            background: #f8fafc;
        }

        .item-lista:last-child {
            border-bottom: none;
        }

        .item-checkbox {
            width: 24px;
            height: 24px;
            margin-right: 1rem;
            cursor: pointer;
        }

        .item-conteudo {
            flex: 1;
        }

        .item-nome {
            font-size: 1rem;
            color: #334155;
            font-weight: 500;
        }

        .item-receita {
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        .item-comprado .item-nome {
            text-decoration: line-through;
            color: #94a3b8;
        }

        .btn-remover {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            padding: 0.5rem;
            font-size: 1.25rem;
            transition: color 0.3s;
        }

        .btn-remover:hover {
            color: #dc2626;
        }

        .acoes-lista {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .btn-acao {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-limpar-comprados {
            background: #f59e0b;
            color: white;
        }

        .btn-limpar-comprados:hover {
            background: #f97316;
        }

        .btn-limpar-tudo {
            background: #ef4444;
            color: white;
        }

        .btn-limpar-tudo:hover {
            background: #dc2626;
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

        .loading-spinner {
            text-align: center;
            padding: 2rem;
            color: #64748b;
        }

        .loading-spinner i {
            font-size: 2rem;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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
                <a href="lista-compras.php" class="nav-link active">Lista de Compras</a>
                <a href="adicionar-receita.php" class="nav-link">Adicionar Receita</a>
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

<div class="lista-compras-container">
    <div class="lista-header">
        <h1><i class="fas fa-shopping-cart"></i> Minha Lista de Compras</h1>
        <p>Gerencie seus ingredientes e organize suas compras</p>

        <div class="lista-stats">
            <div class="stat-card">
                <div class="stat-number" id="total-pendentes">0</div>
                <div class="stat-label">Pendentes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="total-comprados">0</div>
                <div class="stat-label">Comprados</div>
            </div>
        </div>
    </div>

    <!-- Itens Pendentes -->
    <div class="lista-section">
        <div class="acoes-lista">
            <button class="btn-acao btn-limpar-comprados" onclick="limparComprados()">
                <i class="fas fa-check-double"></i> Limpar Comprados
            </button>
            <button class="btn-acao btn-limpar-tudo" onclick="limparTudo()">
                <i class="fas fa-trash"></i> Limpar Tudo
            </button>
        </div>

        <h2 class="section-title">
            <i class="fas fa-list"></i>
            Itens para Comprar
        </h2>
        <div id="lista-pendentes">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Carregando lista...</p>
            </div>
        </div>
    </div>

    <!-- Itens Comprados -->
    <div class="lista-section">
        <h2 class="section-title">
            <i class="fas fa-check-circle"></i>
            Itens Comprados
        </h2>
        <div id="lista-comprados">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
        </div>
    </div>
</div>

<script>
let listaData = null;

// Carrega lista de compras
document.addEventListener('DOMContentLoaded', () => {
    carregarLista();
});

async function carregarLista() {
    try {
        const response = await fetch('../api/get-lista-compras.php');
        const data = await response.json();

        if (data.success) {
            listaData = data;
            renderizarLista();
            atualizarEstatisticas();
        } else {
            mostrarErro('Erro ao carregar lista');
        }
    } catch (error) {
        console.error('Erro ao carregar lista:', error);
        mostrarErro('Erro ao carregar dados');
    }
}

function renderizarLista() {
    const containerPendentes = document.getElementById('lista-pendentes');
    const containerComprados = document.getElementById('lista-comprados');

    // Renderiza itens pendentes
    if (listaData.itens_pendentes && listaData.itens_pendentes.length > 0) {
        containerPendentes.innerHTML = listaData.itens_pendentes.map(item => `
            <div class="item-lista">
                <input type="checkbox" class="item-checkbox"
                    onchange="toggleComprado(${item.id})"
                    ${item.comprado ? 'checked' : ''}>
                <div class="item-conteudo">
                    <div class="item-nome">${item.item}</div>
                    ${item.receita_nome ? `
                        <div class="item-receita">
                            <i class="fas fa-utensils"></i> ${item.receita_nome}
                        </div>
                    ` : ''}
                </div>
                <button class="btn-remover" onclick="removerItem(${item.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `).join('');
    } else {
        containerPendentes.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-shopping-basket"></i>
                <p>Sua lista está vazia!</p>
                <p><a href="index.php">Adicione receitas para começar</a></p>
            </div>
        `;
    }

    // Renderiza itens comprados
    if (listaData.itens_comprados && listaData.itens_comprados.length > 0) {
        containerComprados.innerHTML = listaData.itens_comprados.map(item => `
            <div class="item-lista item-comprado">
                <input type="checkbox" class="item-checkbox"
                    onchange="toggleComprado(${item.id})"
                    checked>
                <div class="item-conteudo">
                    <div class="item-nome">${item.item}</div>
                </div>
                <button class="btn-remover" onclick="removerItem(${item.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `).join('');
    } else {
        containerComprados.innerHTML = `
            <div class="empty-state">
                <p>Nenhum item comprado ainda</p>
            </div>
        `;
    }
}

function atualizarEstatisticas() {
    document.getElementById('total-pendentes').textContent = listaData.total_pendentes;
    document.getElementById('total-comprados').textContent = listaData.total_comprados;
}

async function toggleComprado(itemId) {
    try {
        const response = await fetch('../api/gerenciar-lista-compras.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                acao: 'toggle_comprado',
                item_id: itemId
            })
        });

        const data = await response.json();

        if (data.success) {
            carregarLista(); // Recarrega a lista
        }
    } catch (error) {
        console.error('Erro ao atualizar item:', error);
    }
}

async function removerItem(itemId) {
    if (!confirm('Deseja realmente remover este item?')) return;

    try {
        const response = await fetch('../api/gerenciar-lista-compras.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                acao: 'remover',
                item_id: itemId
            })
        });

        const data = await response.json();

        if (data.success) {
            carregarLista(); // Recarrega a lista
        }
    } catch (error) {
        console.error('Erro ao remover item:', error);
    }
}

async function limparComprados() {
    if (!confirm('Deseja remover todos os itens comprados?')) return;

    try {
        const response = await fetch('../api/gerenciar-lista-compras.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                acao: 'limpar_comprados'
            })
        });

        const data = await response.json();

        if (data.success) {
            alert(`${data.total} itens removidos`);
            carregarLista();
        }
    } catch (error) {
        console.error('Erro ao limpar comprados:', error);
    }
}

async function limparTudo() {
    if (!confirm('Deseja limpar TODA a lista? Esta ação não pode ser desfeita.')) return;

    try {
        const response = await fetch('../api/gerenciar-lista-compras.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                acao: 'limpar_tudo'
            })
        });

        const data = await response.json();

        if (data.success) {
            alert('Lista limpa!');
            carregarLista();
        }
    } catch (error) {
        console.error('Erro ao limpar lista:', error);
    }
}

function mostrarErro(mensagem) {
    alert(mensagem);
}
</script>

</body>
</html>
