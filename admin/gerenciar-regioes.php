<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

// Proteger rota - apenas admins
requireAdmin();

// Obter usuário logado
$usuarioLogado = getUsuario();

// Verificar se a conexão foi estabelecida
if (!isset($pdo)) {
    die("Erro: Conexão com banco de dados não estabelecida.");
}

// Verificar se é uma requisição POST para salvar dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        $pdo->beginTransaction();
        
        switch ($action) {
            case 'add_estado':
                $stmt = $pdo->prepare("
                    INSERT INTO estados_regiao (regiao_id, nome, slug, capital, descricao, ingrediente_destaque, especialidades)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $_POST['regiao_id'],
                    $_POST['nome'],
                    $_POST['slug'],
                    $_POST['capital'],
                    $_POST['descricao'],
                    $_POST['ingrediente_destaque'] ?? '',
                    json_encode($_POST['especialidades'] ?? [])
                ]);
                break;
                
            case 'add_ingrediente':
                $stmt = $pdo->prepare("
                    INSERT INTO ingredientes_regiao (regiao_id, nome, subtitulo, descricao, origem, usos, estados)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $_POST['regiao_id'],
                    $_POST['nome'],
                    $_POST['subtitulo'] ?? '',
                    $_POST['descricao'],
                    $_POST['origem'] ?? '',
                    json_encode($_POST['usos'] ?? []),
                    json_encode($_POST['estados'] ?? [])
                ]);
                break;
                
            case 'add_tecnica':
                $stmt = $pdo->prepare("
                    INSERT INTO tecnicas_regiao (regiao_id, nome, descricao, nivel, duracao, icon, origem)
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
                    INSERT INTO cultura_regiao (regiao_id, titulo, descricao, icon, tipo)
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
        $error = "Erro ao adicionar item: " . $e->getMessage();
    }
}

// Buscar regiões
try {
    $stmt = $pdo->query("SELECT * FROM regioes ORDER BY ordem ASC");
    $regioes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $regioes = [];
    $error = "Erro ao buscar regiões: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Regiões - FlavorWay Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css/gerenciar-regioes.css">
</head>
<body>
    <div class="admin-container">
        <h1>Gerenciar Regiões</h1>
        
        <div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div>
                <strong>👋 Olá, <?php echo htmlspecialchars($usuarioLogado['nome']); ?>!</strong>
                <span style="color: #6b7280; margin-left: 10px;">Nível: <?php echo $usuarioLogado['nivel'] === 'admin' ? 'Administrador' : 'Usuário'; ?></span>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="../index.html" style="padding: 8px 16px; background: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
                    <i class="fas fa-home"></i> Voltar ao Site
                </a>
                <a href="gerenciar-usuarios.php" style="padding: 8px 16px; background: #ea580c; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
                    <i class="fas fa-users"></i> Gerenciar Usuários
                </a>
            </div>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="admin-tabs">
            <button class="admin-tab active" onclick="showTab('estados')">
                <i class="fas fa-map"></i> Estados
            </button>
            <button class="admin-tab" onclick="showTab('ingredientes')">
                <i class="fas fa-leaf"></i> Ingredientes
            </button>
            <button class="admin-tab" onclick="showTab('tecnicas')">
                <i class="fas fa-utensils"></i> Técnicas
            </button>
            <button class="admin-tab" onclick="showTab('cultura')">
                <i class="fas fa-globe"></i> Cultura
            </button>
        </div>
        
        <!-- Tab Estados -->
        <div id="estados" class="tab-content active">
            <div class="form-grid">
                <div class="form-section">
                    <h3>Adicionar Estado</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_estado">
                        
                        <div class="form-group">
                            <label for="regiao_id">Região *</label>
                            <select name="regiao_id" required>
                                <option value="">Selecione uma região</option>
                                <?php foreach ($regioes as $regiao): ?>
                                    <option value="<?php echo htmlspecialchars($regiao['id']); ?>">
                                        <?php echo htmlspecialchars($regiao['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="nome">Nome do Estado *</label>
                            <input type="text" name="nome" placeholder="Ex: Bahia" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">Slug (URL) *</label>
                            <input type="text" name="slug" placeholder="Ex: bahia" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="capital">Capital *</label>
                            <input type="text" name="capital" placeholder="Ex: Salvador" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="descricao">Descrição *</label>
                            <textarea name="descricao" placeholder="Descreva as características do estado..." required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="ingrediente_destaque">Ingrediente Destaque</label>
                            <input type="text" name="ingrediente_destaque" placeholder="Ex: Dendê">
                        </div>
                        
                        <div class="form-group">
                            <label>Especialidades</label>
                            <div class="dynamic-list" id="especialidades-list">
                                <div class="dynamic-item">
                                    <input type="text" name="especialidades[0]" placeholder="Ex: Acarajé">
                                    <button type="button" class="btn-small btn-remove" onclick="removeItem(this)">×</button>
                                </div>
                            </div>
                            <button type="button" class="btn-small btn-add" onclick="addEspecialidade()">Adicionar Especialidade</button>
                        </div>
                        
                        <button type="submit" class="btn-submit">Salvar Estado</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Tab Ingredientes -->
        <div id="ingredientes" class="tab-content">
            <div class="form-grid">
                <div class="form-section">
                    <h3>Adicionar Ingrediente</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_ingrediente">
                        
                        <div class="form-group">
                            <label for="regiao_id">Região *</label>
                            <select name="regiao_id" required>
                                <option value="">Selecione uma região</option>
                                <?php foreach ($regioes as $regiao): ?>
                                    <option value="<?php echo htmlspecialchars($regiao['id']); ?>">
                                        <?php echo htmlspecialchars($regiao['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="nome">Nome do Ingrediente *</label>
                            <input type="text" name="nome" placeholder="Ex: Dendê" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subtitulo">Subtítulo</label>
                            <input type="text" name="subtitulo" placeholder="Ex: Óleo sagrado da Bahia">
                        </div>
                        
                        <div class="form-group">
                            <label for="descricao">Descrição *</label>
                            <textarea name="descricao" placeholder="Descreva o ingrediente..." required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="origem">Origem</label>
                            <input type="text" name="origem" placeholder="Ex: África Ocidental">
                        </div>
                        
                        <div class="form-group">
                            <label>Usos</label>
                            <div class="dynamic-list" id="usos-list">
                                <div class="dynamic-item">
                                    <input type="text" name="usos[0]" placeholder="Ex: Usado em moquecas">
                                    <button type="button" class="btn-small btn-remove" onclick="removeItem(this)">×</button>
                                </div>
                            </div>
                            <button type="button" class="btn-small btn-add" onclick="addUso()">Adicionar Uso</button>
                        </div>
                        
                        <div class="form-group">
                            <label>Estados</label>
                            <div class="dynamic-list" id="estados-ingrediente-list">
                                <div class="dynamic-item">
                                    <input type="text" name="estados[0]" placeholder="Ex: Bahia">
                                    <button type="button" class="btn-small btn-remove" onclick="removeItem(this)">×</button>
                                </div>
                            </div>
                            <button type="button" class="btn-small btn-add" onclick="addEstadoIngrediente()">Adicionar Estado</button>
                        </div>
                        
                        <button type="submit" class="btn-submit">Salvar Ingrediente</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Tab Técnicas -->
        <div id="tecnicas" class="tab-content">
            <div class="form-grid">
                <div class="form-section">
                    <h3>Adicionar Técnica</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_tecnica">
                        
                        <div class="form-group">
                            <label for="regiao_id">Região *</label>
                            <select name="regiao_id" required>
                                <option value="">Selecione uma região</option>
                                <?php foreach ($regioes as $regiao): ?>
                                    <option value="<?php echo htmlspecialchars($regiao['id']); ?>">
                                        <?php echo htmlspecialchars($regiao['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="nome">Nome da Técnica *</label>
                            <input type="text" name="nome" placeholder="Ex: Cozimento lento" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="descricao">Descrição *</label>
                            <textarea name="descricao" placeholder="Descreva a técnica..." required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="nivel">Nível *</label>
                            <select name="nivel" required>
                                <option value="">Selecione o nível</option>
                                <option value="Básico">Básico</option>
                                <option value="Intermediário">Intermediário</option>
                                <option value="Avançado">Avançado</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="duracao">Duração *</label>
                            <input type="text" name="duracao" placeholder="Ex: 30 min" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="icon">Ícone (Emoji)</label>
                            <input type="text" name="icon" placeholder="🔥">
                        </div>
                        
                        <div class="form-group">
                            <label for="origem">Origem</label>
                            <input type="text" name="origem" placeholder="Ex: Tradição indígena">
                        </div>
                        
                        <button type="submit" class="btn-submit">Salvar Técnica</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Tab Cultura -->
        <div id="cultura" class="tab-content">
            <div class="form-grid">
                <div class="form-section">
                    <h3>Adicionar Informação Cultural</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_cultura">
                        
                        <div class="form-group">
                            <label for="regiao_id">Região *</label>
                            <select name="regiao_id" required>
                                <option value="">Selecione uma região</option>
                                <?php foreach ($regioes as $regiao): ?>
                                    <option value="<?php echo htmlspecialchars($regiao['id']); ?>">
                                        <?php echo htmlspecialchars($regiao['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="titulo">Título *</label>
                            <input type="text" name="titulo" placeholder="Ex: Herança Africana" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="descricao">Descrição *</label>
                            <textarea name="descricao" placeholder="Descreva a influência cultural..." required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="icon">Ícone (Emoji)</label>
                            <input type="text" name="icon" placeholder="🏛️">
                        </div>
                        
                        <div class="form-group">
                            <label for="tipo">Tipo *</label>
                            <select name="tipo" required>
                                <option value="">Selecione o tipo</option>
                                <option value="influencia">Influência</option>
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
        function showTab(tabName) {
            // Esconder todas as tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remover classe active de todos os botões
            document.querySelectorAll('.admin-tab').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Mostrar tab selecionada
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }
        
        function removeItem(button) {
            if (button.parentElement.parentElement.children.length > 1) {
                button.parentElement.remove();
            } else {
                alert('Você precisa ter pelo menos um item!');
            }
        }
        
        let especialidadeCount = 1;
        function addEspecialidade() {
            const list = document.getElementById('especialidades-list');
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <input type="text" name="especialidades[${especialidadeCount}]" placeholder="Ex: Acarajé">
                <button type="button" class="btn-small btn-remove" onclick="removeItem(this)">×</button>
            `;
            list.appendChild(div);
            especialidadeCount++;
        }
        
        let usoCount = 1;
        function addUso() {
            const list = document.getElementById('usos-list');
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <input type="text" name="usos[${usoCount}]" placeholder="Ex: Usado em moquecas">
                <button type="button" class="btn-small btn-remove" onclick="removeItem(this)">×</button>
            `;
            list.appendChild(div);
            usoCount++;
        }
        
        let estadoIngredienteCount = 1;
        function addEstadoIngrediente() {
            const list = document.getElementById('estados-ingrediente-list');
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <input type="text" name="estados[${estadoIngredienteCount}]" placeholder="Ex: Bahia">
                <button type="button" class="btn-small btn-remove" onclick="removeItem(this)">×</button>
            `;
            list.appendChild(div);
            estadoIngredienteCount++;
        }
    </script>
</body>
</html>
