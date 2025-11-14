<?php
/**
 * Script para popular banco com técnicas culinárias de exemplo
 */

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Popular Técnicas</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;}h1{color:#333;}.success{color:green;}.error{color:red;}</style>";
echo "</head><body>";
echo "<h1>🔥 Popular Banco com Técnicas Culinárias</h1>";
echo "<hr>";

require_once '../config/database.php';

$tecnicas = [
    [
        'nome' => 'Refogado',
        'descricao' => 'Técnica fundamental que consiste em cozinhar alimentos em fogo médio com óleo ou manteiga, mexendo frequentemente. É a base de muitos pratos brasileiros.',
        'dificuldade' => 'Básico'
    ],
    [
        'nome' => 'Branqueamento',
        'descricao' => 'Técnica de mergulhar rapidamente os alimentos em água fervente e depois em água gelada. Usado para preservar cor, textura e sabor de vegetais.',
        'dificuldade' => 'Básico'
    ],
    [
        'nome' => 'Marinada',
        'descricao' => 'Processo de deixar carnes ou peixes em uma mistura de temperos líquidos para amaciar e dar sabor. Pode levar de 30 minutos a várias horas.',
        'dificuldade' => 'Básico'
    ],
    [
        'nome' => 'Empanamento',
        'descricao' => 'Técnica de passar alimentos por farinha, ovo batido e farinha de rosca antes de fritar. Cria uma camada crocante e dourada.',
        'dificuldade' => 'Básico'
    ],
    [
        'nome' => 'Cozimento em Panela de Pressão',
        'descricao' => 'Método rápido de cozimento que usa vapor sob pressão. Ideal para carnes duras, feijões e outros grãos, reduzindo significativamente o tempo de preparo.',
        'dificuldade' => 'Intermediário'
    ],
    [
        'nome' => 'Defumação',
        'descricao' => 'Técnica de expor alimentos à fumaça de madeiras aromáticas. Adiciona sabor defumado característico e pode ser feito a quente ou a frio.',
        'dificuldade' => 'Intermediário'
    ],
    [
        'nome' => 'Redução de Molhos',
        'descricao' => 'Processo de cozinhar líquidos em fogo baixo até evaporar parte da água, concentrando sabores e engrossando a consistência do molho.',
        'dificuldade' => 'Intermediário'
    ],
    [
        'nome' => 'Flambar',
        'descricao' => 'Técnica de adicionar bebida alcoólica quente a um prato e ateá-lo fogo. Queima o álcool mas mantém o sabor, criando um efeito visual impressionante.',
        'dificuldade' => 'Avançado'
    ],
    [
        'nome' => 'Sous Vide',
        'descricao' => 'Cozimento a vácuo em temperatura controlada. Alimentos são selados a vácuo e cozidos em banho-maria com temperatura precisa, garantindo cozimento uniforme.',
        'dificuldade' => 'Avançado'
    ],
    [
        'nome' => 'Confitar',
        'descricao' => 'Técnica francesa de cozinhar lentamente em gordura (geralmente azeite ou gordura de pato) em baixa temperatura. Resulta em carnes extremamente macias.',
        'dificuldade' => 'Avançado'
    ],
    [
        'nome' => 'Emulsificação',
        'descricao' => 'Processo de misturar dois líquidos que normalmente não se misturam (como óleo e água) para criar uma mistura homogênea. Essencial para molhos como maionese.',
        'dificuldade' => 'Avançado'
    ],
    [
        'nome' => 'Brasear',
        'descricao' => 'Técnica mista que combina dourar em fogo alto e depois cozinhar lentamente em líquido. Ideal para carnes grandes e cortes duros.',
        'dificuldade' => 'Intermediário'
    ],
    [
        'nome' => 'Gratinar',
        'descricao' => 'Técnica de dourar a superfície de um prato no forno ou sob o grill. Cria uma crosta dourada e crocante, geralmente com queijo ou farinha de rosca.',
        'dificuldade' => 'Básico'
    ],
    [
        'nome' => 'Julienne',
        'descricao' => 'Corte em tiras finas e uniformes (palitos). Usado principalmente para vegetais, facilita o cozimento rápido e apresentação elegante.',
        'dificuldade' => 'Intermediário'
    ],
    [
        'nome' => 'Mirepoix',
        'descricao' => 'Combinação clássica de vegetais picados (cebola, cenoura, salsão) usada como base aromática de caldos, molhos e ensopados.',
        'dificuldade' => 'Básico'
    ]
];

try {
    // Verifica se já existem técnicas
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tecnicas");
    $result = $stmt->fetch();

    if ($result['total'] > 0) {
        echo "<p class='error'>⚠️ Já existem {$result['total']} técnicas no banco de dados!</p>";
        echo "<p>Deseja limpar e inserir novamente? <a href='?limpar=1'>Sim, limpar e inserir</a></p>";

        if (isset($_GET['limpar'])) {
            $pdo->exec("DELETE FROM tecnicas");
            echo "<p class='success'>✓ Técnicas anteriores removidas</p>";
        } else {
            echo "</body></html>";
            exit;
        }
    }

    $stmt = $pdo->prepare("
        INSERT INTO tecnicas (nome, descricao, dificuldades_tecnica)
        VALUES (?, ?, ?)
    ");

    $total = 0;
    foreach ($tecnicas as $tec) {
        $stmt->execute([
            $tec['nome'],
            $tec['descricao'],
            $tec['dificuldade']
        ]);
        echo "<p class='success'>✓ {$tec['nome']} ({$tec['dificuldade']})</p>";
        $total++;
    }

    echo "<hr>";
    echo "<h2 class='success'>✅ {$total} técnicas adicionadas com sucesso!</h2>";
    echo "<p><a href='tecnicas.php'>Ver Técnicas</a></p>";

} catch (PDOException $e) {
    echo "<h2 class='error'>❌ Erro ao popular técnicas</h2>";
    echo "<p class='error'>Erro: " . $e->getMessage() . "</p>";
}

echo "</body></html>";
