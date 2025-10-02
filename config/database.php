<?php
$servidor = "localhost";     // geralmente localhost
$usuario  = "root";          // usuário do MySQL
$senha    = "";              // senha (no XAMPP geralmente é vazia)
$banco    = "flavor_way"; // troque pelo nome do seu banco
 
// Criando conexão
$conn = mysqli_connect($servidor, $usuario, $senha, $banco);
 
// Verificando a conexão
if (!$conn) {
    die("Erro na conexão: " . mysqli_connect_error());
}
 
echo "Conectado com sucesso!";
?>