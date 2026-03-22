<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

require_login();

$items = cart_items();

if (!$items) {
    set_flash('warning', 'Your cart is empty.');
    redirect('cart.php');
}

$user = current_user();
$errors = [];
$form = [
    'customer_name' => $user['name'] ?? '',
    'customer_email' => $user['email'] ?? '',
    'customer_phone' => '',
    'shipping_address' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['customer_name'] = trim($_POST['customer_name'] ?? '');
    $form['customer_email'] = trim($_POST['customer_email'] ?? '');
    $form['customer_phone'] = trim($_POST['customer_phone'] ?? '');
    $form['shipping_address'] = trim($_POST['shipping_address'] ?? '');

    foreach ($form as $key => $value) {
        if ($value === '') {
            $errors[$key] = 'This field is required.';
        }
    }

    if (!$errors) {
        $orderId = create_pending_order((int) $user['id'], $form);

        if ($orderId) {
            $demoPaymentId = generate_demo_payment_id();
            mark_order_paid($orderId, $demoPaymentId);
            clear_cart();
            set_flash('success', 'Demo payment completed successfully.');
            redirect('order-success.php?id=' . $orderId);
        }

        $errors['general'] = 'Order could not be placed. Check database setup and try again.';
    }
}

$pageTitle = 'Checkout | Ecoproducts';
$activePage = 'cart';
$total = cart_total();

require __DIR__ . '/includes/header.php';
?>
<section class="py-5">
    <div class="container">
        <div class="section-heading mb-4">
            <p class="eyebrow">Checkout</p>
            <h1 class="cart-title">Complete your order.</h1>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="checkout-card">
                    <h2 class="auth-title">Shipping details</h2>
                    <p class="hero-text mt-2">
                        This project uses a demo payment mode for college and presentation testing.
                    </p>

                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger custom-alert mt-3 mb-0"><?= escape($errors['general']) ?></div>
                    <?php endif; ?>

                    <form method="post" action="checkout.php" class="checkout-form mt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="customer_name">Full Name</label>
                                <input class="form-control" id="customer_name" type="text" name="customer_name" value="<?= escape($form['customer_name']) ?>">
                                <?php if (isset($errors['customer_name'])): ?><div class="field-error"><?= escape($errors['customer_name']) ?></div><?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="customer_email">Email</label>
                                <input class="form-control" id="customer_email" type="email" name="customer_email" value="<?= escape($form['customer_email']) ?>">
                                <?php if (isset($errors['customer_email'])): ?><div class="field-error"><?= escape($errors['customer_email']) ?></div><?php endif; ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="customer_phone">Phone Number</label>
                                <input class="form-control" id="customer_phone" type="text" name="customer_phone" value="<?= escape($form['customer_phone']) ?>">
                                <?php if (isset($errors['customer_phone'])): ?><div class="field-error"><?= escape($errors['customer_phone']) ?></div><?php endif; ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="shipping_address">Shipping Address</label>
                                <textarea class="form-control checkout-textarea" id="shipping_address" name="shipping_address"><?= escape($form['shipping_address']) ?></textarea>
                                <?php if (isset($errors['shipping_address'])): ?><div class="field-error"><?= escape($errors['shipping_address']) ?></div><?php endif; ?>
                            </div>
                        </div>

                        <div class="payment-panel mt-4">
                            <p class="mb-2"><strong>Demo Payment Mode</strong></p>
                            <p class="mb-0 text-muted">No real gateway or bank account is required. Clicking the button below simulates a successful payment for demo use.</p>
                        </div>

                        <button class="mt-4 w-100" type="submit">Pay Demo Amount</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <aside class="summary-card">
                    <p class="eyebrow">Order summary</p>
                    <?php foreach ($items as $item): ?>
                        <div class="summary-product">
                            <div>
                                <strong><?= escape($item['name']) ?></strong>
                                <span><?= (int) $item['quantity'] ?> x <?= escape(format_price((float) $item['price'])) ?></span>
                            </div>
                            <strong><?= escape(format_price((float) $item['subtotal'])) ?></strong>
                        </div>
                    <?php endforeach; ?>
                    <div class="summary-line mt-2"><span>Delivery</span><strong>Free</strong></div>
                    <div class="summary-line total"><span>Total</span><strong><?= escape(format_price($total)) ?></strong></div>
                </aside>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
