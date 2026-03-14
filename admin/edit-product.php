<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$pdo     = getDbConnection();
$error   = '';
$product = null;
$id      = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if (!$pdo || $id < 1) {
    header('Location: products.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, name, description, price, image, category FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']        ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = trim($_POST['price']       ?? '');
    $category    = trim($_POST['category']    ?? '');
    $imagePath   = $product['image'];

    if (empty($name) || empty($price)) {
        $error = 'Name and price are required.';
    } elseif (!is_numeric($price) || (float)$price < 0) {
        $error = 'Price must be a positive number.';
    } else {
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (!in_array($ext, $allowed, true)) {
                $error = 'Invalid image type.';
            } else {
                $uploadDir = '../image/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $filename = uniqid('prod_') . '.' . $ext;
                $dest     = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    if ($imagePath && strpos($imagePath, 'image/products/') === 0) {
                        $oldFile = realpath('../' . $imagePath);
                        $baseDir = realpath('../image/products/');
                        if ($oldFile && $baseDir && strpos($oldFile, $baseDir) === 0) {
                            @unlink($oldFile);
                        }
                    }
                    $imagePath = 'image/products/' . $filename;
                } else {
                    $error = 'Failed to upload image.';
                }
            }
        }

        if (empty($error)) {
            $stmt = $pdo->prepare(
                'UPDATE products SET name = ?, description = ?, price = ?, image = ?, category = ? WHERE id = ?'
            );
            $stmt->execute([$name, $description, (float)$price, $imagePath, $category, $id]);
            $_SESSION['flash'] = 'Product updated successfully.';
            header('Location: products.php');
            exit;
        }
    }

    $product = array_merge($product, [
        'name' => $name, 'description' => $description,
        'price' => $price, 'category' => $category,
    ]);
}

$categories = ['Kottu','Biriyanies','Fried Rice','Noodles','Nasi Goreng','Rice & Curry','Bites & Curries','Snacks','Desserts','Cakes','Soft Drinks'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product – Admin</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-body">
<?php include 'sidebar.php'; ?>
<div class="admin-content">
    <div class="admin-header">
        <h1>Edit Product</h1>
        <a href="products.php" class="btn btn-edit">&larr; Back</a>
    </div>
    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <div class="admin-section">
        <form method="POST" action="" enctype="multipart/form-data" class="admin-form">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <div class="form-group">
                <label>Product Name *</label>
                <input type="text" name="name" required value="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label>Price (Rs.) *</label>
                <input type="number" name="price" step="0.01" min="0" required
                       value="<?php echo htmlspecialchars($product['price']); ?>">
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    <option value="">— Select Category —</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>"
                            <?php echo ($product['category'] === $cat) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <?php if ($product['image']): ?>
                <label>Current Image</label>
                <div style="margin-bottom:10px;">
                    <img src="../<?php echo htmlspecialchars($product['image']); ?>"
                         alt="current" style="height:80px;border-radius:6px;">
                </div>
                <?php endif; ?>
                <label>New Image (leave blank to keep current)</label>
                <input type="file" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
</div>
</body>
</html>
