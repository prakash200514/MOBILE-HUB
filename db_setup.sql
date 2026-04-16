-- =============================================
-- MobileHub E-Commerce Database Setup
-- =============================================

CREATE DATABASE IF NOT EXISTS mobilehub_db;
USE mobilehub_db;

-- ── Users Table ──
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    status TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Categories Table ──
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) DEFAULT 'bi-phone',
    description TEXT,
    sort_order INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Brands Table ──
CREATE TABLE IF NOT EXISTS brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    logo VARCHAR(255),
    status TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Products Table ──
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    brand_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT,
    specifications TEXT,
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2) DEFAULT NULL,
    stock INT DEFAULT 0,
    image1 VARCHAR(255),
    image2 VARCHAR(255),
    image3 VARCHAR(255),
    featured TINYINT(1) DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Cart Table ──
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Orders Table ──
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_number VARCHAR(20) NOT NULL UNIQUE,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    address TEXT NOT NULL,
    city VARCHAR(100),
    state VARCHAR(100),
    pincode VARCHAR(10),
    phone VARCHAR(20),
    payment_method VARCHAR(50) DEFAULT 'COD',
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Order Items Table ──
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Service Bookings Table ──
CREATE TABLE IF NOT EXISTS service_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(150),
    customer_phone VARCHAR(20) NOT NULL,
    device_name VARCHAR(150) NOT NULL,
    service_type VARCHAR(100) NOT NULL,
    description TEXT,
    status ENUM('pending', 'in-progress', 'completed', 'cancelled') DEFAULT 'pending',
    estimated_cost DECIMAL(10,2) DEFAULT NULL,
    booking_date DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Reviews Table ──
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- SEED DATA
-- =============================================

-- ── Admin User (password: admin123) ──
INSERT IGNORE INTO users (name, email, phone, password, role) VALUES
('Admin', 'admin@mobilehub.com', '+91 98765 43210', '$2y$10$WsWRf8IpXUUAJBhO100r7uDgfRGV1.IaabUgxMLMFLuQ/WSsYPbhq', 'admin');

-- ── Categories ──
INSERT IGNORE INTO categories (name, slug, icon, description, sort_order) VALUES
('Smartphones', 'smartphones', 'bi-phone', 'Latest smartphones from top brands', 1),
('Tablets', 'tablets', 'bi-tablet', 'Premium tablets for work and play', 2),
('Earbuds & Audio', 'earbuds-audio', 'bi-earbuds', 'Wireless earbuds and headphones', 3),
('Smartwatches', 'smartwatches', 'bi-smartwatch', 'Smart wearables and fitness trackers', 4),
('Accessories', 'accessories', 'bi-lightning-charge', 'Cases, chargers, and more', 5),
('Power Banks', 'power-banks', 'bi-battery-charging', 'Portable power solutions', 6);

-- ── Brands ──
INSERT IGNORE INTO brands (name, slug) VALUES
('Apple', 'apple'),
('Samsung', 'samsung'),
('OnePlus', 'oneplus'),
('Xiaomi', 'xiaomi'),
('Google', 'google'),
('Vivo', 'vivo'),
('Realme', 'realme'),
('Oppo', 'oppo'),
('Nothing', 'nothing'),
('Motorola', 'motorola');

-- ── Sample Products ──
INSERT IGNORE INTO products (category_id, brand_id, name, slug, description, specifications, price, sale_price, stock, featured) VALUES
-- Apple
(1, 1, 'iPhone 16 Pro Max', 'iphone-16-pro-max',
'The most advanced iPhone ever. Featuring the A18 Pro chip, a 48MP camera system with 5x optical zoom, and an always-on display with ProMotion technology.',
'{"display":"6.9 inch Super Retina XDR OLED","processor":"A18 Pro Bionic","ram":"8 GB","storage":"256 GB","camera":"48MP + 12MP + 12MP","battery":"4685 mAh","os":"iOS 18"}',
144900.00, 139900.00, 25, 1),

(1, 1, 'iPhone 16', 'iphone-16',
'A powerful smartphone with the A18 chip, advanced dual-camera system, and all-day battery life.',
'{"display":"6.1 inch Super Retina XDR OLED","processor":"A18 Bionic","ram":"8 GB","storage":"128 GB","camera":"48MP + 12MP","battery":"3561 mAh","os":"iOS 18"}',
79900.00, NULL, 40, 1),

-- Samsung
(1, 2, 'Samsung Galaxy S25 Ultra', 'samsung-galaxy-s25-ultra',
'Galaxy AI-powered flagship with the Snapdragon 8 Elite processor, 200MP camera, and built-in S Pen.',
'{"display":"6.8 inch Dynamic AMOLED 2X","processor":"Snapdragon 8 Elite","ram":"12 GB","storage":"256 GB","camera":"200MP + 50MP + 10MP + 12MP","battery":"5000 mAh","os":"Android 15, One UI 7"}',
134999.00, 129999.00, 30, 1),

(1, 2, 'Samsung Galaxy S25', 'samsung-galaxy-s25',
'Compact flagship with Galaxy AI, stunning display, and versatile camera system.',
'{"display":"6.2 inch Dynamic AMOLED 2X","processor":"Snapdragon 8 Elite","ram":"12 GB","storage":"128 GB","camera":"50MP + 12MP + 10MP","battery":"4000 mAh","os":"Android 15, One UI 7"}',
80999.00, 74999.00, 35, 0),

-- OnePlus
(1, 3, 'OnePlus 13', 'oneplus-13',
'Performance beast with Snapdragon 8 Elite, 6000 mAh battery, and Hasselblad-tuned cameras.',
'{"display":"6.82 inch LTPO AMOLED","processor":"Snapdragon 8 Elite","ram":"12 GB","storage":"256 GB","camera":"50MP + 50MP + 50MP","battery":"6000 mAh","os":"Android 15, OxygenOS 15"}',
69999.00, 64999.00, 20, 1),

(1, 3, 'OnePlus 13R', 'oneplus-13r',
'Flagship killer with stunning AMOLED display and all-day battery performance.',
'{"display":"6.78 inch LTPO AMOLED","processor":"Snapdragon 8 Gen 3","ram":"12 GB","storage":"256 GB","camera":"50MP + 8MP + 50MP","battery":"6000 mAh","os":"Android 15, OxygenOS 15"}',
42999.00, 39999.00, 45, 1),

-- Xiaomi
(1, 4, 'Xiaomi 15 Pro', 'xiaomi-15-pro',
'Leica-powered photography flagship with Snapdragon 8 Elite and 50W wireless charging.',
'{"display":"6.73 inch LTPO AMOLED","processor":"Snapdragon 8 Elite","ram":"16 GB","storage":"512 GB","camera":"50MP + 50MP + 50MP","battery":"6100 mAh","os":"Android 15, HyperOS 2"}',
49999.00, 44999.00, 15, 0),

-- Google
(1, 5, 'Google Pixel 9 Pro', 'google-pixel-9-pro',
'The best of Google AI in a phone. Incredible camera, seamless Android experience, and 7 years of updates.',
'{"display":"6.3 inch LTPO OLED","processor":"Google Tensor G4","ram":"16 GB","storage":"128 GB","camera":"50MP + 48MP + 48MP","battery":"5060 mAh","os":"Android 15"}',
109999.00, 99999.00, 18, 1),

-- Nothing
(1, 9, 'Nothing Phone (2a) Plus', 'nothing-phone-2a-plus',
'Iconic Glyph Interface with unique transparent design and clean software experience.',
'{"display":"6.7 inch AMOLED","processor":"MediaTek Dimensity 7350 Pro","ram":"12 GB","storage":"256 GB","camera":"50MP + 50MP","battery":"5000 mAh","os":"Android 14, Nothing OS 2.6"}',
27999.00, 24999.00, 50, 1),

-- Earbuds
(3, 1, 'Apple AirPods Pro 2', 'apple-airpods-pro-2',
'Active Noise Cancellation, Adaptive Transparency, and Personalized Spatial Audio with dynamic head tracking.',
'{"driver":"Apple H2 chip","anc":"Yes","battery":"6h (30h with case)","connectivity":"Bluetooth 5.3","water_resistance":"IPX4"}',
24900.00, 22900.00, 60, 1),

(3, 2, 'Samsung Galaxy Buds3 Pro', 'samsung-galaxy-buds3-pro',
'Premium earbuds with intelligent ANC, 360 Audio, and crystal-clear call quality.',
'{"driver":"Dual driver","anc":"Yes","battery":"7h (30h with case)","connectivity":"Bluetooth 5.4","water_resistance":"IP57"}',
17999.00, 14999.00, 40, 0),

-- Smartwatches
(4, 1, 'Apple Watch Ultra 2', 'apple-watch-ultra-2',
'The most rugged Apple Watch ever. Built for extreme adventure with precision GPS and 36-hour battery life.',
'{"display":"1.93 inch LTPO OLED","processor":"S9 SiP","battery":"36 hours","water_resistance":"100m","connectivity":"LTE, Bluetooth 5.3, Wi-Fi"}',
89900.00, 84900.00, 12, 1),

(4, 2, 'Samsung Galaxy Watch Ultra', 'samsung-galaxy-watch-ultra',
'Premium smartwatch with titanium build, advanced health sensors, and multi-day battery.',
'{"display":"1.47 inch Super AMOLED","processor":"Exynos W1000","battery":"60 hours","water_resistance":"10ATM","connectivity":"LTE, Bluetooth 5.3, Wi-Fi"}',
59999.00, 54999.00, 20, 0),

-- Accessories
(5, 1, 'MagSafe Charger', 'magsafe-charger',
'Perfectly aligned wireless charging for your iPhone with snap-on magnetic alignment.',
'{"type":"Wireless Charger","output":"15W","compatibility":"iPhone 12 and later","cable":"USB-C"}',
4500.00, 3999.00, 100, 0),

(6, 4, 'Xiaomi 20000mAh Power Bank', 'xiaomi-20000mah-power-bank',
'High-capacity power bank with 33W fast charging and dual USB output.',
'{"capacity":"20000 mAh","input":"USB-C 18W","output":"USB-A 33W, USB-C 33W","weight":"405g"}',
1999.00, 1499.00, 200, 0);
