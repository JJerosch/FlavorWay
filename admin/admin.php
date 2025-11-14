<?php
session_start();

// === VERIFICA LOGIN E PERMISSÕES DE ADMIN ===
if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit;
}

// === CARREGA CONEXÃO ===
require_once '../config/database.php';

// === VERIFICA SE É ADMIN ===
try {
    $stmt = $pdo->prepare("SELECT nome, email, is_admin FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user || !$user['is_admin']) {
        header('Location: ../public/index.php');
        exit;
    }

    $_SESSION['username'] = $user['nome'] ?? 'Administrador';
} catch (Exception $e) {
    header('Location: ../public/index.php');
    exit;
}

function getUserName() {
    return htmlspecialchars($_SESSION['username'] ?? 'Admin', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - FlavorWay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #1e293b;
        }

        /* Header Admin */
        .admin-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .logo i {
            color: #f59e0b;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Container Principal */
        .admin-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .welcome-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .welcome-card h1 {
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .welcome-card p {
            color: #64748b;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon.users { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .stat-icon.recipes { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .stat-icon.ingredients { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .stat-icon.techniques { background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); }

        .stat-info h3 {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #1e293b;
        }

        /* Tabs Navigation */
        .tabs-nav {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            background: transparent;
            color: #64748b;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tab-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
        }

        /* Tab Content */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .content-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .content-header h2 {
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .data-table thead {
            background: #f8fafc;
        }

        .data-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table tbody tr:hover {
            background: #f8fafc;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.2s;
            margin-right: 0.5rem;
        }

        .action-btn.edit {
            background: #3b82f6;
            color: white;
        }

        .action-btn.delete {
            background: #ef4444;
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .badge.admin {
            background: #fef3c7;
            color: #92400e;
        }

        .badge.user {
            background: #dbeafe;
            color: #1e40af;
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

        /* Search Bar */
        .search-bar {
            margin-bottom: 1.5rem;
        }

        .search-bar input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
        }
    </style>
</head>
<body>

<!-- Header -->
<header class="admin-header">
    <div class="header-content">
        <div class="logo">
            <i class="fas fa-shield-alt"></i>
            <span>FlavorWay Admin</span>
        </div>
        <div class="header-actions">
            <span>Olá, <?= getUserName() ?>!</span>
            <a href="../public/index.php" class="btn-logout">
                <i class="fas fa-home"></i> Voltar ao Site
            </a>
            <a href="../auth/logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
        </div>
    </div>
</header>

<!-- Main Container -->
<div class="admin-container">
    <!-- Welcome Card -->
    <div class="welcome-card">
        <h1><i class="fas fa-dashboard"></i> Painel Administrativo</h1>
        <p>Gerencie usuários, receitas, ingredientes e técnicas do FlavorWay</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon users">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>Total de Usuários</h3>
                <div class="stat-number" id="total-users">0</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon recipes">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-info">
                <h3>Total de Receitas</h3>
                <div class="stat-number" id="total-recipes">0</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon ingredients">
                <i class="fas fa-carrot"></i>
            </div>
            <div class="stat-info">
                <h3>Total de Ingredientes</h3>
                <div class="stat-number" id="total-ingredients">0</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon techniques">
                <i class="fas fa-fire"></i>
            </div>
            <div class="stat-info">
                <h3>Total de Técnicas</h3>
                <div class="stat-number" id="total-techniques">0</div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="tabs-nav">
        <button class="tab-btn active" onclick="switchTab('users')">
            <i class="fas fa-users"></i> Usuários
        </button>
        <button class="tab-btn" onclick="switchTab('recipes')">
            <i class="fas fa-book"></i> Receitas
        </button>
        <button class="tab-btn" onclick="switchTab('ingredients')">
            <i class="fas fa-carrot"></i> Ingredientes
        </button>
        <button class="tab-btn" onclick="switchTab('techniques')">
            <i class="fas fa-fire"></i> Técnicas
        </button>
    </div>

    <!-- Tab: Usuários -->
    <div id="users-tab" class="tab-content active">
        <div class="content-card">
            <div class="content-header">
                <h2><i class="fas fa-users"></i> Gerenciar Usuários</h2>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Buscar usuários..." onkeyup="searchUsers(this.value)">
            </div>
            <div id="users-table">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin"></i> Carregando usuários...
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Receitas -->
    <div id="recipes-tab" class="tab-content">
        <div class="content-card">
            <div class="content-header">
                <h2><i class="fas fa-book"></i> Gerenciar Receitas</h2>
                <a href="../public/adicionar-receita.php" class="btn-primary">
                    <i class="fas fa-plus"></i> Adicionar Receita
                </a>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Buscar receitas..." onkeyup="searchRecipes(this.value)">
            </div>
            <div id="recipes-table">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin"></i> Carregando receitas...
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Ingredientes -->
    <div id="ingredients-tab" class="tab-content">
        <div class="content-card">
            <div class="content-header">
                <h2><i class="fas fa-carrot"></i> Gerenciar Ingredientes</h2>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Buscar ingredientes..." onkeyup="searchIngredients(this.value)">
            </div>
            <div id="ingredients-table">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin"></i> Carregando ingredientes...
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Técnicas -->
    <div id="techniques-tab" class="tab-content">
        <div class="content-card">
            <div class="content-header">
                <h2><i class="fas fa-fire"></i> Gerenciar Técnicas</h2>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Buscar técnicas..." onkeyup="searchTechniques(this.value)">
            </div>
            <div id="techniques-table">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin"></i> Carregando técnicas...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let allUsers = [];
let allRecipes = [];
let allIngredients = [];
let allTechniques = [];

document.addEventListener('DOMContentLoaded', () => {
    loadStats();
    loadUsers();
});

function switchTab(tabName) {
    // Remove active from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

    // Add active to selected tab
    event.target.classList.add('active');
    document.getElementById(tabName + '-tab').classList.add('active');

    // Load data if not loaded yet
    if (tabName === 'recipes' && allRecipes.length === 0) loadRecipes();
    if (tabName === 'ingredients' && allIngredients.length === 0) loadIngredients();
    if (tabName === 'techniques' && allTechniques.length === 0) loadTechniques();
}

async function loadStats() {
    try {
        const [users, recipes, ingredients, techniques] = await Promise.all([
            fetch('../api/admin/get-users.php').catch(() => ({ json: () => ({ success: false }) })),
            fetch('../api/get-receitas-destaque.php').catch(() => ({ json: () => ({ success: false }) })),
            fetch('../api/get-ingredientes.php').catch(() => ({ json: () => ({ success: false }) })),
            fetch('../api/get-tecnicas.php').catch(() => ({ json: () => ({ success: false }) }))
        ]);

        const usersData = await users.json();
        const recipesData = await recipes.json();
        const ingredientsData = await ingredients.json();
        const techniquesData = await techniques.json();

        document.getElementById('total-users').textContent = usersData.usuarios?.length || 0;
        document.getElementById('total-recipes').textContent = recipesData.receitas?.length || 0;
        document.getElementById('total-ingredients').textContent = ingredientsData.ingredientes?.length || 0;
        document.getElementById('total-techniques').textContent = techniquesData.tecnicas?.length || 0;
    } catch (error) {
        console.error('Erro ao carregar estatísticas:', error);
    }
}

async function loadUsers() {
    try {
        const response = await fetch('../api/admin/get-users.php');
        const data = await response.json();

        if (data.success && data.usuarios) {
            allUsers = data.usuarios;
            renderUsers(allUsers);
        } else {
            document.getElementById('users-table').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Nenhum usuário encontrado</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Erro ao carregar usuários:', error);
        document.getElementById('users-table').innerHTML = `
            <div class="empty-state">
                <i class="fas fa-exclamation-circle"></i>
                <p>Erro ao carregar usuários. Verifique se a API existe em /api/admin/get-users.php</p>
            </div>
        `;
    }
}

function renderUsers(users) {
    const container = document.getElementById('users-table');

    if (users.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <p>Nenhum usuário encontrado</p>
            </div>
        `;
        return;
    }

    container.innerHTML = `
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Data de Cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                ${users.map(user => `
                    <tr>
                        <td>#${user.id}</td>
                        <td>${user.nome}</td>
                        <td>${user.email}</td>
                        <td>
                            <span class="badge ${user.is_admin ? 'admin' : 'user'}">
                                ${user.is_admin ? 'Admin' : 'Usuário'}
                            </span>
                        </td>
                        <td>${new Date(user.created_at || Date.now()).toLocaleDateString('pt-BR')}</td>
                        <td>
                            <button class="action-btn edit" onclick="editUser(${user.id})">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="action-btn delete" onclick="deleteUser(${user.id})">
                                <i class="fas fa-trash"></i> Excluir
                            </button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
}

async function loadRecipes() {
    try {
        const response = await fetch('../api/get-receitas-destaque.php');
        const data = await response.json();

        if (data.success && data.receitas) {
            allRecipes = data.receitas;
            renderRecipes(allRecipes);
        }
    } catch (error) {
        console.error('Erro ao carregar receitas:', error);
    }
}

function renderRecipes(recipes) {
    const container = document.getElementById('recipes-table');

    if (recipes.length === 0) {
        container.innerHTML = '<div class="empty-state"><i class="fas fa-search"></i><p>Nenhuma receita encontrada</p></div>';
        return;
    }

    container.innerHTML = `
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Região</th>
                    <th>Avaliação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                ${recipes.map(recipe => `
                    <tr>
                        <td>#${recipe.id}</td>
                        <td>${recipe.nome}</td>
                        <td>${recipe.culinaria || 'N/A'}</td>
                        <td><i class="fas fa-star" style="color: #f59e0b;"></i> ${recipe.rating || 'N/A'}</td>
                        <td>
                            <button class="action-btn edit" onclick="window.location.href='../public/receita.php?id=${recipe.id}'">
                                <i class="fas fa-eye"></i> Ver
                            </button>
                            <button class="action-btn delete" onclick="deleteRecipe(${recipe.id})">
                                <i class="fas fa-trash"></i> Excluir
                            </button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
}

async function loadIngredients() {
    try {
        const response = await fetch('../api/get-ingredientes.php');
        const data = await response.json();

        if (data.success && data.ingredientes) {
            allIngredients = data.ingredientes;
            renderIngredients(allIngredients);
        }
    } catch (error) {
        console.error('Erro ao carregar ingredientes:', error);
    }
}

function renderIngredients(ingredients) {
    const container = document.getElementById('ingredients-table');

    if (ingredients.length === 0) {
        container.innerHTML = '<div class="empty-state"><i class="fas fa-search"></i><p>Nenhum ingrediente encontrado</p></div>';
        return;
    }

    container.innerHTML = `
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Usado em Receitas</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                ${ingredients.map(ing => `
                    <tr>
                        <td>${ing.nome}</td>
                        <td>${ing.categoria || 'N/A'}</td>
                        <td>${ing.count || 0} receitas</td>
                        <td>
                            <button class="action-btn edit" onclick="window.location.href='../public/ingrediente.php?nome=${encodeURIComponent(ing.nome)}'">
                                <i class="fas fa-eye"></i> Ver
                            </button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
}

async function loadTechniques() {
    try {
        const response = await fetch('../api/get-tecnicas.php');
        const data = await response.json();

        if (data.success && data.tecnicas) {
            allTechniques = data.tecnicas;
            renderTechniques(allTechniques);
        }
    } catch (error) {
        console.error('Erro ao carregar técnicas:', error);
    }
}

function renderTechniques(techniques) {
    const container = document.getElementById('techniques-table');

    if (techniques.length === 0) {
        container.innerHTML = '<div class="empty-state"><i class="fas fa-search"></i><p>Nenhuma técnica encontrada</p></div>';
        return;
    }

    container.innerHTML = `
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Dificuldade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                ${techniques.map(tec => `
                    <tr>
                        <td>#${tec.id}</td>
                        <td>${tec.nome}</td>
                        <td>${tec.dificuldade || 'N/A'}</td>
                        <td>
                            <button class="action-btn edit" onclick="window.location.href='../public/tecnica.php?id=${tec.id}'">
                                <i class="fas fa-eye"></i> Ver
                            </button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
}

// Search functions
function searchUsers(query) {
    const filtered = allUsers.filter(u =>
        u.nome.toLowerCase().includes(query.toLowerCase()) ||
        u.email.toLowerCase().includes(query.toLowerCase())
    );
    renderUsers(filtered);
}

function searchRecipes(query) {
    const filtered = allRecipes.filter(r =>
        r.nome.toLowerCase().includes(query.toLowerCase())
    );
    renderRecipes(filtered);
}

function searchIngredients(query) {
    const filtered = allIngredients.filter(i =>
        i.nome.toLowerCase().includes(query.toLowerCase())
    );
    renderIngredients(filtered);
}

function searchTechniques(query) {
    const filtered = allTechniques.filter(t =>
        t.nome.toLowerCase().includes(query.toLowerCase())
    );
    renderTechniques(filtered);
}

// Placeholder functions for actions
function editUser(id) {
    alert(`Funcionalidade de editar usuário #${id} será implementada em breve`);
}

function deleteUser(id) {
    if (confirm('Tem certeza que deseja excluir este usuário?')) {
        alert(`Usuário #${id} será excluído (funcionalidade a implementar)`);
    }
}

function deleteRecipe(id) {
    if (confirm('Tem certeza que deseja excluir esta receita?')) {
        alert(`Receita #${id} será excluída (funcionalidade a implementar)`);
    }
}
</script>

</body>
</html>
