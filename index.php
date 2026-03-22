<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$pageTitle = 'Ecoproducts | Sustainable Ecommerce Store';
$activePage = 'home';
$selectedCategory = $_GET['category'] ?? 'All';
$allCategories = categories();

$collectionCards = [
    ['name' => 'Kitchen Essentials', 'image' => 'img/cutlery.jpg', 'description' => 'Reusable cutlery, bowls, straws, and practical low-waste dining picks.'],
    ['name' => 'Carry & Storage', 'image' => 'img/jute-shopping-bag.jpg', 'description' => 'Jute bags, woven baskets, and reusable storage built for everyday errands.'],
    ['name' => 'Home Living', 'image' => 'img/floormate2.jpg', 'description' => 'Natural floor mats, decor items, and durable home essentials.'],
    ['name' => 'Personal Care', 'image' => 'img/manybrush.jpg', 'description' => 'Eco-friendly grooming and self-care products with better materials.'],
];

require __DIR__ . '/includes/header.php';
?>
<section class="hero py-5">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <p class="eyebrow">Sustainable living, made practical</p>
                <h1>Eco-friendly products for home, kitchen, and daily life.</h1>
                <p class="hero-text mt-4">
                    Ecoproducts brings together plastic-free essentials, reusable home goods,
                    natural care items, and handmade accessories that lower waste without lowering quality.
                </p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a class="button button-primary" href="#shop">Shop Best Sellers</a>
                    <a class="button button-secondary" href="#impact">Why Ecoproducts</a>
                    <a class="button button-secondary" href="auth.php">Login / Signup</a>
                </div>
                <div class="row g-3 mt-4">
                    <div class="col-sm-4"><div class="metric-card h-100"><strong>1200+</strong><span>Orders fulfilled</span></div></div>
                    <div class="col-sm-4"><div class="metric-card h-100"><strong>48h</strong><span>Dispatch on featured items</span></div></div>
                    <div class="col-sm-4"><div class="metric-card h-100"><strong>92%</strong><span>Plastic-free packaging</span></div></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="hero-card hero-card-main"><img src="img/claypot1.png" alt="Sustainable clay pot set"></div>
                    <div class="hero-card hero-card-accent"><img src="img/waterbottle.jpg" alt="Reusable eco-friendly water bottle"></div>
                    <div class="hero-badge"><span>Planet-first picks</span><strong>Curated for everyday use</strong></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="category-strip py-5" id="collections">
    <div class="container">
        <div class="section-heading mb-4">
            <p class="eyebrow">Featured collections</p>
            <h2>Start with the category that fits your routine.</h2>
        </div>
        <div class="row g-4">
            <?php foreach ($collectionCards as $collection): ?>
                <div class="col-sm-6 col-lg-3">
                    <article class="category-card">
                        <img src="<?= escape($collection['image']) ?>" alt="<?= escape($collection['name']) ?>">
                        <div class="category-copy">
                            <strong><?= escape($collection['name']) ?></strong>
                            <p><?= escape($collection['description']) ?></p>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="shop-section py-5" id="shop">
    <div class="container">
        <div class="d-lg-flex justify-content-between align-items-end gap-3 mb-4">
            <div class="section-heading mb-0">
                <p class="eyebrow">Top eco picks</p>
                <h2>Shop sustainable best sellers.</h2>
            </div>
            <div class="filters d-flex flex-wrap gap-2 mt-3 mt-lg-0">
                <a class="filter-button <?= $selectedCategory === 'All' ? 'is-active' : '' ?>" href="index.php#shop">All</a>
                <?php foreach ($allCategories as $category): ?>
                    <a class="filter-button <?= $selectedCategory === $category ? 'is-active' : '' ?>" href="index.php?category=<?= urlencode($category) ?>#shop"><?= escape($category) ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach (all_products($selectedCategory) as $product): ?>
                <div class="col-sm-6 col-xl-3">
                    <article class="product-card">
                        <a href="product.php?id=<?= (int) $product['id'] ?>"><img src="<?= escape($product['image']) ?>" alt="<?= escape($product['name']) ?>"></a>
                        <div class="product-info">
                            <span class="product-tag"><?= escape($product['category']) ?></span>
                            <h3><a href="product.php?id=<?= (int) $product['id'] ?>"><?= escape($product['name']) ?></a></h3>
                            <p><?= escape($product['short_description']) ?></p>
                            <div class="product-meta">
                                <span class="product-price"><?= escape(format_price((float) $product['price'])) ?></span>
                                <span>Ready to ship</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a class="button button-secondary flex-fill py-2" href="product.php?id=<?= (int) $product['id'] ?>">View</a>
                                <form class="flex-fill" method="post" action="cart.php">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                                    <button type="submit">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="story-section py-5" id="impact">
    <div class="container">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-5">
                <div class="story-panel">
                    <p class="eyebrow">Built for impact</p>
                    <h2>Better materials, practical design, lower waste.</h2>
                    <p class="mt-3">Every product on Ecoproducts is selected for a specific reason: durable natural materials, useful daily performance, and fewer disposable replacements.</p>
                    <div class="impact-list">
                        <article><strong>Responsible materials</strong><span>Bamboo, jute, clay, coconut shell, and reusable fabric options.</span></article>
                        <article><strong>Useful everyday swaps</strong><span>Home and kitchen alternatives that are simple to adopt immediately.</span></article>
                        <article><strong>Packaging with less waste</strong><span>Recycled paper wraps and low-plastic dispatch for featured collections.</span></article>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="story-highlight">
                    <img src="img/jute-shopping-bag.jpg" alt="Reusable jute shopping bag">
                    <div class="story-highlight-card">
                        <span>Eco Starter Bundle</span>
                        <strong>Reusable kitchen + carry essentials</strong>
                        <p>Bundle savings available on selected combinations of bottles, bags, and cutlery.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="review-section py-5">
    <div class="container">
        <div class="section-heading mb-4">
            <p class="eyebrow">Customer feedback</p>
            <h2>Trusted by shoppers building lower-waste habits.</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4"><article class="review-card h-100"><img src="img/avatar-1.jpg" alt="Customer portrait"><p>"The jute bags and bamboo cutlery feel sturdy enough for real daily use, not just gifting."</p><strong>Aditi Sharma</strong></article></div>
            <div class="col-md-4"><article class="review-card h-100"><img src="img/avatar-2.jpg" alt="Customer portrait"><p>"I replaced disposable kitchen items in one order. The product mix is practical and well curated."</p><strong>Rahul Sen</strong></article></div>
            <div class="col-md-4"><article class="review-card h-100"><img src="img/avatar-3.jpg" alt="Customer portrait"><p>"The store feels focused. It is easy to find eco alternatives without digging through generic products."</p><strong>Meera Nair</strong></article></div>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
