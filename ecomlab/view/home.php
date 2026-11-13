<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();

// Determine which products to display based on GET parameters
$pageTitle = 'Featured Products';
$products = [];

if (isset($_GET['cat']) && is_numeric($_GET['cat'])) {
    // Filter by category
    $products = $controller->getProductsByCategory($_GET['cat']);
    
    // Get category name for title
    $categoryInfo = $controller->getCategoryById($_GET['cat']);
    if ($categoryInfo) {
        $pageTitle = 'Category: ' . htmlspecialchars($categoryInfo['cat_name'], ENT_QUOTES, 'UTF-8');
    }
} elseif (isset($_GET['brand']) && is_numeric($_GET['brand'])) {
    // Filter by brand
    $products = $controller->getProductsByBrand($_GET['brand']);
    
    // Get brand name for title
    $brandInfo = $controller->getBrandById($_GET['brand']);
    if ($brandInfo) {
        $pageTitle = 'Brand: ' . htmlspecialchars($brandInfo['brand_name'], ENT_QUOTES, 'UTF-8');
    }
} else {
    // Show featured products
    $products = $controller->getFeaturedProducts(12);
    $pageTitle = 'Featured Products';
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?> - Shoppn</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <?php include __DIR__ . '/layout/header.php'; ?>

    <div class="page-container">
        <?php include __DIR__ . '/layout/sidebar.php'; ?>

        <main class="main-content">
            <div class="content-header">
                <h1><?= $pageTitle ?></h1>
                <p class="product-count"><?= count($products) ?> product(s) found</p>
            </div>

            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📦</div>
                    <h2>No Products Found</h2>
                    <p>We couldn't find any products matching your criteria.</p>
                    <a href="home.php" class="btn btn-primary">View All Products</a>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if ($product['product_image']): ?>
                                    <img src="../images/products/<?= htmlspecialchars($product['product_image'], ENT_QUOTES, 'UTF-8') ?>" 
                                         alt="<?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?>">
                                <?php else: ?>
                                    <div class="product-no-image">No Image</div>
                                <?php endif; ?>
                            </div>

                            <div class="product-info">
                                <h3 class="product-title">
                                    <?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?>
                                </h3>

                                <p class="product-meta">
                                    <span class="product-category"><?= htmlspecialchars($product['cat_name'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="product-separator">•</span>
                                    <span class="product-brand"><?= htmlspecialchars($product['brand_name'], ENT_QUOTES, 'UTF-8') ?></span>
                                </p>

                                <p class="product-price">$<?= number_format($product['product_price'], 2) ?></p>

                                <div class="product-actions">
                                    <a href="single_product.php?pro_id=<?= $product['product_id'] ?>" class="btn btn-secondary">
                                        View Details
                                    </a>
                                    <a href="../actions/add_to_cart_action.php?add_cart=<?= $product['product_id'] ?>" class="btn btn-primary">
                                        Add to Cart
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>
