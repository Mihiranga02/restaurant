<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Fetch first product ID from each featured category for "Add to Cart" buttons
$pdo = getDbConnection();
$popProducts = [];
if ($pdo) {
    $stmt = $pdo->query(
        "SELECT category, MIN(id) AS pid FROM products
         WHERE category IN ('kottu','noodles','rice-curry','nasi-goreng')
         GROUP BY category"
    );
    if ($stmt) {
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $popProducts[$r['category']] = (int)$r['pid'];
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    
    <title><?php echo SITE_NAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="image/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    .cart-badge {
        background: #000;
        color: #fac031;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 0.72rem;
        font-weight: 700;
        margin-left: 3px;
        vertical-align: middle;
        display: inline-block;
        min-width: 20px;
        text-align: center;
    }
    </style>
</head>
<body>

    <!--Home-->
    <section id="Home">
        <nav><!--nav section-->
            <div class="logo">
                <a href="#Home"><img src="image/logo 3.png"></a>
            </div>

            <button class="hamburger" id="hamburger" aria-label="Toggle navigation" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>

            <ul id="nav-menu"> 
                <li><a href="#Home">Home</a></li>
                <li><a href="#About">About</a></li>
                <li><a href="#Menu">Menu</a></li>
                <li><a href="#Gallary">Gallary</a></li>
                <li><a href="#Review">Review</a></li>
                <?php if (isLoggedIn()): ?>
                <?php $cnt = getCartCount(); ?>
                <li>
                    <a href="cart.php" class="cart-link">
                        <i class="fa fa-shopping-cart"></i> Cart
                        <?php if ($cnt > 0): ?>
                        <span class="cart-badge" id="cart-badge"><?php echo $cnt; ?></span>
                        <?php else: ?>
                        <span class="cart-badge" id="cart-badge" style="display:none;">0</span>
                        <?php endif; ?>
                    </a>
                </li>
                <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)</a></li>
                <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php" style="color:#fac031;">Sign Up</a></li>
                <?php endif; ?>
            
            </ul>

        </nav>

        <div class="main">

            <div class="men_text">
                <h1>Get Fresh<span>Food</span><br></h1>
            </div>

            <div class="main_image">
                <img src="image/main_img.png">
            </div>

        </div>

        <p><b>
             This is a service designed to simplify your grocery shopping 
             experience by delivering fresh, high-quality produce and food items directly to your doorstep. 
             With a user-friendly platform, you can easily browse, select, and order the freshest
             ingredients with just a few clicks. The service ensures that you receive top-notch 
             products, making healthy eating convenient and accessible for everyone. Whether 
             you're cooking at home or need groceries for the week, this service makes it 
             effortless to enjoy fresh food without the hassle.
        </b></p>

        
    </section>



    <!--About-->

    <div class="about" id="About">
        <div class="about_main">

            <div class="image">
                <img src="image/Food-Plate.png">
            </div>

            <div class="about_text">
                <h1><span>About</span>Us</h1>
                <h3>Why Choose us?</h3>
                <p><b>
                    We offers a unique blend of flavors and a warm, inviting atmosphere.
                    The menu features
                    fresh, locally sourced ingredients, which ensure every dish
                    is both delicious and of the highest quality. The staff is
                    friendly and attentive, making every visit a pleasant
                    experience. Whether it's for a casual meal or a special
                    occasion, this restaurant consistently delivers exceptional
                    food and service that keeps me coming back.
                </p></b>
            </div>

        </div>
    </div>

<!--Menu-->

<div class="menu" id="Menu">
    <h1>Popular<span>Now</span></h1>

<!--kottu-->

    <div class="menu_box">
        <div class="menu_card">

            <div class="menu_image">
                <img src="image/kottu.jpg">
            </div>

            <div class="menu_info">
                <h2>Kottu</h2>
                <p>
                    A popular street food made from chopped roti 
                    stir-fried with vegetables, meat, and spices.   
                </p>
                <h3>Rs.850.00</h3>
                <?php if (!empty($popProducts['kottu'])): ?>
                <button type="button" class="menu_btn add-to-cart-btn"
                        data-product-id="<?php echo $popProducts['kottu']; ?>"
                        style="background:#fac031;color:#000;border:none;padding:8px 18px;border-radius:6px;cursor:pointer;font-weight:700;width:100%;">Add to Cart</button>
                <?php else: ?>
                <a href="pages/category.php?category=kottu" class="menu_btn">View Menu</a>
                <?php endif; ?>
            </div>
         </div> 

<!--Pasta-->
        <div class="menu_card">

            <div class="menu_image">
                <img src="image/pasta.jpg">
            </div>

            <div class="menu_info">
                <h2>Pasta</h2>
                <p>
                    Pasta tossed with a sauce made from 
                    fresh basil, garlic, pine nuts, Parmesan 
                    cheese, and olive oil.
                </p>
                <h3>Rs.600.00</h3>
                <?php if (!empty($popProducts['noodles'])): ?>
                <button type="button" class="menu_btn add-to-cart-btn"
                        data-product-id="<?php echo $popProducts['noodles']; ?>"
                        style="background:#fac031;color:#000;border:none;padding:8px 18px;border-radius:6px;cursor:pointer;font-weight:700;width:100%;">Add to Cart</button>
                <?php else: ?>
                <a href="pages/category.php?category=noodles" class="menu_btn">View Menu</a>
                <?php endif; ?>
            </div>

        </div> 
<!--Rice & curry-->
        <div class="menu_card">

            <div class="menu_image">
                <img src="image/rice.jpg">
            </div>
            <div class="menu_info">
                <h2>Rice & Curry</h2>
                <p>
                    A staple meal consisting of steamed 
                    rice served with a variety of curries,
                     which can include vegetables, meat, or seafood.
                </p>
   
                <h3>Rs.350.00</h3>
                <?php if (!empty($popProducts['rice-curry'])): ?>
                <button type="button" class="menu_btn add-to-cart-btn"
                        data-product-id="<?php echo $popProducts['rice-curry']; ?>"
                        style="background:#fac031;color:#000;border:none;padding:8px 18px;border-radius:6px;cursor:pointer;font-weight:700;width:100%;">Add to Cart</button>
                <?php else: ?>
                <a href="pages/category.php?category=rice-curry" class="menu_btn">View Menu</a>
                <?php endif; ?>
            </div>

        </div> 
<!--hopper-->
        <div class="menu_card">

            <div class="menu_image">
                <img src="image/Chicken Nasi Goreng.jpg">
            </div>
            <div class="menu_info">
                <h2>Nasi Goreng</h2>
                <p>
                    popular Indonesian fried rice dish, 
                    made with soy sauce, garlic, and chili, 
                    often topped with a fried egg
                </p>
                <h3>Rs.870.00</h3>
                <?php if (!empty($popProducts['nasi-goreng'])): ?>
                <button type="button" class="menu_btn add-to-cart-btn"
                        data-product-id="<?php echo $popProducts['nasi-goreng']; ?>"
                        style="background:#fac031;color:#000;border:none;padding:8px 18px;border-radius:6px;cursor:pointer;font-weight:700;width:100%;">Add to Cart</button>
                <?php else: ?>
                <a href="pages/category.php?category=nasi-goreng" class="menu_btn">View Menu</a>
                <?php endif; ?>
            </div>

        </div>

    </div>

</div>

<!-- categories -->
<div class="category" id="category">
    <h1>All<span>Categories</span></h1>

   
    <div class="category_box">
         <!-- kottu -->
        <div class="category_card">
            <div class="category_image">
                <a href="pages/category.php?category=kottu">
                <img src="image/kottu.jpg"></a>
            </div>

            <div class="category_info">
                <h2>Kottu</h2>
            </div>
        </div> 
        <!-- biriyani -->
        <div class="category_card">
            <div class="category_image">
                <a href="pages/category.php?category=biriyanies">
                <img src="Food Items/Biriyanies/Roasted Chicken Biriyani.jpg"></a>
            </div>

            <div class="category_info">
                <h2>Biriyani</h2>
            </div>
        </div> 
        <!-- fried rice -->
        <div class="category_card">
            <div class="category_image">
                <a href="pages/category.php?category=fried-rice">
                <img src="Food Items/Fried Rice/Chicken Fried Rice.jpg"></a>
            </div>

            <div class="category_info">
                <h2>Fried Rice</h2>
            </div>
        </div> 
        <!-- noodles -->
        <div class="category_card">
            <div class="category_image">
                <a href="pages/category.php?category=noodles">
                <img src="Food Items/Noodles/Vegetable Fried Noodles.jpeg"></a>
            </div>

            <div class="category_info">
                <h2>Noodles</h2>
            </div>
        </div> 
        <!-- nasi goreng -->
        <div class="category_card">
            <div class="category_image">
                <a href="pages/category.php?category=nasi-goreng">
                <img src="Food Items/Nasi Goreng/Chicken Nasi Goreng.jpg"></a>
            </div>

            <div class="category_info">
                <h2>Nasi Goreng</h2>
            </div>
        </div> 
        <!-- rice and curry -->
        <div class="category_card">
            <div class="category_image">
                <a href="pages/category.php?category=rice-curry">
                <img src="Food Items/Rice & Curry/Chicken Lamprais.jpg"></a>
            </div>

            <div class="category_info">
                <h2>Rice and curry</h2>
            </div>
        </div> 
    </div>
    <div class="category_box2">
        <!-- bites and curries -->
        <div class="category_card">
            <div class="category_image">
                <a href="pages/category.php?category=bites-curries">
                <img src="Food Items/Bites & Curries/Cuttlefish Pepper Fry.jpg"></a>
            </div>

            <div class="category_info">
                <h2>Bites and Curries</h2>
            </div>
        </div>
        <!-- snaks  -->
        <div class="category_card">
            <div class="category_image">
                <a href="pages/category.php?category=snacks">
                <img src="Food Items/Snacks/Chicken Pakoda.jpg"></a>
            </div>

            <div class="category_info">
                <h2>snaks</h2>
            </div>
        </div> 
        <!-- desserts -->
        <div class="category_card">
            <div class="category_image">
                <a href="pages/category.php?category=desserts">
                <img src="Food Items/Dessert/Watalappam.jpg"></a>
            </div>

            <div class="category_info">
                <h2>Desserts</h2>
            </div>
        </div> 
        <!-- cakes -->
        <div class="category_card">
            <div class="category_image">
                <a href="pages/category.php?category=cakes">
                <img src="Food Items/Cakes/Red Velvet Cake with Fruit.jpg"></a>
            </div>

            <div class="category_info">
                <h2>Cakes</h2>
            </div>
        </div> 
        <!-- soft drinks -->
        <div class="category_card">
            <div class="category_image">
                <a href="pages/category.php?category=soft-drinks">
                <img src="Food Items/Soft Drinks/Coke.jpg"></a>
            </div>

            <div class="category_info">
                <h2>Soft Drinks</h2>
            </div>
        </div> 
    </div>
</div>
    

<!--Gallary-->

<div class="gallary" id="Gallary">
    <h1>Our<span>Gallary</span></h1>

    <div class="gallary_image_box">
        <div class="gallary_image">
            <img src="image/gallary_1.jpg">
        </div>

        <div class="gallary_image">
            <img src="image/gallary_2.jpg">
        </div>

        <div class="gallary_image">
            <img src="image/gallary_3.jpg">
        </div>

        <div class="gallary_image">
            <img src="image/gallary_4.jpg">
        </div>

        <div class="gallary_image">
            <img src="image/gallary_5.jpg">
        </div>

        <div class="gallary_image">
            <img src="image/gallary_6.jpg">
        </div>

    </div>

</div>


    <!--Review-->

    <div class="review" id="Review">
        <h1>Customer<span>Review</span></h1>

        <div class="review_box">
            <div class="review_card">

                <div class="review_profile">
                    <img src="image/review 1.jpg">
                </div>

                <div class="review_text">
                    <h2 class="name">Akash Madusanka </h2>

                    <p>
                        Best of Sri Lankan The place to enjoy the best 
                        Sri Lankan dishes. Always busy with guests. 
                        Variety of local foods is massive. Ideal place
                        to visit with foreigners in order to introduce them local foods....
                    </p>

                </div>

            </div>

            <div class="review_card">

                <div class="review_profile">
                    <img src="image/review 2.jpg">
                </div>

                <div class="review_text">
                    <h2 class="name">Kavini Navodya</h2>
                    
                    <p>
                        One of our Friend recommended this restaurant. 
                        We went there and we ordered kottu, appa, 
                        country style chicken curry,parata with fish curry.
                        The food is really very very delicious. We 
                        didn't wait too Long for the food to be served. 
                        We enjoyed the food and ambience. The staff 
                        is very friendly. You must try the kottu. you must 
                        visit restaurant if u are in Colombo and wanted 
                        to try the best Sri Lankan food.
                    </p>

                </div>

            </div>

            <div class="review_card">

                <div class="review_profile">
                    <img src="image/review 3.jpg">
                </div>

                <div class="review_text">
                    <h2 class="name">Janidu Kavishka</h2>

                    <p>
                        Excellent restaurant for Sri Lankan food both
                         for locals and foreigners. Reasonable pricing 
                         and great service. Suggest a reservation if 
                         you are going for dinner especially in the 
                         evening on a weekend. They have a takeaway 
                         outlet right next door for those who 
                         don't want to dine at the restaurant
                    </p>

                </div>

            </div>

            <div class="review_card">

                <div class="review_profile">
                    <img src="image/review 4.jpg">
                </div>

                <div class="review_text">
                    <h2 class="name">Jos Buttler</h2>

                    <p>
                        Good introduction to Sri Lankan food 
                        We came here for our first night in Sri Lanka. Helpful 
                       staff who could explain to us what was what.
                        We particularly enjoyed the vegetarian kottu 
                        but also enjoyed the Sri Lankan vegetarian noodles
                        and a fish curry. Price was more expensive for
                        Sri Lanka but would recommend as the food was delicious.
                    </p>

                </div>

            </div>

        </div>

    </div>

    
<!--Order-->

<div class="fast_delevery">
    <ul> 
        <li><div class="delevery_image">
            <img src="image/output-onlinegiftools.gif">
        </div></li>
        <li><h1>Fast<br> Delevery<br><span>Call For Order.+94 12 345 6789</span></h1></li>
    </ul>
</div>    


    <!--Footer-->

    <!-- toast notification -->
    <div id="cart-toast" style="display:none;position:fixed;bottom:24px;right:24px;background:#fac031;color:#000;padding:12px 22px;border-radius:8px;font-weight:700;z-index:9999;box-shadow:0 4px 12px rgba(0,0,0,0.3);transition:opacity 0.4s;">
        <i class="fa fa-check-circle"></i> Item added to cart!
    </div>

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
                    <li><a href="#Home">Home</a></li>
                    <li><a href="#About">About</a></li>
                    <li><a href="#Menu">Menu</a></li>
                    <li><a href="#Gallary">Gallary</a></li>
                    <li><a href="#Review">Review</a></li>
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
                <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook-f fa-lg"></i></a>
                <a href="https://www.instagram.com/"><i class="fa-brands fa-instagram fa-lg"></i></a>
                <a href="https://www.whatsapp.com"><i class="fa-brands fa-whatsapp fa-lg"></i></a>
            </div>

        </div>

        <p class="end"></p>

    </footer>


    <script src="app.js"></script>
    <script>
    // Hamburger menu toggle
    (function(){
        var btn = document.getElementById('hamburger');
        var menu = document.getElementById('nav-menu');
        if (btn && menu) {
            btn.addEventListener('click', function(){
                var open = menu.classList.toggle('open');
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
            // Close nav when a menu link is clicked (single-page scroll)
            menu.querySelectorAll('a').forEach(function(a){
                a.addEventListener('click', function(){
                    menu.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                });
            });
        }
    })();

    // AJAX add-to-cart
    (function () {
        var actionUrl = 'cart-action.php';

        function showToast() {
            var toast = document.getElementById('cart-toast');
            if (!toast) return;
            toast.style.display = 'block';
            toast.style.opacity = '1';
            clearTimeout(toast._timer);
            toast._timer = setTimeout(function () {
                toast.style.opacity = '0';
                setTimeout(function () { toast.style.display = 'none'; }, 400);
            }, 2500);
        }

        function updateBadge(count) {
            var badge = document.getElementById('cart-badge');
            if (!badge) return;
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = '';
            } else {
                badge.style.display = 'none';
            }
        }

        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.add-to-cart-btn');
            if (!btn) return;
            var productId = parseInt(btn.dataset.productId, 10);
            if (!productId) return;
            btn.disabled = true;
            fetch(actionUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'add', product_id: productId, quantity: 1 })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    showToast();
                    updateBadge(data.cartCount);
                }
            })
            .catch(function () {
                var toast = document.getElementById('cart-toast');
                if (toast) {
                    toast.style.display = 'block';
                    toast.style.opacity = '1';
                    toast.innerHTML = '<i class="fa fa-times-circle"></i> Could not add item. Please try again.';
                    toast.style.background = '#e74c3c';
                    toast.style.color = '#fff';
                    clearTimeout(toast._timer);
                    toast._timer = setTimeout(function () {
                        toast.style.opacity = '0';
                        setTimeout(function () {
                            toast.style.display = 'none';
                            toast.innerHTML = '<i class="fa fa-check-circle"></i> Item added to cart!';
                            toast.style.background = '';
                            toast.style.color = '';
                        }, 400);
                    }, 3000);
                }
            })
            .finally(function () { btn.disabled = false; });
        });
    })();
    </script>
</body>
</html>