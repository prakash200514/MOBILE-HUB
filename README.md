# 📱 MobileHub — Premium Mobile E-Commerce Store
A full-featured, modern mobile e-commerce web application built with **PHP**, **MySQL**, and **Bootstrap**. MobileHub lets customers browse, filter, and purchase the latest smartphones, tablets, audio gear, smartwatches, and accessories — alongside a professional device repair & services booking platform.

## 🖥️ Live Demo
> **Local URL:** `http://localhost/mobile-store`  
> **Admin Panel:** `http://localhost/mobile-store/admin`
---

## ✨ Features
### 🛍️ Customer-Facing
- **Homepage** — Hero carousel, brand explorer, category grid, featured products, new arrivals, offer cards, testimonials
- **Shop Page** — Filter by brand/category, sort by price & newest, search, pagination
- **Product Detail** — Image gallery, specifications table, star ratings & reviews, related products, add-to-cart
- **Shopping Cart** — Quantity management, real-time subtotal, coupon support
- **Checkout** — Address entry, payment method selection (COD, UPI, Card), order summary
- **User Authentication** — Register, Login, Profile management
- **Order History** — Track past orders and their status
- **Services Booking** — Book screen repair, battery replacement, software update, and more

### 🔧 Admin Panel
- **Dashboard** — Summary stats (revenue, orders, users, products)
- **Products Management** — Add, edit, delete products with image uploads
- **Orders Management** — View and update order statuses
- **Users Management** — View all registered customers
- **Services Management** — Manage repair/service bookings
---


## 🛠️ Tech Stack
| Layer        | Technology                            |
|--------------|---------------------------------------|
| Backend      | PHP 8.x (procedural + OOP)            |
| Database     | MySQL 8.x via `mysqli`                |
| Frontend     | HTML5, CSS3, Bootstrap 5              |
| Icons        | Bootstrap Icons                       |
| Server       | Apache (XAMPP recommended)            |
| Image Upload | PHP `move_uploaded_file()`            |

## 📁 Project Structure
```
mobile-store/
├── admin/                  # Admin panel pages
│   ├── index.php           # Admin dashboard
│   ├── products.php        # Manage products
│   ├── orders.php          # Manage orders
│   ├── users.php           # Manage users
│   └── services.php        # Manage service bookings
├── api/                    # AJAX endpoints (cart, wishlist, etc.)
├── assets/
│   └── images/
│       ├── products/       # Product images (upload directory)
│       └── banners/        # Hero & promotional banners
├── includes/
│   ├── db.php              # Database connection & site config
│   ├── functions.php       # Reusable helper functions
│   ├── auth.php            # Authentication helpers
│   ├── header.php          # Global header & navigation
│   └── footer.php          # Global footer
├── index.php               # Homepage
├── shop.php                # Product listing / shop page
├── product.php             # Single product detail page
├── cart.php                # Shopping cart
├── checkout.php            # Checkout & order placement
├── login.php               # Login page
├── profile.php             # User profile & order history
├── services.php            # Repair & services booking
├── check_admin.php         # Admin access guard
├── db_setup.sql            # Database schema + seed data
└── README.md
```
## ⚙️ Installation & Setup
### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (PHP 8.x + MySQL + Apache)
- A modern web browser
### Step-by-Step
**1. Clone / Copy the Project**
```bash
# Place the folder in your XAMPP htdocs directory
C:\xampp\htdocs\mobile-store\

**2. Start XAMPP Services**
- Open XAMPP Control Panel
- Start **Apache** and **MySQL**
**3. Create the Database**
- Open your browser and go to `http://localhost/phpmyadmin`
- Click **New** → create a database named `mobilehub_db`
- Select `mobilehub_db`, click the **Import** tab
- Import `db_setup.sql` (found in the project root)
**4. Configure Database Connection**
Edit `includes/db.php` and update the credentials if needed:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // Leave empty for default XAMPP setup
define('DB_NAME', 'mobilehub_db');
define('SITE_URL', 'http://localhost/mobile-store');
```
**5. Set Up Upload Directory**
Ensure the product image upload folder exists and is writable:
```
assets/images/products/
assets/images/banners/
```
> On Windows with XAMPP these are writable by default.

**6. Launch the App**
Open your browser and visit:
```
http://localhost/mobile-store
```
