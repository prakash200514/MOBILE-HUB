<?php
/**
 * MobileHub — Admin Orders Management
 */
$pageTitle = 'Manage Orders';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $orderId = intval($_POST['order_id']);
    $newStatus = sanitize($_POST['status']);
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $orderId);
    $stmt->execute();
    $stmt->close();
    setFlash('success', 'Order status updated!');
    header('Location: ' . SITE_URL . '/admin/orders.php');
    exit;
}

// Get all orders
$orders = $conn->query("
    SELECT o.*, u.name as user_name, u.email as user_email 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC
")->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

  <div class="container-custom">
    <div class="page-header">
      <h1 class="page-header-title"><i class="bi bi-bag-check me-2"></i>Manage Orders</h1>
      <p class="page-header-desc"><?php echo count($orders); ?> total orders</p>
    </div>
  </div>

  <div class="container-custom" style="padding-bottom: 80px;">
    <!-- Admin Nav -->
    <div class="d-flex gap-2 flex-wrap mb-4">
      <a href="<?php echo SITE_URL; ?>/admin/" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
      <a href="<?php echo SITE_URL; ?>/admin/products.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-box me-1"></i>Products</a>
      <a href="<?php echo SITE_URL; ?>/admin/orders.php" class="btn-gradient btn-gradient-sm"><i class="bi bi-bag-check me-1"></i>Orders</a>
      <a href="<?php echo SITE_URL; ?>/admin/services.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-tools me-1"></i>Services</a>
      <a href="<?php echo SITE_URL; ?>/admin/users.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-people me-1"></i>Users</a>
    </div>

    <div class="glass-card reveal">
      <?php if (empty($orders)): ?>
        <div class="empty-state"><div class="empty-state-icon">📦</div><h4 class="empty-state-title">No orders yet</h4></div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Address</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $order): ?>
              <tr>
                <td><strong style="color: var(--primary);"><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                <td>
                  <div><?php echo htmlspecialchars($order['user_name']); ?></div>
                  <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($order['user_email']); ?></div>
                  <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($order['phone']); ?></div>
                </td>
                <td><strong><?php echo formatPrice($order['total']); ?></strong></td>
                <td><?php echo $order['payment_method']; ?></td>
                <td style="max-width: 200px; font-size: 0.82rem;"><?php echo htmlspecialchars($order['address']); ?></td>
                <td><?php echo statusBadge($order['status']); ?></td>
                <td style="white-space: nowrap;"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                <td>
                  <form method="POST" class="d-flex gap-2 align-items-center">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <select name="status" class="form-select" style="width: auto; font-size: 0.78rem; padding: 6px 10px;">
                      <?php foreach (['pending','confirmed','shipped','delivered','cancelled'] as $s): ?>
                        <option value="<?php echo $s; ?>" <?php echo $order['status'] === $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option>
                      <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary" style="white-space: nowrap;">Update</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
