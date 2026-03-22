<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$productId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = find_product($productId);

if (!$product) {
    set_flash('danger', 'Product not found.');
    redirect('index.php');
}

$pageTitle = $product['name'] . ' | Ecoproducts';
$related = related_products($product['category'], (int) $product['id']);

require __DIR__ . '/includes/header.php';
?>
<section class="py-5">
    <div class="container">
        <div class="row g-4 g-lg-5 align-items-start">
            <div class="col-lg-6">
                <div class="product-detail-media"><img src="<?= escape($product['image']) ?>" alt="<?= escape($product['name']) ?>"></div>
            </div>
            <div class="col-lg-6">
                <p class="eyebrow mb-2"><?= escape($product['category']) ?></p>
                <h1 class="product-detail-title"><?= escape($product['name']) ?></h1>
                <p class="product-detail-price mt-3"><?= escape(format_price((float) $product['price'])) ?></p>
                <p class="hero-text mt-3"><?= escape($product['description']) ?></p>
                <div class="detail-points">
                    <div><strong>Material</strong><span><?= escape($product['material']) ?></span></div>
                    <div><strong>Best for</strong><span><?= escape($product['best_for']) ?></span></div>
                    <div><strong>Shipping</strong><span>Dispatch in 48 hours</span></div>
                </div>
                <form class="detail-form mt-4" method="post" action="cart.php">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                    <div class="row g-3 align-items-end">
                        <div class="col-sm-4">
                            <label class="form-label" for="quantity">Quantity</label>
                            <input class="form-control quantity-input" id="quantity" type="number" name="quantity" min="1" value="1">
                        </div>
                        <div class="col-sm-8"><button class="w-100" type="submit">Add to Cart</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="py-5 border-top border-opacity-25">
    <div class="container">
        <div class="section-heading mb-4">
            <p class="eyebrow">You may also like</p>
            <h2>Similar eco-friendly picks.</h2>
        </div>
        <div class="row g-4">
            <?php foreach ($related as $item): ?>
                <div class="col-sm-6 col-xl-3">
                    <article class="product-card">
                        <a href="product.php?id=<?= (int) $item['id'] ?>"><img src="<?= escape($item['image']) ?>" alt="<?= escape($item['name']) ?>"></a>
                        <div class="product-info">
                            <span class="product-tag"><?= escape($item['category']) ?></span>
                            <h3><a href="product.php?id=<?= (int) $item['id'] ?>"><?= escape($item['name']) ?></a></h3>
                            <p><?= escape($item['short_description']) ?></p>
                            <div class="product-meta">
                                <span class="product-price"><?= escape(format_price((float) $item['price'])) ?></span>
                                <span>Ready to ship</span>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
