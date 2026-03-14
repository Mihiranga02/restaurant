<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $pdo = getDbConnection();
        if ($pdo) {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'An account with this email already exists.';
            } else {
                $hashed = password_hash($password, PASSWORD_BCRYPT);
                $stmt   = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
                $stmt->execute([$name, $email, $hashed]);
                $userId = $pdo->lastInsertId();
                $_SESSION['user_id']    = $userId;
                $_SESSION['user_name']  = $name;
                $_SESSION['user_email'] = $email;
                header('Location: index.php');
                exit;
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
    <title>Sign Up – <?php echo htmlspecialchars(SITE_NAME); ?></title>
    <link rel="stylesheet" href="style3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="wrapper register-wrapper">
    <div class="logo">
        <a href="index.php"><img src="image/logo.png" alt="Logo" style="height:60px;"></a>
    </div>
    <h2>Create Account</h2>
    <?php if ($error): ?>
        <p class="error-msg" style="color:#ff6b6b;text-align:center;margin-bottom:10px;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="input-field">
            <input type="text" name="name" id="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            <label for="name">Full Name</label>
        </div>
        <div class="input-field">
            <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <label for="email">Email Address</label>
        </div>
        <div class="input-field">
            <input type="password" name="password" id="password" required>
            <label for="password">Password</label>
        </div>
        <div class="input-field">
            <input type="password" name="confirm_password" id="confirm_password" required>
            <label for="confirm_password">Confirm Password</label>
        </div>
        <button type="submit">Create Account</button>
    </form>
    <div class="register" style="text-align:center;margin-top:15px;color:#ccc;">
        Already have an account? <a href="login.php" style="color:#fac031;">Login</a>
    </div>
</div>
</body>
</html>
