<?php

declare(strict_types=1);

function db(): ?PDO
{
    static $pdo = false;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    if ($pdo === null) {
        return null;
    }

    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    } catch (Throwable $exception) {
        $pdo = null;
        $_SESSION['db_error'] = $exception->getMessage();
    }

    return $pdo;
}

function db_error_message(): ?string
{
    return $_SESSION['db_error'] ?? null;
}

function clear_db_error(): void
{
    unset($_SESSION['db_error']);
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function cart(): array
{
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    return $_SESSION['cart'];
}

function cart_count(): int
{
    return array_sum(cart());
}

function categories(): array
{
    $pdo = db();

    if (!$pdo) {
        return [];
    }

    $statement = $pdo->query('SELECT DISTINCT category FROM products ORDER BY category');
    return $statement->fetchAll(PDO::FETCH_COLUMN);
}

function all_products(?string $category = null): array
{
    $pdo = db();

    if (!$pdo) {
        return [];
    }

    if ($category && $category !== 'All') {
        $statement = $pdo->prepare('SELECT * FROM products WHERE category = :category ORDER BY id');
        $statement->execute(['category' => $category]);
        return $statement->fetchAll();
    }

    $statement = $pdo->query('SELECT * FROM products ORDER BY id');
    return $statement->fetchAll();
}

function find_product(int $id): ?array
{
    $pdo = db();

    if (!$pdo) {
        return null;
    }

    $statement = $pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
    $statement->execute(['id' => $id]);
    $product = $statement->fetch();

    return $product ?: null;
}

function related_products(string $category, int $currentId, int $limit = 4): array
{
    $pdo = db();

    if (!$pdo) {
        return [];
    }

    $statement = $pdo->prepare(
        'SELECT * FROM products WHERE category = :category AND id != :id ORDER BY is_featured DESC, id ASC LIMIT :limit'
    );
    $statement->bindValue(':category', $category);
    $statement->bindValue(':id', $currentId, PDO::PARAM_INT);
    $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetchAll();
}

function format_price(float $price): string
{
    return 'Rs. ' . number_format($price, 0);
}

function add_to_cart(int $productId, int $quantity = 1): void
{
    $quantity = max(1, $quantity);
    $cart = cart();
    $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
    $_SESSION['cart'] = $cart;
}

function update_cart_item(int $productId, int $quantity): void
{
    $cart = cart();

    if ($quantity <= 0) {
        unset($cart[$productId]);
    } else {
        $cart[$productId] = $quantity;
    }

    $_SESSION['cart'] = $cart;
}

function clear_cart(): void
{
    $_SESSION['cart'] = [];
}

function cart_items(): array
{
    $items = [];

    foreach (cart() as $productId => $quantity) {
        $product = find_product((int) $productId);

        if (!$product) {
            continue;
        }

        $product['quantity'] = $quantity;
        $product['subtotal'] = (float) $product['price'] * $quantity;
        $items[] = $product;
    }

    return $items;
}

function cart_total(): float
{
    $total = 0.0;

    foreach (cart_items() as $item) {
        $total += $item['subtotal'];
    }

    return $total;
}

function register_user(string $name, string $email, string $password): array
{
    $pdo = db();

    if (!$pdo) {
        return [false, 'Database connection failed. Import `database.sql` and update `config.php`.'];
    }

    $statement = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
    $statement->execute(['email' => $email]);

    if ($statement->fetch()) {
        return [false, 'An account with that email already exists.'];
    }

    $insert = $pdo->prepare(
        'INSERT INTO users (name, email, password_hash, created_at) VALUES (:name, :email, :password_hash, NOW())'
    );
    $insert->execute([
        'name' => $name,
        'email' => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ]);

    $_SESSION['user'] = [
        'id' => (int) $pdo->lastInsertId(),
        'name' => $name,
        'email' => $email,
    ];

    return [true, 'Account created successfully.'];
}

function login_user(string $email, string $password): array
{
    $pdo = db();

    if (!$pdo) {
        return [false, 'Database connection failed. Import `database.sql` and update `config.php`.'];
    }

    $statement = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
    $statement->execute(['email' => $email]);
    $user = $statement->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return [false, 'Invalid email or password.'];
    }

    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
    ];

    return [true, 'Logged in successfully.'];
}

function logout_user(): void
{
    unset($_SESSION['user']);
}

function require_login(): void
{
    if (!current_user()) {
        set_flash('warning', 'Please login to continue to checkout.');
        redirect('auth.php');
    }
}

function create_pending_order(int $userId, array $customerData): ?int
{
    $pdo = db();
    $items = cart_items();

    if (!$pdo || !$items) {
        return null;
    }

    try {
        $pdo->beginTransaction();

        $orderStatement = $pdo->prepare(
            'INSERT INTO orders (
                user_id, total_amount, status, customer_name, customer_email, customer_phone,
                shipping_address, payment_status, created_at
            ) VALUES (
                :user_id, :total_amount, :status, :customer_name, :customer_email, :customer_phone,
                :shipping_address, :payment_status, NOW()
            )'
        );

        $orderStatement->execute([
            'user_id' => $userId,
            'total_amount' => cart_total(),
            'status' => 'pending',
            'customer_name' => $customerData['customer_name'],
            'customer_email' => $customerData['customer_email'],
            'customer_phone' => $customerData['customer_phone'],
            'shipping_address' => $customerData['shipping_address'],
            'payment_status' => 'pending',
        ]);

        $orderId = (int) $pdo->lastInsertId();

        $itemStatement = $pdo->prepare(
            'INSERT INTO order_items (order_id, product_id, quantity, unit_price)
             VALUES (:order_id, :product_id, :quantity, :unit_price)'
        );

        foreach ($items as $item) {
            $itemStatement->execute([
                'order_id' => $orderId,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ]);
        }

        $pdo->commit();
        return $orderId;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $_SESSION['db_error'] = $exception->getMessage();
        return null;
    }
}

function find_order(int $orderId, int $userId): ?array
{
    $pdo = db();

    if (!$pdo) {
        return null;
    }

    $statement = $pdo->prepare('SELECT * FROM orders WHERE id = :id AND user_id = :user_id LIMIT 1');
    $statement->execute([
        'id' => $orderId,
        'user_id' => $userId,
    ]);

    $order = $statement->fetch();
    return $order ?: null;
}

function mark_order_paid(int $orderId, string $paymentId): void
{
    $pdo = db();

    if (!$pdo) {
        return;
    }

    $statement = $pdo->prepare(
        'UPDATE orders
         SET payment_status = :payment_status, status = :status, razorpay_payment_id = :payment_id
         WHERE id = :id'
    );
    $statement->execute([
        'payment_status' => 'paid',
        'status' => 'placed',
        'payment_id' => $paymentId,
        'id' => $orderId,
    ]);
}

function generate_demo_payment_id(): string
{
    return 'DEMO-PAY-' . strtoupper(bin2hex(random_bytes(4)));
}
