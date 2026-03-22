<?php

$pageTitle = $pageTitle ?? 'Ecoproducts';
$activePage = $activePage ?? '';
$flash = get_flash();
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($pageTitle) ?></title>
    <meta
        name="description"
        content="Shop sustainable daily essentials, zero-waste kitchenware, natural personal care, and eco-friendly home products at Ecoproducts."
    >
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="site-header sticky-top">
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand brand" href="index.php" aria-label="Ecoproducts home">
                <span class="brand-mark">Eco</span>
                <span class="brand-text">products</span>
            </a>
            <button
                class="navbar-toggler border-0 shadow-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNav"
                aria-controls="mainNav"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-3 mb-lg-0 gap-lg-3">
                    <li class="nav-item"><a class="nav-link <?= $activePage === 'home' ? 'active' : '' ?>" href="index.php#shop">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#collections">Collections</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#impact">Impact</a></li>
                    <li class="nav-item"><a class="nav-link <?= $activePage === 'cart' ? 'active' : '' ?>" href="cart.php">Cart</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <?php if ($user): ?>
                        <span class="user-chip">Hi, <?= escape($user['name']) ?></span>
                        <a class="button button-secondary py-2 px-3" href="logout.php">Logout</a>
                    <?php else: ?>
                        <a class="button button-secondary py-2 px-3" href="auth.php">Login / Signup</a>
                    <?php endif; ?>
                    <a class="cart-button btn" href="cart.php" aria-label="Shopping cart">
                        Cart
                        <span class="cart-count"><?= cart_count() ?></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

<main>
    <div class="container pt-4">
        <?php if ($flash): ?>
            <div class="alert alert-<?= escape($flash['type']) ?> alert-dismissible fade show custom-alert" role="alert">
                <?= escape($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (db_error_message()): ?>
            <div class="alert alert-warning custom-alert" role="alert">
                Database connection failed. Import <code>database.sql</code> and update <code>config.php</code>.
            </div>
            <?php clear_db_error(); ?>
        <?php endif; ?>
    </div>
