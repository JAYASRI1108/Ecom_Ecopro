CREATE DATABASE IF NOT EXISTS ecoproducts CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecoproducts;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category VARCHAR(80) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    short_description VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    material VARCHAR(120) NOT NULL,
    best_for VARCHAR(120) NOT NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(40) NOT NULL DEFAULT 'pending',
    customer_name VARCHAR(120) NOT NULL,
    customer_email VARCHAR(190) NOT NULL,
    customer_phone VARCHAR(40) NOT NULL,
    shipping_address TEXT NOT NULL,
    payment_status VARCHAR(40) NOT NULL DEFAULT 'pending',
    razorpay_order_id VARCHAR(120) DEFAULT NULL,
    razorpay_payment_id VARCHAR(120) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

INSERT INTO products (name, category, price, image, short_description, description, material, best_for, is_featured) VALUES
('Bamboo Cutlery Set', 'Kitchen', 349.00, 'img/cutlery.jpg', 'Reusable dining kit for travel, office, and lunch bags.', 'Portable bamboo spoon, fork, and knife set designed to replace disposable plastic cutlery in everyday routines.', 'Bamboo', 'Office lunches and travel', 1),
('Jute Shopping Bag', 'Carry', 499.00, 'img/jute-shopping-bag.jpg', 'Strong reusable carry bag for groceries and daily errands.', 'A sturdy jute shopping bag made to handle groceries, market trips, and repeat use without depending on single-use plastic bags.', 'Jute fiber', 'Groceries and general carry', 1),
('Clay Pot Duo', 'Home', 899.00, 'img/claypot1.png', 'Traditional clayware for natural serving and storage.', 'A clay pot pair that adds an earthy, handcrafted look to everyday serving while supporting natural material choices at home.', 'Clay', 'Serving and countertop storage', 1),
('Natural Floor Mat', 'Home', 699.00, 'img/floormate2.jpg', 'Textured mat for entryways, kitchens, and compact spaces.', 'A durable floor mat with a natural woven look that fits kitchens, doorways, and low-waste home setups.', 'Natural fiber blend', 'Entrances and kitchen floors', 1),
('Coconut Shell Bowl', 'Kitchen', 299.00, 'img/coconutshell.jpg', 'Handcrafted bowl for snacks, prep, and table settings.', 'A lightweight coconut shell bowl that works well for snack servings, ingredient prep, and decorative tabletop use.', 'Coconut shell', 'Snacks and dry serving', 1),
('Eco Water Bottle', 'Lifestyle', 549.00, 'img/waterbottle.jpg', 'Reusable bottle designed to reduce single-use plastic use.', 'A dependable everyday bottle for work, study, and travel that supports a simple reusable hydration habit.', 'Reusable composite', 'Daily hydration', 1),
('Bamboo Brush Set', 'Care', 459.00, 'img/manybrush.jpg', 'Low-waste brush set for home and personal care use.', 'A set of bamboo-handle brushes selected for sustainable care routines and a cleaner natural aesthetic.', 'Bamboo and natural bristles', 'Personal and home care', 0),
('Paper Towel Roll', 'Home', 259.00, 'img/papertowel1.png', 'Reusable cleanup essential for everyday spills and wiping.', 'A reusable towel alternative intended to cut down on disposable paper usage around the kitchen and home.', 'Reusable fabric pulp blend', 'Kitchen cleanup', 0),
('Jute Hat', 'Lifestyle', 599.00, 'img/jutehat.png', 'Breathable woven accessory with a natural finish.', 'A lightweight hat made from natural woven fibers for outdoor routines and earthy styling.', 'Jute', 'Sun-ready casual wear', 0),
('Eco Cosmetic Oil', 'Care', 799.00, 'img/oil1.png', 'Plant-based care oil with a premium finish.', 'A natural care oil with a simple presentation and eco-conscious positioning for daily self-care shelves.', 'Plant-based oil blend', 'Skin and self-care', 0),
('Phone Case', 'Lifestyle', 429.00, 'img/Phonecase1.png', 'Minimal responsible accessory for daily device protection.', 'A clean and practical phone case chosen as a more responsible option for everyday carry.', 'Eco composite', 'Phone protection', 0),
('Handwoven Basket', 'Carry', 749.00, 'img/basket1.png', 'Storage basket for laundry, throws, produce, or display.', 'A versatile woven basket that works for utility and decor while bringing natural texture into a room.', 'Handwoven natural fiber', 'Storage and decor', 1);
