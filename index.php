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

// Get brands for exploration bar
$allBrands = $conn->query("SELECT * FROM brands ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
?>

  <!-- ── TOP CATEGORY BAR (Flipkart Style) ── -->
  <div class="top-cat-bar no-print">
    <div class="top-cat-list">
      <?php 
      $topCats = [
        ['name' => 'Mobiles', 'slug' => 'smartphones', 'icon' => '📱'],
        ['name' => 'Tablets', 'slug' => 'tablets', 'icon' => '📟'],
        ['name' => 'Audio', 'slug' => 'earbuds-audio', 'icon' => '🎧'],
        ['name' => 'Watches', 'slug' => 'smartwatches', 'icon' => '⌚'],
        ['name' => 'Accessories', 'slug' => 'accessories', 'icon' => '🔌'],
        ['name' => 'Power Banks', 'slug' => 'power-banks', 'icon' => '🔋'],
        ['name' => 'Services', 'slug' => 'services', 'icon' => '🛠️']
      ];
      foreach ($topCats as $tc): 
      ?>
      <a href="<?php echo $tc['slug'] === 'services' ? SITE_URL . '/services.php' : SITE_URL . '/shop.php?category=' . $tc['slug']; ?>" class="top-cat-item">
        <div class="top-cat-icon"><?php echo $tc['icon']; ?></div>
        <div class="top-cat-label"><?php echo $tc['name']; ?></div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- ── HERO CAROUSEL ── -->
  <section class="section-padding pb-0">
    <div class="container-custom">
      <div id="heroCarousel" class="carousel slide main-carousel" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
          <!-- Slide 1: Mobile Launch -->
          <div class="carousel-item active" style="background-image: url('<?php echo SITE_URL; ?>/assets/images/banners/hero_mobile_launch.png');">
            <div class="carousel-caption-custom">
              <div class="hero-badge" style="background: var(--accent); border-color: transparent;">Flash Sale</div>
              <h1 class="carousel-title">iPhone 16 Pro<br><span style="color: var(--accent-light);">The Ultimate Power</span></h1>
              <p class="carousel-subtitle">Get up to ₹10,000 instant discount with HDFC cards.</p>
              <a href="<?php echo SITE_URL; ?>/product.php?slug=iphone-16-pro-max" class="btn-primary-solid px-5 py-3">
                Pre-order Now <i class="bi bi-arrow-right ms-2"></i>
              </a>
            </div>
          </div>
          <!-- Slide 2: Accessories -->
          <div class="carousel-item" style="background-image: url('<?php echo SITE_URL; ?>/assets/images/banners/hero_accessories.png');">
            <div class="carousel-caption-custom">
              <div class="hero-badge">Limited Offer</div>
              <h1 class="carousel-title">Premium Gear<br>for your Tech</h1>
              <p class="carousel-subtitle">Flat 20% OFF on all original accessories this week.</p>
              <a href="<?php echo SITE_URL; ?>/shop.php?category=accessories" class="btn-primary-solid px-5 py-3">
                Explore Deals <i class="bi bi-bag-plus ms-2"></i>
              </a>
            </div>
          </div>
          <!-- Slide 3: Service -->
          <div class="carousel-item" style="background-color: #0f172a; border-left: 10px solid var(--primary);">
            <div class="carousel-caption-custom">
              <div class="hero-badge" style="background: var(--success); border-color: transparent;">Expert Care</div>
              <h1 class="carousel-title">Broken Screen?<br>We've got you.</h1>
              <p class="carousel-subtitle">Same-day repair service with 100% genuine brand parts.</p>
              <a href="<?php echo SITE_URL; ?>/services.php" class="btn-primary-solid px-5 py-3" style="background: var(--success);">
                Book Repair <i class="bi bi-tools ms-2"></i>
              </a>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
      </div>

      <!-- Quick Info Bar (Flipkart Style) -->
      <div class="deal-banners-grid no-print">
        <div class="deal-banner-item" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: #fff;">
          <div class="deal-banner-content">
            <span class="deal-banner-tag">Trending</span>
            <h3 class="deal-banner-title">Smart Savings</h3>
            <p>On Tablets & iPads</p>
            <a href="#" class="text-white fw-bold small text-decoration-underline">Shop Now</a>
          </div>
          <div style="position: absolute; right: -20px; bottom: -20px; font-size: 8rem; opacity: 0.15;">📟</div>
        </div>
        <div class="deal-banner-item" style="background: linear-gradient(135deg, #7c2d12 0%, #ea580c 100%); color: #fff;">
          <div class="deal-banner-content">
            <span class="deal-banner-tag" style="background: #fff; color: #ea580c;">New</span>
            <h3 class="deal-banner-title">Best of Audio</h3>
            <p>Buds, Speakers & more</p>
            <a href="#" class="text-white fw-bold small text-decoration-underline">Grab Deals</a>
          </div>
          <div style="position: absolute; right: -20px; bottom: -20px; font-size: 8rem; opacity: 0.15;">🎧</div>
        </div>
        <div class="deal-banner-item" style="background: linear-gradient(135deg, #065f46 0%, #10b981 100%); color: #fff;">
          <div class="deal-banner-content">
            <span class="deal-banner-tag" style="background: #fff; color: #10b981;">Exclusive</span>
            <h3 class="deal-banner-title">Refurbished</h3>
            <p>Quality at half price</p>
            <a href="#" class="text-white fw-bold small text-decoration-underline">Explore More</a>
          </div>
          <div style="position: absolute; right: -10px; bottom: -10px; font-size: 8rem; opacity: 0.15;">♻️</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── BRAND EXPLORATION BAR (Flipkart Style) ── -->
  <div class="container-custom no-print reveal">
    <div class="brand-nav-section">
      <div class="brand-nav-scroll">
        <?php 
        $brandIcons = [
          'apple' => '🍎',
          'samsung' => '📱',
          'google' => '💎',
          'oneplus' => '⚡',
          'xiaomi' => '🍊',
          'vivo' => '🔹',
          'realme' => '⭐',
          'nothing' => '🔳',
          'oppo' => '🟢',
          'motorola' => 'Ⓜ️'
        ];
        foreach ($allBrands as $brand): 
          $slug = strtolower($brand['slug']);
          $icon = $brandIcons[$slug] ?? '🔥';
        ?>
        <a href="<?php echo SITE_URL; ?>/shop.php?brand=<?php echo $brand['id']; ?>" class="brand-explore-card">
          <div class="brand-explore-icon"><?php echo $icon; ?></div>
          <div class="brand-explore-name"><?php echo htmlspecialchars($brand['name']); ?></div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- ── SPECIAL OFFERS SECTION (Flipkart style) ── -->
  <section class="offers-section no-print reveal">
    <div class="container-custom">
      <div class="section-eyebrow">🚀 Super Value Week</div>
      <div class="offers-scroll">
        <!-- Card 1 -->
        <div class="offer-card offer-card-1">
          <div class="offer-badge">Best Seller</div>
          <div class="offer-content">
            <h3 class="offer-title">Realme P4 Power</h3>
            <div class="offer-price">From <strong>₹25,999*</strong></div>
            <p class="mt-2 small">India's Biggest 6000mAh Battery</p>
          </div>
          <img src="https://placehold.co/400x400/7c3aed/ffffff?text=Realme+P4" alt="Realme P4" class="offer-img-floating">
        </div>
        <!-- Card 2 -->
        <div class="offer-card offer-card-2">
          <div class="offer-badge">Hot Deal</div>
          <div class="offer-content">
            <h3 class="offer-title">Note 50s 5G+</h3>
            <div class="offer-price">Just <strong>₹17,250</strong></div>
            <p class="mt-2 small">Slimmest 144Hz Curved Displays</p>
          </div>
          <img src="https://placehold.co/400x400/db2777/ffffff?text=Note+50s" alt="Note 50s" class="offer-img-floating">
        </div>
        <!-- Card 3 -->
        <div class="offer-card offer-card-3">
          <div class="offer-badge">Luxury</div>
          <div class="offer-content">
            <h3 class="offer-title">POVA Curve 2</h3>
            <div class="offer-price">From <strong>₹26,999*</strong></div>
            <p class="mt-2 small">World's Slimmest 8000mAh Curve Dis.</p>
          </div>
          <img src="https://placehold.co/400x400/1e3a8a/ffffff?text=POVA+Curve" alt="POVA Curve" class="offer-img-floating">
        </div>
      </div>
    </div>
  </section>

  <!-- ── FEATURED BRAND BANNER (Flipkart style) ── -->
  <section class="featured-brand-section no-print reveal">
    <div class="container-custom">
      <div class="brand-launch-banner">
        <a href="<?php echo SITE_URL; ?>/shop.php?brand=10" class="brand-launch-link"></a>
        <img src="<?php echo SITE_URL; ?>/assets/images/banners/brand_banner_motorola.png" alt="Motorola Edge 70 Pro Launch">
        <div class="brand-launch-overlay">
          <!-- Text is already baked into the image, but we could add dynamic elements here if needed -->
        </div>
      </div>
    </div>
  </section>

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
