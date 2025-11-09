<?php
/**
 * FlavorWay - Database Configuration
 *
 * Establishes PDO connection with MySQL
 * - Automatically creates database if it doesn't exist
 * - Configures UTF-8 charset for full character support
 * - Enables error mode with exceptions
 */

// Database credentials
$host = "localhost";
$dbname = "flavor_way";
$username = "root";
$password = "";

try {
    // Connect to MySQL without specifying database (to be able to create if needed)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Select database for use
    $pdo->exec("USE `$dbname`");

    // Security and performance settings
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Returns associative arrays
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              // Uses native prepared statements

} catch(PDOException $e) {
    // In production, log error and show generic message
    // For now, shows detailed error for debugging
    die("Connection error: SQLSTATE[" . $e->getCode() . "] - " . $e->getMessage());
}
