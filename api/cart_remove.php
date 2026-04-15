<?php
/**
 * MobileHub API — Remove from Cart
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$input = json_decode(file_get_contents('php://input'), true);
$productId = intval($input['product_id'] ?? 0);

if (!isLoggedIn() || !$productId) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $userId, $productId);
$stmt->execute();
$stmt->close();

// Calculate totals
$cartTotal = getCartTotal();
$shipping = $cartTotal >= 2999 ? 0 : 99;
$tax = $cartTotal * 0.18;
$grandTotal = $cartTotal + $tax + $shipping;

echo json_encode([
    'success' => true,
    'message' => 'Item removed',
    'cart_count' => getCartCount(),
    'cart_subtotal' => '₹' . number_format($cartTotal, 0),
    'cart_tax' => '₹' . number_format($tax, 0),
    'cart_grand_total' => '₹' . number_format($grandTotal, 0)
]);
