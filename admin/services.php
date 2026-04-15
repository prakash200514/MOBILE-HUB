<?php
/**
 * MobileHub — Admin Services Management
 */
$pageTitle = 'Manage Services';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingId = intval($_POST['booking_id']);
    $newStatus = sanitize($_POST['status']);
    $estimatedCost = $_POST['estimated_cost'] ? floatval($_POST['estimated_cost']) : null;
    
    $stmt = $conn->prepare("UPDATE service_bookings SET status = ?, estimated_cost = ? WHERE id = ?");
    $stmt->bind_param("sdi", $newStatus, $estimatedCost, $bookingId);
    $stmt->execute();
    $stmt->close();
    setFlash('success', 'Service booking updated!');
    header('Location: ' . SITE_URL . '/admin/services.php');
    exit;
}

// Get all bookings
$bookings = $conn->query("SELECT s.*, u.name as user_name FROM service_bookings s LEFT JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC")->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

  <div class="container-custom">
    <div class="page-header">
      <h1 class="page-header-title"><i class="bi bi-tools me-2"></i>Manage Service Bookings</h1>
      <p class="page-header-desc"><?php echo count($bookings); ?> total bookings</p>
    </div>
  </div>

  <div class="container-custom" style="padding-bottom: 80px;">
    <div class="d-flex gap-2 flex-wrap mb-4">
      <a href="<?php echo SITE_URL; ?>/admin/" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
      <a href="<?php echo SITE_URL; ?>/admin/products.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-box me-1"></i>Products</a>
      <a href="<?php echo SITE_URL; ?>/admin/orders.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-bag-check me-1"></i>Orders</a>
      <a href="<?php echo SITE_URL; ?>/admin/services.php" class="btn-gradient btn-gradient-sm"><i class="bi bi-tools me-1"></i>Services</a>
      <a href="<?php echo SITE_URL; ?>/admin/users.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-people me-1"></i>Users</a>
    </div>

    <div class="glass-card reveal">
      <?php if (empty($bookings)): ?>
        <div class="empty-state"><div class="empty-state-icon">🔧</div><h4 class="empty-state-title">No service bookings yet</h4></div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Device</th>
                <th>Service</th>
                <th>Description</th>
                <th>Date</th>
                <th>Cost</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($bookings as $i => $b): ?>
              <tr>
                <td><?php echo $i + 1; ?></td>
                <td>
                  <div><strong><?php echo htmlspecialchars($b['customer_name']); ?></strong></div>
                  <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($b['customer_phone']); ?></div>
                </td>
                <td><?php echo htmlspecialchars($b['device_name']); ?></td>
                <td><span class="badge bg-info"><?php echo htmlspecialchars($b['service_type']); ?></span></td>
                <td style="max-width: 200px; font-size: 0.82rem;"><?php echo htmlspecialchars($b['description'] ?? '—'); ?></td>
                <td style="white-space: nowrap;"><?php echo $b['booking_date'] ? date('M j, Y', strtotime($b['booking_date'])) : 'N/A'; ?></td>
                <td><?php echo $b['estimated_cost'] ? formatPrice($b['estimated_cost']) : 'TBD'; ?></td>
                <td><?php echo statusBadge($b['status']); ?></td>
                <td>
                  <form method="POST" class="d-flex gap-2 align-items-center flex-wrap">
                    <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
                    <input type="number" name="estimated_cost" class="form-control" style="width: 100px; font-size: 0.78rem; padding: 6px;" placeholder="Cost" value="<?php echo $b['estimated_cost'] ?? ''; ?>">
                    <select name="status" class="form-select" style="width: auto; font-size: 0.78rem; padding: 6px 10px;">
                      <?php foreach (['pending','in-progress','completed','cancelled'] as $s): ?>
                        <option value="<?php echo $s; ?>" <?php echo $b['status'] === $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option>
                      <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
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
