<?php
/**
 * MobileHub — Admin Dashboard
 */
$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Get stats
$totalProducts = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'];
$totalOrders = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];
$totalRevenue = $conn->query("SELECT COALESCE(SUM(total), 0) as t FROM orders WHERE status != 'cancelled'")->fetch_assoc()['t'];
$totalUsers = $conn->query("SELECT COUNT(*) as c FROM users WHERE role = 'customer'")->fetch_assoc()['c'];
$totalServices = $conn->query("SELECT COUNT(*) as c FROM service_bookings")->fetch_assoc()['c'];
$pendingOrders = $conn->query("SELECT COUNT(*) as c FROM orders WHERE status = 'pending'")->fetch_assoc()['c'];

// Recent orders
$recentOrders = $conn->query("SELECT o.*, u.name as user_name, u.email as user_email FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);

$currentAdminPage = 'dashboard';
require_once __DIR__ . '/../includes/header.php';
?>

  <div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
      <div class="px-3 mb-4">
        <h5 style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.4); font-weight: 700;">Menu</h5>
      </div>
      <a href="<?php echo SITE_URL; ?>/admin/" class="admin-sidebar-link active">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
      </a>
      <a href="<?php echo SITE_URL; ?>/admin/products.php" class="admin-sidebar-link">
        <i class="bi bi-box"></i>
        <span>Products</span>
      </a>
      <a href="<?php echo SITE_URL; ?>/admin/orders.php" class="admin-sidebar-link">
        <i class="bi bi-bag-check"></i>
        <span>Orders</span>
      </a>
      <a href="<?php echo SITE_URL; ?>/admin/services.php" class="admin-sidebar-link">
        <i class="bi bi-tools"></i>
        <span>Services</span>
      </a>
      <a href="<?php echo SITE_URL; ?>/admin/users.php" class="admin-sidebar-link">
        <i class="bi bi-people"></i>
        <span>Users</span>
      </a>
      
      <div class="mt-auto px-1">
        <a href="<?php echo SITE_URL; ?>/login.php?action=logout" class="admin-sidebar-link" style="color: #f87171;">
          <i class="bi bi-box-arrow-right"></i>
          <span>Logout</span>
        </a>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="admin-main-content">
      <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
          <h1 style="font-family: var(--font-display); font-weight: 900; font-size: 2.2rem; margin-bottom: 5px;">Dashboard</h1>
          <p style="color: var(--text-secondary); margin: 0;">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!</p>
        </div>
        <div class="d-none d-md-block text-end">
          <div style="font-size: 0.8rem; color: var(--text-muted);"><?php echo date('l, F j, Y'); ?></div>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
          <div class="admin-stat-card-new admin-reveal">
            <div class="stat-icon-wrapper" style="background: rgba(124, 58, 237, 0.1); color: #7c3aed;">
              <i class="bi bi-bag-check"></i>
            </div>
            <div class="stat-value"><?php echo $totalOrders; ?></div>
            <div class="stat-label">Total Orders</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="admin-stat-card-new admin-reveal">
            <div class="stat-icon-wrapper" style="background: rgba(5, 150, 105, 0.1); color: #059669;">
              <i class="bi bi-currency-rupee"></i>
            </div>
            <div class="stat-value"><?php echo number_format($totalRevenue); ?></div>
            <div class="stat-label">Total Revenue</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="admin-stat-card-new admin-reveal">
            <div class="stat-icon-wrapper" style="background: rgba(37, 99, 235, 0.1); color: #2563eb;">
              <i class="bi bi-box"></i>
            </div>
            <div class="stat-value"><?php echo $totalProducts; ?></div>
            <div class="stat-label">Products In Stock</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="admin-stat-card-new admin-reveal">
            <div class="stat-icon-wrapper" style="background: rgba(217, 119, 6, 0.1); color: #d97706;">
              <i class="bi bi-people"></i>
            </div>
            <div class="stat-value"><?php echo $totalUsers; ?></div>
            <div class="stat-label">Total Customers</div>
          </div>
        </div>
      </div>

      <!-- Charts Area -->
      <div class="row mb-4">
        <div class="col-lg-8">
          <div class="chart-container-card admin-reveal">
            <h5 class="mb-4" style="font-weight: 700;">Revenue Performance</h5>
            <div style="height: 250px; position: relative;">
              <canvas id="revenueChart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="chart-container-card admin-reveal" style="height: 100%;">
            <h5 class="mb-4" style="font-weight: 700;">Order Distribution</h5>
            <div class="d-flex flex-column gap-4 mt-4">
              <div class="text-center py-4">
                 <div style="font-size: 3rem; margin-bottom: 10px;">⏳</div>
                 <div class="stat-value" style="color: var(--warning);"><?php echo $pendingOrders; ?></div>
                 <div class="stat-label">Orders Pending</div>
              </div>
              <div class="text-center py-4 border-top">
                 <div style="font-size: 3rem; margin-bottom: 10px;">🔧</div>
                 <div class="stat-value" style="color: var(--primary);"><?php echo $totalServices; ?></div>
                 <div class="stat-label">Service Requests</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Orders Table -->
      <div class="admin-table-card admin-reveal">
        <div class="table-header-custom">
          <h3 style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; margin: 0;">Recent Transactions</h3>
          <a href="<?php echo SITE_URL; ?>/admin/orders.php" class="btn btn-sm btn-link text-decoration-none fw-bold" style="font-size: 0.8rem;">View All Orders <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">Order Code</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th class="pe-4">Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentOrders as $order): ?>
              <tr>
                <td class="ps-4"><code><?php echo htmlspecialchars($order['order_number']); ?></code></td>
                <td>
                  <div class="fw-bold"><?php echo htmlspecialchars($order['user_name']); ?></div>
                  <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($order['user_email']); ?></div>
                </td>
                <td><span class="fw-bold">₹<?php echo number_format($order['total']); ?></span></td>
                <td><?php echo statusBadge($order['status']); ?></td>
                <td class="pe-4 text-muted"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="<?php echo SITE_URL; ?>/assets/js/admin_charts.js" defer></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // GSAP Entrance Animations
      gsap.from(".admin-sidebar", {
        x: -260,
        duration: 0.8,
        ease: "power3.out"
      });

      gsap.from(".admin-reveal", {
        y: 30,
        opacity: 0,
        duration: 0.8,
        stagger: 0.1,
        ease: "power3.out",
        delay: 0.2
      });
    });
  </script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
