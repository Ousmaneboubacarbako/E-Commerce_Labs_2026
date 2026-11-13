<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();

// Get search query
$searchQuery = trim($_GET['user_query'] ?? '');
$products = [];

if (!empty($searchQuery)) {
    $products = $controller->searchProducts($searchQuery);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Results - Shoppn</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <?php include __DIR__ . '/layout/header.php'; ?>

    <div class="page-container">
        <?php include __DIR__ . '/layout/sidebar.php'; ?>

        <main class="main-content">
            <div class="content-header">
                <h1>Search Results</h1>
                <?php if (!empty($searchQuery)): ?>
                    <p class="search-query">
                        Showing results for: <strong>"<?= htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8') ?>"</strong>
                    </p>
                    <p class="product-count"><?= count($products) ?> product(s) found</p>
                <?php endif; ?>
            </div>

            <?php if (empty($searchQuery)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">🔍</div>
                    <h2>Enter a Search Term</h2>
                    <p>Please enter a search term to find products.</p>
                    <a href="home.php" class="btn btn-primary">Browse Products</a>
                </div>
            <?php elseif (empty($products)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">😕</div>
                    <h2>No Results Found</h2>
                    <p>We couldn't find any products matching "<?= htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8') ?>"</p>
                    <p>Try different keywords or browse our categories.</p>
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
