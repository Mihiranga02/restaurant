<?php
/**
 * header.php - Reusable navigation header for category pages
 *
 * Expects:
 *   $pageTitle  (string) - the <title> tag content
 *   $basePath   (string) - relative path prefix to reach the site root (e.g. '../' for pages/)
 */
if (!function_exists('isLoggedIn')) {
    require_once $basePath . 'includes/auth.php';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="icon" href="<?php echo $basePath; ?>image/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo $basePath; ?>style2.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<!-- navigation bar -->
    <section id="Home">
          <nav>
             <div class="logo">
                <a href="#Home"><img src="<?php echo $basePath; ?>image/logo 3.png"></a>
            </div>

            <ul> 
                <li><a href="<?php echo $basePath; ?>index.php#Home">Home</a></li>
                <li><a href="<?php echo $basePath; ?>index.php#About">About</a></li>
                <li><a href="<?php echo $basePath; ?>index.php#Menu">Menu</a></li>
                <li><a href="<?php echo $basePath; ?>index.php#Gallary">Gallary</a></li>
                <li><a href="<?php echo $basePath; ?>index.php#Review">Review</a></li>
                <?php if (isLoggedIn()): ?>
                <li><a href="<?php echo $basePath; ?>cart.php"><i class="fa fa-shopping-cart"></i> Cart</a></li>
                <li><a href="<?php echo $basePath; ?>logout.php">Logout</a></li>
                <?php else: ?>
                <li><a href="<?php echo $basePath; ?>login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </section>
