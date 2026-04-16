/**
 * MobileHub — Cart AJAX Operations
 */

const SITE_URL = document.querySelector('meta[name="site-url"]')?.content || 'http://localhost/mobile-store';

/**
 * Add product to cart via AJAX
 */
function addToCart(productId, button) {
  const originalText = button.innerHTML;
  button.disabled = true;
  button.innerHTML = '<span class="spinner-glow" style="width:16px;height:16px;border-width:2px;display:inline-block;"></span> Adding...';

  fetch(`${SITE_URL}/api/cart_add.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      product_id: productId,
      quantity: parseInt(document.getElementById('productQty')?.value || 1)
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showToast(data.message || 'Added to cart!', 'success');
      updateCartBadge(data.cart_count);
      const qtyText = data.product_qty > 1 ? ` (${data.product_qty})` : '';
      button.innerHTML = `<i class="bi bi-check-lg"></i> Added${qtyText}!`;
      setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
      }, 2000);
    } else {
      showToast(data.message || 'Please login first', 'error');
      button.innerHTML = originalText;
      button.disabled = false;
      if (data.redirect) {
        setTimeout(() => window.location.href = data.redirect, 1500);
      }
    }
  })
  .catch(err => {
    showToast('Something went wrong. Please try again.', 'error');
    button.innerHTML = originalText;
    button.disabled = false;
  });
}

/**
 * Update cart quantity
 */
function updateCartQty(productId, delta) {
  fetch(`${SITE_URL}/api/cart_update.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ product_id: productId, delta: delta })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      if (data.removed) {
        // Item was removed (qty went to 0)
        const item = document.getElementById(`cartItem-${productId}`);
        if (item) {
          item.style.transition = 'all 0.3s ease';
          item.style.opacity = '0';
          item.style.transform = 'translateX(-20px)';
          setTimeout(() => item.remove(), 300);
        }
      } else {
        // Update quantity display
        const qtyEl = document.getElementById(`qty-${productId}`);
        if (qtyEl) qtyEl.textContent = data.new_qty;

        const totalEl = document.getElementById(`itemTotal-${productId}`);
        if (totalEl) totalEl.textContent = data.item_total;
      }

      // Update summary
      if (data.cart_subtotal) {
        const subtotalEl = document.getElementById('cartSubtotal');
        if (subtotalEl) subtotalEl.textContent = data.cart_subtotal;
      }
      if (data.cart_tax) {
        const taxEl = document.getElementById('cartTax');
        if (taxEl) taxEl.textContent = data.cart_tax;
      }
      if (data.cart_grand_total) {
        const grandEl = document.getElementById('cartGrandTotal');
        if (grandEl) grandEl.textContent = data.cart_grand_total;
      }

      updateCartBadge(data.cart_count);

      // If cart is empty, reload
      if (data.cart_count === 0) {
        setTimeout(() => location.reload(), 500);
      }
    }
  })
  .catch(err => {
    showToast('Failed to update cart', 'error');
  });
}

/**
 * Remove item from cart
 */
function removeFromCart(productId) {
  fetch(`${SITE_URL}/api/cart_remove.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ product_id: productId })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showToast('Item removed from cart', 'info');
      const item = document.getElementById(`cartItem-${productId}`);
      if (item) {
        item.style.transition = 'all 0.3s ease';
        item.style.opacity = '0';
        item.style.transform = 'translateX(-30px)';
        setTimeout(() => {
          item.remove();
          if (data.cart_count === 0) location.reload();
        }, 300);
      }
      updateCartBadge(data.cart_count);

      // Update totals
      if (data.cart_subtotal) {
        const subtotalEl = document.getElementById('cartSubtotal');
        if (subtotalEl) subtotalEl.textContent = data.cart_subtotal;
      }
      if (data.cart_tax) {
        const taxEl = document.getElementById('cartTax');
        if (taxEl) taxEl.textContent = data.cart_tax;
      }
      if (data.cart_grand_total) {
        const grandEl = document.getElementById('cartGrandTotal');
        if (grandEl) grandEl.textContent = data.cart_grand_total;
      }
    }
  })
  .catch(err => {
    showToast('Failed to remove item', 'error');
  });
}

/**
 * Update cart badge in navbar
 */
function updateCartBadge(count) {
  const badge = document.getElementById('cartCountBadge');
  if (count > 0) {
    if (badge) {
      badge.textContent = count;
    } else {
      // Create badge if it doesn't exist
      const cartLink = document.querySelector('.nav-cart-badge');
      if (cartLink) {
        const newBadge = document.createElement('span');
        newBadge.className = 'cart-count';
        newBadge.id = 'cartCountBadge';
        newBadge.textContent = count;
        cartLink.appendChild(newBadge);
      }
    }
  } else if (badge) {
    badge.remove();
  }
}
