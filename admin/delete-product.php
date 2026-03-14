<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit;
}

$id  = (int)($_POST['id'] ?? 0);
$pdo = getDbConnection();

if ($pdo && $id > 0) {
    $stmt = $pdo->prepare('SELECT image FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        if ($product['image'] && strpos($product['image'], 'image/products/') === 0) {
            $oldFile = realpath('../' . $product['image']);
            $baseDir = realpath('../image/products/');
            if ($oldFile && $baseDir && strpos($oldFile, $baseDir) === 0) {
                @unlink($oldFile);
            }
        }
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $_SESSION['flash'] = 'Product deleted successfully.';
    }
}

header('Location: products.php');
exit;
