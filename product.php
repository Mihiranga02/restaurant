<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$basePath  = '';
$pageTitle = SITE_NAME . ' - Product';
$product   = null;
$error     = '';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $pdo = getDbConnection();
    if ($pdo) {
        $stmt = $pdo->prepare('SELECT id, name, description, price, image, category FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    if ($product) {
        $pageTitle = SITE_NAME . ' - ' . $product['name'];
    }
}

include 'includes/header.php';
?>
<div style="max-width:900px;margin:60px auto;padding:20px;">
<?php if (!$product): ?>
    <div style="text-align:center;padding:60px 20px;color:#fff;">
        <h2>Product Not Found</h2>
        <p style="margin:20px 0;"><a href="index.php" style="color:#fac031;">Back to Home</a></p>
    </div>
<?php else: ?>
    <div style="display:flex;gap:40px;flex-wrap:wrap;background:rgba(0,0,0,0.6);border-radius:12px;padding:30px;">
        <div style="flex:1;min-width:260px;">
            <?php if ($product['image']): ?>
            <img src="<?php echo htmlspecialchars($product['image']); ?>"
                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                 style="width:100%;border-radius:10px;object-fit:cover;max-height:360px;">
            <?php else: ?>
            <div style="width:100%;height:300px;background:#333;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#999;">No Image</div>
            <?php endif; ?>
        </div>
        <div style="flex:1;min-width:260px;color:#fff;">
            <h1 style="font-size:2rem;margin-bottom:10px;"><?php echo htmlspecialchars($product['name']); ?></h1>
            <?php if ($product['category']): ?>
            <span style="background:#fac031;color:#000;padding:3px 12px;border-radius:20px;font-size:0.85rem;">
                <?php echo htmlspecialchars($product['category']); ?>
            </span>
            <?php endif; ?>
            <p style="margin:20px 0;line-height:1.7;color:#ccc;">
                <?php echo nl2br(htmlspecialchars($product['description'] ?? '')); ?>
            </p>
            <h2 style="color:#fac031;font-size:1.8rem;margin-bottom:20px;">
                Rs.<?php echo number_format((float)$product['price'], 2); ?>
            </h2>
            <?php if (isLoggedIn()): ?>
            <form method="POST" action="cart.php">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="redirect" value="product.php?id=<?php echo $product['id']; ?>">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                    <label style="color:#ccc;">Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" max="99"
                           style="width:70px;padding:8px;background:#333;border:1px solid #555;color:#fff;border-radius:6px;">
                </div>
                <button type="submit"
                        style="background:#fac031;color:#000;border:none;padding:12px 30px;border-radius:8px;font-size:1rem;font-weight:700;cursor:pointer;">
                    <i class="fa fa-shopping-cart"></i> Add to Cart
                </button>
            </form>
            <?php else: ?>
            <p style="color:#ccc;margin-bottom:15px;">Please log in to add items to your cart.</p>
            <a href="login.php" style="background:#fac031;color:#000;padding:12px 30px;border-radius:8px;font-size:1rem;font-weight:700;text-decoration:none;">
                Login to Order
            </a>
            <?php endif; ?>
            <div style="margin-top:20px;">
                <a href="javascript:history.back()" style="color:#aaa;text-decoration:none;">
                    &larr; Back
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
