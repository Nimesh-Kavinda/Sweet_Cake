<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the user
try {
    $cartStmt = $conn->prepare("
        SELECT 
            c.cart_id,
            c.quantity,
            p.id as product_id,
            p.name,
            p.price,
            p.image,
            (c.quantity * p.price) as item_total
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
        ORDER BY c.added_at DESC
    ");
    $cartStmt->execute([$user_id]);
    $cartItems = $cartStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // If cart is empty, redirect to cart page
    if (empty($cartItems)) {
        header('Location: cart.php');
        exit();
    }
    
    // Calculate totals
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['item_total'];
    }
    $deliveryFee = 3.00;
    $total = $subtotal + $deliveryFee;
    
} catch (PDOException $e) {
    header('Location: cart.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Sweety Cake</title>
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
        .checkout-section {
            padding: 3.5rem 0 2rem 0;
            margin-top: 80px;
        }
        .checkout-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-pink, #e75480);
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            letter-spacing: 1px;
        }
        .checkout-card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 4px 32px rgba(0,0,0,0.08);
            padding: 2.5rem 2rem;
            margin-bottom: 2rem;
        }
        .checkout-label {
            font-weight: 500;
            color: #d13c6a;
        }
        .form-control {
            border-radius: 0.7rem;
            border: 1.5px solid #ffe3ec;
            background: #fff8fa;
        }
        .form-control:focus {
            border-color: var(--primary-pink, #e75480);
            box-shadow: 0 0 0 0.2rem rgba(231,84,128,0.13);
        }
        .order-summary {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 2px 16px rgba(231,84,128,0.08);
            padding: 2rem 1.5rem;
        }
        .order-summary-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-pink, #e75480);
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
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
        }
        .btn-theme:hover {
            background: #d13c6a;
            box-shadow: 0 4px 24px rgba(231,84,128,0.18);
        }
        @media (max-width: 900px) {
            .checkout-section {
                padding: 2rem 0 1rem 0;
            }
            .checkout-card, .order-summary {
                padding: 1.5rem 0.7rem;
            }
        }
    </style>
</head>
<body>

        <?php include '../includes/nav.php'; ?>

    <section class="checkout-section">
        <div class="container">
            <h2 class="checkout-title"><i class="fas fa-credit-card me-2"></i>Checkout</h2>
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="checkout-card">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label checkout-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your full name">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label checkout-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label checkout-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required placeholder="Enter your phone number">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label checkout-label">Delivery Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required placeholder="Enter your delivery address"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label checkout-label">Order Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Any special instructions?"></textarea>
                            </div>
                            <button type="submit" class="btn btn-theme w-100 mt-3"><i class="fas fa-check-circle me-2"></i>Place Order</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="order-summary">
                        <div class="order-summary-title">Order Summary</div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Chocolate Dream Cake</span>
                            <span>$25.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Red Velvet Cupcake x2</span>
                            <span>$10.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery</span>
                            <span>$3.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong>$38.00</strong>
                        </div>
                        <button class="btn btn-theme w-100"><i class="fas fa-credit-card me-2"></i>Pay Now</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include '../includes/footer.php'; ?>

</body>
</html>
