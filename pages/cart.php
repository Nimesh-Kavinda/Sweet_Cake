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
    
    // Calculate totals
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['item_total'];
    }
    $deliveryFee = 3.00;
    $total = $subtotal + $deliveryFee;
    
} catch (PDOException $e) {
    $cartItems = [];
    $subtotal = 0;
    $deliveryFee = 3.00;
    $total = $deliveryFee;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Sweety Cake</title>
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
        .cart-section {
            padding: 3.5rem 0 2rem 0;
            margin-top: 80px;
        }
        .cart-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-pink, #e75480);
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            letter-spacing: 1px;
        }
        .cart-table {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 4px 32px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .cart-table th, .cart-table td {
            text-align: center;
        }
        .cart-table th {
            font-size: 1.08rem;
            padding: 1rem 0.5rem;
        }
        .cart-table td {
            font-size: 1.05rem;
            padding: 1.1rem 0.5rem;
        }
        .cart-img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 1.2rem;
            box-shadow: 0 2px 8px rgba(231,84,128,0.10);
            margin: 0 auto;
            display: block;
        }
        .cart-table td:nth-child(2) {
            text-align: left;
            font-weight: 600;
            color: #d13c6a;
        }
        .cart-table td:nth-child(3),
        .cart-table td:nth-child(5) {
            color: #e75480;
            font-weight: 500;
        }
        .cart-table input[type="number"] {
            text-align: center;
            border-radius: 0.5rem;
            border: 1.5px solid #ffe3ec;
            background: #fff8fa;
            width: 70px;
            margin: 0 auto;
        }
        .cart-remove {
            color: var(--primary-pink, #e75480);
            cursor: pointer;
            font-size: 1.2rem;
        }        .cart-remove:hover {
            color: #d13c6a;
            transform: scale(1.1);
            transition: all 0.2s ease;
        }
        .quantity-input:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 0.2rem rgba(231, 84, 128, 0.25);
        }
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        .cart-summary {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 2px 16px rgba(231,84,128,0.08);
            padding: 2rem 1.5rem;
            margin-top: 2rem;
        }
        .cart-summary-title {
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
            .cart-section {
                padding: 2rem 0 1rem 0;
            }
            .cart-summary {
                margin-top: 1.2rem;
            }
            .cart-img {
                width: 60px;
                height: 60px;
            }
            .cart-table th, .cart-table td {
                font-size: 0.98rem;
                padding: 0.7rem 0.2rem;
            }
        }
    </style>
</head>
<body>

<?php include '../includes/nav.php'; ?>    <section class="cart-section">
        <div class="container">
            <h1 class="cart-title">Your Shopping Cart</h1>
            <?php if (empty($cartItems)): ?>
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="text-center p-5" style="background: #fff; border-radius: 1.5rem; box-shadow: 0 4px 32px rgba(0,0,0,0.08);">
                            <i class="fas fa-shopping-cart" style="font-size: 4rem; color: var(--primary-pink); margin-bottom: 1rem;"></i>
                            <h3 style="color: var(--dark-chocolate, #2c2c2c); margin-bottom: 1rem;">Your cart is empty</h3>
                            <p style="color: #888; margin-bottom: 2rem;">Start shopping to add items to your cart!</p>
                            <a href="gallery.php" class="btn btn-theme" style="padding: 0.75rem 2rem;">
                                <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cart-table p-3 p-md-4">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                <tr data-cart-id="<?php echo $item['cart_id']; ?>">
                                    <td>
                                        <img src="<?php echo !empty($item['image']) ? '../uploads/products/' . htmlspecialchars($item['image']) : 'https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=80&q=80'; ?>" 
                                             class="cart-img" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                                             onerror="this.src='https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=80&q=80'">
                                    </td>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm quantity-input" 
                                               value="<?php echo $item['quantity']; ?>" 
                                               min="1" 
                                               data-cart-id="<?php echo $item['cart_id']; ?>"
                                               style="width: 70px;">
                                    </td>
                                    <td class="item-total">₹<?php echo number_format($item['item_total'], 2); ?></td>
                                    <td>
                                        <span class="cart-remove" 
                                              data-cart-id="<?php echo $item['cart_id']; ?>" 
                                              title="Remove item">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <div class="cart-summary-title">Order Summary</div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span id="subtotal">₹<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery</span>
                            <span>₹<?php echo number_format($deliveryFee, 2); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong id="total">₹<?php echo number_format($total, 2); ?></strong>
                        </div>
                        <button class="btn btn-theme w-100" onclick="proceedToCheckout()">
                            <i class="fas fa-credit-card me-2"></i>Checkout
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>    <?php include '../includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update cart quantity
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('.quantity-input');
            const removeButtons = document.querySelectorAll('.cart-remove');
            
            // Handle quantity changes
            quantityInputs.forEach(input => {
                let timeoutId;
                input.addEventListener('input', function() {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        updateCartQuantity(this.dataset.cartId, this.value);
                    }, 500); // Wait 500ms after user stops typing
                });
            });
            
            // Handle remove buttons
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to remove this item from your cart?')) {
                        removeCartItem(this.dataset.cartId);
                    }
                });
            });
        });
        
        function updateCartQuantity(cartId, quantity) {
            if (quantity < 1) {
                alert('Quantity must be at least 1');
                return;
            }
            
            fetch('../function/update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `cart_id=${cartId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the item total
                    const row = document.querySelector(`tr[data-cart-id="${cartId}"]`);
                    if (row) {
                        row.querySelector('.item-total').textContent = '₹' + data.item_total;
                    }
                    
                    // Update summary
                    document.getElementById('subtotal').textContent = '₹' + data.subtotal;
                    document.getElementById('total').textContent = '₹' + data.total;
                    
                    // Update nav cart count
                    updateNavCartCount(data.cart_count);
                    
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                    // Reset the input to previous value if update failed
                    location.reload();
                }
            })
            .catch(error => {
                showNotification('An error occurred while updating cart', 'error');
                console.error('Error:', error);
            });
        }
        
        function removeCartItem(cartId) {
            fetch('../function/remove_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `cart_id=${cartId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the row
                    const row = document.querySelector(`tr[data-cart-id="${cartId}"]`);
                    if (row) {
                        row.remove();
                    }
                    
                    // Update summary
                    document.getElementById('subtotal').textContent = '₹' + data.subtotal;
                    document.getElementById('total').textContent = '₹' + data.total;
                    
                    // Update nav cart count
                    updateNavCartCount(data.cart_count);
                    
                    // Check if cart is empty
                    if (data.cart_count == 0) {
                        location.reload(); // Reload to show empty cart message
                    }
                    
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('An error occurred while removing item', 'error');
                console.error('Error:', error);
            });
        }
        
        function updateNavCartCount(count) {
            // Update cart count in navigation
            const cartLink = document.querySelector('a[href="./cart.php"]');
            if (cartLink) {
                const badge = cartLink.querySelector('.badge');
                if (count > 0) {
                    if (badge) {
                        badge.textContent = count;
                    } else {
                        // Create badge if it doesn't exist
                        const newBadge = document.createElement('span');
                        newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill';
                        newBadge.style.cssText = 'background-color: var(--primary-pink); font-size: 0.7rem; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center;';
                        newBadge.textContent = count;
                        cartLink.appendChild(newBadge);
                    }
                } else {
                    // Remove badge if count is 0
                    if (badge) {
                        badge.remove();
                    }
                }
            }
        }
        
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
        
        function proceedToCheckout() {
            // Navigate to checkout page
            window.location.href = 'checkout.php';
        }
    </script>
</body>
</html>
