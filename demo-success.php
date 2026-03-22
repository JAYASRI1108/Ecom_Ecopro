<?php

declare(strict_types=1);

$name = htmlspecialchars($_GET['name'] ?? '', ENT_QUOTES, 'UTF-8');
$amount = (int) ($_GET['amount'] ?? 0);
$txnId = htmlspecialchars($_GET['txn'] ?? ('TXN' . random_int(100000, 999999)), ENT_QUOTES, 'UTF-8');
$method = htmlspecialchars($_GET['method'] ?? 'UPI', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Success</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #ecfdf5;
      display: grid;
      place-items: center;
      min-height: 100vh;
    }

    .success-card {
      width: min(460px, calc(100% - 2rem));
      padding: 2rem;
      border-radius: 18px;
      background: #ffffff;
      box-shadow: 0 18px 40px rgba(0, 0, 0, 0.08);
      text-align: center;
    }

    h2 {
      margin-top: 0;
      color: #15803d;
    }

    p {
      margin: 0.8rem 0;
      color: #1f2937;
      font-size: 1rem;
    }

    strong {
      color: #0f172a;
    }

    a {
      display: inline-block;
      margin-top: 1rem;
      color: #2563eb;
      text-decoration: none;
      font-weight: 700;
    }

    @media (max-width: 520px) {
      body {
        padding: 0.75rem;
      }

      .success-card {
        width: 100%;
        padding: 1.4rem;
      }

      p {
        font-size: 0.95rem;
      }
    }
  </style>
</head>
<body>
  <div class="success-card">
    <h2>Payment Successful</h2>
    <p>Name: <strong><?php echo $name; ?></strong></p>
    <p>Amount: <strong>Rs. <?php echo $amount; ?></strong></p>
    <p>Method: <strong><?php echo $method; ?></strong></p>
    <p>Transaction ID: <strong><?php echo $txnId; ?></strong></p>
    <a href="demo-payment.html">Make Another Payment</a>
  </div>
</body>
</html>
