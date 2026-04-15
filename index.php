<?php
/**
 * MobileHub — Home Page
 */
$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';

$featuredProducts = getFeaturedProducts(8);
$categories = getCategories();
?>

  <!-- ══════════════════════════════════════════
       HERO SECTION
       ══════════════════════════════════════════ -->
  <section class="hero-section" id="hero">
    <div class="container">
      <div class="row align-items-center">
        <!-- Left: Content -->
        <div class="col-lg-6">
          <div class="hero-badge reveal">🚀 Premium Mobile Store</div>
          <h1 class="hero-title reveal">
            Get the <span class="gradient-text">Latest Phones</span> at Best Prices
          </h1>
          <p class="hero-desc reveal">
            Discover flagship smartphones, premium accessories, and expert repair services — all in one place. Trusted by thousands of happy customers across India.
          </p>
          <div class="hero-actions reveal">
            <a href="<?php echo SITE_URL; ?>/shop.php" class="btn-gradient" id="hero-shop-btn">
              <i class="bi bi-bag"></i> Shop Now
            </a>
            <a href="<?php echo SITE_URL; ?>/services.php" class="btn-outline-glow" id="hero-service-btn">
              <i class="bi bi-tools"></i> Book a Service
            </a>
          </div>
          <div class="hero-stats reveal">
            <div>
              <div class="hero-stat-num">500+</div>
              <div class="hero-stat-label">Products</div>
            </div>
            <div>
              <div class="hero-stat-num">10K+</div>
              <div class="hero-stat-label">Happy Customers</div>
            </div>
            <div>
              <div class="hero-stat-num">24/7</div>
              <div class="hero-stat-label">Support</div>
            </div>
          </div>
        </div>

        <!-- Right: Phone Showcase -->
        <div class="col-lg-6 d-none d-lg-block">
          <div class="hero-phone-showcase">
            <div class="hero-phone-glow"></div>
            <!-- Large phone SVG placeholder -->
            <svg class="hero-phone-img" width="300" height="520" viewBox="0 0 300 520" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="10" y="10" width="280" height="500" rx="40" fill="#0a0f1e" stroke="url(#grad)" stroke-width="2"/>
              <rect x="25" y="60" width="250" height="400" rx="8" fill="#111827"/>
              <circle cx="150" cy="35" r="8" fill="#1f2937"/>
              <rect x="120" y="475" width="60" height="5" rx="2.5" fill="#1f2937"/>
              <!-- Screen content -->
              <rect x="40" y="80" width="220" height="120" rx="12" fill="url(#screenGrad)" opacity="0.8"/>
              <rect x="40" y="215" width="100" height="100" rx="12" fill="rgba(124,58,237,0.2)"/>
              <rect x="155" y="215" width="105" height="100" rx="12" fill="rgba(0,212,255,0.2)"/>
              <rect x="40" y="330" width="220" height="50" rx="12" fill="rgba(16,185,129,0.15)"/>
              <rect x="40" y="395" width="220" height="50" rx="25" fill="url(#grad)"/>
              <text x="150" y="425" text-anchor="middle" fill="white" font-family="sans-serif" font-size="14" font-weight="bold">Shop Now</text>
              <defs>
                <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" style="stop-color:#7c3aed;stop-opacity:1"/>
                  <stop offset="100%" style="stop-color:#00d4ff;stop-opacity:1"/>
                </linearGradient>
                <linearGradient id="screenGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" style="stop-color:#1a0533;stop-opacity:1"/>
                  <stop offset="100%" style="stop-color:#0a1628;stop-opacity:1"/>
                </linearGradient>
              </defs>
            </svg>

            <!-- Floating cards -->
            <div class="hero-float-card" style="top: 60px; right: -20px;">
              <span class="card-icon">📱</span> iPhone 16 Pro
            </div>
            <div class="hero-float-card" style="bottom: 120px; left: -40px;">
              <span class="card-icon">⚡</span> Fast Delivery
            </div>
            <div class="hero-float-card" style="bottom: 40px; right: 0;">
              <span class="card-icon">🛡️</span> 1 Year Warranty
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       BRAND MARQUEE
       ══════════════════════════════════════════ -->
  <section style="padding: 0; position: relative; z-index: 1;">
    <div class="brand-marquee">
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
        <!-- Duplicate for seamless loop -->
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
  </section>

  <!-- ══════════════════════════════════════════
       CATEGORIES
       ══════════════════════════════════════════ -->
  <section class="section-padding" id="categories">
    <div class="container">
      <div class="text-center mb-5">
        <div class="section-eyebrow justify-content-center reveal">Browse Categories</div>
        <h2 class="section-title reveal">Shop by Category</h2>
        <p class="section-subtitle mx-auto reveal">Find exactly what you need from our curated collection</p>
      </div>

      <div class="row g-4">
        <?php 
        $catIcons = ['📱', '📟', '🎧', '⌚', '🔌', '🔋'];
        $i = 0;
        foreach ($categories as $cat): 
        ?>
        <div class="col-6 col-md-4 col-lg-2">
          <a href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo $cat['slug']; ?>" class="category-card reveal reveal-delay-<?php echo ($i % 4) + 1; ?>">
            <div class="category-card-icon"><?php echo $catIcons[$i] ?? '📦'; ?></div>
            <div class="category-card-name"><?php echo htmlspecialchars($cat['name']); ?></div>
          </a>
        </div>
        <?php $i++; endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       FEATURED PRODUCTS
       ══════════════════════════════════════════ -->
  <section class="section-padding" id="featured">
    <div class="container">
      <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
        <div>
          <div class="section-eyebrow reveal">Hot Picks</div>
          <h2 class="section-title reveal">Featured Products</h2>
          <p class="section-subtitle reveal">Handpicked top sellers and trending devices</p>
        </div>
        <a href="<?php echo SITE_URL; ?>/shop.php" class="btn-outline-glow reveal" style="white-space: nowrap;">
          View All Products <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>

      <div class="row g-4">
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
              <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo $product['image1'] ?? 'placeholder.png'; ?>" 
                   alt="<?php echo htmlspecialchars($product['name']); ?>"
                   onerror="this.src='https://placehold.co/400x400/0a0f1e/7c3aed?text=<?php echo urlencode($product['name']); ?>'">
            </div>
            <div class="product-card-body">
              <div class="product-card-brand"><?php echo htmlspecialchars($product['brand_name']); ?></div>
              <a href="<?php echo SITE_URL; ?>/product.php?slug=<?php echo $product['slug']; ?>" class="product-card-name">
                <?php echo htmlspecialchars($product['name']); ?>
              </a>
              <?php echo renderStars($rating['average']); ?>
              <div class="product-card-price">
                <span class="price-current"><?php echo formatPrice($effectivePrice); ?></span>
                <?php if ($discount > 0): ?>
                  <span class="price-old"><?php echo formatPrice($product['price']); ?></span>
                <?php endif; ?>
              </div>
              <button class="btn-cart" onclick="addToCart(<?php echo $product['id']; ?>, this)" id="add-cart-<?php echo $product['id']; ?>">
                <i class="bi bi-bag-plus"></i> Add to Cart
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
  <section class="section-padding" id="services-preview">
    <div class="container">
      <div class="text-center mb-5">
        <div class="section-eyebrow justify-content-center reveal">Expert Services</div>
        <h2 class="section-title reveal">We Fix, You Relax</h2>
        <p class="section-subtitle mx-auto reveal">Professional device repair and maintenance services with certified technicians</p>
      </div>

      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-1">
            <div class="service-card-icon" style="background: rgba(239,68,68,0.1); color: var(--accent-red);">
              <i class="bi bi-phone"></i>
            </div>
            <h3 class="service-card-title">Screen Repair</h3>
            <p class="service-card-desc">Cracked or broken screen? We replace displays with OEM-quality parts and full warranty.</p>
            <div class="service-card-price">Starting from ₹1,499</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-2">
            <div class="service-card-icon" style="background: rgba(16,185,129,0.1); color: var(--accent-green);">
              <i class="bi bi-battery-charging"></i>
            </div>
            <h3 class="service-card-title">Battery Replacement</h3>
            <p class="service-card-desc">Restore your phone's battery life with genuine replacement batteries and expert installation.</p>
            <div class="service-card-price">Starting from ₹999</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-3">
            <div class="service-card-icon" style="background: rgba(0,212,255,0.1); color: var(--accent-cyan);">
              <i class="bi bi-cpu"></i>
            </div>
            <h3 class="service-card-title">Software Update</h3>
            <p class="service-card-desc">OS upgrades, malware removal, data recovery, and performance optimization services.</p>
            <div class="service-card-price">Starting from ₹499</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-1">
            <div class="service-card-icon" style="background: rgba(124,58,237,0.1); color: var(--accent-violet);">
              <i class="bi bi-droplet"></i>
            </div>
            <h3 class="service-card-title">Water Damage</h3>
            <p class="service-card-desc">Dropped your phone in water? Our ultrasonic cleaning process can save your device.</p>
            <div class="service-card-price">Starting from ₹1,999</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-2">
            <div class="service-card-icon" style="background: rgba(245,158,11,0.1); color: var(--accent-orange);">
              <i class="bi bi-camera"></i>
            </div>
            <h3 class="service-card-title">Camera Repair</h3>
            <p class="service-card-desc">Fix blurry photos, broken lens, or camera module issues with precision repair work.</p>
            <div class="service-card-price">Starting from ₹1,299</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="service-card reveal reveal-delay-3">
            <div class="service-card-icon" style="background: rgba(232,121,249,0.1); color: var(--accent-pink);">
              <i class="bi bi-shield-check"></i>
            </div>
            <h3 class="service-card-title">General Checkup</h3>
            <p class="service-card-desc">Complete device health check including diagnostics, cleaning, and optimization.</p>
            <div class="service-card-price">Starting from ₹299</div>
          </div>
        </div>
      </div>

      <div class="text-center mt-5 reveal">
        <a href="<?php echo SITE_URL; ?>/services.php" class="btn-gradient">
          <i class="bi bi-calendar-check"></i> Book a Service
        </a>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       WHY CHOOSE US
       ══════════════════════════════════════════ -->
  <section class="section-padding">
    <div class="container">
      <div class="text-center mb-5">
        <div class="section-eyebrow justify-content-center reveal">Why MobileHub</div>
        <h2 class="section-title reveal">Why Customers Love Us</h2>
      </div>
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="glass-card text-center reveal reveal-delay-1" style="padding: 36px 24px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🚚</div>
            <h4 style="font-family: var(--font-display); font-weight: 700; font-size: 1.05rem; margin-bottom: 8px;">Free Delivery</h4>
            <p style="font-size: 0.85rem; color: var(--text-secondary);">Free shipping on orders above ₹2,999 across India</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="glass-card text-center reveal reveal-delay-2" style="padding: 36px 24px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">✅</div>
            <h4 style="font-family: var(--font-display); font-weight: 700; font-size: 1.05rem; margin-bottom: 8px;">100% Genuine</h4>
            <p style="font-size: 0.85rem; color: var(--text-secondary);">All products are verified genuine with brand warranty</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="glass-card text-center reveal reveal-delay-3" style="padding: 36px 24px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🔄</div>
            <h4 style="font-family: var(--font-display); font-weight: 700; font-size: 1.05rem; margin-bottom: 8px;">Easy Returns</h4>
            <p style="font-size: 0.85rem; color: var(--text-secondary);">7-day hassle-free return and replacement policy</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="glass-card text-center reveal reveal-delay-4" style="padding: 36px 24px;">
            <div style="font-size: 2.5rem; margin-bottom: 16px;">🔒</div>
            <h4 style="font-family: var(--font-display); font-weight: 700; font-size: 1.05rem; margin-bottom: 8px;">Secure Payment</h4>
            <p style="font-size: 0.85rem; color: var(--text-secondary);">100% secure checkout with multiple payment options</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
       TESTIMONIALS
       ══════════════════════════════════════════ -->
  <section class="section-padding">
    <div class="container">
      <div class="text-center mb-5">
        <div class="section-eyebrow justify-content-center reveal">Reviews</div>
        <h2 class="section-title reveal">What Our Customers Say</h2>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="testimonial-card reveal reveal-delay-1">
            <div class="stars mb-3">
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
            </div>
            <p class="testimonial-quote">"Amazing experience! Got my iPhone 16 Pro delivered the next day. The packaging was premium and the price was unbeatable. Will definitely shop again!"</p>
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
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
            </div>
            <p class="testimonial-quote">"Got my Samsung screen repaired here — fast, affordable, and they used genuine parts. My phone looks brand new! Excellent service team."</p>
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
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
              <i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>
              <i class="bi bi-star-half" style="color: var(--accent-orange);"></i>
            </div>
            <p class="testimonial-quote">"Best mobile store website I've used! Clean UI, easy navigation, and the customer support is top-notch. Bought OnePlus 13 at the best price."</p>
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
