<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$pdo      = getDbConnection();
$products = [];
$message  = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

if ($pdo) {
    $products = $pdo->query('SELECT id, name, description, price, image, category FROM products ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products – Admin</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-body">
<?php include 'sidebar.php'; ?>
<div class="admin-content">
    <div class="admin-header">
        <h1>Products</h1>
        <a href="add-product.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add Product</a>
    </div>
    <?php if ($message): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <div class="admin-section">
        <?php if (empty($products)): ?>
        <p style="color:#aaa;">No products yet. <a href="add-product.php" style="color:#fac031;">Add one</a>.</p>
        <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td>
                    <?php if ($product['image']): ?>
                    <img src="../<?php echo htmlspecialchars($product['image']); ?>"
                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                    <div style="width:50px;height:50px;background:#333;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#777;font-size:0.7rem;">-</div>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['category'] ?? '-'); ?></td>
                <td>Rs.<?php echo number_format((float)$product['price'], 2); ?></td>
                <td>
                    <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-edit">Edit</a>
                    <form method="POST" action="delete-product.php" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
