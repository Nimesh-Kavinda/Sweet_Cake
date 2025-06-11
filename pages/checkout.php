<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$messageType = '';

// Process order if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = trim($_POST['phone']);
    $shipping_address = trim($_POST['shipping_address']);
    $payment_method = $_POST['payment_method'];
    
    // Validate required fields
    if (empty($phone) || empty($shipping_address) || empty($payment_method)) {
        $message = 'Please fill in all required fields.';
        $messageType = 'error';
    } else {
        try {
            // Start transaction
            $conn->beginTransaction();
            
            // Get cart items and calculate total
            $cartStmt = $conn->prepare("
                SELECT c.*, p.name, p.price 
                FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ?
            ");
            $cartStmt->execute([$user_id]);
            $cartItems = $cartStmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($cartItems)) {
                throw new Exception('Cart is empty');
            }
            
            // Calculate total amount
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }
            $deliveryFee = 3.00;
            $totalAmount = $subtotal + $deliveryFee;
            
            // Insert order into orders table
            $orderStmt = $conn->prepare("
                INSERT INTO orders (user_id, order_date, total_amount, status, payment_method, phone, shipping_address) 
                VALUES (?, NOW(), ?, 'pending', ?, ?, ?)
            ");
            $orderStmt->execute([$user_id, $totalAmount, $payment_method, $phone, $shipping_address]);
            $orderId = $conn->lastInsertId();
            
            // Insert order items
            $orderItemStmt = $conn->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price) 
                VALUES (?, ?, ?, ?)
            ");
            
            foreach ($cartItems as $item) {
                $orderItemStmt->execute([
                    $orderId, 
                    $item['product_id'], 
                    $item['quantity'], 
                    $item['price']
                ]);
            }
            
            // Clear the cart
            $clearCartStmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $clearCartStmt->execute([$user_id]);
            
            // Commit transaction
            $conn->commit();
            
            // Redirect to success page or show success message
            $message = 'Order placed successfully! Order ID: #' . $orderId;
            $messageType = 'success';
            
            // Redirect after 2 seconds
            header("refresh:2;url=gallery.php");
            
        } catch (Exception $e) {
            // Rollback transaction
            $conn->rollback();
            $message = 'Error placing order: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

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
        }        .btn-theme:hover {
            background: #d13c6a;
            box-shadow: 0 4px 24px rgba(231,84,128,0.18);
        }
        .alert {
            border-radius: 1rem;
            margin-bottom: 2rem;
            padding: 1rem 1.5rem;
            font-weight: 500;
        }
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 2px solid #27ae60;
            color: #155724;
        }
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: 2px solid #e74c3c;
            color: #721c24;
        }
        .payment-option {
            border: 2px solid #ffe3ec;
            border-radius: 0.7rem;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-option:hover {
            border-color: var(--primary-pink);
            background: #fff8fa;
        }
        .payment-option.selected {
            border-color: var(--primary-pink);
            background: #fff8fa;
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

        <?php include '../includes/nav.php'; ?>    <section class="checkout-section">
        <div class="container">
            <h2 class="checkout-title"><i class="fas fa-credit-card me-2"></i>Checkout</h2>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType === 'error' ? 'danger' : 'success'; ?>">
                    <i class="fas fa-<?php echo $messageType === 'error' ? 'exclamation-triangle' : 'check-circle'; ?> me-2"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="checkout-card">
                        <h4 style="color: var(--primary-pink); margin-bottom: 1.5rem;">
                            <i class="fas fa-truck me-2"></i>Delivery Information
                        </h4>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="phone" class="form-label checkout-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required 
                                       placeholder="Enter your phone number" 
                                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label checkout-label">Delivery Address *</label>
                                <textarea class="form-control" id="shipping_address" name="shipping_address" rows="4" required 
                                          placeholder="Enter your complete delivery address including street, city, and postal code"><?php echo isset($_POST['shipping_address']) ? htmlspecialchars($_POST['shipping_address']) : ''; ?></textarea>
                            </div>
                            
                            <h4 style="color: var(--primary-pink); margin-bottom: 1.5rem; margin-top: 2rem;">
                                <i class="fas fa-credit-card me-2"></i>Payment Method *
                            </h4>
                            
                            <div class="payment-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" 
                                           <?php echo (!isset($_POST['payment_method']) || $_POST['payment_method'] === 'cod') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="cod">
                                        <i class="fas fa-money-bill-wave me-2"></i>Cash on Delivery
                                        <small class="d-block text-muted">Pay when your order arrives</small>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="payment-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="card" value="card" disabled>
                                    <label class="form-check-label" for="card">
                                        <i class="fas fa-credit-card me-2"></i>Credit/Debit Card
                                        <small class="d-block text-muted">Coming Soon</small>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="payment-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="upi" value="upi" disabled>
                                    <label class="form-check-label" for="upi">
                                        <i class="fab fa-google-pay me-2"></i>UPI Payment
                                        <small class="d-block text-muted">Coming Soon</small>
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-theme w-100 mt-4">
                                <i class="fas fa-check-circle me-2"></i>Place Order
                            </button>
                        </form>
                    </div>
                </div>                <div class="col-lg-5">
                    <div class="order-summary">
                        <div class="order-summary-title">Order Summary</div>
                        
                        <?php foreach ($cartItems as $item): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <small class="fw-medium"><?php echo htmlspecialchars($item['name']); ?></small>
                                <small class="d-block text-muted">Qty: <?php echo $item['quantity']; ?></small>
                            </div>
                            <span>₹<?php echo number_format($item['item_total'], 2); ?></span>
                        </div>
                        <?php endforeach; ?>
                        
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>₹<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery</span>
                            <span>₹<?php echo number_format($deliveryFee, 2); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong>₹<?php echo number_format($total, 2); ?></strong>
                        </div>
                        
                        <a href="cart.php" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="fas fa-arrow-left me-2"></i>Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include '../includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Payment option selection styling
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove selected class from all options
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to current option
            if (this.checked && !this.disabled) {
                this.closest('.payment-option').classList.add('selected');
            }
        });
    });
    
    // Initialize selected option on page load
    const checkedRadio = document.querySelector('input[name="payment_method"]:checked');
    if (checkedRadio && !checkedRadio.disabled) {
        checkedRadio.closest('.payment-option').classList.add('selected');
    }
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const phone = document.getElementById('phone').value.trim();
        const address = document.getElementById('shipping_address').value.trim();
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (!phone || !address || !paymentMethod) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
        
        // Phone number validation (basic)
        const phoneRegex = /^[0-9+\-\s()]{10,}$/;
        if (!phoneRegex.test(phone)) {
            e.preventDefault();
            alert('Please enter a valid phone number.');
            return false;
        }
        
        // Address validation (minimum length)
        if (address.length < 10) {
            e.preventDefault();
            alert('Please enter a complete delivery address.');
            return false;
        }
        
        // Check if payment method is enabled
        if (paymentMethod && paymentMethod.disabled) {
            e.preventDefault();
            alert('Please select an available payment method.');
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        submitBtn.disabled = true;
        
        return true;
    });
    
    // Auto-hide success/error messages
    const alertElement = document.querySelector('.alert');
    if (alertElement) {
        setTimeout(() => {
            alertElement.style.opacity = '0';
            setTimeout(() => {
                alertElement.remove();
            }, 300);
        }, 5000);
    }
</script>

</body>
</html>
