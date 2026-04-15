<?php
/**
 * MobileHub API — Add to Cart
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$input = json_decode(file_get_contents('php://input'), true);
$productId = intval($input['product_id'] ?? 0);
$quantity = max(1, intval($input['quantity'] ?? 1));

if (!isLoggedIn()) {
    echo json_encode([
        'success' => false, 
        'message' => 'Please login to add items to cart',
        'redirect' => SITE_URL . '/login.php'
    ]);
    exit;
}

if (!$productId) {
    echo json_encode(['success' => false, 'message' => 'Invalid product']);
    exit;
}

$userId = $_SESSION['user_id'];

// Check if product exists and has stock
$stmt = $conn->prepare("SELECT id, stock, name FROM products WHERE id = ? AND status = 1");
$stmt->bind_param("i", $productId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

if ($product['stock'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Product is out of stock']);
    exit;
}

// Check if already in cart
$stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $userId, $productId);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($existing) {
    // Update quantity
    $newQty = $existing['quantity'] + $quantity;
    if ($newQty > $product['stock']) $newQty = $product['stock'];
    
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $newQty, $existing['id']);
    $stmt->execute();
    $stmt->close();
} else {
    // Insert new
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $userId, $productId, $quantity);
    $stmt->execute();
    $stmt->close();
}

echo json_encode([
    'success' => true,
    'message' => $product['name'] . ' added to cart!',
    'cart_count' => getCartCount()
]);
