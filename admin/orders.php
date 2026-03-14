<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$pdo     = getDbConnection();
$message = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId   = (int)$_POST['order_id'];
    $newStatus = $_POST['status'];
    $allowed   = ['pending', 'processing', 'completed', 'cancelled'];
    if ($pdo && in_array($newStatus, $allowed, true) && $orderId > 0) {
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([$newStatus, $orderId]);
        $_SESSION['flash'] = 'Order status updated.';
    }
    header('Location: orders.php');
    exit;
}

$orders = [];
if ($pdo) {
    $orders = $pdo->query(
        'SELECT o.id, u.name AS user_name, u.email AS user_email,
                o.total_price, o.status, o.order_date
         FROM orders o
         JOIN users u ON u.id = o.user_id
         ORDER BY o.order_date DESC'
    )->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders – Admin</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-body">
<?php include 'sidebar.php'; ?>
<div class="admin-content">
    <div class="admin-header">
        <h1>Orders</h1>
        <span style="color:#aaa;"><?php echo count($orders); ?> total orders</span>
    </div>
    <?php if ($message): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <div class="admin-section">
        <?php if (empty($orders)): ?>
        <p style="color:#aaa;">No orders yet.</p>
        <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr><th>Order ID</th><th>Customer</th><th>Email</th><th>Total</th><th>Date</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                <td><?php echo htmlspecialchars($order['user_email']); ?></td>
                <td>Rs.<?php echo number_format((float)$order['total_price'], 2); ?></td>
                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                <td>
                    <form method="POST" action="" style="display:flex;gap:6px;align-items:center;">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <select name="status" style="padding:5px 8px;background:#0f3460;border:1px solid #444;color:#fff;border-radius:6px;font-size:0.85rem;">
                            <?php foreach (['pending','processing','completed','cancelled'] as $s): ?>
                            <option value="<?php echo $s; ?>" <?php echo $order['status'] === $s ? 'selected' : ''; ?>>
                                <?php echo ucfirst($s); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary" style="padding:5px 10px;font-size:0.8rem;">Update</button>
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
