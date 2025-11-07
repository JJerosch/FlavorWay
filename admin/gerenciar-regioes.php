<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// ========================================
// PROTEÇÃO: SÓ ADMIN ENTRA AQUI
// ========================================
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../public/login.php');
    exit;
}

// PEGAR USUÁRIO LOGADO
$stmt = $pdo->prepare("SELECT id, nome, email, nivel FROM usuarios WHERE id = ? AND ativo = 1");
$stmt->execute([$_SESSION['user_id']]);
$usuarioLogado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuarioLogado) {
    session_destroy();
    header('Location: ../public/login.php');
    exit;
}

// ========================================
// PROCESSAR FORMULÁRIOS
// ========================================
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        $pdo->beginTransaction();

        switch ($action) {
            case 'add_estado':
                $especialidades = array_filter($_POST['especialidades'] ?? []);
                $stmt = $pdo->prepare("
                    INSERT INTO estados_regiao 
                    (regiao_id, nome, slug, capital, descricao, ingrediente_destaque, especialidades) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $_POST['regiao_id'],
                    $_POST['nome'],
                    $_POST['slug'],
                    $_POST['capital'],
                    $_POST['descricao'],
                    $_POST['ingrediente_destaque'] ?? '',
                    json_encode($especialidades, JSON_UNESCAPED_UNICODE)
                ]);
                break;

            case 'add_ingrediente':
                $usos = array_filter($_POST['usos'] ?? []);
                $estados = array_filter($_POST['estados'] ?? []);
                $stmt = $pdo->prepare("
                    INSERT INTO ingredientes_regiao 
                    (regiao_id, nome, subtitulo, descricao, origem, usos, estados) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $_POST['regiao_id'],
                    $_POST['nome'],
                    $_POST['subtitulo'] ?? '',
                    $_POST['descricao'],
                    $_POST['origem'] ?? '',
                    json_encode($usos, JSON_UNESCAPED_UNICODE),
                    json_encode($estados, JSON_UNESCAPED_UNICODE)
                ]);
                break;

            case 'add_tecnica':
                $stmt = $pdo->prepare("
                    INSERT INTO tecnicas_regiao 
                    (regiao_id, nome, descricao, nivel, duracao, icon, origem) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $_POST['regiao_id'],
                    $_POST['nome'],
                    $_POST['descricao'],
                    $_POST['nivel'],
                    $_POST['duracao'],
                    $_POST['icon'] ?? '',
                    $_POST['origem'] ?? ''
                ]);
                break;

            case 'add_cultura':
                $stmt = $pdo->prepare("
                    INSERT INTO cultura_regiao 
                    (regiao_id, titulo, descricao, icon, tipo) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $_POST['regiao_id'],
                    $_POST['titulo'],
                    $_POST['descricao'],
                    $_POST['icon'] ?? '',
                    $_POST['tipo']
                ]);
                break;
        }

        $pdo->commit();
        $success = "Item adicionado com sucesso!";

    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Erro: " . $e->getMessage();
    }
}

// ========================================
// BUSCAR REGIÕES
// ========================================
try {
    $stmt = $pdo->query("SELECT id, nome FROM regioes ORDER BY ordem ASC");
    $regioes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $regioes = [];
    $error = "Erro ao carregar regiões: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Regiões - FlavorWay Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #ea580c;
            --danger: #dc3545;
            --success: #28a745;
            --dark: #333;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; padding: 20px; }
        .container { max-width: 1300px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .header { background: var(--primary); color: white; padding: 25px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 28px; }
        .user-info { background: rgba(255,255,255,0.2); padding: 12px 20px; border-radius: 50px; font-weight: 600; }
        .tabs { display: flex; background: var(--dark); }
        .tab { padding: 18px 30px; color: white; cursor: pointer; transition: 0.3s; font-weight: 600; }
        .tab:hover, .tab.active { background: var(--primary); }
        .content { padding: 40px; display: none; }
        .content.active { display: block; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .form-section { background: #f8f9fa; padding: 30px; border-radius: 12px; border: 1px solid #e0e0e0; }
        .form-section h3 { margin-bottom: 25px; color: var(--primary); font-size: 22px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #444; }
        input, textarea, select { width: 100%; padding: 14px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px; transition: 0.3s; }
        input:focus, textarea:focus, select:focus { outline: none; border-color: var(--primary); }
        textarea { height: 120px; resize: vertical; }
        .dynamic-list { background: white; border: 2px dashed #ccc; border-radius: 8px; padding: 15px; min-height: 80px; }
        .dynamic-item { display: flex; gap: 10px; margin-bottom: 10px; align-items: center; }
        .dynamic-item input { flex: 1; }
        .btn-small { background: var(--danger); color: white; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; font-size: 18px; }
        .btn-add { background: var(--success); color: white; padding: 10px 16px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .btn-submit { background: var(--primary); color: white; padding: 16px; border: none; border-radius: 10px; font-size: 18px; font-weight: 600; cursor: pointer; width: 100%; margin-top: 20px; transition: 0.3s; }
        .btn-submit:hover { background: #c2410c; transform: translateY(-2px); }
        .alert { padding: 18px; border-radius: 10px; margin: 25px 40px; font-weight: 600; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gerenciar Regiões</h1>
            <div class="user-info">
                Olá, <strong><?= htmlspecialchars($usuarioLogado['nome']) ?></strong>
                <a href="../auth/logout.php" style="color:white; margin-left:20px; text-decoration:underline; font-size:14px;">Sair</a>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="tabs">
            <div class="tab active" onclick="openTab('estados', this)">Estados</div>
            <div class="tab" onclick="openTab('ingredientes', this)">Ingredientes</div>
            <div class="tab" onclick="openTab('tecnicas', this)">Técnicas</div>
            <div class="tab" onclick="openTab('cultura', this)">Cultura</div>
        </div>

        <!-- ESTADOS -->
        <div id="estados" class="content active">
            <div class="form-grid">
                <div class="form-section">
                    <h3>Adicionar Novo Estado</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_estado">
                        <div class="form-group">
                            <label>Região *</label>
                            <select name="regiao_id" required>
                                <option value="">Selecione uma região</option>
                                <?php foreach ($regioes as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group"><label>Nome do Estado *</label><input type="text" name="nome" placeholder="Ex: Bahia" required></div>
                        <div class="form-group"><label>Slug (URL) *</label><input type="text" name="slug" placeholder="Ex: bahia" required></div>
                        <div class="form-group"><label>Capital *</label><input type="text" name="capital" placeholder="Ex: Salvador" required></div>
                        <div class="form-group"><label>Descrição *</label><textarea name="descricao" placeholder="Fale sobre o estado..." required></textarea></div>
                        <div class="form-group"><label>Ingrediente em Destaque</label><input type="text" name="ingrediente_destaque" placeholder="Ex: Dendê"></div>
                        <div class="form-group">
                            <label>Especialidades Culinárias</label>
                            <div class="dynamic-list" id="esp-list">
                                <div class="dynamic-item">
                                    <input type="text" name="especialidades[]" placeholder="Ex: Acarajé">
                                    <button type="button" class="btn-small" onclick="this.parentElement.remove()">×</button>
                                </div>
                            </div>
                            <button type="button" class="btn-add" onclick="addField('esp-list', 'especialidades[]')">+ Adicionar Especialidade</button>
                        </div>
                        <button type="submit" class="btn-submit">Salvar Estado</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- INGREDIENTES -->
        <div id="ingredientes" class="content">
            <div class="form-grid">
                <div class="form-section">
                    <h3>Adicionar Ingrediente</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_ingrediente">
                        <div class="form-group">
                            <label>Região *</label>
                            <select name="regiao_id" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($regioes as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group"><label>Nome *</label><input type="text" name="nome" placeholder="Ex: Dendê" required></div>
                        <div class="form-group"><label>Subtítulo</label><input type="text" name="subtitulo" placeholder="Ex: O ouro líquido da Bahia"></div>
                        <div class="form-group"><label>Descrição *</label><textarea name="descricao" required></textarea></div>
                        <div class="form-group"><label>Origem</label><input type="text" name="origem" placeholder="Ex: África"></div>
                        <div class="form-group">
                            <label>Usos na Culinária</label>
                            <div class="dynamic-list" id="usos-list">
                                <div class="dynamic-item">
                                    <input type="text" name="usos[]" placeholder="Ex: Moquecas">
                                    <button type="button" class="btn-small" onclick="this.parentElement.remove()">×</button>
                                </div>
                            </div>
                            <button type="button" class="btn-add" onclick="addField('usos-list', 'usos[]')">+ Adicionar Uso</button>
                        </div>
                        <div class="form-group">
                            <label>Estados onde é comum</label>
                            <div class="dynamic-list" id="estados-ing-list">
                                <div class="dynamic-item">
                                    <input type="text" name="estados[]" placeholder="Ex: Bahia">
                                    <button type="button" class="btn-small" onclick="this.parentElement.remove()">×</button>
                                </div>
                            </div>
                            <button type="button" class="btn-add" onclick="addField('estados-ing-list', 'estados[]')">+ Adicionar Estado</button>
                        </div>
                        <button type="submit" class="btn-submit">Salvar Ingrediente</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- TÉCNICAS -->
        <div id="tecnicas" class="content">
            <div class="form-grid">
                <div class="form-section">
                    <h3>Adicionar Técnica Culinária</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_tecnica">
                        <div class="form-group">
                            <label>Região *</label>
                            <select name="regiao_id" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($regioes as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group"><label>Nome da Técnica *</label><input type="text" name="nome" placeholder="Ex: Cozimento em folha de bananeira" required></div>
                        <div class="form-group"><label>Descrição *</label><textarea name="descricao" required></textarea></div>
                        <div class="form-group">
                            <label>Nível de Dificuldade *</label>
                            <select name="nivel" required>
                                <option value="Básico">Básico</option>
                                <option value="Intermediário">Intermediário</option>
                                <option value="Avançado">Avançado</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Duração *</label><input type="text" name="duracao" placeholder="Ex: 2 horas" required></div>
                        <div class="form-group"><label>Ícone (Emoji)</label><input type="text" name="icon" placeholder="Ex: fire"></div>
                        <div class="form-group"><label>Origem</label><input type="text" name="origem" placeholder="Ex: Indígena"></div>
                        <button type="submit" class="btn-submit">Salvar Técnica</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- CULTURA -->
        <div id="cultura" class="content">
            <div class="form-grid">
                <div class="form-section">
                    <h3>Adicionar Informação Cultural</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_cultura">
                        <div class="form-group">
                            <label>Região *</label>
                            <select name="regiao_id" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($regioes as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group"><label>Título *</label><input type="text" name="titulo" placeholder="Ex: Herança Africana na Bahia" required></div>
                        <div class="form-group"><label>Descrição *</label><textarea name="descricao" required></textarea></div>
                        <div class="form-group"><label>Ícone (Emoji)</label><input type="text" name="icon" placeholder="Ex: drum"></div>
                        <div class="form-group">
                            <label>Tipo *</label>
                            <select name="tipo" required>
                                <option value="influencia">Influência Cultural</option>
                                <option value="tradicao">Tradição</option>
                                <option value="historia">História</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-submit">Salvar Informação Cultural</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openTab(tabName, element) {
            document.querySelectorAll('.content').forEach(c => c.classList.remove('active'));
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.getElementById(tabName).classList.add('active');
            element.classList.add('active');
        }

        function addField(containerId, name) {
            const container = document.getElementById(containerId);
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <input type="text" name="${name}" placeholder="Digite aqui...">
                <button type="button" class="btn-small" onclick="this.parentElement.remove()">×</button>
            `;
            container.appendChild(div);
        }
    </script>
</body>
</html>