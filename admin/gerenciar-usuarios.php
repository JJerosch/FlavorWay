<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

// Proteger rota - apenas admins
requireAdmin();

// Buscar usuários
try {
    $stmt = $pdo->query("
        SELECT 
            id, nome, email, nivel, ativo, 
            data_criacao, ultimo_acesso 
        FROM usuarios 
        ORDER BY data_criacao DESC
    ");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $usuarios = [];
    $error = "Erro ao buscar usuários: " . $e->getMessage();
}

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'add_admin':
                $nome = $_POST['nome'];
                $email = $_POST['email'];
                $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("
                    INSERT INTO usuarios (nome, email, senha, nivel) 
                    VALUES (?, ?, ?, 'admin')
                ");
                $stmt->execute([$nome, $email, $senha]);
                $success = "Administrador criado com sucesso!";
                break;
                
            case 'toggle_status':
                $userId = $_POST['user_id'];
                $stmt = $pdo->prepare("
                    UPDATE usuarios 
                    SET ativo = NOT ativo 
                    WHERE id = ?
                ");
                $stmt->execute([$userId]);
                $success = "Status do usuário atualizado!";
                break;
                
            case 'delete_user':
                $userId = $_POST['user_id'];
                // Não permitir deletar o próprio usuário
                if ($userId == getUsuario()['id']) {
                    $error = "Você não pode remover sua própria conta!";
                } else {
                    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
                    $stmt->execute([$userId]);
                    $success = "Usuário removido com sucesso!";
                }
                break;
        }
        
        // Recarregar usuários
        $stmt = $pdo->query("SELECT * FROM usuarios ORDER BY data_criacao DESC");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        $error = "Erro: " . $e->getMessage();
    }
}

$usuarioLogado = getUsuario();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários - FlavorWay Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css/gerenciar-usuarios.css">
</head>
<body>
    <div class="admin-header">
        <div class="header-content">
            <h1 class="header-title">
                <i class="fas fa-users"></i>
                Gerenciar Usuários
            </h1>
            <div class="header-user">
                <span>Olá, <?php echo htmlspecialchars($usuarioLogado['nome']); ?>!</span>
                <a href="../index.html" class="btn btn-primary">
                    <i class="fas fa-home"></i>
                    Voltar ao Site
                </a>
                <a href="gerenciar-regioes.php" class="btn btn-primary">
                    <i class="fas fa-map"></i>
                    Gerenciar Regiões
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon users">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo count($usuarios); ?></h3>
                    <p>Total de Usuários</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon admins">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo count(array_filter($usuarios, fn($u) => $u['nivel'] === 'admin')); ?></h3>
                    <p>Administradores</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo count(array_filter($usuarios, fn($u) => $u['ativo'] == 1)); ?></h3>
                    <p>Usuários Ativos</p>
                </div>
            </div>
        </div>

        <!-- Actions Bar -->
        <div class="actions-bar">
            <h2>Lista de Usuários</h2>
            <button class="btn btn-primary" onclick="openAddAdminModal()">
                <i class="fas fa-user-plus"></i>
                Adicionar Administrador
            </button>
        </div>

        <!-- Users Table -->
        <div class="users-table">
            <table>
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Nível</th>
                        <th>Status</th>
                        <th>Cadastro</th>
                        <th>Último Acesso</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        <?php 
                                        $initials = implode('', array_map(fn($n) => $n[0], explode(' ', $usuario['nome'])));
                                        echo strtoupper(substr($initials, 0, 2)); 
                                        ?>
                                    </div>
                                    <div class="user-details">
                                        <h4><?php echo htmlspecialchars($usuario['nome']); ?></h4>
                                        <p><?php echo htmlspecialchars($usuario['email']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $usuario['nivel']; ?>">
                                    <?php echo $usuario['nivel'] === 'admin' ? 'Admin' : 'Usuário'; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $usuario['ativo'] ? 'active' : 'inactive'; ?>">
                                    <?php echo $usuario['ativo'] ? 'Ativo' : 'Inativo'; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($usuario['data_criacao'])); ?></td>
                            <td>
                                <?php 
                                echo $usuario['ultimo_acesso'] 
                                    ? date('d/m/Y H:i', strtotime($usuario['ultimo_acesso'])) 
                                    : 'Nunca';
                                ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="toggle_status">
                                        <input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">
                                        <button type="submit" class="btn-small btn-toggle" title="Alternar Status">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                    </form>
                                    
                                    <?php if ($usuario['nivel'] !== 'admin'): ?>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja remover este usuário?')">
                                            <input type="hidden" name="action" value="delete_user">
                                            <input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">
                                            <button type="submit" class="btn-small btn-delete" title="Remover">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Adicionar Admin -->
    <div class="modal" id="addAdminModal">
        <div class="modal-content">
            <h2 style="margin-bottom: 20px;">
                <i class="fas fa-user-shield"></i>
                Adicionar Administrador
            </h2>
            
            <form method="POST">
                <input type="hidden" name="action" value="add_admin">
                
                <div class="form-group">
                    <label class="form-label">Nome Completo</label>
                    <input type="text" name="nome" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Senha</label>
                    <input type="password" name="senha" class="form-input" required minlength="6">
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i>
                        Criar Administrador
                    </button>
                    <button type="button" class="btn" onclick="closeAddAdminModal()" style="background: #6b7280; color: white;">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddAdminModal() {
            document.getElementById('addAdminModal').classList.add('active');
        }

        function closeAddAdminModal() {
            document.getElementById('addAdminModal').classList.remove('active');
        }
    </script>
</body>
</html>
