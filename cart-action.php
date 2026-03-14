<?php
/**
 * cart-action.php — AJAX endpoint for cart operations
 *
 * Accepts POST (JSON or form-encoded) with:
 *   action     : 'add' | 'update' | 'remove'
 *   product_id : int
 *   quantity   : int  (default 1)
 *
 * Returns JSON: { success: bool, cartCount: int, [error: string] }
 */
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

header('Content-Type: application/json');

// Accept both JSON body and form-encoded POST
$raw   = file_get_contents('php://input');
$input = !empty($raw) ? (json_decode($raw, true) ?? []) : [];

$action    = $input['action']     ?? ($_POST['action']     ?? '');
$productId = (int)($input['product_id'] ?? ($_POST['product_id'] ?? 0));
$quantity  = (int)($input['quantity']   ?? ($_POST['quantity']   ?? 1));

if (!in_array($action, ['add', 'update', 'remove'], true) || $productId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid request', 'cartCount' => getCartCount()]);
    exit;
}

$pdo = getDbConnection();

if (isLoggedIn() && $pdo) {
    if ($action === 'add') {
        $qty  = max(1, $quantity);
        $stmt = $pdo->prepare(
            'INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)'
        );
        $stmt->execute([$_SESSION['user_id'], $productId, $qty]);
    } elseif ($action === 'update') {
        if ($quantity < 1) {
            $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id = ? AND product_id = ?');
            $stmt->execute([$_SESSION['user_id'], $productId]);
        } else {
            $stmt = $pdo->prepare('UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?');
            $stmt->execute([$quantity, $_SESSION['user_id'], $productId]);
        }
    } elseif ($action === 'remove') {
        $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id = ? AND product_id = ?');
        $stmt->execute([$_SESSION['user_id'], $productId]);
    }
} else {
    // Guest: session cart
    if ($action === 'add') {
        $qty = max(1, $quantity);
        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $qty;
    } elseif ($action === 'update') {
        if ($quantity < 1) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    } elseif ($action === 'remove') {
        unset($_SESSION['cart'][$productId]);
    }
}

echo json_encode(['success' => true, 'cartCount' => getCartCount()]);
