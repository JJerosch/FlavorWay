<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== TESTE DE CONEXÃO MySQL ===<br><br>";

$configs = [
    ['host' => 'localhost', 'desc' => 'localhost'],
    ['host' => '127.0.0.1', 'desc' => '127.0.0.1'],
    ['host' => 'localhost:3306', 'desc' => 'localhost:3306'],
];

foreach ($configs as $config) {
    try {
        echo "Tentando: {$config['desc']}... ";
        $pdo = new PDO(
            "mysql:host={$config['host']};charset=utf8mb4",
            "root",
            "",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        echo "<span style='color:green'>✓ SUCESSO!</span><br>";
        
        // Verifica se o banco existe
        $stmt = $pdo->query("SHOW DATABASES LIKE 'flavor_way'");
        if ($stmt->rowCount() > 0) {
            echo "  → Banco 'flavor_way' encontrado!<br>";
        } else {
            echo "  → <span style='color:orange'>Banco 'flavor_way' NÃO existe</span><br>";
        }
        
        break;
    } catch (PDOException $e) {
        echo "<span style='color:red'>✗ FALHOU</span><br>";
        echo "  Erro: " . $e->getMessage() . "<br>";
    }
    echo "<br>";
}

// Lista todos os bancos
try {
    echo "<br><b>Bancos de dados disponíveis:</b><br>";
    $stmt = $pdo->query("SHOW DATABASES");
    while ($row = $stmt->fetch()) {
        echo "  - {$row['Database']}<br>";
    }
} catch (Exception $e) {
    echo "Não foi possível listar bancos<br>";
}
?>