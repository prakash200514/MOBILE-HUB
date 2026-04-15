<?php
/**
 * MobileHub — User Profile
 */
$pageTitle = 'My Profile';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$user = getCurrentUser();
$activeTab = $_GET['tab'] ?? 'profile';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $name = sanitize($_POST['name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    
    if ($name && $phone) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $phone, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
        $_SESSION['user_name'] = $name;
        setFlash('success', 'Profile updated successfully!');
        header('Location: ' . SITE_URL . '/profile.php');
        exit;
    }
}

// Get user orders
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get service bookings
$stmt = $conn->prepare("SELECT * FROM service_bookings WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

require_once __DIR__ . '/includes/header.php';
?>

  <div class="container-custom">
    <nav class="mh-breadcrumb">
      <ol>
        <li><a href="<?php echo SITE_URL; ?>/">Home</a></li>
        <li class="separator">/</li>
        <li>My Profile</li>
      </ol>
    </nav>

    <div class="page-header">
      <h1 class="page-header-title"><i class="bi bi-person-circle me-2"></i>My Account</h1>
      <p class="page-header-desc">Manage your profile, orders, and services</p>
    </div>
  </div>

  <div class="container-custom" style="padding-bottom: 80px;">
    <div class="row g-4">
      <!-- Sidebar -->
      <div class="col-lg-3">
        <div class="glass-card reveal" style="padding: 24px;">
          <div class="text-center mb-4">
            <div style="width: 72px; height: 72px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 1.8rem; font-weight: 800; color: #fff;">
              <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
            </div>
            <h5 style="font-family: var(--font-display); font-weight: 700;"><?php echo htmlspecialchars($user['name']); ?></h5>
            <p style="font-size: 0.8rem; color: var(--text-muted);"><?php echo htmlspecialchars($user['email']); ?></p>
          </div>
          <nav class="nav flex-column gap-1">
            <a href="?tab=profile" class="nav-link <?php echo $activeTab === 'profile' ? 'active' : ''; ?>" style="border-radius: var(--radius-sm);">
              <i class="bi bi-person me-2"></i>Profile
            </a>
            <a href="?tab=orders" class="nav-link <?php echo $activeTab === 'orders' ? 'active' : ''; ?>" style="border-radius: var(--radius-sm);">
              <i class="bi bi-box-seam me-2"></i>My Orders
              <?php if (count($orders) > 0): ?><span class="badge bg-primary ms-auto"><?php echo count($orders); ?></span><?php endif; ?>
            </a>
            <a href="?tab=services" class="nav-link <?php echo $activeTab === 'services' ? 'active' : ''; ?>" style="border-radius: var(--radius-sm);">
              <i class="bi bi-tools me-2"></i>Service Bookings
            </a>
            <hr style="border-color: var(--border);">
            <a href="<?php echo SITE_URL; ?>/login.php?action=logout" class="nav-link" style="border-radius: var(--radius-sm); color: var(--accent-red);">
              <i class="bi bi-box-arrow-right me-2"></i>Logout
            </a>
          </nav>
        </div>
      </div>

      <!-- Content -->
      <div class="col-lg-9">
        <?php if ($activeTab === 'profile'): ?>
        <!-- Profile Tab -->
        <div class="form-glass reveal">
          <h3 style="font-family: var(--font-display); font-size: 1.2rem; font-weight: 700; margin-bottom: 24px;">Edit Profile</h3>
          <form method="POST">
            <input type="hidden" name="action" value="update_profile">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly style="opacity: 0.6;">
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Member Since</label>
                <input type="text" class="form-control" value="<?php echo date('F j, Y', strtotime($user['created_at'])); ?>" readonly style="opacity: 0.6;">
              </div>
              <div class="col-12">
                <button type="submit" class="btn-gradient"><i class="bi bi-check-lg me-2"></i>Save Changes</button>
              </div>
            </div>
          </form>
        </div>

        <?php elseif ($activeTab === 'orders'): ?>
        <!-- Orders Tab -->
        <div class="glass-card reveal">
          <h3 style="font-family: var(--font-display); font-size: 1.2rem; font-weight: 700; margin-bottom: 24px;">My Orders</h3>
          <?php if (empty($orders)): ?>
            <div class="empty-state" style="padding: 40px;">
              <div class="empty-state-icon">📦</div>
              <h4 class="empty-state-title">No orders yet</h4>
              <p class="empty-state-desc">Start shopping to see your orders here</p>
              <a href="<?php echo SITE_URL; ?>/shop.php" class="btn-gradient btn-gradient-sm">Shop Now</a>
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($orders as $order): ?>
                  <tr>
                    <td><strong style="color: var(--primary);"><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                    <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                    <td><strong><?php echo formatPrice($order['total']); ?></strong></td>
                    <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                    <td><?php echo statusBadge($order['status']); ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <?php elseif ($activeTab === 'services'): ?>
        <!-- Services Tab -->
        <div class="glass-card reveal">
          <h3 style="font-family: var(--font-display); font-size: 1.2rem; font-weight: 700; margin-bottom: 24px;">Service Bookings</h3>
          <?php if (empty($bookings)): ?>
            <div class="empty-state" style="padding: 40px;">
              <div class="empty-state-icon">🔧</div>
              <h4 class="empty-state-title">No bookings yet</h4>
              <p class="empty-state-desc">Book a service to see your history here</p>
              <a href="<?php echo SITE_URL; ?>/services.php" class="btn-gradient btn-gradient-sm">Book a Service</a>
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Device</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Est. Cost</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($bookings as $booking): ?>
                  <tr>
                    <td><strong><?php echo htmlspecialchars($booking['device_name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($booking['service_type']); ?></td>
                    <td><?php echo $booking['booking_date'] ? date('M j, Y', strtotime($booking['booking_date'])) : 'N/A'; ?></td>
                    <td><?php echo $booking['estimated_cost'] ? formatPrice($booking['estimated_cost']) : 'TBD'; ?></td>
                    <td><?php echo statusBadge($booking['status']); ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
