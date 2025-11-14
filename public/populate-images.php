<?php
/**
 * Script para popular as receitas existentes com imagens placeholder
 * Execute após update-database.php
 */

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Popular Imagens</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;}h1{color:#333;}.success{color:green;}.error{color:red;}</style>";
echo "</head><body>";
echo "<h1>🖼️ Populando Imagens nas Receitas</h1>";
echo "<hr>";

require_once '../config/database.php';

try {
    // Buscar receitas sem imagem
    $stmt = $pdo->query("SELECT id, nome FROM receitas WHERE imagem IS NULL OR imagem = ''");
    $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<p>Encontradas <strong>" . count($receitas) . "</strong> receitas sem imagem.</p>";

    if (count($receitas) > 0) {
        echo "<h2>Adicionando imagens placeholder...</h2>";

        $updated = 0;
        foreach ($receitas as $receita) {
            $placeholder_url = "/placeholder.svg?height=180&width=280&text=" . urlencode($receita['nome']);

            $stmt = $pdo->prepare("UPDATE receitas SET imagem = ? WHERE id = ?");
            $stmt->execute([$placeholder_url, $receita['id']]);

            echo "<p class='success'>✓ Receita #{$receita['id']} - {$receita['nome']}</p>";
            $updated++;
        }

        echo "<hr>";
        echo "<h2 class='success'>✅ {$updated} receitas atualizadas!</h2>";
    } else {
        echo "<p class='success'>✅ Todas as receitas já possuem imagens!</p>";
    }

    echo "<hr>";
    echo "<p><strong>Próximos passos:</strong></p>";
    echo "<ol>";
    echo "<li>Acesse o index e veja as receitas: <a href='index.php'>index.php</a></li>";
    echo "<li>Ou adicione uma nova receita com imagem real: <a href='adicionar-receita.php'>adicionar-receita.php</a></li>";
    echo "</ol>";

} catch (PDOException $e) {
    echo "<h2 class='error'>❌ Erro ao popular imagens</h2>";
    echo "<p class='error'>Erro: " . $e->getMessage() . "</p>";
}

echo "</body></html>";
