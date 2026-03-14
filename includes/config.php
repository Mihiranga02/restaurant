<?php
// ─── Database Configuration ────────────────────────────────────────────────
// Uncomment and fill in these constants to enable MySQL database support.
// When left commented out the site will use the $menuData array below instead.
//
define('DB_HOST', 'localhost');
define('DB_NAME', 'restaurant_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// ─── Site Configuration ────────────────────────────────────────────────────
define('SITE_NAME', 'Food Website');
define('SITE_PHONE_1', '+94 12 3456 789');
define('SITE_PHONE_2', '+94 25 5568456');
define('SITE_EMAIL_1', 'hotandfastres@gmail.com');
define('SITE_EMAIL_2', 'hotnfastresturent@gmail.com');
define('SITE_ADDRESS', 'One Galle Face Mall, 1A Centre Road, Colombo, Sri Lanka');
define('SITE_HOURS', '08.00am-09.30pm');
define('SITE_DESIGN_CREDIT', 'ICT Intake 41 Students');

// Menu data for each category
$menuData = [
    'kottu' => [
        'title' => 'KOTTU',
        'items' => [
            ['image' => 'Food Items/Kottu/Chicken Rotti Kottu.jpeg', 'name' => 'Chicken Rotti Kottu', 'price' => 'Rs.820.00'],
            ['image' => 'Food Items/Kottu/Cut.Fish Rotti Kottu.jpg', 'name' => 'CuttleFish Rotti Kottu', 'price' => 'Rs.880.00'],
            ['image' => 'Food Items/Kottu/Mutton Rotti Kottu.jpg', 'name' => 'Mutton Rotti Kottu', 'price' => 'Rs.1160.00'],
            ['image' => 'Food Items/Kottu/Prawns Rotti Kottu.jpg', 'name' => 'Prawns Rotti Kottu', 'price' => 'Rs.1100.00'],
            ['image' => 'Food Items/Kottu/Sea Food Rotti Kottu.jpg', 'name' => 'Sea Food Rotti Kottu', 'price' => 'Rs.900.00'],
            ['image' => 'Food Items/Kottu/Veg Rotti Kottu.jpg', 'name' => 'Veg Rotti Kottu', 'price' => 'Rs.630.00'],
        ],
    ],
    'biriyanies' => [
        'title' => 'BIRIYANIES',
        'items' => [
            ['image' => 'Food Items/Biriyanies/Roasted Chicken Biriyani.jpg', 'name' => 'Roasted Chicken Biriyani', 'price' => 'Rs.1030.00'],
            ['image' => 'Food Items/Biriyanies/Fish Biriyani.jpg', 'name' => 'Fish Biriyani', 'price' => 'Rs.950.00'],
            ['image' => 'Food Items/Biriyanies/Egg Biriyani.jpg', 'name' => 'Egg Biriyani', 'price' => 'Rs.720.00'],
            ['image' => 'Food Items/Biriyanies/Vegetable Biriyani.jpg', 'name' => 'Vegetable Biriyani', 'price' => 'Rs.640.00'],
            ['image' => 'Food Items/Biriyanies/Beef Biriyani.jpg', 'name' => 'Beef Biriyani', 'price' => 'Rs.870.00'],
            ['image' => 'Food Items/Biriyanies/Mutton Biriyani.jpg', 'name' => 'Mutton Biriyani', 'price' => 'Rs.1600.00'],
        ],
    ],
    'fried-rice' => [
        'title' => 'FRIED RICE',
        'items' => [
            ['image' => 'Food Items/Fried Rice/Chicken Fried Rice.jpg', 'name' => 'Chicken Fried Rice', 'price' => 'Rs.810.00'],
            ['image' => 'Food Items/Fried Rice/Egg Fried Rice.jpg', 'name' => 'Egg Fried Rice', 'price' => 'Rs.600.00'],
            ['image' => 'Food Items/Fried Rice/Mix Fried Rice.jpg', 'name' => 'Mix Fried Rice', 'price' => 'Rs.920.00'],
            ['image' => 'Food Items/Fried Rice/Mutton Fried Rice.jpg', 'name' => 'Mutton Fried Rice', 'price' => 'Rs.1150.00'],
            ['image' => 'Food Items/Fried Rice/SeaFood Fried Rice.jpg', 'name' => 'SeaFood Fried Rice', 'price' => 'Rs.870.00'],
            ['image' => 'Food Items/Fried Rice/Vegetable Fried Rice.jpg', 'name' => 'Vegetable Fried Rice', 'price' => 'Rs.580.00'],
        ],
    ],
    'noodles' => [
        'title' => 'NOODLES',
        'items' => [
            ['image' => 'Food Items/Noodles/Chicken Fried Noodles.jpg', 'name' => 'Chicken Fried Noodles', 'price' => 'Rs.780.00'],
            ['image' => 'Food Items/Noodles/Fish Noodles.jpeg', 'name' => 'Fish Noodles', 'price' => 'Rs.660.00'],
            ['image' => 'Food Items/Noodles/Prawns Noodless.jpg', 'name' => 'Prawns Noodles', 'price' => 'Rs.1090.00'],
            ['image' => 'Food Items/Noodles/SeaFood Fried Noodles.jpg', 'name' => 'Seafood Fried Noodles', 'price' => 'Rs.770.00'],
            ['image' => 'Food Items/Noodles/Egg Fried Noodles.jpg', 'name' => 'Egg Fried Noodles', 'price' => 'Rs.700.00'],
            ['image' => 'Food Items/Noodles/Vegetable Fried Noodles.jpeg', 'name' => 'Vegetable Fried Noodles', 'price' => 'Rs.450.00'],
        ],
    ],
    'nasi-goreng' => [
        'title' => 'NASI GORENG',
        'items' => [
            ['image' => 'Food Items/Nasi Goreng/Chicken Nasi Goreng.jpg', 'name' => 'Chicken Nasi Goreng', 'price' => 'Rs.870.00'],
            ['image' => 'Food Items/Nasi Goreng/Mutton Nasi  Goreng.jpg', 'name' => 'Mutton Nasi Goreng', 'price' => 'Rs.1360.00'],
            ['image' => 'Food Items/Nasi Goreng/Seafood Nasi Goreng.jpg', 'name' => 'Seafood Nasi Goreng', 'price' => 'Rs.980.00'],
            ['image' => 'Food Items/Nasi Goreng/Beef Nasi Goreng.jpg', 'name' => 'Beef Nasi Goreng', 'price' => 'Rs.870.00'],
            ['image' => 'Food Items/Nasi Goreng/Mix Nasi Goreng.jpg', 'name' => 'Mix Nasi Goreng', 'price' => 'Rs.930.00'],
        ],
    ],
    'rice-curry' => [
        'title' => 'RICE & CURRY',
        'items' => [
            ['image' => 'Food Items/Rice & Curry/Vegetable Rice And Curry.jpg', 'name' => 'Vegetable Rice & Curry', 'price' => 'Rs.550.00'],
            ['image' => 'Food Items/Rice & Curry/Chicken Rice & Curry.jpg', 'name' => 'Chicken Rice & Curry', 'price' => 'Rs.900.00'],
            ['image' => 'Food Items/Rice & Curry/Fish Rice & Curry.jpg', 'name' => 'Fish Rice & Curry', 'price' => 'Rs.820.00'],
            ['image' => 'Food Items/Rice & Curry/Chicken Lamprais.jpg', 'name' => 'Chicken Lamprais', 'price' => 'Rs.950.00'],
        ],
    ],
    'bites-curries' => [
        'title' => 'BITES & CURRIES',
        'items' => [
            ['image' => 'Food Items/Bites & Curries/Egg Masala.jpg', 'name' => 'Egg Masala', 'price' => 'Rs.370.00'],
            ['image' => 'Food Items/Bites & Curries/Fish 65.jpg', 'name' => 'Fish 65', 'price' => 'Rs.1020.00'],
            ['image' => 'Food Items/Bites & Curries/Fish Curry.jpg', 'name' => 'Fish Curry', 'price' => 'Rs.470.00'],
            ['image' => 'Food Items/Bites & Curries/Mushroom 65.jpg', 'name' => 'Mushroom', 'price' => 'Rs.330.00'],
            ['image' => 'Food Items/Bites & Curries/Mutton Pepper Fry.jpg', 'name' => 'Mutton Pepper', 'price' => 'Rs.650.00'],
            ['image' => 'Food Items/Bites & Curries/Omelet.jpg', 'name' => 'Omelet', 'price' => 'Rs.120.00'],
            ['image' => 'Food Items/Bites & Curries/Roasted Chicken Full.jpg', 'name' => 'Roasted Chicken Full', 'price' => 'Rs.950.00'],
            ['image' => 'Food Items/Bites & Curries/Roasted Chicken Half.jpg', 'name' => 'Roasted Chicken Half', 'price' => 'Rs.400.00'],
        ],
    ],
    'snacks' => [
        'title' => 'SNACKS',
        'items' => [
            ['image' => 'Food Items/Snacks/French fries.webp', 'name' => 'French fries', 'price' => 'Rs.760.00'],
            ['image' => 'Food Items/Snacks/Chicken Pakoda.jpg', 'name' => 'Chicken Pakoda', 'price' => 'Rs.820.00'],
            ['image' => 'Food Items/Snacks/Mix Veg Pakoda.jpg', 'name' => 'Mix veg pakoda', 'price' => 'Rs.400.00'],
            ['image' => 'Food Items/Snacks/Mutton Pakoda.jpg', 'name' => 'Mutton Pakoda', 'price' => 'Rs.1020.00'],
            ['image' => 'Food Items/Snacks/Onion Pakoda.jpg', 'name' => 'Onion Pakoda', 'price' => 'Rs.440.00'],
        ],
    ],
    'desserts' => [
        'title' => 'DESSERTS',
        'items' => [
            ['image' => 'Food Items/Dessert/Ice Cream.jpg', 'name' => 'Ice Cream', 'price' => 'Rs.390.00'],
            ['image' => 'Food Items/Dessert/Cream Caramel.jpg', 'name' => 'Cream Caramel', 'price' => 'Rs.360.00'],
            ['image' => 'Food Items/Dessert/Watalappam.jpg', 'name' => 'Watalappam', 'price' => 'Rs.470.00'],
        ],
    ],
    'cakes' => [
        'title' => 'CAKES',
        'items' => [
            ['image' => 'Food Items/Cakes/Apple Tea Cake.jpeg', 'name' => 'Apple Tea Cake (500g)', 'price' => 'Rs.2500.00'],
            ['image' => 'Food Items/Cakes/Chocolate Cake.jpg', 'name' => 'Chocolate Cake (750g)', 'price' => 'Rs.3300.00'],
            ['image' => 'Food Items/Cakes/Red Velvet Cake with Fruit.jpg', 'name' => 'Red Velvet Cake (2kg)', 'price' => 'Rs.4500.00'],
            ['image' => 'Food Items/Cakes/Strawberry Cake.jpg', 'name' => 'Strawberry Cake (500g)', 'price' => 'Rs.2300.00'],
            ['image' => 'Food Items/Cakes/Vanilla Cream Cake.jpg', 'name' => 'Vanila Cream Cake (500g)', 'price' => 'Rs.2200.00'],
        ],
    ],
    'soft-drinks' => [
        'title' => 'SOFT DRINKS',
        'items' => [
            ['image' => 'Food Items/Soft Drinks/Coke.jpg', 'name' => 'Coke (400ml)', 'price' => 'Rs.180.00'],
            ['image' => 'Food Items/Soft Drinks/Sprite.jpg', 'name' => 'Sprite (400ml)', 'price' => 'Rs.180.00'],
            ['image' => 'Food Items/Soft Drinks/Mineral Water 500ml.jpg', 'name' => 'Mineral Water (500ml)', 'price' => 'Rs.120.00'],
            ['image' => 'Food Items/Soft Drinks/Fresh Watermelon Juice.jpg', 'name' => 'Fresh Watermelon Juice', 'price' => 'Rs.430.00'],
            ['image' => 'Food Items/Soft Drinks/Fresh Lemon Juice.jpg', 'name' => 'Fresh Lemon Juice', 'price' => 'Rs.320.00'],
            ['image' => 'Food Items/Soft Drinks/Fresh Orange Juice.jpg', 'name' => 'Fresh Orange Juice', 'price' => 'Rs.460.00'],
            ['image' => 'Food Items/Soft Drinks/Fresh Mix Fruit Juice.jpg', 'name' => 'Fresh Mix Fruit Juice', 'price' => 'Rs.420.00'],
        ],
    ],
];
