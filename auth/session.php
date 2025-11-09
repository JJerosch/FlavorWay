<?php
/**
 * FlavorWay - Gerenciamento de Sessão
 *
 * Este arquivo:
 * - Inicia a sessão do usuário
 * - Fornece dados do usuário para JavaScript
 * - Deve ser incluído em páginas que precisam acessar info de sessão
 *
 * Uso: require_once '../auth/session.php';
 */

session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';

/**
 * Busca dados completos do usuário atual do banco
 * @return array|null - Dados do usuário ou null se não logado
 */
function getUser() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    global $pdo;
    $stmt = $pdo->prepare("SELECT id, nome, email, ativo FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Disponibiliza dados da sessão no JavaScript
 * Cria objeto global window.USER acessível em scripts client-side
 */
echo "<script>
    window.USER = {
        id: " . json_encode($_SESSION['user_id'] ?? null) . ",
        nome: " . json_encode($_SESSION['user_name'] ?? null) . ",
        email: " . json_encode($_SESSION['user_email'] ?? null) . ",
        tipo: " . json_encode($_SESSION['user_type'] ?? null) . ",
        isAdmin: " . (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin' ? 'true' : 'false') . "
    };

    // Função helper para acessar dados do usuário
    function getUserJS() {
        return window.USER;
    }
</script>";
?>
