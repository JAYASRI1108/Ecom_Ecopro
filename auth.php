<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if (current_user()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode = $_POST['mode'] ?? '';

    if ($mode === 'signup') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            set_flash('danger', 'All signup fields are required.');
        } else {
            [$success, $message] = register_user($name, $email, $password);
            set_flash($success ? 'success' : 'danger', $message);

            if ($success) {
                redirect('index.php');
            }
        }
    }

    if ($mode === 'login') {
        [$success, $message] = login_user(trim($_POST['email'] ?? ''), $_POST['password'] ?? '');
        set_flash($success ? 'success' : 'danger', $message);

        if ($success) {
            redirect('index.php');
        }
    }
}

$pageTitle = 'Login / Signup | Ecoproducts';

require __DIR__ . '/includes/header.php';
?>
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <div class="auth-shell">
                    <div class="row g-0">
                        <div class="col-lg-5 auth-panel">
                            <div class="auth-panel-inner">
                                <p class="eyebrow">Member access</p>
                                <h1>Sign in or create your Ecoproducts account.</h1>
                                <p class="hero-text mt-3">
                                    Keep your eco shopping simple. Save account details, manage your cart faster,
                                    and come back to your favorite sustainable picks anytime.
                                </p>

                                <div class="auth-stat-list">
                                    <div class="auth-stat-card">
                                        <strong>Fast access</strong>
                                        <span>Login and continue shopping without losing your cart flow.</span>
                                    </div>
                                    <div class="auth-stat-card">
                                        <strong>Safer account setup</strong>
                                        <span>Passwords are stored securely using PHP password hashing.</span>
                                    </div>
                                    <div class="auth-stat-card">
                                        <strong>Built for repeat orders</strong>
                                        <span>Ideal for customers regularly buying reusable home essentials.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="auth-forms-wrap">
                                <div class="row g-4 h-100">
                                <div class="col-md-6">
                                    <div class="auth-form-card h-100">
                                    <div class="auth-card-top">
                                        <span class="auth-badge">Existing user</span>
                                        <h2 class="auth-title">Sign In</h2>
                                        <p>Access your cart and continue your eco shopping journey.</p>
                                    </div>
                                    <form method="post" action="auth.php" class="auth-form">
                                        <input type="hidden" name="mode" value="login">
                                        <label class="form-label" for="login-email">Email</label>
                                        <input class="form-control" id="login-email" type="email" name="email" placeholder="Enter your email" required>
                                        <label class="form-label mt-3" for="login-password">Password</label>
                                        <input class="form-control" id="login-password" type="password" name="password" placeholder="Enter your password" required>
                                        <button class="w-100 mt-4" type="submit">Sign In</button>
                                    </form>
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="auth-form-card auth-form-card-secondary h-100">
                                    <div class="auth-card-top">
                                        <span class="auth-badge">New customer</span>
                                        <h2 class="auth-title">Create Account</h2>
                                        <p>Register once and keep your sustainable purchases organized.</p>
                                    </div>
                                    <form method="post" action="auth.php" class="auth-form">
                                        <input type="hidden" name="mode" value="signup">
                                        <label class="form-label" for="signup-name">Full Name</label>
                                        <input class="form-control" id="signup-name" type="text" name="name" placeholder="Enter your full name" required>
                                        <label class="form-label mt-3" for="signup-email">Email</label>
                                        <input class="form-control" id="signup-email" type="email" name="email" placeholder="Enter your email" required>
                                        <label class="form-label mt-3" for="signup-password">Password</label>
                                        <input class="form-control" id="signup-password" type="password" name="password" placeholder="Create a password" required>
                                        <button class="w-100 mt-4" type="submit">Create Account</button>
                                    </form>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
