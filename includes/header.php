<?php
/**
 * MobileHub — Shared Header Component
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

$cartCount = getCartCount();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="MobileHub — Your premium destination for smartphones, accessories, and expert device services. Shop the latest phones from Apple, Samsung, OnePlus, and more.">
  <meta name="keywords" content="mobile shop, smartphones, iPhone, Samsung, OnePlus, phone repair, accessories">
  <title><?php echo isset($pageTitle) ? $pageTitle . ' — ' . SITE_NAME : SITE_NAME . ' — Premium Mobile Store'; ?></title>
  <link rel="shortcut icon" href="<?php echo SITE_URL; ?>/assets/images/favicon.png" type="image/png">
  
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

  <!-- Ambient Orbs -->
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>

  <!-- Cursor Glow -->
  <div class="cursor-glow" id="cursorGlow"></div>

  <!-- Toast -->
  <div class="toast-notification" id="toast"></div>

  <!-- ── NAVIGATION ── -->
  <nav class="mh-navbar" id="navbar">
    <div class="container-fluid px-3 px-lg-5">
      <div class="d-flex align-items-center justify-content-between w-100">
        <!-- Brand -->
        <a class="navbar-brand" href="<?php echo SITE_URL; ?>/">
          Mobile<span>Hub</span>
        </a>

        <!-- Desktop Nav -->
        <ul class="nav d-none d-lg-flex align-items-center gap-1" id="navLinks">
          <li><a href="<?php echo SITE_URL; ?>/" class="nav-link <?php echo $currentPage === 'index' ? 'active' : ''; ?>">Home</a></li>
          <li><a href="<?php echo SITE_URL; ?>/shop.php" class="nav-link <?php echo $currentPage === 'shop' ? 'active' : ''; ?>">Shop</a></li>
          <li><a href="<?php echo SITE_URL; ?>/services.php" class="nav-link <?php echo $currentPage === 'services' ? 'active' : ''; ?>">Services</a></li>
          <li>
            <a href="<?php echo SITE_URL; ?>/cart.php" class="nav-link nav-cart-badge <?php echo $currentPage === 'cart' ? 'active' : ''; ?>">
              <i class="bi bi-bag"></i> Cart
              <?php if ($cartCount > 0): ?>
                <span class="cart-count" id="cartCountBadge"><?php echo $cartCount; ?></span>
              <?php endif; ?>
            </a>
          </li>
        </ul>

        <!-- Right Actions -->
        <div class="d-flex align-items-center gap-3">
          <?php if (isLoggedIn()): ?>
            <div class="dropdown d-none d-lg-block">
              <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius: var(--radius-full); padding: 8px 16px; font-size: 0.82rem;">
                <i class="bi bi-person-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
              </button>
              <ul class="dropdown-menu dropdown-menu-end mt-2">
                <?php if (isAdmin()): ?>
                  <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/admin/"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</a></li>
                  <li><hr class="dropdown-divider"></li>
                <?php endif; ?>
                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/profile.php"><i class="bi bi-person me-2"></i>My Profile</a></li>
                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/profile.php?tab=orders"><i class="bi bi-box-seam me-2"></i>My Orders</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/login.php?action=logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
              </ul>
            </div>
          <?php else: ?>
            <a href="<?php echo SITE_URL; ?>/login.php" class="nav-cta-btn d-none d-lg-inline-flex">
              <i class="bi bi-person-plus me-1"></i> Login
            </a>
          <?php endif; ?>

          <!-- Mobile hamburger -->
          <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-label="Toggle menu">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Mobile Offcanvas Menu -->
  <div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title">
        <span style="font-family: var(--font-display); font-weight: 800; background: var(--grad-1); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">MobileHub</span>
      </h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="nav flex-column gap-2">
        <li><a href="<?php echo SITE_URL; ?>/" class="nav-link <?php echo $currentPage === 'index' ? 'active' : ''; ?>"><i class="bi bi-house me-2"></i>Home</a></li>
        <li><a href="<?php echo SITE_URL; ?>/shop.php" class="nav-link <?php echo $currentPage === 'shop' ? 'active' : ''; ?>"><i class="bi bi-shop me-2"></i>Shop</a></li>
        <li><a href="<?php echo SITE_URL; ?>/services.php" class="nav-link <?php echo $currentPage === 'services' ? 'active' : ''; ?>"><i class="bi bi-tools me-2"></i>Services</a></li>
        <li>
          <a href="<?php echo SITE_URL; ?>/cart.php" class="nav-link <?php echo $currentPage === 'cart' ? 'active' : ''; ?>">
            <i class="bi bi-bag me-2"></i>Cart
            <?php if ($cartCount > 0): ?>
              <span class="badge bg-primary ms-2"><?php echo $cartCount; ?></span>
            <?php endif; ?>
          </a>
        </li>
        <li><hr style="border-color: var(--border);"></li>
        <?php if (isLoggedIn()): ?>
          <li><a href="<?php echo SITE_URL; ?>/profile.php" class="nav-link"><i class="bi bi-person me-2"></i>Profile</a></li>
          <li><a href="<?php echo SITE_URL; ?>/profile.php?tab=orders" class="nav-link"><i class="bi bi-box-seam me-2"></i>My Orders</a></li>
          <?php if (isAdmin()): ?>
            <li><a href="<?php echo SITE_URL; ?>/admin/" class="nav-link"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</a></li>
          <?php endif; ?>
          <li><a href="<?php echo SITE_URL; ?>/login.php?action=logout" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
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
      <?php echo $flash['message']; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
  <?php endif; ?>
