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

  <div class="container-custom">
    <div class="page-header">
      <h1 class="page-header-title"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h1>
      <p class="page-header-desc">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
    </div>
  </div>

  <div class="container-custom" style="padding-bottom: 80px;">
    <!-- Admin Nav -->
    <div class="d-flex gap-2 flex-wrap mb-4 reveal">
      <a href="<?php echo SITE_URL; ?>/admin/" class="btn-gradient btn-gradient-sm"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
      <a href="<?php echo SITE_URL; ?>/admin/products.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-box me-1"></i>Products</a>
      <a href="<?php echo SITE_URL; ?>/admin/orders.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-bag-check me-1"></i>Orders</a>
      <a href="<?php echo SITE_URL; ?>/admin/services.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-tools me-1"></i>Services</a>
      <a href="<?php echo SITE_URL; ?>/admin/users.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-people me-1"></i>Users</a>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-5">
      <div class="col-md-6 col-lg-3">
        <div class="admin-stat-card reveal reveal-delay-1">
          <div class="admin-stat-icon" style="background: #f5f3ff; color: #7c3aed;">
            <i class="bi bi-bag-check"></i>
          </div>
          <div>
            <div class="admin-stat-num"><?php echo $totalOrders; ?></div>
            <div class="admin-stat-label">Total Orders</div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="admin-stat-card reveal reveal-delay-2">
          <div class="admin-stat-icon" style="background: #ecfdf5; color: #059669;">
            <i class="bi bi-currency-rupee"></i>
          </div>
          <div>
            <div class="admin-stat-num"><?php echo formatPrice($totalRevenue); ?></div>
            <div class="admin-stat-label">Total Revenue</div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="admin-stat-card reveal reveal-delay-3">
          <div class="admin-stat-icon" style="background: #eff6ff; color: #2563eb;">
            <i class="bi bi-box"></i>
          </div>
          <div>
            <div class="admin-stat-num"><?php echo $totalProducts; ?></div>
            <div class="admin-stat-label">Products</div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="admin-stat-card reveal reveal-delay-4">
          <div class="admin-stat-icon" style="background: #fffbeb; color: #d97706;">
            <i class="bi bi-people"></i>
          </div>
          <div>
            <div class="admin-stat-num"><?php echo $totalUsers; ?></div>
            <div class="admin-stat-label">Customers</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-5">
      <div class="col-md-4">
        <div class="glass-card text-center reveal">
          <div style="font-size: 2rem; margin-bottom: 8px;">⏳</div>
          <div style="font-size: 1.8rem; font-weight: 800; font-family: var(--font-display); color: var(--warning);"><?php echo $pendingOrders; ?></div>
          <div style="font-size: 0.85rem; color: var(--text-secondary);">Pending Orders</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="glass-card text-center reveal">
          <div style="font-size: 2rem; margin-bottom: 8px;">🔧</div>
          <div style="font-size: 1.8rem; font-weight: 800; font-family: var(--font-display); color: var(--primary);"><?php echo $totalServices; ?></div>
          <div style="font-size: 0.85rem; color: var(--text-secondary);">Service Bookings</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="glass-card text-center reveal">
          <div style="font-size: 2rem; margin-bottom: 8px;">📈</div>
          <div style="font-size: 1.8rem; font-weight: 800; font-family: var(--font-display); color: var(--success);"><?php echo formatPrice($totalOrders > 0 ? $totalRevenue / $totalOrders : 0); ?></div>
          <div style="font-size: 0.85rem; color: var(--text-secondary);">Avg Order Value</div>
        </div>
      </div>
    </div>

    <!-- Recent Orders -->
    <div class="glass-card reveal">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 style="font-family: var(--font-display); font-size: 1.2rem; font-weight: 700; margin: 0;">Recent Orders</h3>
        <a href="<?php echo SITE_URL; ?>/admin/orders.php" class="btn-outline-glow" style="font-size: 0.8rem; padding: 8px 16px;">View All</a>
      </div>
      <?php if (empty($recentOrders)): ?>
        <p style="color: var(--text-muted); text-align: center; padding: 30px;">No orders yet</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentOrders as $order): ?>
              <tr>
                <td><strong style="color: var(--primary);"><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                <td>
                  <div><?php echo htmlspecialchars($order['user_name']); ?></div>
                  <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($order['user_email']); ?></div>
                </td>
                <td><strong><?php echo formatPrice($order['total']); ?></strong></td>
                <td><?php echo $order['payment_method']; ?></td>
                <td><?php echo statusBadge($order['status']); ?></td>
                <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
