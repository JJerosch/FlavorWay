<?php
require_once '../config/database.php';

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
                    $_POST['ingrediente_destaque'],
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
                    $_POST['subtitulo'],
                    $_POST['descricao'],
                    $_POST['origem'],
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
                    $_POST['icon'],
                    $_POST['origem']
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
                    $_POST['icon'],
                    $_POST['tipo']
                ]);
                break;
        }
        
        $pdo->commit();
        $success = "Item adicionado com sucesso!";
        
    } catch (Exception $e) {
        $pdo->rollback();
        $error = "Erro ao adicionar item: " . $e->getMessage();
    }
}

// Buscar regiões
$regioes = $pdo->query("SELECT * FROM regioes ORDER BY ordem ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Regiões - FlavorWay Admin</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 20px;
        }
        
        .admin-tabs {
            display: flex;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 30px;
        }
        
        .admin-tab {
            padding: 12px 24px;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            color: #6b7280;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
        }
        
        .admin-tab.active {
            color: #ea580c;
            border-bottom-color: #ea580c;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .form-section {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .form-section h3 {
            color: #ea580c;
            margin-bottom: 20px;
            font-size: 1.25rem;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #374151;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .form-group textarea {
            height: 80px;
            resize: vertical;
        }
        
        .dynamic-list {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 12px;
            background: #f9fafb;
        }
        
        .dynamic-item {
            display: flex;
            gap: 8px;
            margin-bottom: 8px;
            align-items: center;
        }
        
        .dynamic-item input {
            margin: 0;
            flex: 1;
        }
        
        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
        }
        
        .btn-remove {
            background: #dc2626;
            color: white;
        }
        
        .btn-add {
            background: #16a34a;
            color: white;
            margin-top: 8px;
        }
        
        .btn-submit {
            background: #ea580c;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .data-table th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Gerenciar Regiões</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="admin-tabs">
            <button class="admin-tab active" onclick="showTab('estados')">Estados</button>
            <button class="admin-tab" onclick="showTab('ingredientes')">Ingredientes</button>
            <button class="admin-tab" onclick="showTab('tecnicas')">Técnicas</button>
            <button class="admin-tab" onclick="showTab('cultura')">Cultura</button>
        </div>
        
        <!-- Tab Estados -->
        <div id="estados" class="tab-content active">
            <div class="form-grid">
                <div class="form-section">
                    <h3>Adicionar Estado</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_estado">
                        
                        <div class="form-group">
                            <label for="regiao_id">Região</label>
                            <select name="regiao_id" required>
                                <?php foreach ($regioes as $regiao): ?>
                                    <option value="<?php echo $regiao['id']; ?>"><?php echo $regiao['nome']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="nome">Nome do Estado</label>
                            <input type="text" name="nome" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">Slug (URL)</label>
                            <input type="text" name="slug" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="capital">Capital</label>
                            <input type="text" name="capital" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea name="descricao" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="ingrediente_destaque">Ingrediente Destaque</label>
                            <input type="text" name="ingrediente_destaque">
                        </div>
                        
                        <div class="form-group">
                            <label>Especialidades</label>
                            <div class="dynamic-list" id="especialidades-list">
                                <div class="dynamic-item">
                                    <input type="text" name="especialidades[0]" placeholder="Especialidade">
                                    <button type="button" class="btn-small btn-remove" onclick="removeItem(this)">×</button>
                                </div>
                            </div>
                            <button type="button" class="btn-small btn-add" onclick="addEspecialidade()">+ Adicionar</button>
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
                            <label for="regiao_id">Região</label>
                            <select name="regiao_id" required>
                                <?php foreach ($regioes as $regiao): ?>
                                    <option value="<?php echo $regiao['id']; ?>"><?php echo $regiao['nome']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="nome">Nome do Ingrediente</label>
                            <input type="text" name="nome" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subtitulo">Subtítulo</label>
                            <input type="text" name="subtitulo">
                        </div>
                        
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea name="descricao" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="origem">Origem</label>
                            <input type="text" name="origem">
                        </div>
                        
                        <div class="form-group">
                            <label>Usos</label>
                            <div class="dynamic-list" id="usos-list">
                                <div class="dynamic-item">
                                    <input type="text" name="usos[0]" placeholder="Uso do ingrediente">
                                    <button type="button" class="btn-small btn-remove" onclick="removeItem(this)">×</button>
                                </div>
                            </div>
                            <button type="button" class="btn-small btn-add" onclick="addUso()">+ Adicionar</button>
                        </div>
                        
                        <div class="form-group">
                            <label>Estados</label>
                            <div class="dynamic-list" id="estados-ingrediente-list">
                                <div class="dynamic-item">
                                    <input type="text" name="estados[0]" placeholder="Estado">
                                    <button type="button" class="btn-small btn-remove" onclick="removeItem(this)">×</button>
                                </div>
                            </div>
                            <button type="button" class="btn-small btn-add" onclick="addEstadoIngrediente()">+ Adicionar</button>
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
                            <label for="regiao_id">Região</label>
                            <select name="regiao_id" required>
                                <?php foreach ($regioes as $regiao): ?>
                                    <option value="<?php echo $regiao['id']; ?>"><?php echo $regiao['nome']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="nome">Nome da Técnica</label>
                            <input type="text" name="nome" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea name="descricao" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="nivel">Nível</label>
                            <select name="nivel" required>
                                <option value="Básico">Básico</option>
                                <option value="Intermediário">Intermediário</option>
                                <option value="Avançado">Avançado</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="duracao">Duração</label>
                            <input type="text" name="duracao" placeholder="Ex: 30 min" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="icon">Ícone (Emoji)</label>
                            <input type="text" name="icon" placeholder="🔥">
                        </div>
                        
                        <div class="form-group">
                            <label for="origem">Origem</label>
                            <input type="text" name="origem">
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
                            <label for="regiao_id">Região</label>
                            <select name="regiao_id" required>
                                <?php foreach ($regioes as $regiao): ?>
                                    <option value="<?php echo $regiao['id']; ?>"><?php echo $regiao['nome']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <input type="text" name="titulo" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea name="descricao" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="icon">Ícone (Emoji)</label>
                            <input type="text" name="icon" placeholder="🏛️">
                        </div>
                        
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <select name="tipo" required>
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
            button.parentElement.remove();
        }
        
        let especialidadeCount = 1;
        function addEspecialidade() {
            const list = document.getElementById('especialidades-list');
            const div = document.createElement('div');
            div.className = 'dynamic-item';
            div.innerHTML = `
                <input type="text" name="especialidades[${especialidadeCount}]" placeholder="Especialidade">
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
                <input type="text" name="usos[${usoCount}]" placeholder="Uso do ingrediente">
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
                <input type="text" name="estados[${estadoIngredienteCount}]" placeholder="Estado">
                <button type="button" class="btn-small btn-remove" onclick="removeItem(this)">×</button>
            `;
            list.appendChild(div);
            estadoIngredienteCount++;
        }
    </script>
</body>
</html>
