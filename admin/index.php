<?php
/**
 * MobileHub — Admin Dashboard (Grand Modern UI)
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
    <!-- Premium Sidebar -->
    <aside class="admin-sidebar">
      <div class="px-3 mb-4 d-flex align-items-center gap-2">
        <div style="width: 32px; height: 32px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 900;">M</div>
        <h5 style="font-size: 0.9rem; margin: 0; color: #fff; font-weight: 700; letter-spacing: 0.05em;">ADMIN PANEL</h5>
      </div>
      
      <a href="<?php echo SITE_URL; ?>/admin/" class="admin-sidebar-link active">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Overview</span>
      </a>
      <a href="<?php echo SITE_URL; ?>/admin/products.php" class="admin-sidebar-link">
        <i class="bi bi-phone-fill"></i>
        <span>Inventory</span>
      </a>
      <a href="<?php echo SITE_URL; ?>/admin/orders.php" class="admin-sidebar-link">
        <i class="bi bi-cart-fill"></i>
        <span>Sales</span>
      </a>
      <a href="<?php echo SITE_URL; ?>/admin/services.php" class="admin-sidebar-link">
        <i class="bi bi-wrench-adjustable"></i>
        <span>Services</span>
      </a>
      <a href="<?php echo SITE_URL; ?>/admin/users.php" class="admin-sidebar-link">
        <i class="bi bi-people-fill"></i>
        <span>Customers</span>
      </a>
      
      <div class="mt-auto pt-4 border-top border-secondary">
        <a href="<?php echo SITE_URL; ?>/login.php?action=logout" class="admin-sidebar-link" style="color: #f87171;">
          <i class="bi bi-box-arrow-right"></i>
          <span>Logout Session</span>
        </a>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="admin-main-content">
      <!-- Grand Hero Header -->
      <div class="mb-5 admin-reveal">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
          <div>
            <div class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 mb-3 rounded-pill" style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.05em;">STORE COMMAND CENTER</div>
            <h1 style="font-family: var(--font-display); font-weight: 900; font-size: 2.8rem; margin: 0; letter-spacing: -0.01em;">Good Day, <span style="background: linear-gradient(to right, #6366f1, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Admin.</span></h1>
            <p class="text-secondary mt-2 mb-0" style="font-size: 1.05rem;">Your store metrics are looking solid today.</p>
          </div>
          <div class="text-md-end">
            <div class="admin-card-glass px-4 py-3 mb-0 d-inline-flex align-items-center gap-3">
              <div class="text-start">
                <div style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">System Time</div>
                <div style="font-weight: 700; font-size: 1rem; color: var(--text-dark);"><?php echo date('h:i A'); ?></div>
              </div>
              <div style="width: 1px; height: 30px; background: var(--border);"></div>
              <div class="text-start">
                <div style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Status</div>
                <div class="d-flex align-items-center gap-2">
                  <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px; box-shadow: 0 0 8px var(--success);"></span>
                  <span style="font-weight: 700; font-size: 1rem; color: var(--success);">Operational</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3">
          <div class="admin-stat-card-grand admin-reveal">
            <div class="stat-icon-grand grad-primary">
              <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <div class="stat-value-grand"><?php echo $totalOrders; ?></div>
            <div class="stat-label-grand">Total Orders</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="admin-stat-card-grand admin-reveal">
            <div class="stat-icon-grand grad-success">
              <i class="bi bi-wallet2"></i>
            </div>
            <div class="stat-value-grand">₹<?php echo number_format($totalRevenue); ?></div>
            <div class="stat-label-grand">Total Revenue</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="admin-stat-card-grand admin-reveal">
            <div class="stat-icon-grand grad-info">
              <i class="bi bi-layers-fill"></i>
            </div>
            <div class="stat-value-grand"><?php echo $totalProducts; ?></div>
            <div class="stat-label-grand">Unique Items</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="admin-stat-card-grand admin-reveal">
            <div class="stat-icon-grand grad-warning">
              <i class="bi bi-stars"></i>
            </div>
            <div class="stat-value-grand"><?php echo $totalUsers; ?></div>
            <div class="stat-label-grand">Active Users</div>
          </div>
        </div>
      </div>

      <!-- Main Visuals Section -->
      <div class="row g-4 mb-5">
        <div class="col-lg-8">
          <div class="admin-card-glass p-4 admin-reveal h-100">
            <div class="d-flex justify-content-between align-items-center mb-5">
              <h5 style="font-weight: 800; margin: 0; display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-bar-chart-fill text-primary"></i> Revenue Analytics
              </h5>
              <div class="dropdown">
                <button class="btn btn-sm btn-light border-0 rounded-pill px-3" type="button" style="font-size: 0.75rem; font-weight: 600;">Last 10 Months</button>
              </div>
            </div>
            <div style="height: 320px; position: relative;">
              <canvas id="revenueChart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="admin-card-glass p-4 admin-reveal h-100">
            <h5 class="mb-5" style="font-weight: 800; display: flex; align-items: center; gap: 10px;">
              <i class="bi bi-inboxes-fill text-warning"></i> Operational Flow
            </h5>
            <div class="d-flex flex-column gap-4 mt-2">
              <div class="admin-stat-card-grand" style="padding: 20px; border-radius: var(--radius-lg); height: auto;">
                 <div class="d-flex align-items-center gap-3">
                   <div style="font-size: 2.2rem;">⏳</div>
                   <div>
                     <div class="stat-value-grand" style="font-size: 1.8rem; margin: 0; color: var(--warning);"><?php echo $pendingOrders; ?></div>
                     <div class="stat-label-grand" style="font-size: 0.75rem;">Orders Pending</div>
                   </div>
                 </div>
              </div>
              <div class="admin-stat-card-grand" style="padding: 20px; border-radius: var(--radius-lg); height: auto;">
                 <div class="d-flex align-items-center gap-3">
                   <div style="font-size: 2.2rem;">🔧</div>
                   <div>
                     <div class="stat-value-grand" style="font-size: 1.8rem; margin: 0; color: var(--primary);"><?php echo $totalServices; ?></div>
                     <div class="stat-label-grand" style="font-size: 0.75rem;">Service Requests</div>
                   </div>
                 </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity Table -->
      <div class="admin-card-glass admin-reveal">
        <div class="table-header-grand">
          <h3 style="font-family: var(--font-display); font-size: 1.2rem; font-weight: 800; margin: 0;">Latest Transactions</h3>
          <a href="<?php echo SITE_URL; ?>/admin/orders.php" class="btn btn-sm btn-primary rounded-pill px-4" style="font-size: 0.75rem; font-weight: 700;">Audit All Sales</a>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.92rem;">
            <thead class="bg-light-subtle">
              <tr>
                <th class="ps-4 py-3" style="color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 0.7rem;">Code</th>
                <th class="py-3" style="color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 0.7rem;">Customer Entity</th>
                <th class="py-3" style="color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 0.7rem;">Transaction</th>
                <th class="py-3" style="color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 0.7rem;">Status</th>
                <th class="pe-4 py-3" style="color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 0.7rem;">Timeline</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentOrders as $order): ?>
              <tr>
                <td class="ps-4">
                  <span class="user-select-none opacity-50">#</span><span class="fw-bold text-dark"><?php echo htmlspecialchars($order['order_number']); ?></span>
                </td>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <div style="width: 34px; height: 34px; background: var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; color: var(--text-secondary);">
                      <?php echo strtoupper(substr($order['user_name'], 0, 1)); ?>
                    </div>
                    <div>
                      <div class="fw-bold text-dark"><?php echo htmlspecialchars($order['user_name']); ?></div>
                      <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($order['user_email']); ?></div>
                    </div>
                  </div>
                </td>
                <td><span class="fw-bold text-dark">₹<?php echo number_format($order['total']); ?></span></td>
                <td>
                  <div class="d-inline-flex">
                    <?php echo statusBadge($order['status']); ?>
                  </div>
                </td>
                <td class="pe-4 text-muted" style="font-size: 0.85rem;"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
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
      // Cinematic Intro
      const tl = gsap.timeline();

      tl.from(".admin-sidebar", {
        x: -300,
        opacity: 0,
        duration: 1.2,
        ease: "power4.out"
      });

      tl.from(".admin-reveal", {
        y: 60,
        opacity: 0,
        duration: 1,
        stagger: 0.2,
        ease: "power3.out",
        clearProps: "all"
      }, "-=0.8");
    });
  </script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
