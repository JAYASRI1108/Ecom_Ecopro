<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

logout_user();
set_flash('success', 'Logged out successfully.');
redirect('index.php');
