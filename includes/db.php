<?php
/**
 * db.php - PDO database connection helper
 *
 * Returns a PDO instance when DB credentials are configured, or null when they
 * are not set (triggering the config.php fallback in category.php).
 *
 * To enable the database, define these constants in includes/config.php:
 *   define('DB_HOST', 'localhost');
 *   define('DB_NAME', 'restaurant_db');
 *   define('DB_USER', 'your_user');
 *   define('DB_PASS', 'your_password');
 */

function getDbConnection(): ?PDO
{
    // If no DB credentials are configured, signal the caller to use the fallback.
    if (!defined('DB_HOST') || !defined('DB_NAME') || !defined('DB_USER') || !defined('DB_PASS')) {
        return null;
    }

    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        // Connection failed — fall back to static data.
        $pdo = null;
    }

    return $pdo;
}
