<?php
/**
 * Script para criar tabela de lista de compras
 * Execute: http://localhost/FlavorWay/public/create-shopping-list-table.php
 */

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Criar Tabela Lista de Compras</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;}h1{color:#333;}.success{color:green;}.error{color:red;}</style>";
echo "</head><body>";
echo "<h1>🛒 Criação da Tabela Lista de Compras</h1>";
echo "<hr>";

require_once '../config/database.php';

try {
    // Verifica se a tabela já existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'lista_compras'");
    if ($stmt->rowCount() > 0) {
        echo "<p class='error'>⚠️ Tabela 'lista_compras' já existe!</p>";
    } else {
        // Cria a tabela
        $sql = "CREATE TABLE `lista_compras` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `usuario_id` INT(11) NOT NULL,
            `receita_id` INT(11) DEFAULT NULL,
            `item` VARCHAR(500) NOT NULL,
            `quantidade` VARCHAR(100) DEFAULT NULL,
            `comprado` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_usuario` (`usuario_id`),
            KEY `idx_receita` (`receita_id`),
            KEY `idx_comprado` (`comprado`),
            CONSTRAINT `fk_lista_compras_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_lista_compras_receita` FOREIGN KEY (`receita_id`) REFERENCES `receitas` (`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $pdo->exec($sql);
        echo "<p class='success'>✅ Tabela 'lista_compras' criada com sucesso!</p>";
    }

    // Mostra estrutura da tabela
    echo "<h2>Estrutura da Tabela:</h2>";
    $stmt = $pdo->query("DESCRIBE lista_compras");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Padrão</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<hr>";
    echo "<h2>✅ Próximos Passos:</h2>";
    echo "<ol>";
    echo "<li>Acesse uma receita: <a href='receita.php?id=1'>Ver Receita #1</a></li>";
    echo "<li>Clique em 'Adicionar à Lista de Compras'</li>";
    echo "<li>Acesse sua lista: <a href='lista-compras.php'>Minha Lista de Compras</a></li>";
    echo "</ol>";

} catch (PDOException $e) {
    echo "<h2 class='error'>❌ Erro ao criar tabela</h2>";
    echo "<p class='error'>Erro: " . $e->getMessage() . "</p>";
    echo "<p>Código do erro: " . $e->getCode() . "</p>";
}

echo "</body></html>";
