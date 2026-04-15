<?php
/**
 * MobileHub — Utility Functions
 */

require_once __DIR__ . '/db.php';

/**
 * Sanitize user input
 */
function sanitize($data) {
    global $conn;
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($data))));
}

/**
 * Format price in INR
 */
function formatPrice($price) {
    return '₹' . number_format($price, 0);
}

/**
 * Calculate discount percentage
 */
function getDiscount($price, $salePrice) {
    if (!$salePrice || $salePrice >= $price) return 0;
    return round((($price - $salePrice) / $price) * 100);
}

/**
 * Get cart count for current user
 */
function getCartCount() {
    if (!isLoggedIn()) return 0;
    global $conn;
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result['total'] ?? 0;
}

/**
 * Get cart items for current user
 */
function getCartItems() {
    if (!isLoggedIn()) return [];
    global $conn;
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("
        SELECT c.*, p.name, p.price, p.sale_price, p.image1, p.stock, p.slug
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    $stmt->close();
    return $items;
}

/**
 * Get cart total
 */
function getCartTotal() {
    $items = getCartItems();
    $total = 0;
    foreach ($items as $item) {
        $price = $item['sale_price'] ?? $item['price'];
        $total += $price * $item['quantity'];
    }
    return $total;
}

/**
 * Generate unique order number
 */
function generateOrderNumber() {
    return 'MH' . date('Ymd') . strtoupper(substr(uniqid(), -5));
}

/**
 * Get all categories
 */
function getCategories() {
    global $conn;
    $result = $conn->query("SELECT * FROM categories WHERE status = 1 ORDER BY sort_order ASC");
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    return $categories;
}

/**
 * Get all brands
 */
function getBrands() {
    global $conn;
    $result = $conn->query("SELECT * FROM brands WHERE status = 1 ORDER BY name ASC");
    $brands = [];
    while ($row = $result->fetch_assoc()) {
        $brands[] = $row;
    }
    return $brands;
}

/**
 * Get featured products
 */
function getFeaturedProducts($limit = 8) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT p.*, b.name as brand_name, c.name as category_name 
        FROM products p 
        JOIN brands b ON p.brand_id = b.id 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.featured = 1 AND p.status = 1 
        ORDER BY p.created_at DESC 
        LIMIT ?
    ");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $stmt->close();
    return $products;
}

/**
 * Get product by slug
 */
function getProductBySlug($slug) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT p.*, b.name as brand_name, c.name as category_name 
        FROM products p 
        JOIN brands b ON p.brand_id = b.id 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.slug = ? AND p.status = 1
    ");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $product;
}

/**
 * Get related products
 */
function getRelatedProducts($categoryId, $excludeId, $limit = 4) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT p.*, b.name as brand_name 
        FROM products p 
        JOIN brands b ON p.brand_id = b.id 
        WHERE p.category_id = ? AND p.id != ? AND p.status = 1 
        ORDER BY RAND() 
        LIMIT ?
    ");
    $stmt->bind_param("iii", $categoryId, $excludeId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $stmt->close();
    return $products;
}

/**
 * Get product average rating
 */
function getProductRating($productId) {
    global $conn;
    $stmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return [
        'average' => round($result['avg_rating'] ?? 0, 1),
        'count' => $result['total_reviews'] ?? 0
    ];
}

/**
 * Render star rating HTML
 */
function renderStars($rating) {
    $html = '<div class="stars">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= floor($rating)) {
            $html .= '<i class="bi bi-star-fill"></i>';
        } elseif ($i - 0.5 <= $rating) {
            $html .= '<i class="bi bi-star-half"></i>';
        } else {
            $html .= '<i class="bi bi-star"></i>';
        }
    }
    $html .= '</div>';
    return $html;
}

/**
 * Get status badge HTML
 */
function statusBadge($status) {
    $colors = [
        'pending' => 'warning',
        'confirmed' => 'info',
        'shipped' => 'primary',
        'delivered' => 'success',
        'cancelled' => 'danger',
        'in-progress' => 'info',
        'completed' => 'success'
    ];
    $color = $colors[$status] ?? 'secondary';
    return '<span class="badge bg-' . $color . '">' . ucfirst($status) . '</span>';
}

/**
 * Upload product image
 */
function uploadImage($file) {
    $targetDir = UPLOAD_DIR;
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    
    if (!in_array($extension, $allowed)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'File too large (max 5MB)'];
    }
    
    $fileName = uniqid('prod_') . '.' . $extension;
    $targetPath = $targetDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'filename' => $fileName];
    }
    
    return ['success' => false, 'message' => 'Upload failed'];
}

/**
 * Set flash message
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Get and clear flash message
 */
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
