<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

// Validate product ID
if (!isset($_GET['pro_id']) || !is_numeric($_GET['pro_id'])) {
    header('Location: home.php');
    exit;
}

$controller = new ProductController();
$product = $controller->getProductById($_GET['pro_id']);

// Redirect if product not found
if (!$product) {
    $_SESSION['error'] = 'Product not found.';
    header('Location: home.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?> - Shoppn</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <?php include __DIR__ . '/layout/header.php'; ?>

    <div class="page-container">
        <?php include __DIR__ . '/layout/sidebar.php'; ?>

        <main class="main-content">
            <div class="breadcrumb">
                <a href="home.php">Home</a>
                <span class="breadcrumb-separator">›</span>
                <a href="home.php?cat=<?= $product['product_cat'] ?>">
                    <?= htmlspecialchars($product['cat_name'], ENT_QUOTES, 'UTF-8') ?>
                </a>
                <span class="breadcrumb-separator">›</span>
                <span><?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>

            <div class="product-detail">
                <div class="product-detail-image">
                    <?php if ($product['product_image']): ?>
                        <img src="../images/products/<?= htmlspecialchars($product['product_image'], ENT_QUOTES, 'UTF-8') ?>" 
                             alt="<?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?>">
                    <?php else: ?>
                        <div class="product-no-image-large">No Image Available</div>
                    <?php endif; ?>
                </div>

                <div class="product-detail-info">
                    <h1 class="product-detail-title">
                        <?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?>
                    </h1>

                    <div class="product-detail-meta">
                        <span class="badge badge-primary">
                            <?= htmlspecialchars($product['cat_name'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                        <span class="badge badge-secondary">
                            <?= htmlspecialchars($product['brand_name'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>

                    <p class="product-detail-price">$<?= number_format($product['product_price'], 2) ?></p>

                    <?php if ($product['product_desc']): ?>
                        <div class="product-detail-description">
                            <h3>Description</h3>
                            <p><?= nl2br(htmlspecialchars($product['product_desc'], ENT_QUOTES, 'UTF-8')) ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($product['product_keywords']): ?>
                        <div class="product-detail-keywords">
                            <h4>Tags:</h4>
                            <div class="product-tags">
                                <?php 
                                $keywords = explode(',', $product['product_keywords']);
                                foreach ($keywords as $keyword): 
                                    $keyword = trim($keyword);
                                    if ($keyword):
                                ?>
                                    <span class="product-tag"><?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?></span>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="product-detail-actions">
                        <a href="../actions/add_to_cart_action.php?add_cart=<?= $product['product_id'] ?>" class="btn btn-primary btn-large">
                            Add to Cart
                        </a>
                        <a href="home.php" class="btn btn-secondary">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
