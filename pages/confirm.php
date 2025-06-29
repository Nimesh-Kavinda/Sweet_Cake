<?php
session_start();
include '../config/db.php';

// Check if order confirmation data exists
if (!isset($_SESSION['order_confirmation'])) {
    header("Location: gallery.php");
    exit();
}

$orderData = $_SESSION['order_confirmation'];

// Clear order confirmation data
unset($_SESSION['order_confirmation']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Sweety Cake</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
    <style>
        body {
            background: linear-gradient(135deg, #fff0f6 0%, #ffe3ec 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        .confirm-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .confirm-card {
            background: #fff;
            border-radius: 2rem;
            box-shadow: 0 8px 40px rgba(0,0,0,0.10);
            padding: 2.5rem 2.2rem 2rem 2.2rem;
            max-width: 480px;
            width: 100%;
            text-align: center;
        }
        .confirm-icon {
            color: var(--primary-pink, #e75480);
            font-size: 3.5rem;
            margin-bottom: 1.2rem;
        }
        .confirm-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-pink, #e75480);
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .confirm-message {
            color: #d13c6a;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        .order-details {
            background: #fff8fa;
            border-radius: 1.2rem;
            padding: 1.2rem 1rem;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            color: #444;
            box-shadow: 0 2px 12px rgba(231,84,128,0.07);
            text-align: left;
        }
        .order-details > div {
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
        .order-details > div:last-child {
            margin-bottom: 0;
        }
        .btn-theme {
            background: var(--primary-pink, #e75480);
            color: #fff;
            border: none;
            border-radius: 0.7rem;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.7rem 0;
            margin-top: 0.5rem;
            box-shadow: 0 2px 12px rgba(231,84,128,0.10);
            transition: background 0.2s, box-shadow 0.2s;
            text-decoration: none;
        }
        .btn-theme:hover {
            background: #d13c6a;
            box-shadow: 0 4px 24px rgba(231,84,128,0.18);
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <section class="confirm-section">
        <div class="confirm-card">
            <div class="confirm-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="confirm-title">Thank You for Your Order!</div>
            <div class="confirm-message">
                Your order has been placed successfully.<br>
                We're baking your sweet treats and will deliver them soon!
            </div>
            <div class="order-details">
                <div><strong>Order ID:</strong> #<?php echo $orderData['order_id']; ?></div>
                <div><strong>Total Amount:</strong> $<?php echo number_format($orderData['total_amount'], 2); ?></div>
                <div><strong>Order Date:</strong> <?php echo date('M d, Y H:i', strtotime($orderData['order_date'])); ?></div>
                <div><strong>Payment Method:</strong> <?php echo ucfirst($orderData['payment_method']); ?></div>
                <div><strong>Phone:</strong> <?php echo $orderData['phone']; ?></div>
                <div><strong>Delivery Address:</strong> <?php echo $orderData['address']; ?></div>
            </div>
            <a href="../index.php" class="btn btn-theme w-100">
                <i class="fas fa-home me-2"></i>Back to Home
            </a>
        </div>
    </section>
</body>
</html>
