<?php
/**
 * MobileHub — Home Page (E-Commerce Design)
 */
$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';

$featuredProducts = getFeaturedProducts(8);
$categories = getCategories();

// Get new arrivals (latest products)
$newArrivals = $conn->query("SELECT p.*, b.name as brand_name FROM products p JOIN brands b ON p.brand_id = b.id WHERE p.status = 1 ORDER BY p.created_at DESC LIMIT 4")->fetch_all(MYSQLI_ASSOC);
?>

  <!-- ══════════════════════════════════════════
       HERO BANNER
       ══════════════════════════════════════════ -->
  <section class="hero-section">
    <div class="container-custom" style="padding-top: 24px;">
      <div class="hero-banner reveal">
        <div class="hero-content">
          <div class="hero-badge">🔥 New Launch</div>
          <h1 class="hero-title">
            Upgrade to the <span class="gradient-text">Latest Smartphones</span>
          </h1>
          <p class="hero-desc">
            Get up to 40% off on flagship devices. Free delivery on orders above ₹2,999. EMI options available.
          </p>
          <div class="hero-actions">
            <a href="<?php echo SITE_URL; ?>/shop.php" class="btn-gradient" id="hero-shop-btn">
              <i class="bi bi-bag"></i> Shop Now
            </a>
            <a href="<?php echo SITE_URL; ?>/shop.php?sort=newest" class="btn-outline-glow" style="background: rgba(255,255,255,0.15); color: #fff; border-color: rgba(255,255,255,0.3);">
              <i class="bi bi-stars"></i> New Arrivals
            </a>
          </div>
        </div>
        <div class="hero-phone-showcase d-none d-lg-block">
          <svg class="hero-phone-img" width="220" height="340" viewBox="0 0 220 340" fill="none">
            <rect x="6" y="6" width="208" height="328" rx="32" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.25)" stroke-width="1.5"/>
            <rect x="18" y="42" width="184" height="258" rx="6" fill="rgba(255,255,255,0.06)"/>
            <circle cx="110" cy="24" r="5" fill="rgba(255,255,255,0.15)"/>
            <rect x="85" y="314" width="50" height="4" rx="2" fill="rgba(255,255,255,0.15)"/>
            <rect x="35" y="60" width="150" height="85" rx="10" fill="rgba(255,255,255,0.1)"/>
            <text x="110" y="110" text-anchor="middle" fill="rgba(255,255,255,0.5)" font-family="sans-serif" font-size="11" font-weight="600">MobileHub</text>
            <rect x="35" y="160" width="70" height="70" rx="10" fill="rgba(255,255,255,0.07)"/>
            <rect x="115" y="160" width="70" height="70" rx="10" fill="rgba(255,255,255,0.07)"/>
            <rect x="35" y="245" width="150" height="40" rx="20" fill="rgba(255,255,255,0.15)"/>
            <text x="110" y="270" text-anchor="middle" fill="rgba(255,255,255,0.7)" font-family="sans-serif" font-size="12" font-weight="700">Shop Now</text>
          </svg>
        </div>
      </div>

      <!-- Promo Cards -->
      <div class="promo-grid reveal">
        <div class="promo-card">
          <div class="promo-card-icon" style="background: #eff6ff; color: #2563eb;">🚚</div>
          <div>
            <div class="promo-card-title">Free Delivery</div>
            <div class="promo-card-desc">Orders above ₹2,999</div>
          </div>
        </div>
        <div class="promo-card">
          <div class="promo-card-icon" style="background: #ecfdf5; color: #059669;">✅</div>
          <div>
            <div class="promo-card-title">100% Genuine</div>
            <div class="promo-card-desc">Brand warranty included</div>
          </div>
        </div>
        <div class="promo-card">
          <div class="promo-card-icon" style="background: #fef3c7; color: #d97706;">🔄</div>
          <div>
            <div class="promo-card-title">Easy Returns</div>
            <div class="promo-card-desc">7-day return policy</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       BRAND MARQUEE
       ══════════════════════════════════════════ -->
  <div class="brand-marquee" style="margin-top: 32px;">
    <div class="brand-track">
      <span class="brand-item">Apple</span>
      <span class="brand-item">Samsung</span>
      <span class="brand-item">OnePlus</span>
      <span class="brand-item">Xiaomi</span>
      <span class="brand-item">Google</span>
      <span class="brand-item">Vivo</span>
      <span class="brand-item">Realme</span>
      <span class="brand-item">Nothing</span>
      <span class="brand-item">Oppo</span>
      <span class="brand-item">Motorola</span>
      <span class="brand-item">Apple</span>
      <span class="brand-item">Samsung</span>
      <span class="brand-item">OnePlus</span>
      <span class="brand-item">Xiaomi</span>
      <span class="brand-item">Google</span>
      <span class="brand-item">Vivo</span>
      <span class="brand-item">Realme</span>
      <span class="brand-item">Nothing</span>
      <span class="brand-item">Oppo</span>
      <span class="brand-item">Motorola</span>
    </div>
  </div>

  <!-- ══════════════════════════════════════════
       CATEGORIES
       ══════════════════════════════════════════ -->
  <section class="section-padding" id="categories">
    <div class="container-custom">
      <div class="text-center mb-4">
        <div class="section-eyebrow justify-content-center reveal">Browse by Category</div>
        <h2 class="section-title reveal" style="text-align: center;">Shop by Category</h2>
      </div>

      <div class="row g-3 g-md-4">
        <?php 
        $catIcons = ['📱', '📟', '🎧', '⌚', '🔌', '🔋'];
        $i = 0;
        foreach ($categories as $cat): 
        ?>
        <div class="col-4 col-md-2">
          <a href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo $cat['slug']; ?>" class="category-card reveal reveal-delay-<?php echo ($i % 6) + 1; ?>">
            <div class="category-card-icon"><?php echo $catIcons[$i] ?? '📦'; ?></div>
            <div class="category-card-name"><?php echo htmlspecialchars($cat['name']); ?></div>
          </a>
        </div>
        <?php $i++; endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       DEAL BANNER
       ══════════════════════════════════════════ -->
  <section style="padding: 0;">
    <div class="container-custom">
      <div class="deal-strip reveal">
        <div class="deal-strip-text">
          ⚡ Flash Sale — Up to 40% OFF on iPhones <span>| Limited period offer</span>
        </div>
        <a href="<?php echo SITE_URL; ?>/shop.php?category=smartphones" class="btn-gradient btn-gradient-sm" style="color: #dc2626;">
          Shop Deals <i class="bi bi-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       FEATURED PRODUCTS
       ══════════════════════════════════════════ -->
  <section class="section-padding" id="featured">
    <div class="container-custom">
      <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
        <div>
          <div class="section-eyebrow reveal">🔥 Hot Picks</div>
          <h2 class="section-title reveal">Featured Products</h2>
          <p class="section-subtitle reveal">Handpicked top sellers and trending devices</p>
        </div>
        <a href="<?php echo SITE_URL; ?>/shop.php" class="btn-outline-glow reveal" style="white-space: nowrap;">
          View All <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>

      <div class="row g-3 g-lg-4">
        <?php foreach ($featuredProducts as $idx => $product): 
          $effectivePrice = $product['sale_price'] ?? $product['price'];
          $discount = getDiscount($product['price'], $product['sale_price']);
          $rating = getProductRating($product['id']);
        ?>
        <div class="col-6 col-lg-3">
          <div class="product-card reveal reveal-delay-<?php echo ($idx % 4) + 1; ?>">
            <div class="product-card-img">
              <?php if ($discount > 0): ?>
                <span class="product-card-badge badge-sale"><?php echo $discount; ?>% OFF</span>
              <?php elseif ($product['featured']): ?>
                <span class="product-card-badge badge-featured">Featured</span>
              <?php endif; ?>
              <div class="product-card-wishlist"><i class="bi bi-heart"></i></div>
              <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo $product['image1'] ?? 'placeholder.png'; ?>" 
                   alt="<?php echo htmlspecialchars($product['name']); ?>"
                   onerror="this.src='https://placehold.co/400x400/f8fafc/2563eb?text=<?php echo urlencode($product['name']); ?>'">
            </div>
            <div class="product-card-body">
              <div class="product-card-brand"><?php echo htmlspecialchars($product['brand_name']); ?></div>
              <a href="<?php echo SITE_URL; ?>/product.php?slug=<?php echo $product['slug']; ?>" class="product-card-name">
                <?php echo htmlspecialchars($product['name']); ?>
              </a>
              <div class="stars">
                <?php echo renderStars($rating['average']); ?>
              </div>
              <div class="product-card-price">
                <span class="price-current"><?php echo formatPrice($effectivePrice); ?></span>
                <?php if ($discount > 0): ?>
                  <span class="price-old"><?php echo formatPrice($product['price']); ?></span>
                  <span class="price-discount"><?php echo $discount; ?>% off</span>
                <?php endif; ?>
              </div>
              <button class="btn-cart" onclick="addToCart(<?php echo $product['id']; ?>, this)" id="add-cart-<?php echo $product['id']; ?>">
                <i class="bi bi-cart-plus"></i> Add to Cart
              </button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       SERVICES PREVIEW
       ══════════════════════════════════════════ -->
  <section class="section-padding" style="background: var(--bg-white); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
    <div class="container-custom">
      <div class="text-center mb-4">
        <div class="section-eyebrow justify-content-center reveal">🔧 Expert Services</div>
        <h2 class="section-title reveal" style="text-align: center;">We Fix, You Relax</h2>
        <p class="section-subtitle mx-auto reveal" style="text-align: center;">Professional device repair and maintenance by certified technicians</p>
      </div>

      <div class="row g-3 g-lg-4">
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-1">
            <div class="service-card-icon" style="background: #fef2f2; color: #dc2626;"><i class="bi bi-phone"></i></div>
            <h3 class="service-card-title">Screen Repair</h3>
            <p class="service-card-desc">OEM-quality display replacement with full warranty coverage.</p>
            <div class="service-card-price">Starting from ₹1,499</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-2">
            <div class="service-card-icon" style="background: #ecfdf5; color: #059669;"><i class="bi bi-battery-charging"></i></div>
            <h3 class="service-card-title">Battery Replacement</h3>
            <p class="service-card-desc">Genuine battery replacement to restore full-day battery life.</p>
            <div class="service-card-price">Starting from ₹999</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-3">
            <div class="service-card-icon" style="background: #eff6ff; color: #2563eb;"><i class="bi bi-cpu"></i></div>
            <h3 class="service-card-title">Software Update</h3>
            <p class="service-card-desc">OS upgrades, malware removal, and performance optimization.</p>
            <div class="service-card-price">Starting from ₹499</div>
          </div>
        </div>
      </div>

      <div class="text-center mt-4 reveal">
        <a href="<?php echo SITE_URL; ?>/services.php" class="btn-primary-solid">
          <i class="bi bi-calendar-check"></i> Book a Service
        </a>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       NEW ARRIVALS
       ══════════════════════════════════════════ -->
  <?php if (!empty($newArrivals)): ?>
  <section class="section-padding">
    <div class="container-custom">
      <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
        <div>
          <div class="section-eyebrow reveal">✨ Just Arrived</div>
          <h2 class="section-title reveal">New Arrivals</h2>
        </div>
        <a href="<?php echo SITE_URL; ?>/shop.php?sort=newest" class="btn-outline-glow reveal">
          View All <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>

      <div class="row g-3 g-lg-4">
        <?php foreach ($newArrivals as $idx => $product):
          $effectivePrice = $product['sale_price'] ?? $product['price'];
          $discount = getDiscount($product['price'], $product['sale_price']);
        ?>
        <div class="col-6 col-lg-3">
          <div class="product-card reveal reveal-delay-<?php echo ($idx % 4) + 1; ?>">
            <div class="product-card-img">
              <span class="product-card-badge badge-new">NEW</span>
              <div class="product-card-wishlist"><i class="bi bi-heart"></i></div>
              <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo $product['image1'] ?? 'placeholder.png'; ?>"
                   alt="<?php echo htmlspecialchars($product['name']); ?>"
                   onerror="this.src='https://placehold.co/400x400/f8fafc/2563eb?text=<?php echo urlencode($product['name']); ?>'">
            </div>
            <div class="product-card-body">
              <div class="product-card-brand"><?php echo htmlspecialchars($product['brand_name']); ?></div>
              <a href="<?php echo SITE_URL; ?>/product.php?slug=<?php echo $product['slug']; ?>" class="product-card-name">
                <?php echo htmlspecialchars($product['name']); ?>
              </a>
              <div class="product-card-price">
                <span class="price-current"><?php echo formatPrice($effectivePrice); ?></span>
                <?php if ($discount > 0): ?>
                  <span class="price-old"><?php echo formatPrice($product['price']); ?></span>
                <?php endif; ?>
              </div>
              <button class="btn-cart" onclick="addToCart(<?php echo $product['id']; ?>, this)">
                <i class="bi bi-cart-plus"></i> Add to Cart
              </button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ══════════════════════════════════════════
       WHY CHOOSE US
       ══════════════════════════════════════════ -->
  <section class="section-padding" style="background: var(--bg-white); border-top: 1px solid var(--border);">
    <div class="container-custom">
      <div class="text-center mb-4">
        <h2 class="section-title reveal" style="text-align: center;">Why Shop at MobileHub?</h2>
      </div>
      <div class="row g-3 g-lg-4">
        <div class="col-6 col-lg-3">
          <div class="glass-card text-center reveal reveal-delay-1" style="padding: 32px 20px;">
            <div style="font-size: 2.5rem; margin-bottom: 14px;">🚚</div>
            <h4 style="font-family: var(--font-display); font-weight: 700; font-size: 1rem; margin-bottom: 6px; color: var(--text-dark);">Free Delivery</h4>
            <p style="font-size: 0.82rem; color: var(--text-secondary); margin-bottom: 0;">Free shipping on orders above ₹2,999</p>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="glass-card text-center reveal reveal-delay-2" style="padding: 32px 20px;">
            <div style="font-size: 2.5rem; margin-bottom: 14px;">✅</div>
            <h4 style="font-family: var(--font-display); font-weight: 700; font-size: 1rem; margin-bottom: 6px; color: var(--text-dark);">100% Genuine</h4>
            <p style="font-size: 0.82rem; color: var(--text-secondary); margin-bottom: 0;">Verified genuine with brand warranty</p>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="glass-card text-center reveal reveal-delay-3" style="padding: 32px 20px;">
            <div style="font-size: 2.5rem; margin-bottom: 14px;">🔄</div>
            <h4 style="font-family: var(--font-display); font-weight: 700; font-size: 1rem; margin-bottom: 6px; color: var(--text-dark);">Easy Returns</h4>
            <p style="font-size: 0.82rem; color: var(--text-secondary); margin-bottom: 0;">7-day hassle-free return policy</p>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="glass-card text-center reveal reveal-delay-4" style="padding: 32px 20px;">
            <div style="font-size: 2.5rem; margin-bottom: 14px;">🔒</div>
            <h4 style="font-family: var(--font-display); font-weight: 700; font-size: 1rem; margin-bottom: 6px; color: var(--text-dark);">Secure Payment</h4>
            <p style="font-size: 0.82rem; color: var(--text-secondary); margin-bottom: 0;">Multiple secure payment options</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       TESTIMONIALS
       ══════════════════════════════════════════ -->
  <section class="section-padding">
    <div class="container-custom">
      <div class="text-center mb-4">
        <div class="section-eyebrow justify-content-center reveal">⭐ Reviews</div>
        <h2 class="section-title reveal" style="text-align: center;">Customer Reviews</h2>
      </div>
      <div class="row g-3 g-lg-4">
        <div class="col-md-4">
          <div class="testimonial-card reveal reveal-delay-1">
            <div class="stars mb-3">
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
            </div>
            <p class="testimonial-quote">"Amazing experience! Got my iPhone 16 Pro delivered the next day. Premium packaging and unbeatable price. Will shop again!"</p>
            <div class="testimonial-author">
              <div class="testimonial-avatar">RK</div>
              <div>
                <div class="testimonial-name">Rajesh Kumar</div>
                <div class="testimonial-role">Chennai, Tamil Nadu</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testimonial-card reveal reveal-delay-2">
            <div class="stars mb-3">
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
            </div>
            <p class="testimonial-quote">"Got my Samsung screen repaired — fast, affordable, genuine parts. Phone looks brand new! Excellent service team."</p>
            <div class="testimonial-author">
              <div class="testimonial-avatar">PS</div>
              <div>
                <div class="testimonial-name">Priya Selvam</div>
                <div class="testimonial-role">Tirunelveli, Tamil Nadu</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testimonial-card reveal reveal-delay-3">
            <div class="stars mb-3">
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
              <i class="bi bi-star-fill" style="color: var(--warning);"></i>
              <i class="bi bi-star-half" style="color: var(--warning);"></i>
            </div>
            <p class="testimonial-quote">"Best mobile store! Clean UI, easy navigation, top-notch support. Bought OnePlus 13 at the best price available."</p>
            <div class="testimonial-author">
              <div class="testimonial-avatar">AV</div>
              <div>
                <div class="testimonial-name">Arun Vijay</div>
                <div class="testimonial-role">Madurai, Tamil Nadu</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
