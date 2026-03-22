<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $productId = (int) ($_POST['product_id'] ?? 0);

    if ($action === 'add' && $productId > 0) {
        $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
        add_to_cart($productId, $quantity);
        set_flash('success', 'Product added to cart.');
        redirect('cart.php');
    }

    if ($action === 'update' && isset($_POST['quantities']) && is_array($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $id => $quantity) {
            update_cart_item((int) $id, (int) $quantity);
        }
        set_flash('success', 'Cart updated.');
        redirect('cart.php');
    }

    if ($action === 'remove' && $productId > 0) {
        update_cart_item($productId, 0);
        set_flash('success', 'Item removed from cart.');
        redirect('cart.php');
    }

    if ($action === 'clear') {
        clear_cart();
        set_flash('success', 'Cart cleared.');
        redirect('cart.php');
    }
}

$pageTitle = 'Shopping Cart | Ecoproducts';
$activePage = 'cart';
$items = cart_items();
$total = cart_total();

require __DIR__ . '/includes/header.php';
?>
<section class="py-5">
    <div class="container">
        <div class="section-heading mb-4">
            <p class="eyebrow">Shopping cart</p>
            <h1 class="cart-title">Your eco picks, ready to ship.</h1>
        </div>

        <?php if (!$items): ?>
            <div class="empty-state">
                <h2>Your cart is empty.</h2>
                <p class="hero-text mt-3">Browse the catalog and add products to start your sustainable order.</p>
                <a class="button button-primary mt-3" href="index.php#shop">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <form method="post" action="cart.php">
                        <input type="hidden" name="action" value="update">
                        <div class="cart-table">
                            <?php foreach ($items as $item): ?>
                                <div class="cart-row">
                                    <img src="<?= escape($item['image']) ?>" alt="<?= escape($item['name']) ?>">
                                    <div class="cart-row-main">
                                        <div>
                                            <p class="product-tag mb-2"><?= escape($item['category']) ?></p>
                                            <h3 class="mb-2"><a href="product.php?id=<?= (int) $item['id'] ?>"><?= escape($item['name']) ?></a></h3>
                                            <p class="hero-text mb-0"><?= escape($item['short_description']) ?></p>
                                        </div>
                                        <div class="cart-row-actions">
                                            <span class="product-price"><?= escape(format_price((float) $item['price'])) ?></span>
                                            <input class="form-control quantity-input" type="number" name="quantities[<?= (int) $item['id'] ?>]" min="0" value="<?= (int) $item['quantity'] ?>">
                                            <span class="fw-semibold"><?= escape(format_price((float) $item['subtotal'])) ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="d-flex gap-3 mt-4 flex-wrap">
                            <button type="submit">Update Cart</button>
                            <a class="button button-secondary" href="index.php#shop">Continue Shopping</a>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4">
                    <aside class="summary-card">
                        <p class="eyebrow">Order summary</p>
                        <div class="summary-line"><span>Items</span><strong><?= cart_count() ?></strong></div>
                        <div class="summary-line"><span>Delivery</span><strong>Free</strong></div>
                        <div class="summary-line total"><span>Total</span><strong><?= escape(format_price($total)) ?></strong></div>
                        <a class="button button-primary w-100 mt-3" href="<?= current_user() ? 'checkout.php' : 'auth.php' ?>">
                            <?= current_user() ? 'Proceed to Checkout' : 'Proceed to Login' ?>
                        </a>
                        <form method="post" action="cart.php" class="mt-3">
                            <input type="hidden" name="action" value="clear">
                            <button class="w-100 button button-secondary" type="submit">Clear Cart</button>
                        </form>
                    </aside>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
