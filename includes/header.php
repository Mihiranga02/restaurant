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
$_cartCount = getCartCount();
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <a href="<?php echo $basePath; ?>index.php#Home"><img src="<?php echo $basePath; ?>image/logo 3.png"></a>
            </div>

            <button class="hamburger" id="hamburger" aria-label="Toggle navigation" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>

            <ul id="nav-menu"> 
                <li><a href="<?php echo $basePath; ?>index.php#Home">Home</a></li>
                <li><a href="<?php echo $basePath; ?>index.php#About">About</a></li>
                <li><a href="<?php echo $basePath; ?>index.php#Menu">Menu</a></li>
                <li><a href="<?php echo $basePath; ?>index.php#Gallary">Gallary</a></li>
                <li><a href="<?php echo $basePath; ?>index.php#Review">Review</a></li>
                <?php if (isLoggedIn()): ?>
                <li>
                    <a href="<?php echo $basePath; ?>cart.php" class="cart-link">
                        <i class="fa fa-shopping-cart"></i> Cart
                        <?php if ($_cartCount > 0): ?>
                        <span class="cart-badge" id="cart-badge"><?php echo $_cartCount; ?></span>
                        <?php else: ?>
                        <span class="cart-badge" id="cart-badge" style="display:none;">0</span>
                        <?php endif; ?>
                    </a>
                </li>
                <li><a href="<?php echo $basePath; ?>logout.php">Logout</a></li>
                <?php else: ?>
                <li><a href="<?php echo $basePath; ?>login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </section>
<script>
(function(){
    var btn = document.getElementById('hamburger');
    var menu = document.getElementById('nav-menu');
    if (btn && menu) {
        btn.addEventListener('click', function(){
            var open = menu.classList.toggle('open');
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        // Close nav when any menu link is clicked
        menu.querySelectorAll('a').forEach(function(a){
            a.addEventListener('click', function(){
                menu.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');
            });
        });
    }
})();
</script>
