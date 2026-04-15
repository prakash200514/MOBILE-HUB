<?php
/**
 * MobileHub — Checkout Page
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$cartItems = getCartItems();
$cartTotal = getCartTotal();

if (empty($cartItems)) {
    header('Location: ' . SITE_URL . '/cart.php');
    exit;
}

// Handle checkout submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = sanitize($_POST['address'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $state = sanitize($_POST['state'] ?? '');
    $pincode = sanitize($_POST['pincode'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $paymentMethod = sanitize($_POST['payment_method'] ?? 'COD');
    $notes = sanitize($_POST['notes'] ?? '');

    if ($address && $city && $state && $pincode && $phone) {
        $orderNumber = generateOrderNumber();
        $shipping = $cartTotal >= 2999 ? 0 : 99;
        $tax = $cartTotal * 0.18;
        $grandTotal = $cartTotal + $tax + $shipping;
        $fullAddress = "$address, $city, $state - $pincode";
        $userId = $_SESSION['user_id'];

        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, total, address, city, state, pincode, phone, payment_method, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdsssssss", $userId, $orderNumber, $grandTotal, $fullAddress, $city, $state, $pincode, $phone, $paymentMethod, $notes);
        $stmt->execute();
        $orderId = $stmt->insert_id;
        $stmt->close();

        // Insert order items & reduce stock
        foreach ($cartItems as $item) {
            $price = $item['sale_price'] ?? $item['price'];
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $orderId, $item['product_id'], $item['quantity'], $price);
            $stmt->execute();
            $stmt->close();

            // Reduce stock
            $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
            $stmt->execute();
            $stmt->close();
        }

        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Redirect to success
        header('Location: ' . SITE_URL . '/checkout.php?success=' . $orderNumber);
        exit;
    } else {
        $error = 'Please fill in all required fields';
    }
}

// Success page
if (isset($_GET['success'])) {
    $orderNumber = $_GET['success'];
    $pageTitle = 'Order Confirmed';
    require_once __DIR__ . '/includes/header.php';
?>
    <div class="container-custom section-padding text-center">
      <div class="glass-card" style="max-width: 600px; margin: 0 auto; padding: 60px 40px;">
        <div style="font-size: 4rem; margin-bottom: 20px;">🎉</div>
        <h1 style="font-family: var(--font-display); font-size: 2rem; font-weight: 800; margin-bottom: 12px;">Order Confirmed!</h1>
        <p style="color: var(--text-secondary); font-size: 1rem; margin-bottom: 8px;">Thank you for your purchase</p>
        <div style="background: var(--bg-surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 16px; margin: 24px 0; display: inline-block;">
          <div style="font-family: var(--font-mono); font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em;">Order Number</div>
          <div style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 800; color: var(--primary);"><?php echo htmlspecialchars($orderNumber); ?></div>
        </div>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 32px;">We'll send you an email confirmation with your order details shortly.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
          <a href="<?php echo SITE_URL; ?>/profile.php?tab=orders" class="btn-gradient">
            <i class="bi bi-box-seam"></i> View My Orders
          </a>
          <a href="<?php echo SITE_URL; ?>/shop.php" class="btn-outline-glow">
            <i class="bi bi-bag"></i> Continue Shopping
          </a>
        </div>
      </div>
    </div>
<?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = 'Checkout';
$user = getCurrentUser();
require_once __DIR__ . '/includes/header.php';
$shipping = $cartTotal >= 2999 ? 0 : 99;
$tax = $cartTotal * 0.18;
$grandTotal = $cartTotal + $tax + $shipping;
?>

  <div class="container-custom">
    <nav class="mh-breadcrumb">
      <ol>
        <li><a href="<?php echo SITE_URL; ?>/">Home</a></li>
        <li class="separator">/</li>
        <li><a href="<?php echo SITE_URL; ?>/cart.php">Cart</a></li>
        <li class="separator">/</li>
        <li>Checkout</li>
      </ol>
    </nav>

    <div class="page-header">
      <h1 class="page-header-title"><i class="bi bi-lock me-2"></i>Checkout</h1>
    </div>
  </div>

  <div class="container-custom" style="padding-bottom: 80px;">
    <?php if (isset($error)): ?>
      <div class="alert alert-danger mb-4"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" id="checkoutForm">
      <div class="row g-4">
        <!-- Shipping Info -->
        <div class="col-lg-8">
          <div class="form-glass reveal">
            <h3 style="font-family: var(--font-display); font-size: 1.2rem; font-weight: 700; margin-bottom: 28px;">
              <i class="bi bi-geo-alt me-2" style="color: var(--primary);"></i>Shipping Address
            </h3>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Full Name *</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone Number *</label>
                <input type="tel" class="form-control" name="phone" placeholder="+91 XXXXX XXXXX" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
              </div>
              <div class="col-12">
                <label class="form-label">Street Address *</label>
                <input type="text" class="form-control" name="address" placeholder="House no, Street name, Area" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">City *</label>
                <input type="text" class="form-control" name="city" placeholder="City" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">State *</label>
                <input type="text" class="form-control" name="state" placeholder="State" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">PIN Code *</label>
                <input type="text" class="form-control" name="pincode" placeholder="600001" maxlength="6" required>
              </div>
              <div class="col-12">
                <label class="form-label">Order Notes (Optional)</label>
                <textarea class="form-control" name="notes" rows="3" placeholder="Special instructions for delivery..."></textarea>
              </div>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="form-glass mt-4 reveal">
            <h3 style="font-family: var(--font-display); font-size: 1.2rem; font-weight: 700; margin-bottom: 28px;">
              <i class="bi bi-credit-card me-2" style="color: var(--primary);"></i>Payment Method
            </h3>
            <div class="d-flex flex-column gap-3">
              <label class="d-flex align-items-center gap-3 p-3" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); cursor: pointer;">
                <input type="radio" name="payment_method" value="COD" checked class="form-check-input" style="margin: 0;">
                <div>
                  <div style="font-weight: 600;">💵 Cash on Delivery (COD)</div>
                  <div style="font-size: 0.8rem; color: var(--text-muted);">Pay when your order arrives</div>
                </div>
              </label>
              <label class="d-flex align-items-center gap-3 p-3" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); cursor: pointer;">
                <input type="radio" name="payment_method" value="UPI" class="form-check-input" style="margin: 0;">
                <div>
                  <div style="font-weight: 600;">📱 UPI Payment</div>
                  <div style="font-size: 0.8rem; color: var(--text-muted);">Pay via Google Pay, PhonePe, Paytm</div>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
          <div class="cart-summary reveal">
            <h3 class="cart-summary-title">Order Review</h3>
            <?php foreach ($cartItems as $item):
              $itemPrice = $item['sale_price'] ?? $item['price'];
            ?>
            <div class="d-flex align-items-center gap-3 mb-3 pb-3" style="border-bottom: 1px solid var(--border);">
              <div style="width: 50px; height: 50px; border-radius: var(--radius-sm); background: var(--bg-surface); display: flex; align-items: center; justify-content: center; padding: 5px; flex-shrink: 0;">
                <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo $item['image1'] ?? 'placeholder.png'; ?>" 
                     alt="" style="max-width: 100%; max-height: 100%; object-fit: contain;"
                     onerror="this.src='https://placehold.co/50x50/f8fafc/2563eb?text=P'">
              </div>
              <div style="flex: 1; min-width: 0;">
                <div style="font-size: 0.82rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($item['name']); ?></div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Qty: <?php echo $item['quantity']; ?></div>
              </div>
              <div style="font-weight: 600; font-size: 0.88rem; white-space: nowrap;"><?php echo formatPrice($itemPrice * $item['quantity']); ?></div>
            </div>
            <?php endforeach; ?>

            <div class="cart-summary-row">
              <span>Subtotal</span>
              <span><?php echo formatPrice($cartTotal); ?></span>
            </div>
            <div class="cart-summary-row">
              <span>Shipping</span>
              <span style="color: var(--success);"><?php echo $shipping === 0 ? 'FREE' : formatPrice($shipping); ?></span>
            </div>
            <div class="cart-summary-row">
              <span>Tax (GST 18%)</span>
              <span><?php echo formatPrice($tax); ?></span>
            </div>
            <div class="cart-summary-total">
              <span>Total</span>
              <span style="color: var(--primary); font-weight: 800;"><?php echo formatPrice($grandTotal); ?></span>
            </div>

            <button type="submit" class="btn-gradient w-100 justify-content-center mt-4" id="placeOrderBtn">
              <i class="bi bi-check-circle"></i> Place Order — <?php echo formatPrice($grandTotal); ?>
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
