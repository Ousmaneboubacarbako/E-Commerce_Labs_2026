<?php
require_once __DIR__ . '/../core/core.php';

// Require login
core_require_login('login.php');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Failed - Shoppn</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .error-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 40px;
            background: white;
            border: 2px solid #dc3545;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .error-icon {
            font-size: 4em;
            color: #dc3545;
            margin-bottom: 20px;
        }

        .error-title {
            color: #dc3545;
            font-size: 2em;
            margin-bottom: 20px;
        }

        .error-message {
            color: #555;
            font-size: 1.1em;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .error-actions {
            margin-top: 30px;
        }

        .error-actions a {
            margin: 0 10px;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/layout/header.php'; ?>

    <div class="error-container">
        <div class="error-icon">❌</div>
        <h1 class="error-title">Payment Failed</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php else: ?>
            <div class="error-message">
                Your payment could not be processed. This could be due to:
                <ul style="text-align: left; display: inline-block; margin-top: 15px;">
                    <li>Insufficient funds</li>
                    <li>Incorrect card details</li>
                    <li>Payment cancelled</li>
                    <li>Network connection issues</li>
                </ul>
            </div>
        <?php endif; ?>

        <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>💡 Need Help?</strong><br>
            Please contact our support team if you continue experiencing issues.
        </div>

        <div class="error-actions">
            <a href="payment.php" class="btn btn-primary">Try Again</a>
            <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
            <a href="../index.php" class="btn btn-secondary">Continue Shopping</a>
        </div>
    </div>

</body>

</html>
