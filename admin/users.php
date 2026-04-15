<?php
/**
 * MobileHub — Admin Users Management
 */
$pageTitle = 'Manage Users';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

// Handle toggle status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_status') {
    $userId = intval($_POST['user_id']);
    $newStatus = intval($_POST['new_status']);
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ? AND role != 'admin'");
    $stmt->bind_param("ii", $newStatus, $userId);
    $stmt->execute();
    $stmt->close();
    setFlash('success', 'User status updated!');
    header('Location: ' . SITE_URL . '/admin/users.php');
    exit;
}

// Get all users
$users = $conn->query("SELECT u.*, (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as order_count FROM users u ORDER BY u.created_at DESC")->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

  <div class="container-custom">
    <div class="page-header">
      <h1 class="page-header-title"><i class="bi bi-people me-2"></i>Manage Users</h1>
      <p class="page-header-desc"><?php echo count($users); ?> registered users</p>
    </div>
  </div>

  <div class="container-custom" style="padding-bottom: 80px;">
    <div class="d-flex gap-2 flex-wrap mb-4">
      <a href="<?php echo SITE_URL; ?>/admin/" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
      <a href="<?php echo SITE_URL; ?>/admin/products.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-box me-1"></i>Products</a>
      <a href="<?php echo SITE_URL; ?>/admin/orders.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-bag-check me-1"></i>Orders</a>
      <a href="<?php echo SITE_URL; ?>/admin/services.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-tools me-1"></i>Services</a>
      <a href="<?php echo SITE_URL; ?>/admin/users.php" class="btn-gradient btn-gradient-sm"><i class="bi bi-people me-1"></i>Users</a>
    </div>

    <div class="glass-card reveal">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Role</th>
              <th>Orders</th>
              <th>Status</th>
              <th>Joined</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $i => $u): ?>
            <tr>
              <td><?php echo $i + 1; ?></td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--grad-1); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: #fff; flex-shrink: 0;">
                    <?php echo strtoupper(substr($u['name'], 0, 1)); ?>
                  </div>
                  <strong><?php echo htmlspecialchars($u['name']); ?></strong>
                </div>
              </td>
              <td><?php echo htmlspecialchars($u['email']); ?></td>
              <td><?php echo htmlspecialchars($u['phone'] ?? '—'); ?></td>
              <td>
                <span class="badge <?php echo $u['role'] === 'admin' ? 'bg-primary' : 'bg-secondary'; ?>">
                  <?php echo ucfirst($u['role']); ?>
                </span>
              </td>
              <td><?php echo $u['order_count']; ?></td>
              <td>
                <span class="badge <?php echo $u['status'] ? 'bg-success' : 'bg-danger'; ?>">
                  <?php echo $u['status'] ? 'Active' : 'Inactive'; ?>
                </span>
              </td>
              <td style="white-space: nowrap;"><?php echo date('M j, Y', strtotime($u['created_at'])); ?></td>
              <td>
                <?php if ($u['role'] !== 'admin'): ?>
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="action" value="toggle_status">
                  <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                  <input type="hidden" name="new_status" value="<?php echo $u['status'] ? 0 : 1; ?>">
                  <button type="submit" class="btn btn-sm btn-outline-light" title="<?php echo $u['status'] ? 'Deactivate' : 'Activate'; ?>">
                    <i class="bi bi-<?php echo $u['status'] ? 'person-dash' : 'person-check'; ?>"></i>
                  </button>
                </form>
                <?php else: ?>
                  <span style="color: var(--text-muted);">—</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
