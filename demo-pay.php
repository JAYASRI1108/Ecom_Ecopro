<?php

declare(strict_types=1);

header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'payment_demo');

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error,
    ]);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true);
$name = trim((string) ($payload['name'] ?? ''));
$amount = (int) ($payload['amount'] ?? 0);
$method = trim((string) ($payload['method'] ?? 'UPI'));

if ($name === '' || $amount <= 0) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid payment input.',
    ]);
    exit;
}

$status = 'Success';
$txnId = 'TXN' . random_int(100000, 999999);
if ($method === '') {
    $method = 'UPI';
}
if (strlen($method) > 20) {
    $method = substr($method, 0, 20);
}

$statement = $conn->prepare('INSERT INTO payments (name, amount, method, transaction_id, status) VALUES (?, ?, ?, ?, ?)');
$statement->bind_param('sisss', $name, $amount, $method, $txnId, $status);
$statement->execute();

$statement->close();
$conn->close();

echo json_encode([
    'success' => true,
    'name' => $name,
    'amount' => $amount,
    'method' => strtoupper($method),
    'txnId' => $txnId,
]);
