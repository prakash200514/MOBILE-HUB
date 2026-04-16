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

    // Fetch order details
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_number = ?");
    $stmt->bind_param("s", $orderNumber);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$order) {
        header('Location: ' . SITE_URL . '/shop.php');
        exit;
    }

    // Fetch order items
    $stmt = $conn->prepare("
        SELECT oi.*, p.name as product_name, p.image1 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?
    ");
    $stmt->bind_param("i", $order['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    $stmt->close();

    require_once __DIR__ . '/includes/header.php';

    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $shipping = $subtotal >= 2999 ? 0 : 99;
    $tax = $subtotal * 0.18;
    $grandTotal = $subtotal + $tax + $shipping;
?>
    <div class="container-custom section-padding">
      <div id="receipt-content" class="glass-card receipt-card mx-auto reveal" style="max-width: 800px; padding: 40px;">
        <!-- Receipt Header -->
        <div class="d-flex justify-content-between align-items-start mb-5 pb-4 border-bottom">
          <div>
            <div class="navbar-brand mb-2" style="font-size: 1.8rem;">
              <div class="brand-icon d-inline-flex me-2"><i class="bi bi-phone"></i></div>
              Mobile<span style="color: var(--primary);">Hub</span>
            </div>
            <p class="text-muted small mb-0">123 Tech Avenue, Digital Park<br>Tirunelveli, Tamil Nadu 627001<br>support@mobilehub.com</p>
          </div>
          <div class="text-end">
            <h2 style="font-family: var(--font-display); font-weight: 800; color: var(--primary); margin-bottom: 5px;">INVOICE</h2>
            <div style="font-family: var(--font-mono); font-size: 0.9rem; color: var(--text-muted);">#<?php echo htmlspecialchars($order['order_number']); ?></div>
            <div class="mt-2 small text-muted">Date: <?php echo date('d M, Y', strtotime($order['created_at'])); ?></div>
          </div>
        </div>

        <div class="row g-4 mb-5">
          <div class="col-sm-6">
            <h6 class="text-uppercase fw-bold small text-muted mb-3" style="letter-spacing: 0.05em;">Shipping To:</h6>
            <div class="fw-bold fs-5 mb-1"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
            <div class="text-muted small">
              <?php echo nl2br(htmlspecialchars($order['address'])); ?><br>
              Phone: <?php echo htmlspecialchars($order['phone']); ?>
            </div>
          </div>
          <div class="col-sm-6 text-sm-end">
            <h6 class="text-uppercase fw-bold small text-muted mb-3" style="letter-spacing: 0.05em;">Payment Details:</h6>
            <div class="mb-1">Method: <span class="fw-bold"><?php echo htmlspecialchars($order['payment_method']); ?></span></div>
            <div>Status: <span class="badge bg-success-subtle text-success border border-success-subtle px-2"><?php echo ucfirst($order['status']); ?></span></div>
          </div>
        </div>

        <!-- Order Items Table -->
        <div class="table-responsive mb-4">
          <table class="table table-borderless">
            <thead style="background: var(--bg-surface); border-radius: var(--radius-sm);">
              <tr>
                <th class="ps-3 py-3 small text-uppercase text-muted">Description</th>
                <th class="py-3 small text-uppercase text-muted text-center">Qty</th>
                <th class="py-3 small text-uppercase text-muted text-end">Price</th>
                <th class="pe-3 py-3 small text-uppercase text-muted text-end">Amount</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $item): ?>
              <tr style="border-bottom: 1px solid var(--border);">
                <td class="ps-3 py-3">
                  <div class="d-flex align-items-center gap-3">
                    <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo $item['image1'] ?? 'placeholder.png'; ?>" 
                         alt="" style="width: 40px; height: 40px; object-fit: contain;"
                         onerror="this.src='https://placehold.co/40x40/f8fafc/2563eb?text=P'">
                    <span class="fw-medium"><?php echo htmlspecialchars($item['product_name']); ?></span>
                  </div>
                </td>
                <td class="py-3 text-center"><?php echo $item['quantity']; ?></td>
                <td class="py-3 text-end"><?php echo formatPrice($item['price']); ?></td>
                <td class="pe-3 py-3 text-end fw-bold"><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Summary Totals -->
        <div class="d-flex justify-content-end">
          <div style="width: 100%; max-width: 300px;">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Subtotal:</span>
              <span class="fw-medium"><?php echo formatPrice($subtotal); ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">GST (18%):</span>
              <span class="fw-medium"><?php echo formatPrice($tax); ?></span>
            </div>
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
              <span class="text-muted">Shipping:</span>
              <span class="text-success fw-medium"><?php echo $shipping === 0 ? 'FREE' : formatPrice($shipping); ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-0">
              <span class="h5 mb-0 fw-bold">Total:</span>
              <span class="h4 mb-0 fw-extrabold text-primary"><?php echo formatPrice($order['total']); ?></span>
            </div>
          </div>
        </div>

        <div class="mt-5 pt-4 border-top text-center">
          <p class="text-muted small mb-0">Thank you for shopping with MobileHub! This is a computer-generated invoice.</p>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex gap-3 justify-content-center mt-5 no-print">
        <button onclick="window.print()" class="btn-outline-glow px-4">
          <i class="bi bi-file-earmark-pdf me-2"></i> Download Receipt
        </button>
        <a href="<?php echo SITE_URL; ?>/shop.php" class="btn-gradient px-4">
          <i class="bi bi-bag me-2"></i> Continue Shopping
        </a>
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
