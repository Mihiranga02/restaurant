<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$pdo    = getDbConnection();
$error  = '';
$flash  = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

// ── Handle POST: add or delete admin ────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = 'Username and password are required.';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $stmt = $pdo->prepare('SELECT id FROM admins WHERE username = ?');
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = "Username '{$username}' is already taken.";
            } else {
                $hashed = password_hash($password, PASSWORD_BCRYPT);
                $stmt   = $pdo->prepare('INSERT INTO admins (username, password) VALUES (?, ?)');
                $stmt->execute([$username, $hashed]);
                $_SESSION['flash'] = "Admin '{$username}' created successfully.";
                header('Location: admins.php');
                exit;
            }
        }

    } elseif ($action === 'delete') {
        $deleteId = (int)($_POST['delete_id'] ?? 0);

        if ($deleteId === (int)$_SESSION['admin_id']) {
            $error = 'You cannot delete your own account.';
        } elseif ($deleteId > 0) {
            // Prevent deleting the last admin
            $count = (int)$pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
            if ($count <= 1) {
                $error = 'Cannot delete the last admin account.';
            } else {
                $stmt = $pdo->prepare('DELETE FROM admins WHERE id = ?');
                $stmt->execute([$deleteId]);
                $_SESSION['flash'] = 'Admin account deleted.';
                header('Location: admins.php');
                exit;
            }
        }
    }
}

// ── Load admin list ──────────────────────────────────────────────────────────
$admins = [];
if ($pdo) {
    $admins = $pdo->query('SELECT id, username FROM admins ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins – Admin Panel</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-body">
<?php include 'sidebar.php'; ?>
<div class="admin-content">
    <div class="admin-header">
        <h1>Manage Admins</h1>
        <span>Logged in as: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
    </div>

    <?php if ($flash): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($flash); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Add New Admin -->
    <div class="admin-section" style="margin-bottom:30px;">
        <h2><i class="fa fa-user-plus"></i> Add New Admin</h2>
        <form method="POST" action="admins.php" class="admin-form">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                       placeholder="e.g. admin2">
            </div>
            <div class="form-group">
                <label for="password">Password <small style="color:#aaa;">(min 8 characters)</small></label>
                <input type="password" name="password" id="password" required minlength="8" placeholder="••••••••">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required minlength="8" placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Create Admin</button>
        </form>
    </div>

    <!-- Existing Admins -->
    <div class="admin-section">
        <h2><i class="fa fa-users-cog"></i> Existing Admins</h2>
        <?php if (empty($admins)): ?>
        <p style="color:#aaa;">No admins found.</p>
        <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($admins as $admin): ?>
            <tr>
                <td><?php echo (int)$admin['id']; ?></td>
                <td>
                    <?php echo htmlspecialchars($admin['username']); ?>
                    <?php if ((int)$admin['id'] === (int)$_SESSION['admin_id']): ?>
                    <span class="status-badge status-processing" style="margin-left:8px;">You</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ((int)$admin['id'] !== (int)$_SESSION['admin_id']): ?>
                    <form method="POST" action="admins.php" style="display:inline;"
                          onsubmit="return confirm('Delete admin ' + <?php echo json_encode($admin['username']); ?> + '?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="delete_id" value="<?php echo (int)$admin['id']; ?>">
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                    </form>
                    <?php else: ?>
                    <span style="color:#555;font-size:0.85rem;">Cannot delete own account</span>
                    <?php endif; ?>
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
