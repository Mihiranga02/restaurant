<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireLogin('login.php');

$basePath  = '';
$pageTitle = SITE_NAME . ' - Checkout';

$pdo = getDbConnection();
$cartItems  = [];
$totalPrice = 0.0;
$orderId    = null;
$error      = '';

if ($pdo) {
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
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($cartItems) && $pdo) {
    $name    = trim($_POST['name']    ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($name) || empty($address)) {
        $error = 'Please fill in your name and delivery address.';
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare(
                'INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, ?)'
            );
            $stmt->execute([$_SESSION['user_id'], $totalPrice, 'pending']);
            $orderId = $pdo->lastInsertId();

            $stmt = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)'
            );
            foreach ($cartItems as $item) {
                $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
            }

            $stmt = $pdo->prepare('DELETE FROM cart WHERE user_id = ?');
            $stmt->execute([$_SESSION['user_id']]);

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Order could not be placed. Please try again.';
            $orderId = null;
        }
    }
}

include 'includes/header.php';
?>
<div style="max-width:900px;margin:60px auto;padding:20px;">
    <?php if ($orderId): ?>
    <!-- Order Confirmation -->
    <div style="text-align:center;padding:60px 20px;background:rgba(0,0,0,0.6);border-radius:12px;color:#fff;">
        <i class="fa fa-check-circle" style="font-size:5rem;color:#2ecc71;margin-bottom:20px;display:block;"></i>
        <h1 style="color:#2ecc71;margin-bottom:10px;">Order Placed!</h1>
        <p style="color:#ccc;font-size:1.1rem;margin-bottom:10px;">
            Thank you for your order. Your order ID is
            <strong style="color:#fac031;">#<?php echo $orderId; ?></strong>
        </p>
        <p style="color:#aaa;margin-bottom:30px;">We'll prepare your food right away!</p>
        <a href="index.php" style="background:#fac031;color:#000;padding:12px 28px;border-radius:8px;font-weight:700;text-decoration:none;">Back to Home</a>
    </div>
    <?php elseif (empty($cartItems)): ?>
    <div style="text-align:center;padding:60px;background:rgba(0,0,0,0.5);border-radius:12px;color:#fff;">
        <h2>Your cart is empty</h2>
        <p style="color:#aaa;margin:15px 0 25px;">Add some items before checking out.</p>
        <a href="index.php#Menu" style="background:#fac031;color:#000;padding:12px 28px;border-radius:8px;font-weight:700;text-decoration:none;">Browse Menu</a>
    </div>
    <?php else: ?>
    <h1 style="color:#fac031;margin-bottom:30px;">Checkout</h1>
    <?php if ($error): ?>
    <p style="color:#ff6b6b;background:rgba(255,107,107,0.1);padding:12px;border-radius:8px;margin-bottom:20px;">
        <?php echo htmlspecialchars($error); ?>
    </p>
    <?php endif; ?>
    <div style="display:flex;gap:30px;flex-wrap:wrap;">
        <!-- Order Items -->
        <div style="flex:1;min-width:280px;">
            <h2 style="color:#fff;margin-bottom:15px;">Order Summary</h2>
            <?php foreach ($cartItems as $item): ?>
            <div style="display:flex;justify-content:space-between;align-items:center;background:rgba(0,0,0,0.5);border-radius:8px;padding:12px 15px;margin-bottom:10px;color:#fff;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <?php if ($item['image']): ?>
                    <img src="<?php echo htmlspecialchars($item['image']); ?>"
                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                         style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                    <?php endif; ?>
                    <div>
                        <p style="font-weight:600;"><?php echo htmlspecialchars($item['name']); ?></p>
                        <p style="color:#aaa;font-size:0.85rem;">Qty: <?php echo $item['quantity']; ?></p>
                    </div>
                </div>
                <span style="color:#fac031;font-weight:700;">
                    Rs.<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                </span>
            </div>
            <?php endforeach; ?>
            <div style="background:rgba(0,0,0,0.6);border-radius:8px;padding:15px;margin-top:10px;color:#fff;display:flex;justify-content:space-between;font-size:1.2rem;font-weight:700;">
                <span>Total</span>
                <span style="color:#fac031;">Rs.<?php echo number_format($totalPrice, 2); ?></span>
            </div>
        </div>
        <!-- Delivery Form -->
        <div style="flex:1;min-width:280px;">
            <h2 style="color:#fff;margin-bottom:15px;">Delivery Details</h2>
            <form method="POST" action="checkout.php">
                <div style="margin-bottom:15px;">
                    <label style="color:#ccc;display:block;margin-bottom:6px;">Full Name</label>
                    <input type="text" name="name"
                           value="<?php echo htmlspecialchars($_POST['name'] ?? $_SESSION['user_name'] ?? ''); ?>"
                           required
                           style="width:100%;padding:12px;background:#222;border:1px solid #555;color:#fff;border-radius:8px;font-size:1rem;">
                </div>
                <div style="margin-bottom:20px;">
                    <label style="color:#ccc;display:block;margin-bottom:6px;">Delivery Address</label>
                    <textarea name="address" required rows="4"
                              style="width:100%;padding:12px;background:#222;border:1px solid #555;color:#fff;border-radius:8px;font-size:1rem;resize:vertical;"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                </div>
                <button type="submit"
                        style="width:100%;background:#fac031;color:#000;border:none;padding:14px;border-radius:8px;font-size:1rem;font-weight:700;cursor:pointer;">
                    Place Order – Rs.<?php echo number_format($totalPrice, 2); ?>
                </button>
            </form>
            <a href="cart.php" style="display:block;text-align:center;margin-top:12px;color:#aaa;text-decoration:none;">
                &larr; Back to Cart
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
