<?php
/**
 * MobileHub — Shop Page
 */
$pageTitle = 'Shop';
require_once __DIR__ . '/includes/header.php';

$categories = getCategories();
$brands = getBrands();

// Filters
$categoryFilter = $_GET['category'] ?? '';
$brandFilter = $_GET['brand'] ?? '';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'newest';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Build query
$where = ["p.status = 1"];
$params = [];
$types = "";

if ($categoryFilter) {
    $where[] = "c.slug = ?";
    $params[] = $categoryFilter;
    $types .= "s";
}

if ($brandFilter) {
    $where[] = "b.slug = ?";
    $params[] = $brandFilter;
    $types .= "s";
}

if ($search) {
    $where[] = "(p.name LIKE ? OR p.description LIKE ? OR b.name LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

$whereClause = implode(' AND ', $where);

$orderBy = match($sort) {
    'price-low' => 'COALESCE(p.sale_price, p.price) ASC',
    'price-high' => 'COALESCE(p.sale_price, p.price) DESC',
    'name' => 'p.name ASC',
    default => 'p.created_at DESC'
};

// Count total
$countQuery = "SELECT COUNT(*) as total FROM products p JOIN brands b ON p.brand_id = b.id JOIN categories c ON p.category_id = c.id WHERE $whereClause";
$stmt = $conn->prepare($countQuery);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$totalProducts = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $perPage);
$stmt->close();

// Get products
$query = "SELECT p.*, b.name as brand_name, c.name as category_name 
          FROM products p 
          JOIN brands b ON p.brand_id = b.id 
          JOIN categories c ON p.category_id = c.id 
          WHERE $whereClause 
          ORDER BY $orderBy 
          LIMIT $perPage OFFSET $offset";

$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Active category name
$activeCategoryName = '';
if ($categoryFilter) {
    foreach ($categories as $c) {
        if ($c['slug'] === $categoryFilter) {
            $activeCategoryName = $c['name'];
            break;
        }
    }
}
?>

  <!-- Page Header -->
  <div class="container-custom">
    <nav class="mh-breadcrumb">
      <ol>
        <li><a href="<?php echo SITE_URL; ?>/">Home</a></li>
        <li class="separator">/</li>
        <li>Shop</li>
        <?php if ($activeCategoryName): ?>
          <li class="separator">/</li>
          <li><?php echo htmlspecialchars($activeCategoryName); ?></li>
        <?php endif; ?>
      </ol>
    </nav>

    <div class="page-header">
      <h1 class="page-header-title">
        <?php echo $activeCategoryName ? htmlspecialchars($activeCategoryName) : ($search ? 'Search: "' . htmlspecialchars($search) . '"' : 'All Products'); ?>
      </h1>
      <p class="page-header-desc"><?php echo $totalProducts; ?> products found</p>
    </div>
  </div>

  <div class="container-custom">
    <div class="row g-4">
      <!-- Sidebar Filters -->
      <div class="col-lg-3">
        <div class="filter-sidebar reveal">
          <h3 class="filter-title"><i class="bi bi-funnel me-2"></i>Filters</h3>

          <!-- Search -->
          <div class="filter-group">
            <div class="filter-group-title">Search</div>
            <form method="GET" action="shop.php" id="searchForm">
              <input type="text" class="form-control" name="search" placeholder="Search products..." 
                     value="<?php echo htmlspecialchars($search); ?>" style="font-size: 0.85rem;">
              <?php if ($categoryFilter): ?><input type="hidden" name="category" value="<?php echo htmlspecialchars($categoryFilter); ?>"><?php endif; ?>
              <?php if ($brandFilter): ?><input type="hidden" name="brand" value="<?php echo htmlspecialchars($brandFilter); ?>"><?php endif; ?>
            </form>
          </div>

          <!-- Categories -->
          <div class="filter-group">
            <div class="filter-group-title">Categories</div>
            <?php foreach ($categories as $cat): ?>
              <a href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo $cat['slug']; ?><?php echo $brandFilter ? '&brand=' . $brandFilter : ''; ?>" 
                 class="filter-option d-block text-decoration-none" style="color: <?php echo $categoryFilter === $cat['slug'] ? 'var(--accent-cyan)' : 'var(--text-secondary)'; ?>;">
                <i class="bi <?php echo $cat['icon']; ?> me-1"></i>
                <?php echo htmlspecialchars($cat['name']); ?>
              </a>
            <?php endforeach; ?>
          </div>

          <!-- Brands -->
          <div class="filter-group">
            <div class="filter-group-title">Brands</div>
            <?php foreach ($brands as $brand): ?>
              <a href="<?php echo SITE_URL; ?>/shop.php?brand=<?php echo $brand['slug']; ?><?php echo $categoryFilter ? '&category=' . $categoryFilter : ''; ?>" 
                 class="filter-option d-block text-decoration-none" style="color: <?php echo $brandFilter === $brand['slug'] ? 'var(--accent-cyan)' : 'var(--text-secondary)'; ?>;">
                <?php echo htmlspecialchars($brand['name']); ?>
              </a>
            <?php endforeach; ?>
          </div>

          <?php if ($categoryFilter || $brandFilter || $search): ?>
            <a href="<?php echo SITE_URL; ?>/shop.php" class="btn-outline-glow w-100 justify-content-center" style="font-size: 0.82rem;">
              <i class="bi bi-x-lg"></i> Clear Filters
            </a>
          <?php endif; ?>
        </div>
      </div>

      <!-- Products Grid -->
      <div class="col-lg-9">
        <!-- Sort bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 reveal">
          <div style="font-size: 0.85rem; color: var(--text-muted);">
            Showing <?php echo min($offset + 1, $totalProducts); ?>–<?php echo min($offset + $perPage, $totalProducts); ?> of <?php echo $totalProducts; ?> products
          </div>
          <div class="d-flex align-items-center gap-2">
            <label style="font-size: 0.8rem; color: var(--text-muted); white-space: nowrap;">Sort by:</label>
            <select class="form-select" style="width: auto; font-size: 0.85rem; padding: 8px 16px;" 
                    onchange="window.location.href=this.value" id="sortSelect">
              <option value="?sort=newest<?php echo $categoryFilter ? '&category='.$categoryFilter : ''; ?><?php echo $brandFilter ? '&brand='.$brandFilter : ''; ?><?php echo $search ? '&search='.$search : ''; ?>" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest</option>
              <option value="?sort=price-low<?php echo $categoryFilter ? '&category='.$categoryFilter : ''; ?><?php echo $brandFilter ? '&brand='.$brandFilter : ''; ?><?php echo $search ? '&search='.$search : ''; ?>" <?php echo $sort === 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
              <option value="?sort=price-high<?php echo $categoryFilter ? '&category='.$categoryFilter : ''; ?><?php echo $brandFilter ? '&brand='.$brandFilter : ''; ?><?php echo $search ? '&search='.$search : ''; ?>" <?php echo $sort === 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
              <option value="?sort=name<?php echo $categoryFilter ? '&category='.$categoryFilter : ''; ?><?php echo $brandFilter ? '&brand='.$brandFilter : ''; ?><?php echo $search ? '&search='.$search : ''; ?>" <?php echo $sort === 'name' ? 'selected' : ''; ?>>Name: A-Z</option>
            </select>
          </div>
        </div>

        <?php if (empty($products)): ?>
          <div class="empty-state">
            <div class="empty-state-icon">📱</div>
            <h3 class="empty-state-title">No Products Found</h3>
            <p class="empty-state-desc">Try adjusting your filters or search terms</p>
            <a href="<?php echo SITE_URL; ?>/shop.php" class="btn-gradient">View All Products</a>
          </div>
        <?php else: ?>
          <div class="row g-4">
            <?php foreach ($products as $idx => $product):
              $effectivePrice = $product['sale_price'] ?? $product['price'];
              $discount = getDiscount($product['price'], $product['sale_price']);
              $rating = getProductRating($product['id']);
            ?>
            <div class="col-6 col-md-4">
              <div class="product-card reveal reveal-delay-<?php echo ($idx % 4) + 1; ?>">
                <div class="product-card-img">
                  <?php if ($discount > 0): ?>
                    <span class="product-card-badge badge-sale"><?php echo $discount; ?>% OFF</span>
                  <?php endif; ?>
                  <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo $product['image1'] ?? 'placeholder.png'; ?>" 
                       alt="<?php echo htmlspecialchars($product['name']); ?>"
                       onerror="this.src='https://placehold.co/400x400/f8fafc/2563eb?text=<?php echo urlencode($product['name']); ?>'">
                </div>
                <div class="product-card-body">
                  <div class="product-card-brand"><?php echo htmlspecialchars($product['brand_name']); ?></div>
                  <a href="<?php echo SITE_URL; ?>/product.php?slug=<?php echo $product['slug']; ?>" class="product-card-name">
                    <?php echo htmlspecialchars($product['name']); ?>
                  </a>
                  <?php echo renderStars($rating['average']); ?>
                  <div class="product-card-price">
                    <span class="price-current"><?php echo formatPrice($effectivePrice); ?></span>
                    <?php if ($discount > 0): ?>
                      <span class="price-old"><?php echo formatPrice($product['price']); ?></span>
                    <?php endif; ?>
                  </div>
                  <button class="btn-cart" onclick="addToCart(<?php echo $product['id']; ?>, this)">
                    <i class="bi bi-bag-plus"></i> Add to Cart
                  </button>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <!-- Pagination -->
          <?php if ($totalPages > 1): ?>
          <div class="mh-pagination">
            <?php if ($page > 1): ?>
              <a href="?page=<?php echo $page - 1; ?><?php echo $categoryFilter ? '&category='.$categoryFilter : ''; ?><?php echo $brandFilter ? '&brand='.$brandFilter : ''; ?><?php echo $sort !== 'newest' ? '&sort='.$sort : ''; ?>">
                <i class="bi bi-chevron-left"></i>
              </a>
            <?php endif; ?>
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
              <a href="?page=<?php echo $p; ?><?php echo $categoryFilter ? '&category='.$categoryFilter : ''; ?><?php echo $brandFilter ? '&brand='.$brandFilter : ''; ?><?php echo $sort !== 'newest' ? '&sort='.$sort : ''; ?>" 
                 class="<?php echo $p === $page ? 'active' : ''; ?>">
                <?php echo $p; ?>
              </a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
              <a href="?page=<?php echo $page + 1; ?><?php echo $categoryFilter ? '&category='.$categoryFilter : ''; ?><?php echo $brandFilter ? '&brand='.$brandFilter : ''; ?><?php echo $sort !== 'newest' ? '&sort='.$sort : ''; ?>">
                <i class="bi bi-chevron-right"></i>
              </a>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div style="height: 80px;"></div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
