<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

require_login();

$user = current_user();
$orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$order = find_order($orderId, (int) $user['id']);

if (!$order) {
    set_flash('warning', 'Order not found.');
    redirect('index.php');
}

$pageTitle = 'Order Success | Ecoproducts';

require __DIR__ . '/includes/header.php';
?>
<section class="py-5">
    <div class="container">
        <div class="success-card">
            <span class="success-badge">Order confirmed</span>
            <h1 class="cart-title mt-3">Your order has been placed.</h1>
            <p class="hero-text mt-3">
                Thanks for shopping with Ecoproducts. Your order reference is
                <strong>#<?= (int) $order['id'] ?></strong>.
            </p>
            <p class="hero-text mt-2">
                Payment mode:
                <strong>Demo Payment</strong>
            </p>

            <div class="success-grid mt-4">
                <div class="success-item">
                    <strong>Name</strong>
                    <span><?= escape($order['customer_name']) ?></span>
                </div>
                <div class="success-item">
                    <strong>Email</strong>
                    <span><?= escape($order['customer_email']) ?></span>
                </div>
                <div class="success-item">
                    <strong>Phone</strong>
                    <span><?= escape($order['customer_phone']) ?></span>
                </div>
                <div class="success-item">
                    <strong>Total</strong>
                    <span><?= escape(format_price((float) $order['total_amount'])) ?></span>
                </div>
                <div class="success-item">
                    <strong>Payment ID</strong>
                    <span><?= escape($order['razorpay_payment_id'] ?? 'N/A') ?></span>
                </div>
                <div class="success-item success-item-full">
                    <strong>Shipping Address</strong>
                    <span><?= nl2br(escape($order['shipping_address'])) ?></span>
                </div>
            </div>

            <div class="d-flex gap-3 flex-wrap mt-4">
                <a class="button button-primary" href="index.php#shop">Continue Shopping</a>
                <a class="button button-secondary" href="cart.php">View Cart</a>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
