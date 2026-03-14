<?php
/**
 * create-admin.php – CLI script to create an admin account
 *
 * Run from the repository root:
 *   php db/create-admin.php
 *
 * Or pass arguments directly:
 *   php db/create-admin.php --username=admin --password=Secret123
 *
 * The script hashes the password with bcrypt and inserts the admin
 * into the `admins` table in the configured MySQL database.
 */

// ── Must be run from the command line ────────────────────────────────────────
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("This script can only be run from the command line.\n");
}

// ── Load DB configuration ────────────────────────────────────────────────────
$rootDir = dirname(__DIR__);
require_once $rootDir . '/includes/config.php';
require_once $rootDir . '/includes/db.php';

// ── Parse CLI arguments ──────────────────────────────────────────────────────
$opts = getopt('', ['username:', 'password:']);

$username = $opts['username'] ?? null;
$password = $opts['password'] ?? null;

// ── Prompt interactively when arguments are missing ──────────────────────────
if (empty($username)) {
    echo "Enter admin username: ";
    $username = trim(fgets(STDIN));
}

if (empty($username)) {
    exit("Error: username cannot be empty.\n");
}

if (empty($password)) {
    // Hide input while typing the password (Unix only) using proc_open
    // so no shell-command strings containing user data are ever executed.
    if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN' && function_exists('proc_open')) {
        echo "Enter admin password (input hidden): ";
        $tty = fopen('/dev/tty', 'r');
        $proc = proc_open('stty -echo', [['file', '/dev/tty', 'r'], STDOUT, STDERR], $pipes);
        proc_close($proc);
        $password = trim(fgets($tty));
        $proc2 = proc_open('stty echo', [['file', '/dev/tty', 'r'], STDOUT, STDERR], $pipes2);
        proc_close($proc2);
        fclose($tty);
        echo "\n";

        echo "Confirm password: ";
        $tty = fopen('/dev/tty', 'r');
        $proc3 = proc_open('stty -echo', [['file', '/dev/tty', 'r'], STDOUT, STDERR], $pipes3);
        proc_close($proc3);
        $confirm = trim(fgets($tty));
        $proc4 = proc_open('stty echo', [['file', '/dev/tty', 'r'], STDOUT, STDERR], $pipes4);
        proc_close($proc4);
        fclose($tty);
        echo "\n";

        if ($password !== $confirm) {
            exit("Error: passwords do not match.\n");
        }
    } else {
        echo "Enter admin password: ";
        $password = trim(fgets(STDIN));
    }
}

if (strlen($password) < 8) {
    exit("Error: password must be at least 8 characters.\n");
}

// ── Connect to the database ──────────────────────────────────────────────────
$pdo = getDbConnection();
if (!$pdo) {
    exit(
        "Error: could not connect to the database.\n" .
        "Make sure the DB_* constants in includes/config.php are set correctly\n" .
        "and the database/schema has been created (run db/schema.sql first).\n"
    );
}

// ── Check whether username already exists ────────────────────────────────────
$stmt = $pdo->prepare('SELECT id FROM admins WHERE username = ?');
$stmt->execute([$username]);
if ($stmt->fetch()) {
    exit("Error: an admin with username '{$username}' already exists.\n");
}

// ── Insert the new admin ─────────────────────────────────────────────────────
$hashed = password_hash($password, PASSWORD_BCRYPT);
$stmt   = $pdo->prepare('INSERT INTO admins (username, password) VALUES (?, ?)');
$stmt->execute([$username, $hashed]);

echo "✓ Admin account '{$username}' created successfully.\n";
echo "  You can now log in at: admin/login.php\n";
