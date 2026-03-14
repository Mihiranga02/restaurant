<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

// Allow safe same-site redirect after login (relative paths only)
$redirectAfterLogin = 'index.php';
if (!empty($_GET['redirect'])) {
    $r = ltrim($_GET['redirect'], '/');
    // Only allow simple relative paths – no scheme, no path traversal, no fragments
    if (preg_match('#^[a-zA-Z0-9/_\-\.?=&%]+$#', $r)
        && strpos($r, '://') === false
        && strpos($r, '..') === false) {
        $redirectAfterLogin = $r;
    }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $pdo = getDbConnection();
        if ($pdo) {
            $stmt = $pdo->prepare('SELECT id, name, email, password FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_name']  = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                header('Location: ' . $redirectAfterLogin);
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Database connection error. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – <?php echo htmlspecialchars(SITE_NAME); ?></title>
    <link rel="stylesheet" href="style3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="wrapper">
    <div class="logo">
        <a href="index.php"><img src="image/logo.png" alt="Logo" style="height:60px;"></a>
    </div>
    <h2>Welcome Back</h2>
    <?php if ($error): ?>
        <p class="error-msg" style="color:#ff6b6b;text-align:center;margin-bottom:10px;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
        <div class="input-field">
            <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <label for="email">Email Address</label>
        </div>
        <div class="input-field">
            <input type="password" name="password" id="password" required>
            <label for="password">Password</label>
        </div>
        <button type="submit">Login</button>
    </form>
    <div class="register" style="text-align:center;margin-top:15px;color:#ccc;">
        Don't have an account? <a href="signup.php" style="color:#fac031;">Sign Up</a>
    </div>
</div>
</body>
</html>
