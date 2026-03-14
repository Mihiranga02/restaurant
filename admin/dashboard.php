<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = getDbConnection();
$stats = ['orders' => 0, 'products' => 0, 'users' => 0, 'revenue' => 0];
if ($pdo) {
    $stats['orders']   = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
    $stats['products'] = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    $stats['users']    = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    $stats['revenue']  = $pdo->query('SELECT COALESCE(SUM(total_price),0) FROM orders WHERE status != "cancelled"')->fetchColumn();
    $recentOrders = $pdo->query(
        'SELECT o.id, u.name AS user_name, o.total_price, o.status, o.order_date
         FROM orders o JOIN users u ON u.id = o.user_id
         ORDER BY o.order_date DESC LIMIT 5'
    )->fetchAll(PDO::FETCH_ASSOC);
} else {
    $recentOrders = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – Admin</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-body">
<?php include 'sidebar.php'; ?>
<div class="admin-content">
    <div class="admin-header">
        <h1>Dashboard</h1>
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
    </div>
    <div class="stats-grid">
        <div class="stat-card">
            <i class="fa fa-shopping-bag"></i>
            <div>
                <h2><?php echo $stats['orders']; ?></h2>
                <p>Total Orders</p>
            </div>
        </div>
        <div class="stat-card">
            <i class="fa fa-utensils"></i>
            <div>
                <h2><?php echo $stats['products']; ?></h2>
                <p>Products</p>
            </div>
        </div>
        <div class="stat-card">
            <i class="fa fa-users"></i>
            <div>
                <h2><?php echo $stats['users']; ?></h2>
                <p>Users</p>
            </div>
        </div>
        <div class="stat-card">
            <i class="fa fa-dollar-sign"></i>
            <div>
                <h2>Rs.<?php echo number_format((float)$stats['revenue'], 2); ?></h2>
                <p>Revenue</p>
            </div>
        </div>
    </div>
    <div class="admin-section">
        <h2>Recent Orders</h2>
        <?php if (empty($recentOrders)): ?>
        <p style="color:#aaa;">No orders yet.</p>
        <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr><th>ID</th><th>Customer</th><th>Amount</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
            <?php foreach ($recentOrders as $order): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                    <td>Rs.<?php echo number_format((float)$order['total_price'], 2); ?></td>
                    <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
