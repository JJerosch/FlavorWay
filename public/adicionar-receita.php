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

// === BUSCA REGIÕES DISPONÍVEIS ===
try {
    $stmt = $pdo->query("SELECT id, nome FROM regioes WHERE ativo = 1 ORDER BY ordem");
    $regioes = $stmt->fetchAll();
} catch (Exception $e) {
    $regioes = [];
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
    <title>Adicionar Receita - FlavorWay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/public.css/homestyles.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 100px auto 50px;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .form-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-header h1 {
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #64748b;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #334155;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #f59e0b;
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #f59e0b;
            text-decoration: none;
            margin-bottom: 1rem;
            transition: color 0.3s;
        }

        .btn-back:hover {
            color: #f97316;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .required {
            color: #dc2626;
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
                <a href="adicionar-receita.php" class="nav-link active">Adicionar Receita</a>
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

<div class="container">
    <div class="form-container">
        <a href="index.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Voltar ao Início
        </a>

        <div class="form-header">
            <h1><i class="fas fa-plus-circle"></i> Adicionar Nova Receita</h1>
            <p>Compartilhe sua receita favorita com a comunidade FlavorWay</p>
        </div>

        <div id="alertContainer"></div>

        <form id="recipeForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome">Nome da Receita <span class="required">*</span></label>
                <input type="text" id="nome" name="nome" required placeholder="Ex: Feijoada Completa">
            </div>

            <div class="form-group">
                <label for="descricao">Descrição <span class="required">*</span></label>
                <textarea id="descricao" name="descricao" required placeholder="Descreva sua receita..."></textarea>
            </div>

            <div class="form-group">
                <label for="imagem">URL da Imagem <span class="required">*</span></label>
                <input type="url" id="imagem" name="imagem" required placeholder="https://exemplo.com/imagem.jpg">
                <small style="color: #64748b;">Cole a URL de uma imagem da sua receita</small>
            </div>

            <div class="form-group">
                <label for="ingredientes">Ingredientes <span class="required">*</span></label>
                <textarea id="ingredientes" name="ingredientes" required placeholder="Liste os ingredientes, um por linha"></textarea>
            </div>

            <div class="form-group">
                <label for="modo_preparo">Modo de Preparo <span class="required">*</span></label>
                <textarea id="modo_preparo" name="modo_preparo" required placeholder="Descreva o passo a passo do preparo"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tempo_preparo">Tempo de Preparo <span class="required">*</span></label>
                    <input type="text" id="tempo_preparo" name="tempo_preparo" required placeholder="Ex: 30 min">
                </div>

                <div class="form-group">
                    <label for="pessoas">Porções <span class="required">*</span></label>
                    <input type="text" id="pessoas" name="pessoas" required placeholder="Ex: 4 pessoas">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="dificuldade">Dificuldade <span class="required">*</span></label>
                    <select id="dificuldade" name="dificuldade" required>
                        <option value="">Selecione...</option>
                        <option value="Básico">Básico</option>
                        <option value="Intermediário">Intermediário</option>
                        <option value="Avançado">Avançado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="regiao_id">Região <span class="required">*</span></label>
                    <select id="regiao_id" name="regiao_id" required>
                        <option value="">Selecione uma região...</option>
                        <?php foreach ($regioes as $regiao): ?>
                            <option value="<?= $regiao['id'] ?>"><?= htmlspecialchars($regiao['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="destaque" name="destaque" value="1">
                <label for="destaque" style="margin: 0;">Marcar como receita em destaque</label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-check"></i> Adicionar Receita
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('recipeForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const alertContainer = document.getElementById('alertContainer');

    try {
        const response = await fetch('../api/salvar-receita.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alertContainer.innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> ${result.message}
                </div>
            `;
            this.reset();
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 2000);
        } else {
            alertContainer.innerHTML = `
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> ${result.message}
                </div>
            `;
        }
    } catch (error) {
        alertContainer.innerHTML = `
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> Erro ao enviar a receita. Tente novamente.
            </div>
        `;
    }

    // Scroll to alert
    alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
});
</script>

</body>
</html>
