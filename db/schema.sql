-- Restaurant Database Schema
-- Run this file to set up the database for the restaurant website.
-- Usage: mysql -u <user> -p < db/schema.sql

CREATE DATABASE IF NOT EXISTS restaurant_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE restaurant_db;

-- ─────────────────────────────────────────────
-- Table: categories
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS categories (
    id    INT          AUTO_INCREMENT PRIMARY KEY,
    slug  VARCHAR(50)  NOT NULL UNIQUE,
    title VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- Table: menu_items
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS menu_items (
    id          INT           AUTO_INCREMENT PRIMARY KEY,
    category_id INT           NOT NULL,
    name        VARCHAR(200)  NOT NULL,
    price       VARCHAR(50)   NOT NULL,
    image       VARCHAR(500)  NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- Seed: categories
-- ─────────────────────────────────────────────
INSERT INTO categories (slug, title) VALUES
    ('kottu',         'KOTTU'),
    ('biriyanies',    'BIRIYANIES'),
    ('fried-rice',    'FRIED RICE'),
    ('noodles',       'NOODLES'),
    ('nasi-goreng',   'NASI GORENG'),
    ('rice-curry',    'RICE & CURRY'),
    ('bites-curries', 'BITES & CURRIES'),
    ('snacks',        'SNACKS'),
    ('desserts',      'DESSERTS'),
    ('cakes',         'CAKES'),
    ('soft-drinks',   'SOFT DRINKS')
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- ─────────────────────────────────────────────
-- Seed: menu_items (Kottu)
-- ─────────────────────────────────────────────
INSERT INTO menu_items (category_id, name, price, image)
SELECT c.id, v.name, v.price, v.image
FROM categories c
JOIN (
    SELECT 'kottu' AS slug, 'Chicken Rotti Kottu'   AS name, 'Rs.820.00'  AS price, 'Food Items/Kottu/Chicken Rotti Kottu.jpeg'   AS image UNION ALL
    SELECT 'kottu', 'Cut.Fish Rotti Kottu',  'Rs.880.00',  'Food Items/Kottu/Cut.Fish Rotti Kottu.jpg'  UNION ALL
    SELECT 'kottu', 'Mutton Rotti Kottu',    'Rs.1160.00', 'Food Items/Kottu/Mutton Rotti Kottu.jpg'    UNION ALL
    SELECT 'kottu', 'Prawns Rotti Kottu',    'Rs.1100.00', 'Food Items/Kottu/Prawns Rotti Kottu.jpg'    UNION ALL
    SELECT 'kottu', 'Sea Food Rotti Kottu',  'Rs.900.00',  'Food Items/Kottu/Sea Food Rotti Kottu.jpg'  UNION ALL
    SELECT 'kottu', 'Veg Rotti Kottu',       'Rs.630.00',  'Food Items/Kottu/Veg Rotti Kottu.jpg'
) v ON c.slug = v.slug
WHERE NOT EXISTS (
    SELECT 1 FROM menu_items mi WHERE mi.category_id = c.id AND mi.name = v.name
);

-- ─────────────────────────────────────────────
-- Seed: menu_items (Biriyanies)
-- ─────────────────────────────────────────────
INSERT INTO menu_items (category_id, name, price, image)
SELECT c.id, v.name, v.price, v.image
FROM categories c
JOIN (
    SELECT 'biriyanies' AS slug, 'Roasted Chicken Biriyani' AS name, 'Rs.1030.00' AS price, 'Food Items/Biriyanies/Roasted Chicken Biriyani.jpg' AS image UNION ALL
    SELECT 'biriyanies', 'Fish Biriyani',       'Rs.950.00',  'Food Items/Biriyanies/Fish Biriyani.jpg'      UNION ALL
    SELECT 'biriyanies', 'Egg Biriyani',        'Rs.720.00',  'Food Items/Biriyanies/Egg Biriyani.jpg'       UNION ALL
    SELECT 'biriyanies', 'Vegetable Biriyani',  'Rs.640.00',  'Food Items/Biriyanies/Vegetable Biriyani.jpg' UNION ALL
    SELECT 'biriyanies', 'Beef Biriyani',       'Rs.870.00',  'Food Items/Biriyanies/Beef Biriyani.jpg'      UNION ALL
    SELECT 'biriyanies', 'Mutton Biriyani',     'Rs.1600.00', 'Food Items/Biriyanies/Mutton Biriyani.jpg'
) v ON c.slug = v.slug
WHERE NOT EXISTS (
    SELECT 1 FROM menu_items mi WHERE mi.category_id = c.id AND mi.name = v.name
);

-- ─────────────────────────────────────────────
-- Seed: menu_items (Fried Rice)
-- ─────────────────────────────────────────────
INSERT INTO menu_items (category_id, name, price, image)
SELECT c.id, v.name, v.price, v.image
FROM categories c
JOIN (
    SELECT 'fried-rice' AS slug, 'Chicken Fried Rice'    AS name, 'Rs.810.00'  AS price, 'Food Items/Fried Rice/Chicken Fried Rice.jpg'    AS image UNION ALL
    SELECT 'fried-rice', 'Egg Fried Rice',      'Rs.600.00',  'Food Items/Fried Rice/Egg Fried Rice.jpg'      UNION ALL
    SELECT 'fried-rice', 'Mix Fried Rice',      'Rs.920.00',  'Food Items/Fried Rice/Mix Fried Rice.jpg'      UNION ALL
    SELECT 'fried-rice', 'Mutton Fried Rice',   'Rs.1150.00', 'Food Items/Fried Rice/Mutton Fried Rice.jpg'   UNION ALL
    SELECT 'fried-rice', 'SeaFood Fried Rice',  'Rs.870.00',  'Food Items/Fried Rice/SeaFood Fried Rice.jpg'  UNION ALL
    SELECT 'fried-rice', 'Vegetable Fried Rice','Rs.580.00',  'Food Items/Fried Rice/Vegetable Fried Rice.jpg'
) v ON c.slug = v.slug
WHERE NOT EXISTS (
    SELECT 1 FROM menu_items mi WHERE mi.category_id = c.id AND mi.name = v.name
);

-- ─────────────────────────────────────────────
-- Seed: menu_items (Noodles)
-- ─────────────────────────────────────────────
INSERT INTO menu_items (category_id, name, price, image)
SELECT c.id, v.name, v.price, v.image
FROM categories c
JOIN (
    SELECT 'noodles' AS slug, 'Chicken Fried Noodles'    AS name, 'Rs.780.00'  AS price, 'Food Items/Noodles/Chicken Fried Noodles.jpg'    AS image UNION ALL
    SELECT 'noodles', 'Fish Noodles',             'Rs.660.00',  'Food Items/Noodles/Fish Noodles.jpeg'            UNION ALL
    SELECT 'noodles', 'Prawns Noodles',           'Rs.1090.00', 'Food Items/Noodles/Prawns Noodless.jpg'          UNION ALL
    SELECT 'noodles', 'Seafood Fried Noodles',    'Rs.770.00',  'Food Items/Noodles/SeaFood Fried Noodles.jpg'    UNION ALL
    SELECT 'noodles', 'Egg Fried Noodles',        'Rs.700.00',  'Food Items/Noodles/Egg Fried Noodles.jpg'        UNION ALL
    SELECT 'noodles', 'Vegetable Fried Noodles',  'Rs.450.00',  'Food Items/Noodles/Vegetable Fried Noodles.jpeg'
) v ON c.slug = v.slug
WHERE NOT EXISTS (
    SELECT 1 FROM menu_items mi WHERE mi.category_id = c.id AND mi.name = v.name
);

-- ─────────────────────────────────────────────
-- Seed: menu_items (Nasi Goreng)
-- ─────────────────────────────────────────────
INSERT INTO menu_items (category_id, name, price, image)
SELECT c.id, v.name, v.price, v.image
FROM categories c
JOIN (
    SELECT 'nasi-goreng' AS slug, 'Chicken Nasi Goreng'  AS name, 'Rs.870.00'  AS price, 'Food Items/Nasi Goreng/Chicken Nasi Goreng.jpg'  AS image UNION ALL
    SELECT 'nasi-goreng', 'Mutton Nasi Goreng',  'Rs.1360.00', 'Food Items/Nasi Goreng/Mutton Nasi  Goreng.jpg' UNION ALL
    SELECT 'nasi-goreng', 'Seafood Nasi Goreng', 'Rs.980.00',  'Food Items/Nasi Goreng/Seafood Nasi Goreng.jpg' UNION ALL
    SELECT 'nasi-goreng', 'Beef Nasi Goreng',    'Rs.870.00',  'Food Items/Nasi Goreng/Beef Nasi Goreng.jpg'    UNION ALL
    SELECT 'nasi-goreng', 'Mix Nasi Goreng',     'Rs.930.00',  'Food Items/Nasi Goreng/Mix Nasi Goreng.jpg'
) v ON c.slug = v.slug
WHERE NOT EXISTS (
    SELECT 1 FROM menu_items mi WHERE mi.category_id = c.id AND mi.name = v.name
);

-- ─────────────────────────────────────────────
-- Seed: menu_items (Rice & Curry)
-- ─────────────────────────────────────────────
INSERT INTO menu_items (category_id, name, price, image)
SELECT c.id, v.name, v.price, v.image
FROM categories c
JOIN (
    SELECT 'rice-curry' AS slug, 'Vegetable Rice & Curry' AS name, 'Rs.550.00' AS price, 'Food Items/Rice & Curry/Vegetable Rice And Curry.jpg' AS image UNION ALL
    SELECT 'rice-curry', 'Chicken Rice & Curry', 'Rs.900.00', 'Food Items/Rice & Curry/Chicken Rice & Curry.jpg' UNION ALL
    SELECT 'rice-curry', 'Fish Rice & Curry',    'Rs.820.00', 'Food Items/Rice & Curry/Fish Rice & Curry.jpg'    UNION ALL
    SELECT 'rice-curry', 'Chicken Lamprais',     'Rs.950.00', 'Food Items/Rice & Curry/Chicken Lamprais.jpg'
) v ON c.slug = v.slug
WHERE NOT EXISTS (
    SELECT 1 FROM menu_items mi WHERE mi.category_id = c.id AND mi.name = v.name
);

-- ─────────────────────────────────────────────
-- Seed: menu_items (Bites & Curries)
-- ─────────────────────────────────────────────
INSERT INTO menu_items (category_id, name, price, image)
SELECT c.id, v.name, v.price, v.image
FROM categories c
JOIN (
    SELECT 'bites-curries' AS slug, 'Egg Masala'            AS name, 'Rs.370.00'  AS price, 'Food Items/Bites & Curries/Egg Masala.jpg'            AS image UNION ALL
    SELECT 'bites-curries', 'Fish 65',              'Rs.1020.00', 'Food Items/Bites & Curries/Fish 65.jpg'              UNION ALL
    SELECT 'bites-curries', 'Fish Curry',           'Rs.470.00',  'Food Items/Bites & Curries/Fish Curry.jpg'           UNION ALL
    SELECT 'bites-curries', 'Mushroom',             'Rs.330.00',  'Food Items/Bites & Curries/Mushroom 65.jpg'          UNION ALL
    SELECT 'bites-curries', 'Mutton Pepper',        'Rs.650.00',  'Food Items/Bites & Curries/Mutton Pepper Fry.jpg'    UNION ALL
    SELECT 'bites-curries', 'Omelet',               'Rs.120.00',  'Food Items/Bites & Curries/Omelet.jpg'               UNION ALL
    SELECT 'bites-curries', 'Roasted Chicken Full', 'Rs.950.00',  'Food Items/Bites & Curries/Roasted Chicken Full.jpg' UNION ALL
    SELECT 'bites-curries', 'Roasted Chicken Half', 'Rs.400.00',  'Food Items/Bites & Curries/Roasted Chicken Half.jpg'
) v ON c.slug = v.slug
WHERE NOT EXISTS (
    SELECT 1 FROM menu_items mi WHERE mi.category_id = c.id AND mi.name = v.name
);

-- ─────────────────────────────────────────────
-- Seed: menu_items (Snacks)
-- ─────────────────────────────────────────────
INSERT INTO menu_items (category_id, name, price, image)
SELECT c.id, v.name, v.price, v.image
FROM categories c
JOIN (
    SELECT 'snacks' AS slug, 'French fries'    AS name, 'Rs.760.00'  AS price, 'Food Items/Snacks/French fries.webp'   AS image UNION ALL
    SELECT 'snacks', 'Chicken Pakoda',  'Rs.820.00',  'Food Items/Snacks/Chicken Pakoda.jpg'  UNION ALL
    SELECT 'snacks', 'Mix veg pakoda',  'Rs.400.00',  'Food Items/Snacks/Mix Veg Pakoda.jpg'  UNION ALL
    SELECT 'snacks', 'Mutton Pakoda',   'Rs.1020.00', 'Food Items/Snacks/Mutton Pakoda.jpg'   UNION ALL
    SELECT 'snacks', 'Onion Pakoda',    'Rs.440.00',  'Food Items/Snacks/Onion Pakoda.jpg'
) v ON c.slug = v.slug
WHERE NOT EXISTS (
    SELECT 1 FROM menu_items mi WHERE mi.category_id = c.id AND mi.name = v.name
);

-- ─────────────────────────────────────────────
-- Seed: menu_items (Desserts)
-- ─────────────────────────────────────────────
INSERT INTO menu_items (category_id, name, price, image)
SELECT c.id, v.name, v.price, v.image
FROM categories c
JOIN (
    SELECT 'desserts' AS slug, 'Ice Cream'     AS name, 'Rs.390.00' AS price, 'Food Items/Dessert/Ice Cream.jpg'     AS image UNION ALL
    SELECT 'desserts', 'Cream Caramel', 'Rs.360.00', 'Food Items/Dessert/Cream Caramel.jpg' UNION ALL
    SELECT 'desserts', 'Watalappam',    'Rs.470.00', 'Food Items/Dessert/Watalappam.jpg'
) v ON c.slug = v.slug
WHERE NOT EXISTS (
    SELECT 1 FROM menu_items mi WHERE mi.category_id = c.id AND mi.name = v.name
);

-- ─────────────────────────────────────────────
-- Seed: menu_items (Cakes)
-- ─────────────────────────────────────────────
INSERT INTO menu_items (category_id, name, price, image)
SELECT c.id, v.name, v.price, v.image
FROM categories c
JOIN (
    SELECT 'cakes' AS slug, 'Apple Tea Cake (500g)'    AS name, 'Rs.2500.00' AS price, 'Food Items/Cakes/Apple Tea Cake.jpeg'              AS image UNION ALL
    SELECT 'cakes', 'Chocolate Cake (750g)',    'Rs.3300.00', 'Food Items/Cakes/Chocolate Cake.jpg'               UNION ALL
    SELECT 'cakes', 'Red Velvet Cake (2kg)',    'Rs.4500.00', 'Food Items/Cakes/Red Velvet Cake with Fruit.jpg'   UNION ALL
    SELECT 'cakes', 'Strawberry Cake (500g)',   'Rs.2300.00', 'Food Items/Cakes/Strawberry Cake.jpg'              UNION ALL
    SELECT 'cakes', 'Vanila Cream Cake (500g)', 'Rs.2200.00', 'Food Items/Cakes/Vanilla Cream Cake.jpg'
) v ON c.slug = v.slug
WHERE NOT EXISTS (
    SELECT 1 FROM menu_items mi WHERE mi.category_id = c.id AND mi.name = v.name
);

-- ─────────────────────────────────────────────
-- Seed: menu_items (Soft Drinks)
-- ─────────────────────────────────────────────
INSERT INTO menu_items (category_id, name, price, image)
SELECT c.id, v.name, v.price, v.image
FROM categories c
JOIN (
    SELECT 'soft-drinks' AS slug, 'Coke (400ml)'             AS name, 'Rs.180.00' AS price, 'Food Items/Soft Drinks/Coke.jpg'                  AS image UNION ALL
    SELECT 'soft-drinks', 'Sprite (400ml)',           'Rs.180.00', 'Food Items/Soft Drinks/Sprite.jpg'                UNION ALL
    SELECT 'soft-drinks', 'Mineral Water (500ml)',    'Rs.120.00', 'Food Items/Soft Drinks/Mineral Water 500ml.jpg'   UNION ALL
    SELECT 'soft-drinks', 'Fresh Watermelon Juice',   'Rs.430.00', 'Food Items/Soft Drinks/Fresh Watermelon Juice.jpg' UNION ALL
    SELECT 'soft-drinks', 'Fresh Lemon Juice',        'Rs.320.00', 'Food Items/Soft Drinks/Fresh Lemon Juice.jpg'     UNION ALL
    SELECT 'soft-drinks', 'Fresh Orange Juice',       'Rs.460.00', 'Food Items/Soft Drinks/Fresh Orange Juice.jpg'    UNION ALL
    SELECT 'soft-drinks', 'Fresh Mix Fruit Juice',    'Rs.420.00', 'Food Items/Soft Drinks/Fresh Mix Fruit Juice.jpg'
) v ON c.slug = v.slug
WHERE NOT EXISTS (
    SELECT 1 FROM menu_items mi WHERE mi.category_id = c.id AND mi.name = v.name
);

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(500),
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending','processing','completed','cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- Seed: products (all menu items)
-- ─────────────────────────────────────────────
INSERT INTO products (name, description, price, image, category)
SELECT v.name, '' AS description, v.price, v.image, v.category
FROM (
    -- Kottu
    SELECT 'Chicken Rotti Kottu'  AS name, 820.00  AS price, 'Food Items/Kottu/Chicken Rotti Kottu.jpeg'   AS image, 'kottu' AS category UNION ALL
    SELECT 'Cut.Fish Rotti Kottu',         880.00,            'Food Items/Kottu/Cut.Fish Rotti Kottu.jpg',   'kottu' UNION ALL
    SELECT 'Mutton Rotti Kottu',           1160.00,           'Food Items/Kottu/Mutton Rotti Kottu.jpg',     'kottu' UNION ALL
    SELECT 'Prawns Rotti Kottu',           1100.00,           'Food Items/Kottu/Prawns Rotti Kottu.jpg',     'kottu' UNION ALL
    SELECT 'Sea Food Rotti Kottu',         900.00,            'Food Items/Kottu/Sea Food Rotti Kottu.jpg',   'kottu' UNION ALL
    SELECT 'Veg Rotti Kottu',              630.00,            'Food Items/Kottu/Veg Rotti Kottu.jpg',        'kottu' UNION ALL
    -- Biriyanies
    SELECT 'Roasted Chicken Biriyani',     1030.00,           'Food Items/Biriyanies/Roasted Chicken Biriyani.jpg', 'biriyanies' UNION ALL
    SELECT 'Fish Biriyani',                950.00,            'Food Items/Biriyanies/Fish Biriyani.jpg',            'biriyanies' UNION ALL
    SELECT 'Egg Biriyani',                 720.00,            'Food Items/Biriyanies/Egg Biriyani.jpg',             'biriyanies' UNION ALL
    SELECT 'Vegetable Biriyani',           640.00,            'Food Items/Biriyanies/Vegetable Biriyani.jpg',       'biriyanies' UNION ALL
    SELECT 'Beef Biriyani',                870.00,            'Food Items/Biriyanies/Beef Biriyani.jpg',            'biriyanies' UNION ALL
    SELECT 'Mutton Biriyani',              1600.00,           'Food Items/Biriyanies/Mutton Biriyani.jpg',          'biriyanies' UNION ALL
    -- Fried Rice
    SELECT 'Chicken Fried Rice',           810.00,            'Food Items/Fried Rice/Chicken Fried Rice.jpg',       'fried-rice' UNION ALL
    SELECT 'Egg Fried Rice',               600.00,            'Food Items/Fried Rice/Egg Fried Rice.jpg',           'fried-rice' UNION ALL
    SELECT 'Mix Fried Rice',               920.00,            'Food Items/Fried Rice/Mix Fried Rice.jpg',           'fried-rice' UNION ALL
    SELECT 'Mutton Fried Rice',            1150.00,           'Food Items/Fried Rice/Mutton Fried Rice.jpg',        'fried-rice' UNION ALL
    SELECT 'SeaFood Fried Rice',           870.00,            'Food Items/Fried Rice/SeaFood Fried Rice.jpg',       'fried-rice' UNION ALL
    SELECT 'Vegetable Fried Rice',         580.00,            'Food Items/Fried Rice/Vegetable Fried Rice.jpg',     'fried-rice' UNION ALL
    -- Noodles
    SELECT 'Chicken Fried Noodles',        780.00,            'Food Items/Noodles/Chicken Fried Noodles.jpg',       'noodles' UNION ALL
    SELECT 'Fish Noodles',                 660.00,            'Food Items/Noodles/Fish Noodles.jpeg',               'noodles' UNION ALL
    SELECT 'Prawns Noodles',               1090.00,           'Food Items/Noodles/Prawns Noodless.jpg',             'noodles' UNION ALL
    SELECT 'Seafood Fried Noodles',        770.00,            'Food Items/Noodles/SeaFood Fried Noodles.jpg',       'noodles' UNION ALL
    SELECT 'Egg Fried Noodles',            700.00,            'Food Items/Noodles/Egg Fried Noodles.jpg',           'noodles' UNION ALL
    SELECT 'Vegetable Fried Noodles',      450.00,            'Food Items/Noodles/Vegetable Fried Noodles.jpeg',    'noodles' UNION ALL
    -- Nasi Goreng
    SELECT 'Chicken Nasi Goreng',          870.00,            'Food Items/Nasi Goreng/Chicken Nasi Goreng.jpg',     'nasi-goreng' UNION ALL
    SELECT 'Mutton Nasi Goreng',           1360.00,           'Food Items/Nasi Goreng/Mutton Nasi  Goreng.jpg',     'nasi-goreng' UNION ALL
    SELECT 'Seafood Nasi Goreng',          980.00,            'Food Items/Nasi Goreng/Seafood Nasi Goreng.jpg',     'nasi-goreng' UNION ALL
    SELECT 'Beef Nasi Goreng',             870.00,            'Food Items/Nasi Goreng/Beef Nasi Goreng.jpg',        'nasi-goreng' UNION ALL
    SELECT 'Mix Nasi Goreng',              930.00,            'Food Items/Nasi Goreng/Mix Nasi Goreng.jpg',         'nasi-goreng' UNION ALL
    -- Rice & Curry
    SELECT 'Vegetable Rice & Curry',       550.00,            'Food Items/Rice & Curry/Vegetable Rice And Curry.jpg', 'rice-curry' UNION ALL
    SELECT 'Chicken Rice & Curry',         900.00,            'Food Items/Rice & Curry/Chicken Rice & Curry.jpg',    'rice-curry' UNION ALL
    SELECT 'Fish Rice & Curry',            820.00,            'Food Items/Rice & Curry/Fish Rice & Curry.jpg',       'rice-curry' UNION ALL
    SELECT 'Chicken Lamprais',             950.00,            'Food Items/Rice & Curry/Chicken Lamprais.jpg',        'rice-curry' UNION ALL
    -- Bites & Curries
    SELECT 'Egg Masala',                   370.00,            'Food Items/Bites & Curries/Egg Masala.jpg',           'bites-curries' UNION ALL
    SELECT 'Fish 65',                      1020.00,           'Food Items/Bites & Curries/Fish 65.jpg',              'bites-curries' UNION ALL
    SELECT 'Fish Curry',                   470.00,            'Food Items/Bites & Curries/Fish Curry.jpg',           'bites-curries' UNION ALL
    SELECT 'Mushroom',                     330.00,            'Food Items/Bites & Curries/Mushroom 65.jpg',          'bites-curries' UNION ALL
    SELECT 'Mutton Pepper',                650.00,            'Food Items/Bites & Curries/Mutton Pepper Fry.jpg',    'bites-curries' UNION ALL
    SELECT 'Omelet',                       120.00,            'Food Items/Bites & Curries/Omelet.jpg',               'bites-curries' UNION ALL
    SELECT 'Roasted Chicken Full',         950.00,            'Food Items/Bites & Curries/Roasted Chicken Full.jpg', 'bites-curries' UNION ALL
    SELECT 'Roasted Chicken Half',         400.00,            'Food Items/Bites & Curries/Roasted Chicken Half.jpg', 'bites-curries' UNION ALL
    -- Snacks
    SELECT 'French fries',                 760.00,            'Food Items/Snacks/French fries.webp',                 'snacks' UNION ALL
    SELECT 'Chicken Pakoda',               820.00,            'Food Items/Snacks/Chicken Pakoda.jpg',                'snacks' UNION ALL
    SELECT 'Mix veg pakoda',               400.00,            'Food Items/Snacks/Mix Veg Pakoda.jpg',                'snacks' UNION ALL
    SELECT 'Mutton Pakoda',                1020.00,           'Food Items/Snacks/Mutton Pakoda.jpg',                 'snacks' UNION ALL
    SELECT 'Onion Pakoda',                 440.00,            'Food Items/Snacks/Onion Pakoda.jpg',                  'snacks' UNION ALL
    -- Desserts
    SELECT 'Ice Cream',                    390.00,            'Food Items/Dessert/Ice Cream.jpg',                    'desserts' UNION ALL
    SELECT 'Cream Caramel',                360.00,            'Food Items/Dessert/Cream Caramel.jpg',                'desserts' UNION ALL
    SELECT 'Watalappam',                   470.00,            'Food Items/Dessert/Watalappam.jpg',                   'desserts' UNION ALL
    -- Cakes
    SELECT 'Apple Tea Cake (500g)',        2500.00,           'Food Items/Cakes/Apple Tea Cake.jpeg',                'cakes' UNION ALL
    SELECT 'Chocolate Cake (750g)',        3300.00,           'Food Items/Cakes/Chocolate Cake.jpg',                 'cakes' UNION ALL
    SELECT 'Red Velvet Cake (2kg)',        4500.00,           'Food Items/Cakes/Red Velvet Cake with Fruit.jpg',     'cakes' UNION ALL
    SELECT 'Strawberry Cake (500g)',       2300.00,           'Food Items/Cakes/Strawberry Cake.jpg',                'cakes' UNION ALL
    SELECT 'Vanila Cream Cake (500g)',     2200.00,           'Food Items/Cakes/Vanilla Cream Cake.jpg',             'cakes' UNION ALL
    -- Soft Drinks
    SELECT 'Coke (400ml)',                 180.00,            'Food Items/Soft Drinks/Coke.jpg',                     'soft-drinks' UNION ALL
    SELECT 'Sprite (400ml)',               180.00,            'Food Items/Soft Drinks/Sprite.jpg',                   'soft-drinks' UNION ALL
    SELECT 'Mineral Water (500ml)',        120.00,            'Food Items/Soft Drinks/Mineral Water 500ml.jpg',      'soft-drinks' UNION ALL
    SELECT 'Fresh Watermelon Juice',       430.00,            'Food Items/Soft Drinks/Fresh Watermelon Juice.jpg',   'soft-drinks' UNION ALL
    SELECT 'Fresh Lemon Juice',            320.00,            'Food Items/Soft Drinks/Fresh Lemon Juice.jpg',        'soft-drinks' UNION ALL
    SELECT 'Fresh Orange Juice',           460.00,            'Food Items/Soft Drinks/Fresh Orange Juice.jpg',       'soft-drinks' UNION ALL
    SELECT 'Fresh Mix Fruit Juice',        420.00,            'Food Items/Soft Drinks/Fresh Mix Fruit Juice.jpg',    'soft-drinks'
) v
WHERE NOT EXISTS (
    SELECT 1 FROM products p WHERE p.name = v.name AND p.category = v.category
);
