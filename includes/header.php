<?php
/**
 * MobileHub — Shared Header Component (E-Commerce Design)
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

$cartCount = getCartCount();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="MobileHub — Your premium destination for smartphones, accessories, and expert device services. Shop the latest phones from Apple, Samsung, OnePlus, and more.">
  <meta name="keywords" content="mobile shop, smartphones, iPhone, Samsung, OnePlus, phone repair, accessories">
  <meta name="site-url" content="<?php echo SITE_URL; ?>">
  <title><?php echo isset($pageTitle) ? $pageTitle . ' — ' . SITE_NAME : SITE_NAME . ' — Premium Mobile Store'; ?></title>
  <link rel="shortcut icon" href="<?php echo SITE_URL; ?>/assets/images/favicon.png" type="image/png">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- GSAP -->
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js" defer></script>
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/bootstrap-overrides.css">
</head>
<body>

  <!-- Toast -->
  <div class="toast-notification" id="toast"></div>

  <!-- ── TOP INFO BAR ── -->
  <div class="top-bar d-none d-md-block">
    <div class="container-custom">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-4">
          <span><i class="bi bi-geo-alt me-1"></i> Tirunelveli, Tamil Nadu</span>
          <span><i class="bi bi-telephone me-1"></i> +91 98765 43210</span>
        </div>
        <div class="d-flex align-items-center gap-4">
          <span><i class="bi bi-truck me-1"></i> Free Shipping on ₹2,999+</span>
          <span><i class="bi bi-shield-check me-1"></i> 100% Genuine Products</span>
        </div>
      </div>
    </div>
  </div>

  <!-- ── MAIN NAVIGATION ── -->
  <nav class="mh-navbar" id="navbar">
    <div class="navbar-main">
      <!-- Brand -->
      <a class="navbar-brand" href="<?php echo SITE_URL; ?>/">
        <div class="brand-icon"><i class="bi bi-phone"></i></div>
        Mobile<span>Hub</span>
      </a>

      <!-- Search Bar (Desktop) -->
      <form class="nav-search d-none d-lg-block" action="<?php echo SITE_URL; ?>/shop.php" method="GET">
        <i class="bi bi-search search-icon"></i>
        <input type="text" name="search" placeholder="Search for phones, tablets, accessories..." autocomplete="off">
      </form>

      <!-- Right Actions -->
      <div class="nav-actions">
        <?php if (isLoggedIn()): ?>
          <?php if (isAdmin()): ?>
            <a href="<?php echo SITE_URL; ?>/admin/" class="nav-action-btn d-none d-md-flex <?php echo strpos($currentPage, 'admin') !== false ? 'active' : ''; ?>">
              <i class="bi bi-speedometer2"></i>
              <span>Admin</span>
            </a>
          <?php endif; ?>
          <a href="<?php echo SITE_URL; ?>/profile.php" class="nav-action-btn d-none d-md-flex <?php echo $currentPage === 'profile' ? 'active' : ''; ?>">
            <i class="bi bi-person"></i>
            <span><?php echo htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]); ?></span>
          </a>
        <?php else: ?>
          <a href="<?php echo SITE_URL; ?>/login.php" class="nav-login-btn d-none d-md-flex">
            <i class="bi bi-person"></i> Login
          </a>
        <?php endif; ?>
        
        <a href="<?php echo SITE_URL; ?>/cart.php" class="nav-action-btn <?php echo $currentPage === 'cart' ? 'active' : ''; ?>" id="navCartBtn">
          <i class="bi bi-cart3"></i>
          <span>Cart</span>
          <?php if ($cartCount > 0): ?>
            <span class="cart-count" id="cartCountBadge"><?php echo $cartCount; ?></span>
          <?php endif; ?>
        </a>

        <!-- Mobile hamburger -->
        <button class="nav-action-btn d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-label="Toggle menu">
          <i class="bi bi-list" style="font-size: 1.5rem;"></i>
        </button>
      </div>
  </nav>

  <main>

  <!-- Mobile Offcanvas Menu -->
  <div class="offcanvas offcanvas-end d-md-none" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" style="font-family: var(--font-display); font-weight: 800; color: var(--primary);">MobileHub</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <!-- Mobile Search -->
      <form action="<?php echo SITE_URL; ?>/shop.php" method="GET" class="mb-3">
        <div class="input-group">
          <input type="text" class="form-control" name="search" placeholder="Search products..." style="border-radius: var(--radius-full) 0 0 var(--radius-full);">
          <button class="btn btn-primary" type="submit" style="border-radius: 0 var(--radius-full) var(--radius-full) 0;"><i class="bi bi-search"></i></button>
        </div>
      </form>

      <ul class="nav flex-column gap-1">
        <li><a href="<?php echo SITE_URL; ?>/" class="nav-link <?php echo $currentPage === 'index' ? 'active' : ''; ?>"><i class="bi bi-house me-2"></i>Home</a></li>
        <li><a href="<?php echo SITE_URL; ?>/shop.php" class="nav-link <?php echo $currentPage === 'shop' ? 'active' : ''; ?>"><i class="bi bi-shop me-2"></i>Shop</a></li>
        <li><a href="<?php echo SITE_URL; ?>/services.php" class="nav-link <?php echo $currentPage === 'services' ? 'active' : ''; ?>"><i class="bi bi-tools me-2"></i>Services</a></li>
        <li><a href="<?php echo SITE_URL; ?>/cart.php" class="nav-link <?php echo $currentPage === 'cart' ? 'active' : ''; ?>"><i class="bi bi-cart3 me-2"></i>Cart <?php if($cartCount > 0): ?><span class="badge bg-primary ms-1"><?php echo $cartCount; ?></span><?php endif; ?></a></li>
        <li class="mt-2 mb-2"><hr style="border-color: var(--border);"></li>
        <li class="mb-1" style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding: 4px 16px;">Categories</li>
        <?php foreach ($categories as $cat): ?>
          <li><a href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo $cat['slug']; ?>" class="nav-link" style="font-size: 0.88rem;"><?php echo htmlspecialchars($cat['name']); ?></a></li>
        <?php endforeach; ?>
        <li><hr style="border-color: var(--border);"></li>
        <?php if (isLoggedIn()): ?>
          <li><a href="<?php echo SITE_URL; ?>/profile.php" class="nav-link"><i class="bi bi-person me-2"></i>Profile</a></li>
          <li><a href="<?php echo SITE_URL; ?>/profile.php?tab=orders" class="nav-link"><i class="bi bi-box-seam me-2"></i>My Orders</a></li>
          <?php if (isAdmin()): ?>
            <li><a href="<?php echo SITE_URL; ?>/admin/" class="nav-link"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</a></li>
          <?php endif; ?>
          <li><a href="<?php echo SITE_URL; ?>/login.php?action=logout" class="nav-link" style="color: var(--danger);"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
        <?php else: ?>
          <li><a href="<?php echo SITE_URL; ?>/login.php" class="btn btn-primary w-100 mt-2" style="border-radius: var(--radius-full);">Login / Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>

  <main>
  <?php
  // Flash messages
  $flash = getFlash();
  if ($flash):
  ?>
  <div class="container-custom mt-3">
    <div class="alert alert-<?php echo $flash['type'] === 'success' ? 'success' : ($flash['type'] === 'error' ? 'danger' : $flash['type']); ?> alert-dismissible fade show" role="alert">
      <i class="bi bi-<?php echo $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
      <?php echo $flash['message']; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
  <?php endif; ?>
