<?php
/**
 * MobileHub — Admin Products Management
 */
$pageTitle = 'Manage Products';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$categories = getCategories();
$brands = getBrands();

// Handle product actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $name = sanitize($_POST['name']);
        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', strtolower($name)));
        $categoryId = intval($_POST['category_id']);
        $brandId = intval($_POST['brand_id']);
        $description = sanitize($_POST['description']);
        $specifications = $_POST['specifications'] ?? '';
        $price = floatval($_POST['price']);
        $salePrice = $_POST['sale_price'] ? floatval($_POST['sale_price']) : null;
        $stock = intval($_POST['stock']);
        $featured = isset($_POST['featured']) ? 1 : 0;
        
        // Handle image uploads
        $image1 = null; $image2 = null; $image3 = null;
        if (!empty($_FILES['image1']['name'])) {
            $result = uploadImage($_FILES['image1']);
            if ($result['success']) $image1 = $result['filename'];
        }
        if (!empty($_FILES['image2']['name'])) {
            $result = uploadImage($_FILES['image2']);
            if ($result['success']) $image2 = $result['filename'];
        }
        if (!empty($_FILES['image3']['name'])) {
            $result = uploadImage($_FILES['image3']);
            if ($result['success']) $image3 = $result['filename'];
        }
        
        if ($action === 'add') {
            $stmt = $conn->prepare("INSERT INTO products (category_id, brand_id, name, slug, description, specifications, price, sale_price, stock, image1, image2, image3, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissssddisssi", $categoryId, $brandId, $name, $slug, $description, $specifications, $price, $salePrice, $stock, $image1, $image2, $image3, $featured);
            $stmt->execute();
            $stmt->close();
            setFlash('success', 'Product added successfully!');
        } else {
            $productId = intval($_POST['product_id']);
            // Build update query dynamically for images
            $updateFields = "category_id=?, brand_id=?, name=?, slug=?, description=?, specifications=?, price=?, sale_price=?, stock=?, featured=?";
            $paramTypes = "iissssddii";
            $paramValues = [$categoryId, $brandId, $name, $slug, $description, $specifications, $price, $salePrice, $stock, $featured];
            
            if ($image1) { $updateFields .= ", image1=?"; $paramTypes .= "s"; $paramValues[] = $image1; }
            if ($image2) { $updateFields .= ", image2=?"; $paramTypes .= "s"; $paramValues[] = $image2; }
            if ($image3) { $updateFields .= ", image3=?"; $paramTypes .= "s"; $paramValues[] = $image3; }
            
            $updateFields .= " WHERE id=?";
            $paramTypes .= "i";
            $paramValues[] = $productId;
            
            $stmt = $conn->prepare("UPDATE products SET $updateFields");
            $stmt->bind_param($paramTypes, ...$paramValues);
            $stmt->execute();
            $stmt->close();
            setFlash('success', 'Product updated successfully!');
        }
        header('Location: ' . SITE_URL . '/admin/products.php');
        exit;
    }
    
    if ($action === 'delete') {
        $productId = intval($_POST['product_id']);
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->close();
        setFlash('success', 'Product deleted successfully!');
        header('Location: ' . SITE_URL . '/admin/products.php');
        exit;
    }
}

// Get all products
$products = $conn->query("SELECT p.*, b.name as brand_name, c.name as category_name FROM products p JOIN brands b ON p.brand_id = b.id JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC")->fetch_all(MYSQLI_ASSOC);

// Edit mode
$editProduct = null;
if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editProduct = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

require_once __DIR__ . '/../includes/header.php';
?>

  <div class="container-custom">
    <div class="page-header">
      <h1 class="page-header-title"><i class="bi bi-box me-2"></i>Manage Products</h1>
    </div>
  </div>

  <div class="container-custom" style="padding-bottom: 80px;">
    <!-- Admin Nav -->
    <div class="d-flex gap-2 flex-wrap mb-4">
      <a href="<?php echo SITE_URL; ?>/admin/" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
      <a href="<?php echo SITE_URL; ?>/admin/products.php" class="btn-gradient btn-gradient-sm"><i class="bi bi-box me-1"></i>Products</a>
      <a href="<?php echo SITE_URL; ?>/admin/orders.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-bag-check me-1"></i>Orders</a>
      <a href="<?php echo SITE_URL; ?>/admin/services.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-tools me-1"></i>Services</a>
      <a href="<?php echo SITE_URL; ?>/admin/users.php" class="btn-outline-glow" style="font-size: 0.82rem; padding: 10px 20px;"><i class="bi bi-people me-1"></i>Users</a>
    </div>

    <!-- Add/Edit Product Form -->
    <div class="form-glass mb-5 reveal">
      <h3 style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">
        <?php echo $editProduct ? '<i class="bi bi-pencil me-2"></i>Edit Product' : '<i class="bi bi-plus-circle me-2"></i>Add Product'; ?>
      </h3>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $editProduct ? 'edit' : 'add'; ?>">
        <?php if ($editProduct): ?><input type="hidden" name="product_id" value="<?php echo $editProduct['id']; ?>"><?php endif; ?>
        
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Product Name *</label>
            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($editProduct['name'] ?? ''); ?>" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Category *</label>
            <select class="form-select" name="category_id" required>
              <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo ($editProduct && $editProduct['category_id'] == $cat['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Brand *</label>
            <select class="form-select" name="brand_id" required>
              <?php foreach ($brands as $brand): ?>
                <option value="<?php echo $brand['id']; ?>" <?php echo ($editProduct && $editProduct['brand_id'] == $brand['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($brand['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($editProduct['description'] ?? ''); ?></textarea>
          </div>
          <div class="col-12">
            <label class="form-label">Specifications (JSON)</label>
            <textarea class="form-control" name="specifications" rows="3" placeholder='{"display":"6.7 AMOLED","ram":"12GB"}'><?php echo htmlspecialchars($editProduct['specifications'] ?? ''); ?></textarea>
          </div>
          <div class="col-md-3">
            <label class="form-label">Price (₹) *</label>
            <input type="number" class="form-control" name="price" step="0.01" value="<?php echo $editProduct['price'] ?? ''; ?>" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Sale Price (₹)</label>
            <input type="number" class="form-control" name="sale_price" step="0.01" value="<?php echo $editProduct['sale_price'] ?? ''; ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">Stock *</label>
            <input type="number" class="form-control" name="stock" value="<?php echo $editProduct['stock'] ?? 0; ?>" required>
          </div>
          <div class="col-md-3 d-flex align-items-end">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="featured" id="featured" <?php echo ($editProduct && $editProduct['featured']) ? 'checked' : ''; ?>>
              <label class="form-check-label" for="featured" style="color: var(--text-secondary);">Featured Product</label>
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label">Image 1</label>
            <input type="file" class="form-control" name="image1" accept=".jpg,.jpeg,.png,.webp">
          </div>
          <div class="col-md-4">
            <label class="form-label">Image 2</label>
            <input type="file" class="form-control" name="image2" accept=".jpg,.jpeg,.png,.webp">
          </div>
          <div class="col-md-4">
            <label class="form-label">Image 3</label>
            <input type="file" class="form-control" name="image3" accept=".jpg,.jpeg,.png,.webp">
          </div>
          <div class="col-12">
            <button type="submit" class="btn-gradient">
              <i class="bi bi-<?php echo $editProduct ? 'check-lg' : 'plus-lg'; ?>"></i>
              <?php echo $editProduct ? 'Update Product' : 'Add Product'; ?>
            </button>
            <?php if ($editProduct): ?>
              <a href="<?php echo SITE_URL; ?>/admin/products.php" class="btn-outline-glow ms-2">Cancel</a>
            <?php endif; ?>
          </div>
        </div>
      </form>
    </div>

    <!-- Products Table -->
    <div class="glass-card reveal">
      <h3 style="font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;">
        All Products (<?php echo count($products); ?>)
      </h3>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Category</th>
              <th>Brand</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Featured</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo $product['image1'] ?? 'placeholder.png'; ?>" 
                       alt="" style="width: 40px; height: 40px; object-fit: contain; border-radius: 8px; background: var(--bg-surface); padding: 4px;"
                       onerror="this.src='https://placehold.co/40x40/0a0f1e/7c3aed?text=P'">
                  <strong style="color: var(--text-primary);"><?php echo htmlspecialchars($product['name']); ?></strong>
                </div>
              </td>
              <td><?php echo htmlspecialchars($product['category_name']); ?></td>
              <td><?php echo htmlspecialchars($product['brand_name']); ?></td>
              <td>
                <?php if ($product['sale_price']): ?>
                  <span style="color: var(--accent-green);"><?php echo formatPrice($product['sale_price']); ?></span>
                  <br><small style="text-decoration: line-through; color: var(--text-muted);"><?php echo formatPrice($product['price']); ?></small>
                <?php else: ?>
                  <?php echo formatPrice($product['price']); ?>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge <?php echo $product['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                  <?php echo $product['stock']; ?>
                </span>
              </td>
              <td><?php echo $product['featured'] ? '<i class="bi bi-star-fill" style="color: var(--accent-orange);"></i>' : '—'; ?></td>
              <td>
                <div class="d-flex gap-2">
                  <a href="?edit=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-light"><i class="bi bi-pencil"></i></a>
                  <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-outline-light" style="color: var(--accent-red);"><i class="bi bi-trash"></i></button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
