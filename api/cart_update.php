<?php
/**
 * MobileHub API — Update Cart Quantity
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$input = json_decode(file_get_contents('php://input'), true);
$productId = intval($input['product_id'] ?? 0);
$delta = intval($input['delta'] ?? 0);

if (!isLoggedIn() || !$productId) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$userId = $_SESSION['user_id'];

// Get current cart item
$stmt = $conn->prepare("SELECT c.id, c.quantity, p.price, p.sale_price, p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ? AND c.product_id = ?");
$stmt->bind_param("ii", $userId, $productId);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$item) {
    echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
    exit;
}

$newQty = $item['quantity'] + $delta;
$removed = false;

if ($newQty <= 0) {
    // Remove item
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $item['id']);
    $stmt->execute();
    $stmt->close();
    $removed = true;
} else {
    if ($newQty > $item['stock']) $newQty = $item['stock'];
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $newQty, $item['id']);
    $stmt->execute();
    $stmt->close();
}

// Calculate totals
$cartTotal = getCartTotal();
$shipping = $cartTotal >= 2999 ? 0 : 99;
$tax = $cartTotal * 0.18;
$grandTotal = $cartTotal + $tax + $shipping;

$itemPrice = $item['sale_price'] ?? $item['price'];

echo json_encode([
    'success' => true,
    'removed' => $removed,
    'new_qty' => $newQty,
    'item_total' => '₹' . number_format($itemPrice * $newQty, 0),
    'cart_count' => getCartCount(),
    'cart_subtotal' => '₹' . number_format($cartTotal, 0),
    'cart_tax' => '₹' . number_format($tax, 0),
    'cart_grand_total' => '₹' . number_format($grandTotal, 0)
]);
