<div class="admin-sidebar">
    <div class="admin-logo">
        <a href="../index.php"><img src="../image/logo.png" alt="Logo" style="height:50px;"></a>
        <span>Admin</span>
    </div>
    <nav class="admin-nav">
        <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fa fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="products.php" class="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['products.php','add-product.php','edit-product.php']) ? 'active' : ''; ?>">
            <i class="fa fa-utensils"></i> Products
        </a>
        <a href="orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'active' : ''; ?>">
            <i class="fa fa-shopping-bag"></i> Orders
        </a>
        <a href="admins.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'admins.php' ? 'active' : ''; ?>">
            <i class="fa fa-users-cog"></i> Admins
        </a>
        <a href="logout.php">
            <i class="fa fa-sign-out-alt"></i> Logout
        </a>
    </nav>
</div>
