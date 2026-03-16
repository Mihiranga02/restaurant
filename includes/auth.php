<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function requireLogin(string $redirectTo = 'login.php'): void {
    if (!isLoggedIn()) {
        header('Location: ' . $redirectTo);
        exit;
    }
}

function currentUser(): ?array {
    if (!isLoggedIn()) return null;
    return [
        'id'    => $_SESSION['user_id'],
        'name'  => $_SESSION['user_name'],
        'email' => $_SESSION['user_email'],
    ];
}

function getCartCount(): int {
    if (isLoggedIn()) {
        $pdo = getDbConnection();
        if (!$pdo) return 0;
        $stmt = $pdo->prepare('SELECT COALESCE(SUM(quantity),0) FROM cart WHERE user_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        return (int)$stmt->fetchColumn();
    }
    // Guest: count items from session cart
    if (!empty($_SESSION['cart'])) {
        return (int)array_sum(array_map('intval', $_SESSION['cart']));
    }
    return 0;
}
