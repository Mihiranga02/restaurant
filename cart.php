<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$basePath  = '';
$pageTitle = SITE_NAME . ' - Cart';

// ── Session cart helpers (guest) ─────────────────────────────────────────────
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ── Merge session cart into DB cart on login ─────────────────────────────────
function mergeSessionCartToDB(PDO $pdo, int $userId): void {
    if (empty($_SESSION['cart'])) return;
    foreach ($_SESSION['cart'] as $productId => $qty) {
        $stmt = $pdo->prepare(
            'INSERT INTO cart (user_id, product_id, quantity)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)'
        );
        $stmt->execute([$userId, $productId, $qty]);
    }
    $_SESSION['cart'] = [];
}

$pdo = getDbConnection();

if (isLoggedIn() && $pdo) {
    mergeSessionCartToDB($pdo, $_SESSION['user_id']);
}

// ── Handle POST actions ───────────────────────────────────────────────────────
$action    = $_POST['action']    ?? ($_GET['action'] ?? '');
$productId = (int)($_POST['product_id'] ?? ($_GET['product_id'] ?? 0));
$quantity  = (int)($_POST['quantity'] ?? 1);
$redirect  = $_POST['redirect'] ?? 'cart.php';

if (in_array($action, ['add', 'update', 'remove'], true) && $productId > 0) {
    if (isLoggedIn() && $pdo) {
        if ($action === 'add') {
            $qty = max(1, $quantity);
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
        // Guest session cart
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
    header('Location: ' . $redirect);
    exit;
}

// ── Load cart items ───────────────────────────────────────────────────────────
$cartItems  = [];
$totalPrice = 0.0;

if (isLoggedIn() && $pdo) {
    $stmt = $pdo->prepare(
        'SELECT c.product_id, c.quantity, p.name, p.price, p.image
         FROM cart c
         JOIN products p ON p.id = c.product_id
         WHERE c.user_id = ?
         ORDER BY c.id'
    );
    $stmt->execute([$_SESSION['user_id']]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cartItems as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }
} elseif (!empty($_SESSION['cart']) && $pdo) {
    $ids = array_values(array_filter(array_map('intval', array_keys($_SESSION['cart'])), fn($v) => $v > 0));
    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as $p) {
            $qty = $_SESSION['cart'][$p['id']] ?? 1;
            $cartItems[] = [
                'product_id' => $p['id'],
                'quantity'   => $qty,
                'name'       => $p['name'],
                'price'      => $p['price'],
                'image'      => $p['image'],
            ];
            $totalPrice += $p['price'] * $qty;
        }
    }
}

include 'includes/header.php';
?>
<div style="max-width:1000px;margin:60px auto;padding:20px;">
    <h1 style="color:#fac031;margin-bottom:30px;">Shopping Cart
        <?php if (!empty($cartItems)): ?>
        <span style="font-size:1rem;color:#ccc;">(<?php echo count($cartItems); ?> items)</span>
        <?php endif; ?>
    </h1>

    <?php if (empty($cartItems)): ?>
    <div style="text-align:center;padding:60px;background:rgba(0,0,0,0.5);border-radius:12px;color:#fff;">
        <i class="fa fa-shopping-cart" style="font-size:4rem;color:#555;margin-bottom:20px;display:block;"></i>
        <h2>Your cart is empty</h2>
        <p style="color:#aaa;margin:15px 0 25px;">Looks like you haven't added anything yet.</p>
        <a href="index.php#Menu" style="background:#fac031;color:#000;padding:12px 28px;border-radius:8px;font-weight:700;text-decoration:none;">Browse Menu</a>
    </div>
    <?php else: ?>
    <div style="display:flex;gap:30px;flex-wrap:wrap;">
        <div style="flex:2;min-width:300px;">
            <?php foreach ($cartItems as $item): ?>
            <div style="display:flex;align-items:center;gap:15px;background:rgba(0,0,0,0.5);border-radius:10px;padding:15px;margin-bottom:15px;">
                <?php if ($item['image']): ?>
                <img src="<?php echo htmlspecialchars($item['image']); ?>"
                     alt="<?php echo htmlspecialchars($item['name']); ?>"
                     style="width:80px;height:80px;object-fit:cover;border-radius:8px;">
                <?php else: ?>
                <div style="width:80px;height:80px;background:#333;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#777;font-size:0.7rem;">No Img</div>
                <?php endif; ?>
                <div style="flex:1;color:#fff;">
                    <h3 style="margin-bottom:4px;"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p style="color:#fac031;margin:0 0 3px;">Rs.<?php echo number_format((float)$item['price'], 2); ?> each</p>
                    <p style="color:#aaa;font-size:0.85rem;">Subtotal: <strong style="color:#fff;">Rs.<?php echo number_format((float)$item['price'] * $item['quantity'], 2); ?></strong></p>
                </div>
                <form method="POST" action="cart.php" id="qtyForm_<?php echo (int)$item['product_id']; ?>" style="display:flex;align-items:center;gap:4px;">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="product_id" value="<?php echo (int)$item['product_id']; ?>">
                    <button type="button"
                            onclick="changeQty(<?php echo (int)$item['product_id']; ?>, -1)"
                            style="background:#444;color:#fff;border:none;width:32px;height:32px;border-radius:6px;cursor:pointer;font-size:1.3rem;line-height:1;">&#8722;</button>
                    <input type="number" name="quantity" id="qty_<?php echo (int)$item['product_id']; ?>"
                           value="<?php echo (int)$item['quantity']; ?>"
                           min="0" max="99"
                           style="width:52px;padding:5px;background:#333;border:1px solid #555;color:#fff;border-radius:6px;text-align:center;">
                    <button type="button"
                            onclick="changeQty(<?php echo (int)$item['product_id']; ?>, 1)"
                            style="background:#444;color:#fff;border:none;width:32px;height:32px;border-radius:6px;cursor:pointer;font-size:1.3rem;line-height:1;">&#43;</button>
                </form>
                <form method="POST" action="cart.php">
                    <input type="hidden" name="action" value="remove">
                    <input type="hidden" name="product_id" value="<?php echo (int)$item['product_id']; ?>">
                    <button type="submit" style="background:#e74c3c;color:#fff;border:none;padding:7px 12px;border-radius:6px;cursor:pointer;" title="Remove item">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="flex:1;min-width:240px;">
            <div style="background:rgba(0,0,0,0.6);border-radius:12px;padding:25px;color:#fff;position:sticky;top:20px;">
                <h2 style="margin-bottom:20px;border-bottom:1px solid #444;padding-bottom:10px;">Order Summary</h2>
                <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                    <span style="color:#ccc;">Subtotal</span>
                    <span>Rs.<?php echo number_format($totalPrice, 2); ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:20px;">
                    <span style="color:#ccc;">Delivery</span>
                    <span style="color:#2ecc71;">Free</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:1.2rem;font-weight:700;border-top:1px solid #444;padding-top:15px;margin-bottom:20px;">
                    <span>Total</span>
                    <span style="color:#fac031;">Rs.<?php echo number_format($totalPrice, 2); ?></span>
                </div>
                <?php if (isLoggedIn()): ?>
                <a href="checkout.php"
                   style="display:block;background:#fac031;color:#000;padding:14px;border-radius:8px;font-weight:700;text-decoration:none;text-align:center;">
                    Proceed to Checkout
                </a>
                <?php else: ?>
                <p style="color:#aaa;margin-bottom:15px;font-size:0.9rem;">Please log in to proceed to checkout.</p>
                <a href="login.php?redirect=cart.php"
                   style="display:block;background:#fac031;color:#000;padding:14px;border-radius:8px;font-weight:700;text-decoration:none;text-align:center;">
                    Login to Checkout
                </a>
                <?php endif; ?>
                <a href="index.php#Menu"
                   style="display:block;text-align:center;margin-top:12px;color:#aaa;text-decoration:none;">
                    &larr; Continue Shopping
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<script>
function changeQty(productId, delta) {
    var input = document.getElementById('qty_' + productId);
    if (!input) return;
    var newVal = Math.max(0, (parseInt(input.value, 10) || 1) + delta);
    input.value = newVal;
    document.getElementById('qtyForm_' + productId).submit();
}
</script>
<?php include 'includes/footer.php'; ?>
