<?php
/**
 * FlavorWay - Logout
 *
 * Completely destroys user session:
 * 1. Clears all session variables
 * 2. Destroys session on server
 * 3. Removes session cookie from browser
 * 4. Redirects to login page
 */

session_start();

// 1. Clear all session variables
$_SESSION = [];

// 2. Destroy session on server
session_destroy();

// 3. Remove session cookie from browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000, // Expires in the past
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4. Redirect to login
header('Location: ../public/login.php');
exit;
