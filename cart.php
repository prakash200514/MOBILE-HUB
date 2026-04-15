<?php
/**
 * MobileHub — Shopping Cart
 */
$pageTitle = 'Shopping Cart';
require_once __DIR__ . '/includes/header.php';

$cartItems = getCartItems();
$cartTotal = getCartTotal();
?>

  <div class="container-custom">
    <nav class="mh-breadcrumb">
      <ol>
        <li><a href="<?php echo SITE_URL; ?>/">Home</a></li>
        <li class="separator">/</li>
        <li>Shopping Cart</li>
      </ol>
    </nav>

    <div class="page-header">
      <h1 class="page-header-title"><i class="bi bi-bag me-2"></i>Shopping Cart</h1>
      <p class="page-header-desc"><?php echo count($cartItems); ?> item(s) in your cart</p>
    </div>
  </div>

  <div class="container-custom" style="padding-bottom: 80px;">
    <?php if (empty($cartItems)): ?>
      <div class="empty-state">
        <div class="empty-state-icon">🛒</div>
        <h3 class="empty-state-title">Your cart is empty</h3>
        <p class="empty-state-desc">Looks like you haven't added any products yet</p>
        <a href="<?php echo SITE_URL; ?>/shop.php" class="btn-gradient">
          <i class="bi bi-bag-plus"></i> Continue Shopping
        </a>
      </div>
    <?php else: ?>
      <div class="row g-4">
        <!-- Cart Items -->
        <div class="col-lg-8">
          <div id="cartItemsList">
            <?php foreach ($cartItems as $item):
              $itemPrice = $item['sale_price'] ?? $item['price'];
              $itemTotal = $itemPrice * $item['quantity'];
            ?>
            <div class="cart-item reveal" id="cartItem-<?php echo $item['product_id']; ?>">
              <div class="cart-item-img">
                <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo $item['image1'] ?? 'placeholder.png'; ?>" 
                     alt="<?php echo htmlspecialchars($item['name']); ?>"
                     onerror="this.src='https://placehold.co/100x100/0a0f1e/7c3aed?text=Phone'">
              </div>
              <div class="cart-item-info">
                <a href="<?php echo SITE_URL; ?>/product.php?slug=<?php echo $item['slug']; ?>" class="cart-item-name" style="text-decoration: none; color: var(--text-primary);">
                  <?php echo htmlspecialchars($item['name']); ?>
                </a>
                <div class="cart-item-price"><?php echo formatPrice($itemPrice); ?></div>
              </div>
              <div class="cart-qty-controls">
                <button class="cart-qty-btn" onclick="updateCartQty(<?php echo $item['product_id']; ?>, -1)">−</button>
                <span class="cart-qty-num" id="qty-<?php echo $item['product_id']; ?>"><?php echo $item['quantity']; ?></span>
                <button class="cart-qty-btn" onclick="updateCartQty(<?php echo $item['product_id']; ?>, 1)">+</button>
              </div>
              <div class="d-none d-md-block" style="min-width: 100px; text-align: right;">
                <strong id="itemTotal-<?php echo $item['product_id']; ?>"><?php echo formatPrice($itemTotal); ?></strong>
              </div>
              <button class="cart-item-remove" onclick="removeFromCart(<?php echo $item['product_id']; ?>)" title="Remove">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
            <a href="<?php echo SITE_URL; ?>/shop.php" class="btn-outline-glow">
              <i class="bi bi-arrow-left"></i> Continue Shopping
            </a>
          </div>
        </div>

        <!-- Cart Summary -->
        <div class="col-lg-4">
          <div class="cart-summary reveal">
            <h3 class="cart-summary-title">Order Summary</h3>
            <div class="cart-summary-row">
              <span>Subtotal</span>
              <span id="cartSubtotal"><?php echo formatPrice($cartTotal); ?></span>
            </div>
            <div class="cart-summary-row">
              <span>Shipping</span>
              <span style="color: var(--accent-green);"><?php echo $cartTotal >= 2999 ? 'FREE' : formatPrice(99); ?></span>
            </div>
            <div class="cart-summary-row">
              <span>Tax (GST 18%)</span>
              <span id="cartTax"><?php echo formatPrice($cartTotal * 0.18); ?></span>
            </div>
            <div class="cart-summary-total">
              <span>Total</span>
              <span id="cartGrandTotal" style="background: var(--grad-1); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">
                <?php 
                  $shipping = $cartTotal >= 2999 ? 0 : 99;
                  echo formatPrice($cartTotal + ($cartTotal * 0.18) + $shipping); 
                ?>
              </span>
            </div>
            
            <?php if (isLoggedIn()): ?>
              <a href="<?php echo SITE_URL; ?>/checkout.php" class="btn-gradient w-100 justify-content-center mt-4" id="checkoutBtn">
                <i class="bi bi-lock"></i> Proceed to Checkout
              </a>
            <?php else: ?>
              <a href="<?php echo SITE_URL; ?>/login.php?redirect=/cart.php" class="btn-gradient w-100 justify-content-center mt-4">
                <i class="bi bi-person-plus"></i> Login to Checkout
              </a>
            <?php endif; ?>

            <p style="text-align: center; font-size: 0.75rem; color: var(--text-muted); margin-top: 16px;">
              <i class="bi bi-shield-check me-1"></i> Secure checkout powered by MobileHub
            </p>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
