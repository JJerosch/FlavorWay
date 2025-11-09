<?php
/**
 * FlavorWay - Session Management
 *
 * This file:
 * - Starts user session
 * - Provides user data to JavaScript
 * - Should be included in pages that need session access
 *
 * Usage: require_once '../auth/session.php';
 */

session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';

/**
 * Fetches complete current user data from database
 * @return array|null - User data or null if not logged in
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
 * Makes session data available in JavaScript
 * Creates global window.USER object accessible in client-side scripts
 */
echo "<script>
    window.USER = {
        id: " . json_encode($_SESSION['user_id'] ?? null) . ",
        nome: " . json_encode($_SESSION['user_name'] ?? null) . ",
        email: " . json_encode($_SESSION['user_email'] ?? null) . ",
        tipo: " . json_encode($_SESSION['user_type'] ?? null) . ",
        isAdmin: " . (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin' ? 'true' : 'false') . "
    };

    // Helper function to access user data
    function getUserJS() {
        return window.USER;
    }
</script>";
?>
