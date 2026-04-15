<?php
/**
 * MobileHub — Product Detail Page
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$slug = $_GET['slug'] ?? '';
$product = getProductBySlug($slug);

if (!$product) {
    header('Location: ' . SITE_URL . '/shop.php');
    exit;
}

$pageTitle = $product['name'];
$rating = getProductRating($product['id']);
$relatedProducts = getRelatedProducts($product['category_id'], $product['id']);
$effectivePrice = $product['sale_price'] ?? $product['price'];
$discount = getDiscount($product['price'], $product['sale_price']);
$specs = json_decode($product['specifications'] ?? '{}', true);

require_once __DIR__ . '/includes/header.php';
?>

  <div class="container-custom">
    <!-- Breadcrumb -->
    <nav class="mh-breadcrumb">
      <ol>
        <li><a href="<?php echo SITE_URL; ?>/">Home</a></li>
        <li class="separator">/</li>
        <li><a href="<?php echo SITE_URL; ?>/shop.php">Shop</a></li>
        <li class="separator">/</li>
        <li><a href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo strtolower(str_replace(' ', '-', $product['category_name'])); ?>"><?php echo htmlspecialchars($product['category_name']); ?></a></li>
        <li class="separator">/</li>
        <li><?php echo htmlspecialchars($product['name']); ?></li>
      </ol>
    </nav>
  </div>

  <div class="container-custom section-padding" style="padding-top: 20px;">
    <div class="row g-5">
      <!-- Product Gallery -->
      <div class="col-lg-6">
        <div class="product-gallery reveal">
          <div class="product-gallery-main" id="mainImage">
            <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo $product['image1'] ?? 'placeholder.png'; ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                 id="productMainImg"
                 onerror="this.src='https://placehold.co/600x600/0a0f1e/7c3aed?text=<?php echo urlencode($product['name']); ?>'">
          </div>
          <div class="product-gallery-thumbs">
            <?php 
            $images = array_filter([$product['image1'], $product['image2'], $product['image3']]);
            if (empty($images)) $images = ['placeholder.png'];
            foreach ($images as $i => $img): 
            ?>
            <div class="product-gallery-thumb <?php echo $i === 0 ? 'active' : ''; ?>" 
                 onclick="changeImage('<?php echo SITE_URL; ?>/assets/images/products/<?php echo $img; ?>', this)">
              <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo $img; ?>" 
                   alt="<?php echo htmlspecialchars($product['name']); ?> view <?php echo $i + 1; ?>"
                   onerror="this.src='https://placehold.co/100x100/0a0f1e/7c3aed?text=<?php echo $i + 1; ?>'">
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Product Info -->
      <div class="col-lg-6">
        <div class="reveal">
          <div class="product-card-brand" style="font-size: 0.8rem; margin-bottom: 10px;">
            <?php echo htmlspecialchars($product['brand_name']); ?>
          </div>
          
          <h1 style="font-family: var(--font-display); font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 800; margin-bottom: 16px; line-height: 1.15;">
            <?php echo htmlspecialchars($product['name']); ?>
          </h1>

          <!-- Rating -->
          <div class="d-flex align-items-center gap-2 mb-4">
            <?php echo renderStars($rating['average']); ?>
            <span style="font-size: 0.85rem; color: var(--text-muted);">
              (<?php echo $rating['count']; ?> reviews)
            </span>
          </div>

          <!-- Price -->
          <div class="d-flex align-items-baseline gap-3 mb-4">
            <span style="font-family: var(--font-display); font-size: 2.2rem; font-weight: 800; color: var(--text-primary);">
              <?php echo formatPrice($effectivePrice); ?>
            </span>
            <?php if ($discount > 0): ?>
              <span style="font-size: 1.1rem; color: var(--text-muted); text-decoration: line-through;">
                <?php echo formatPrice($product['price']); ?>
              </span>
              <span class="price-discount" style="font-size: 0.85rem; padding: 4px 12px;">
                Save <?php echo $discount; ?>%
              </span>
            <?php endif; ?>
          </div>

          <!-- Description -->
          <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.75; margin-bottom: 32px;">
            <?php echo htmlspecialchars($product['description']); ?>
          </p>

          <!-- Stock status -->
          <div class="d-flex align-items-center gap-2 mb-4">
            <?php if ($product['stock'] > 0): ?>
              <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>In Stock (<?php echo $product['stock']; ?> left)</span>
            <?php else: ?>
              <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Out of Stock</span>
            <?php endif; ?>
          </div>

          <!-- Quantity & Cart -->
          <div class="d-flex gap-3 align-items-center mb-4 flex-wrap">
            <div class="qty-selector">
              <button type="button" onclick="updateQty(-1)">−</button>
              <input type="number" id="productQty" value="1" min="1" max="<?php echo $product['stock']; ?>" readonly>
              <button type="button" onclick="updateQty(1)">+</button>
            </div>
            <button class="btn-gradient flex-grow-1" onclick="addToCart(<?php echo $product['id']; ?>, this)" 
                    <?php echo $product['stock'] <= 0 ? 'disabled style="opacity:0.5;"' : ''; ?>
                    id="addToCartBtn">
              <i class="bi bi-bag-plus"></i> Add to Cart
            </button>
          </div>

          <!-- Quick Info -->
          <div class="glass-card" style="padding: 20px 24px;">
            <div class="row g-3">
              <div class="col-6">
                <div class="d-flex align-items-center gap-2">
                  <i class="bi bi-truck" style="color: var(--accent-cyan);"></i>
                  <span style="font-size: 0.82rem; color: var(--text-secondary);">Free delivery above ₹2,999</span>
                </div>
              </div>
              <div class="col-6">
                <div class="d-flex align-items-center gap-2">
                  <i class="bi bi-shield-check" style="color: var(--accent-green);"></i>
                  <span style="font-size: 0.82rem; color: var(--text-secondary);">1 Year warranty</span>
                </div>
              </div>
              <div class="col-6">
                <div class="d-flex align-items-center gap-2">
                  <i class="bi bi-arrow-counterclockwise" style="color: var(--accent-orange);"></i>
                  <span style="font-size: 0.82rem; color: var(--text-secondary);">7-day returns</span>
                </div>
              </div>
              <div class="col-6">
                <div class="d-flex align-items-center gap-2">
                  <i class="bi bi-patch-check" style="color: var(--accent-violet);"></i>
                  <span style="font-size: 0.82rem; color: var(--text-secondary);">100% genuine</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Specifications -->
    <?php if (!empty($specs)): ?>
    <div class="row mt-5">
      <div class="col-lg-8">
        <div class="glass-card reveal">
          <h3 style="font-family: var(--font-display); font-size: 1.3rem; font-weight: 700; margin-bottom: 24px;">
            <i class="bi bi-cpu me-2" style="color: var(--accent-cyan);"></i>Specifications
          </h3>
          <table class="specs-table">
            <?php foreach ($specs as $key => $value): ?>
            <tr>
              <td><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $key))); ?></td>
              <td><?php echo htmlspecialchars($value); ?></td>
            </tr>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="mt-5">
      <div class="section-eyebrow reveal">You May Also Like</div>
      <h2 class="section-title reveal" style="font-size: 1.5rem;">Related Products</h2>
      <div class="row g-4 mt-3">
        <?php foreach ($relatedProducts as $idx => $rp):
          $rEffective = $rp['sale_price'] ?? $rp['price'];
          $rDiscount = getDiscount($rp['price'], $rp['sale_price']);
        ?>
        <div class="col-6 col-lg-3">
          <div class="product-card reveal reveal-delay-<?php echo ($idx % 4) + 1; ?>">
            <div class="product-card-img">
              <?php if ($rDiscount > 0): ?>
                <span class="product-card-badge badge-sale"><?php echo $rDiscount; ?>% OFF</span>
              <?php endif; ?>
              <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo $rp['image1'] ?? 'placeholder.png'; ?>" 
                   alt="<?php echo htmlspecialchars($rp['name']); ?>"
                   onerror="this.src='https://placehold.co/400x400/0a0f1e/7c3aed?text=<?php echo urlencode($rp['name']); ?>'">
            </div>
            <div class="product-card-body">
              <div class="product-card-brand"><?php echo htmlspecialchars($rp['brand_name']); ?></div>
              <a href="<?php echo SITE_URL; ?>/product.php?slug=<?php echo $rp['slug']; ?>" class="product-card-name">
                <?php echo htmlspecialchars($rp['name']); ?>
              </a>
              <div class="product-card-price">
                <span class="price-current"><?php echo formatPrice($rEffective); ?></span>
                <?php if ($rDiscount > 0): ?>
                  <span class="price-old"><?php echo formatPrice($rp['price']); ?></span>
                <?php endif; ?>
              </div>
              <button class="btn-cart" onclick="addToCart(<?php echo $rp['id']; ?>, this)">
                <i class="bi bi-bag-plus"></i> Add to Cart
              </button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <script>
    function changeImage(src, thumb) {
      document.getElementById('productMainImg').src = src;
      document.querySelectorAll('.product-gallery-thumb').forEach(t => t.classList.remove('active'));
      thumb.classList.add('active');
    }

    function updateQty(delta) {
      const input = document.getElementById('productQty');
      const newVal = parseInt(input.value) + delta;
      if (newVal >= 1 && newVal <= parseInt(input.max)) {
        input.value = newVal;
      }
    }
  </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
