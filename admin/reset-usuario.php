<?php
/**
 * RESET SENHA ADMIN - Versão Simplificada
 * Coloque este arquivo na RAIZ do projeto (junto com as pastas admin, auth, config, public)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Reset de Senha - Admin</h1><hr>";

// Tentar conectar ao banco
try {
    $pdo = new PDO("mysql:host=localhost;dbname=flavor_way;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Conectado ao banco flavor_way</p>";
} catch (PDOException $e) {
    die("<p style='color: red;'>✗ Erro de conexão: " . $e->getMessage() . "</p>");
}

// Gerar hash da senha "admin123"
$novaSenha = 'admin123';
$hashSenha = password_hash($novaSenha, PASSWORD_DEFAULT);

echo "<p>Hash gerado: <code>{$hashSenha}</code></p><hr>";

// Verificar se o usuário existe
try {
    $stmt = $pdo->query("SELECT id, nome, email FROM usuarios WHERE email = 'admin@flavorway.com'");
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        echo "<p style='color: red;'>✗ Usuário não encontrado!</p>";
        echo "<p>Criando usuário admin...</p>";
        
        // Criar o usuário se não existir
        $stmt = $pdo->prepare("
            INSERT INTO usuarios (username, nome, email, senha, ativo) 
            VALUES ('admin', 'Administrador', 'admin@flavorway.com', ?, 1)
        ");
        $stmt->execute([$hashSenha]);
        
        $usuario_id = $pdo->lastInsertId();
        
        // Adicionar como administrador
        $stmt = $pdo->prepare("
            INSERT INTO administradores (usuario_id, nivel) 
            VALUES (?, 'super_admin')
        ");
        $stmt->execute([$usuario_id]);
        
        echo "<p style='color: green;'>✓ Usuário admin criado com sucesso!</p>";
    } else {
        echo "<p>✓ Usuário encontrado:</p>";
        echo "<ul>";
        echo "<li>ID: {$usuario['id']}</li>";
        echo "<li>Nome: {$usuario['nome']}</li>";
        echo "<li>Email: {$usuario['email']}</li>";
        echo "</ul>";
        
        // Atualizar senha
        $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE email = 'admin@flavorway.com'");
        $stmt->execute([$hashSenha]);
        
        echo "<p style='color: green;'>✓ Senha atualizada!</p>";
    }
    
    echo "<hr>";
    echo "<div style='background: #e8f5e9; padding: 20px; border-radius: 8px;'>";
    echo "<h2>✅ SUCESSO!</h2>";
    echo "<p><strong>Email:</strong> admin@flavorway.com</p>";
    echo "<p><strong>Senha:</strong> admin123</p>";
    echo "</div>";
    echo "<p style='color: red; margin-top: 20px;'><strong>⚠️ DELETE ESTE ARQUIVO AGORA!</strong></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Erro: " . $e->getMessage() . "</p>";
}
?>

<style>
body { font-family: Arial; max-width: 700px; margin: 40px auto; padding: 20px; }
code { background: #f5f5f5; padding: 4px 8px; border-radius: 4px; }
</style>