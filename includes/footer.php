<?php
/**
 * footer.php - Reusable footer for category pages
 *
 * Expects:
 *   $basePath (string) - relative path prefix to reach the site root (e.g. '../' for pages/)
 */
?>
<!-- footer -->
<footer>
        <div class="footer_main">

            <div class="footer_tag">
                <h2>Location</h2>
                <p>One Galle Face Mall,</p>
                <p>1A Centre Road,</p>
                <p>Colombo,</p>
                <p>Sri Lanka</p>
                
            </div>

            <div class="footer_tag_1">
                <h2 class="ft">Quick Link</h2>
                <ul>
                    <li><a href="<?php echo $basePath; ?>index.php#Home">Home</a></li>
                    <li><a href="<?php echo $basePath; ?>index.php#About">About</a></li>
                    <li><a href="<?php echo $basePath; ?>index.php#Menu">Menu</a></li>
                    <li><a href="<?php echo $basePath; ?>index.php#Gallary">Gallary</a></li>
                    <li><a href="<?php echo $basePath; ?>index.php#Review">Review</a></li>
                </ul>    
            </div>

            <div class="footer_tag">
                <h2>Contact</h2>
                <p><?php echo SITE_PHONE_1; ?></p>
                <p><?php echo SITE_PHONE_2; ?></p>
                <p><?php echo SITE_EMAIL_1; ?></p>
                <p><?php echo SITE_EMAIL_2; ?></p>
            </div>

            <div class="footer_tag">
                <h2>Our Service</h2>
                <p>Fast Delivery</p>
                <p>Easy Payments</p>
                <h3>Opening Hours</h3>
                <p><?php echo SITE_HOURS; ?></p>
            </div>

            <div class="footer_tag">
                <h2>Follow Us</h2>
                <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://www.whatsapp.com"><i class="fa-brands fa-whatsapp"></i></a>
            </div>

        </div>

        <p class="end"></p>

    </footer>  
</body>
</html>
