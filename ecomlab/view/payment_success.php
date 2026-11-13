<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Require login
core_require_login('login.php');

// Get order ID
$order_id = $_GET['order_id'] ?? 0;

if (!$order_id) {
    $_SESSION['error'] = 'Invalid order ID.';
    header('Location: ../index.php');
    exit;
}

// Get order details
$cartController = new CartController();
$order = $cartController->getOrderById($order_id);

if (!$order) {
    $_SESSION['error'] = 'Order not found.';
    header('Location: ../index.php');
    exit;
}

// Verify this order belongs to the logged-in customer
if ($order['customer_id'] != $_SESSION['customer_id']) {
    $_SESSION['error'] = 'Access denied. This order does not belong to you.';
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Success - Shoppn</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .invoice-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 40px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
        }

        .success-badge {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
            margin-bottom: 20px;
            font-size: 1.1em;
        }

        .invoice-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-section h3 {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .info-section p {
            margin: 5px 0;
            color: #555;
        }

        .invoice-table {
            width: 100%;
            margin: 30px 0;
            border-collapse: collapse;
        }

        .invoice-table th {
            background: #2c3e50;
            color: white;
            padding: 12px;
            text-align: left;
        }

        .invoice-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .invoice-table tr:hover {
            background: #f5f5f5;
        }

        .invoice-total {
            text-align: right;
            margin-top: 20px;
            font-size: 1.3em;
            color: #2c3e50;
        }

        .action-buttons {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #eee;
        }

        .btn-print {
            background: #3498db;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 10px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-print:hover {
            background: #2980b9;
        }

        @media print {
            .action-buttons,
            header,
            .btn-print {
                display: none;
            }

            .invoice-container {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/layout/header.php'; ?>

    <div class="invoice-container">
        <div class="invoice-header">
            <div class="success-badge">✓ Payment Successful</div>
            <h1>INVOICE</h1>
            <p style="font-size: 1.2em; color: #666;">Invoice #<?= htmlspecialchars($order['invoice_no']) ?></p>
        </div>

        <div class="invoice-info">
            <div class="info-section">
                <h3> Order Information</h3>
                <p><strong>Order ID:</strong> <?= htmlspecialchars($order['order_id']) ?></p>
                <p><strong>Invoice Number:</strong> <?= htmlspecialchars($order['invoice_no']) ?></p>
                <p><strong>Order Date:</strong> <?= date('F j, Y', strtotime($order['order_date'])) ?></p>
                <p><strong>Payment Date:</strong> <?= date('F j, Y', strtotime($order['payment_date'])) ?></p>
                <p><strong>Status:</strong> <span style="color: #28a745; font-weight: bold;">PAID</span></p>
            </div>

            <div class="info-section">
                <h3> Customer Information</h3>
                <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                <p><strong>Contact:</strong> <?= htmlspecialchars($order['customer_contact']) ?></p>
                <p><strong>City:</strong> <?= htmlspecialchars($order['customer_city']) ?></p>
                <p><strong>Country:</strong> <?= htmlspecialchars($order['customer_country']) ?></p>
            </div>
        </div>

        <h3> Order Items</h3>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($item['product_title']) ?></strong></td>
                        <td>GHS <?= number_format($item['product_price'], 2) ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td>GHS <?= number_format($item['qty'] * $item['product_price'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="invoice-total">
            <strong>Total Amount Paid:</strong> 
            <span style="color: #28a745;">GHS <?= number_format($order['amt'], 2) ?></span>
        </div>

        <div style="margin-top: 30px; padding: 15px; background: #e8f4f8; border-radius: 5px;">
            <p style="margin: 0; text-align: center; color: #2c3e50;">
                <strong>Thank you for your purchase!</strong><br>
                Your order is being processed and will be delivered soon.
            </p>
        </div>

        <div class="action-buttons">
            <button onclick="window.print()" class="btn-print">🖨️ Print Invoice</button>
            <a href="../index.php" class="btn-print" style="background: #28a745;">🏠 Continue Shopping</a>
            <a href="account.php" class="btn-print" style="background: #6c757d;">📦 View My Orders</a>
        </div>
    </div>

</body>

</html>
