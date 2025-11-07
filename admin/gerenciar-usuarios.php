<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// ========================================
// PROTEÇÃO: SÓ ADMIN ENTRA
// ========================================
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../public/login.php');
    exit;
}

// BUSCAR USUÁRIO LOGADO (SEM DEPENDER DE FUNÇÃO EXTERNA)
$stmt = $pdo->prepare("SELECT id, nome, email FROM usuarios WHERE id = ? AND ativo = 1");
$stmt->execute([$_SESSION['user_id']]);
$usuarioLogado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuarioLogado) {
    session_destroy();
    header('Location: ../public/login.php');
    exit;
}

// ========================================
// BUSCAR TODOS OS USUÁRIOS (SEM COLUNA 'nivel')
// ========================================
try {
    $stmt = $pdo->query("
        SELECT id, nome, email, ativo, data_criacao, ultimo_acesso 
        FROM usuarios 
        ORDER BY data_criacao DESC
    ");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $usuarios = [];
    $error = "Erro: " . $e->getMessage();
}

// ========================================
// PROCESSAR AÇÕES
// ========================================
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'add_admin':
                $nome = trim($_POST['nome']);
                $email = trim($_POST['email']);
                $senha = $_POST['senha'];

                if (empty($nome) || empty($email) || empty($senha)) {
                    throw new Exception("Preencha todos os campos.");
                }
                if (strlen($senha) < 6) {
                    throw new Exception("Senha deve ter no mínimo 6 caracteres.");
                }

                // Verificar se email já existe
                $check = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
                $check->execute([$email]);
                if ($check->fetch()) {
                    throw new Exception("Este e-mail já está cadastrado.");
                }

                $hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    INSERT INTO usuarios (nome, email, senha, ativo) 
                    VALUES (?, ?, ?, 1)
                ");
                $stmt->execute([$nome, $email, $hash]);
                $success = "Administrador criado com sucesso!";
                break;

            case 'toggle_status':
                $userId = (int)$_POST['user_id'];
                if ($userId === $usuarioLogado['id']) {
                    $error = "Você não pode desativar sua própria conta!";
                } else {
                    $stmt = $pdo->prepare("UPDATE usuarios SET ativo = NOT ativo WHERE id = ?");
                    $stmt->execute([$userId]);
                    $success = "Status alterado!";
                }
                break;

            case 'delete_user':
                $userId = (int)$_POST['user_id'];
                if ($userId === $usuarioLogado['id']) {
                    $error = "Você não pode se excluir!";
                } else {
                    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
                    $stmt->execute([$userId]);
                    $success = "Usuário excluído!";
                }
                break;
        }

        // Recarregar lista
        $stmt = $pdo->query("SELECT id, nome, email, ativo, data_criacao, ultimo_acesso FROM usuarios ORDER BY data_criacao DESC");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários - FlavorWay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .header { background: #ea580c; color: white; padding: 20px; border-radius: 12px 12px 0 0; margin: -30px -30px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .btn { padding: 10px 20px; background: #3b82f6; color: white; text-decoration: none; border-radius: 8px; margin-left: 10px; }
        .btn-danger { background: #dc3545; }
        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-admin { background: #ea580c; color: white; }
        .badge-user { background: #6b7280; color: white; }
        .badge-active { background: #28a745; color: white; }
        .badge-inactive { background: #dc3545; color: white; }
        .btn-small { background: none; border: none; font-size: 18px; cursor: pointer; padding: 8px; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); justify-content: center; align-items: center; z-index: 9999; }
        .modal.active { display: flex; }
        .modal-content { background: white; padding: 30px; border-radius: 12px; width: 90%; max-width: 500px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gerenciar Usuários</h1>
            <div>
                <span style="margin-right: 20px;">Olá, <strong><?= htmlspecialchars($usuarioLogado['nome']) ?></strong></span>
                <a href="../index.html" class="btn">Voltar ao Site</a>
                <a href="gerenciar-regioes.php" class="btn">Regiões</a>
                <a href="../auth/logout.php" class="btn btn-danger">Sair</a>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div style="margin: 30px 0; text-align: right;">
            <button onclick="document.getElementById('addAdminModal').classList.add('active')" class="btn" style="background: #28a745;">
                Adicionar Administrador
            </button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>E-mail</th>
                    <th>Status</th>
                    <th>Cadastro</th>
                    <th>Último Acesso</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($u['nome']) ?></strong>
                    </td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <span class="badge <?= $u['ativo'] ? 'badge-active' : 'badge-inactive' ?>">
                            <?= $u['ativo'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($u['data_criacao'])) ?></td>
                    <td><?= $u['ultimo_acesso'] ? date('d/m/Y H:i', strtotime($u['ultimo_acesso'])) : 'Nunca' ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="toggle_status">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <button type="submit" class="btn-small" title="Ativar/Desativar">Power</button>
                        </form>
                        <?php if ($u['id'] != $usuarioLogado['id']): ?>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Excluir permanentemente?')">
                            <input type="hidden" name="action" value="delete_user">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <button type="submit" class="btn-small" style="color:red;" title="Excluir">Trash</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal" id="addAdminModal">
        <div class="modal-content">
            <h2>Adicionar Administrador</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add_admin">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" required>
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Senha (mín. 6 caracteres)</label>
                    <input type="password" name="senha" required minlength="6">
                </div>
                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn" style="flex:1; background:#28a745;">Criar</button>
                    <button type="button" onclick="document.getElementById('addAdminModal').classList.remove('active')" class="btn" style="background:#6b7280;">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>