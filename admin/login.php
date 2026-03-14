<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $pdo = getDbConnection();
        if ($pdo) {
            $stmt = $pdo->prepare('SELECT id, username, password FROM admins WHERE username = ?');
            $stmt->execute([$username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id']       = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Database connection error.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – <?php echo htmlspecialchars(SITE_NAME); ?></title>
    <link rel="stylesheet" href="../style3.css">
</head>
<body>
<div class="wrapper">
    <h2 style="color:#fac031;">Admin Panel</h2>
    <?php if ($error): ?>
        <p style="color:#ff6b6b;text-align:center;margin-bottom:10px;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="input-field">
            <input type="text" name="username" id="username" required
                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            <label for="username">Username</label>
        </div>
        <div class="input-field">
            <input type="password" name="password" id="password" required>
            <label for="password">Password</label>
        </div>
        <button type="submit">Login</button>
    </form>
    <div style="text-align:center;margin-top:15px;">
        <a href="../index.php" style="color:#aaa;text-decoration:none;">← Back to Site</a>
    </div>
</div>
</body>
</html>
