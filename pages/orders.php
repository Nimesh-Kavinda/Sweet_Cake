<?php
// Start session and include database connection
session_start();
include '../config/db.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

$current_user_id = $_SESSION['user_id'];
$current_user_name = $_SESSION['user_name'] ?? 'User';

// Use the existing database connection
$pdo = $conn;

// Handle AJAX requests for order actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if (!$pdo) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }
    
    $action = $_POST['action'];
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    
    try {
        if ($action === 'update_status') {
            $new_status = $_POST['status'];
            $allowed_statuses = ['pending', 'cancelled', 'completed'];
            
            if (!in_array($new_status, $allowed_statuses)) {
                throw new Exception('Invalid status');
            }
              // Check if order belongs to current user and can be updated
            $stmt = $pdo->prepare("SELECT status FROM orders WHERE order_id = ? AND user_id = ?");
            $stmt->execute([$order_id, $current_user_id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$order) {
                throw new Exception('Order not found');
            }
            
            // Only allow certain status transitions for users
            if ($order['status'] === 'delivered' || $order['status'] === 'shipped') {
                if ($new_status !== 'completed') {
                    throw new Exception('Cannot modify this order');
                }
            }
            
            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id = ? AND user_id = ?");
            $stmt->execute([$new_status, $order_id, $current_user_id]);
            
            echo json_encode(['success' => true, 'message' => 'Order status updated successfully']);
              } elseif ($action === 'delete_order') {
            // Check if order belongs to current user and can be deleted
            $stmt = $pdo->prepare("SELECT status FROM orders WHERE order_id = ? AND user_id = ?");
            $stmt->execute([$order_id, $current_user_id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$order) {
                throw new Exception('Order not found');
            }
            
            // Only allow deletion of pending or cancelled orders
            if (!in_array($order['status'], ['pending', 'cancelled'])) {
                throw new Exception('Cannot delete this order');
            }
            
            // Delete order items first, then order
            $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
            $stmt->execute([$order_id]);
            
            $stmt = $pdo->prepare("DELETE FROM orders WHERE order_id = ? AND user_id = ?");
            $stmt->execute([$order_id, $current_user_id]);
            
            echo json_encode(['success' => true, 'message' => 'Order deleted successfully']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// Fetch user orders
$orders = [];
$order_stats = ['total' => 0, 'pending' => 0, 'processing' => 0, 'shipped' => 0, 'delivered' => 0, 'cancelled' => 0, 'completed' => 0];

if ($pdo) {
    try {        // Debug: Check if user exists and has orders
        $debugStmt = $pdo->prepare("SELECT COUNT(*) as order_count FROM orders WHERE user_id = ?");
        $debugStmt->execute([$current_user_id]);
        $debugResult = $debugStmt->fetch(PDO::FETCH_ASSOC);
          // Get orders with order items - Updated to show product names instead of IDs
        $stmt = $pdo->prepare("
            SELECT o.order_id as id, o.user_id, o.order_date as created_at, o.total_amount, 
                   o.status, o.payment_method, o.phone, o.shipping_address as delivery_address,
                   GROUP_CONCAT(
                       CONCAT(
                           COALESCE(p.name, CONCAT('Product ID: ', oi.product_id)), 
                           ' x', oi.quantity, 
                           ' (₹', oi.price, ')'
                       )
                       SEPARATOR '|'
                   ) as order_items
            FROM orders o 
            LEFT JOIN order_items oi ON o.order_id = oi.order_id 
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE o.user_id = ? 
            GROUP BY o.order_id 
            ORDER BY o.order_date DESC
        ");
        $stmt->execute([$current_user_id]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate statistics
        $order_stats['total'] = count($orders);
        foreach ($orders as $order) {
            $status = strtolower($order['status']);
            if (isset($order_stats[$status])) {
                $order_stats[$status]++;
            }
        }
        
    } catch (PDOException $e) {
        // Add error handling for debugging
        error_log("Database error in orders.php: " . $e->getMessage());
        $orders = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Sweety Cake</title>
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
        .orders-section {
            padding: 3.5rem 0 2rem 0;
            margin-top: 80px;
        }
        .orders-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-pink, #e75480);
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            letter-spacing: 1px;
        }

        /* Statistics Cards */
        .stats-container {
            margin-bottom: 3rem;
        }
        .stat-card {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            padding: 1.5rem;
            text-align: center;
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 32px rgba(231,84,128,0.15);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: var(--gradient-main);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #666;
            font-size: 0.95rem;
            font-weight: 500;
        }

        /* Orders Table */
        .orders-table {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 4px 32px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .orders-table th, .orders-table td {
            text-align: center;
            vertical-align: middle;
            border-top: 1.5px solid #fff0f6;
        }
        .orders-table th {
            background: #ffe3ec;
            color: #d13c6a;
            font-weight: 600;
            border: none;
            font-size: 1.08rem;
            padding: 1rem 0.5rem;
        }
        .orders-table td {
            font-size: 1.05rem;
            padding: 1.1rem 0.5rem;
        }
        .order-items-list {
            text-align: left;
            margin: 0;
            padding-left: 0;
            list-style: none;
            font-size: 0.95rem;
            color: #555;
        }
        .order-items-list li {
            margin-bottom: 0.3rem;
            padding-left: 1rem;
            position: relative;
        }
        .order-items-list li:before {
            content: "•";
            color: var(--primary-pink);
            position: absolute;
            left: 0;
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .badge-pending { background: #f39c12; }
        .badge-processing { background: #2980b9; }
        .badge-shipped { background: #8e44ad; }
        .badge-delivered { background: #27ae60; }
        .badge-cancelled { background: #e74c3c; }
        .badge-completed { background: #27ae60; }

        /* Action Buttons */
        .action-btn {
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0.2rem;
            display: inline-block;
        }
        .btn-complete {
            background: #27ae60;
            color: #fff;
        }
        .btn-complete:hover {
            background: #229a54;
            transform: translateY(-1px);
        }
        .btn-cancel {
            background: #f39c12;
            color: #fff;
        }
        .btn-cancel:hover {
            background: #e67e22;
            transform: translateY(-1px);
        }
        .btn-delete {
            background: #e74c3c;
            color: #fff;
        }
        .btn-delete:hover {
            background: #c0392b;
            transform: translateY(-1px);
        }
        .btn-disabled {
            background: #bdc3c7;
            color: #7f8c8d;
            cursor: not-allowed;
        }

        /* Address and Date Styling */
        .order-address {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.5rem;
            line-height: 1.4;
        }
        .order-date {
            font-size: 0.9rem;
            color: #888;
        }

        /* Price Styling */
        .order-total {
            color: var(--primary-pink);
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Empty State */
        .empty-orders {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }
        .empty-orders i {
            font-size: 4rem;
            color: var(--primary-pink);
            margin-bottom: 2rem;
        }
        .empty-orders h3 {
            color: var(--primary-pink);
            margin-bottom: 1rem;
        }

        /* Loading and Success States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        .success-message, .error-message {
            position: fixed;
            top: 100px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            z-index: 1000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        .success-message {
            background: #27ae60;
        }
        .error-message {
            background: #e74c3c;
        }
        .success-message.show, .error-message.show {
            transform: translateX(0);
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .orders-section {
                padding: 2rem 0 1rem 0;
            }
            .orders-table {
                font-size: 0.9rem;
            }
            .orders-table th, .orders-table td {
                padding: 0.7rem 0.3rem;
                font-size: 0.9rem;
            }
            .stat-card {
                margin-bottom: 1rem;
            }
            .action-btn {
                padding: 0.3rem 0.7rem;
                font-size: 0.8rem;
                margin: 0.1rem;
            }
        }
    </style>
</head>
<body>

    <?php include '../includes/nav.php'; ?>

    <section class="orders-section">
        <div class="container">
            <h2 class="orders-title"><i class="fas fa-shopping-bag me-2"></i>My Orders</h2>
            
            <!-- Order Statistics -->
            <div class="stats-container">
                <div class="row g-3">
                    <div class="col-md-2">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
                            <div class="stat-number"><?php echo $order_stats['total']; ?></div>
                            <div class="stat-label">Total Orders</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-clock"></i></div>
                            <div class="stat-number"><?php echo $order_stats['pending']; ?></div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-cog"></i></div>
                            <div class="stat-number"><?php echo $order_stats['processing']; ?></div>
                            <div class="stat-label">Processing</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-truck"></i></div>
                            <div class="stat-number"><?php echo $order_stats['shipped']; ?></div>
                            <div class="stat-label">Shipped</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                            <div class="stat-number"><?php echo $order_stats['delivered'] + $order_stats['completed']; ?></div>
                            <div class="stat-label">Completed</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                            <div class="stat-number"><?php echo $order_stats['cancelled']; ?></div>
                            <div class="stat-label">Cancelled</div>
                        </div>
                    </div>
                </div>
            </div>            <!-- Orders Table -->
            <?php if (empty($orders)): ?>
                <div class="orders-table p-4">
                    <div class="empty-orders">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>No Orders Yet</h3>
                        <p>You haven't placed any orders yet. Start shopping for delicious cakes!</p>
                        
                        <!-- Debug Information (remove in production) -->
                        <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin: 1rem 0; font-size: 0.9rem; color: #666;">
                            <strong>Debug Info:</strong><br>
                            User ID: <?php echo $current_user_id; ?><br>
                            Database Connection: <?php echo $pdo ? 'Connected' : 'Not Connected'; ?><br>
                            <?php if (isset($debugResult)): ?>
                                Orders in DB for this user: <?php echo $debugResult['order_count']; ?><br>
                            <?php endif; ?>
                            <?php 
                            // Check if orders table exists
                            try {
                                $tableCheck = $pdo->query("SHOW TABLES LIKE 'orders'");
                                $tableExists = $tableCheck->rowCount() > 0;
                                echo "Orders table exists: " . ($tableExists ? 'Yes' : 'No') . "<br>";
                                
                                if ($tableExists) {
                                    $allOrdersStmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
                                    $allOrders = $allOrdersStmt->fetch(PDO::FETCH_ASSOC);
                                    echo "Total orders in database: " . $allOrders['total'] . "<br>";
                                }
                            } catch (Exception $e) {
                                echo "Table check error: " . $e->getMessage() . "<br>";
                            }
                            ?>
                        </div>
                        
                        <a href="../pages/gallery.php" class="btn" style="background: var(--primary-pink); color: white; border-radius: 25px; padding: 0.7rem 2rem; text-decoration: none; font-weight: 600;">
                            <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="orders-table p-3 p-md-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Order #</th>
                                    <th scope="col">Items</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr data-order-id="<?php echo $order['id']; ?>">
                                    <td>
                                        <strong>#<?php echo str_pad($order['id'], 4, '0', STR_PAD_LEFT); ?></strong>
                                    </td>
                                    <td>
                                        <ul class="order-items-list">
                                            <?php 
                                            if ($order['order_items']) {
                                                $items = explode('|', $order['order_items']);
                                                foreach ($items as $item) {
                                                    echo "<li>" . htmlspecialchars($item) . "</li>";
                                                }
                                            } else {
                                                echo "<li>No items found</li>";
                                            }
                                            ?>
                                        </ul>
                                        <?php if (!empty($order['delivery_address'])): ?>
                                        <div class="order-address">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            <?php echo htmlspecialchars($order['delivery_address']); ?>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="order-total">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                                    </td>
                                    <td>
                                        <span class="status-badge badge-<?php echo strtolower($order['status']); ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="order-date">
                                            <?php echo date('M j, Y', strtotime($order['created_at'])); ?>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo date('g:i A', strtotime($order['created_at'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php
                                        $status = strtolower($order['status']);
                                        $order_id = $order['id'];
                                        ?>
                                        
                                        <?php if ($status === 'pending'): ?>
                                            <button class="action-btn btn-cancel" onclick="updateOrderStatus(<?php echo $order_id; ?>, 'cancelled')">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if (in_array($status, ['delivered', 'shipped'])): ?>
                                            <button class="action-btn btn-complete" onclick="updateOrderStatus(<?php echo $order_id; ?>, 'completed')">
                                                <i class="fas fa-check me-1"></i>Complete
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if (in_array($status, ['pending', 'cancelled'])): ?>
                                            <button class="action-btn btn-delete" onclick="deleteOrder(<?php echo $order_id; ?>)">
                                                <i class="fas fa-trash me-1"></i>Delete
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if (in_array($status, ['processing', 'completed'])): ?>
                                            <span class="action-btn btn-disabled">
                                                <i class="fas fa-lock me-1"></i>Locked
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Success/Error Messages -->
    <div id="successMessage" class="success-message">
        <i class="fas fa-check-circle me-2"></i>
        <span id="successText"></span>
    </div>
    <div id="errorMessage" class="error-message">
        <i class="fas fa-exclamation-circle me-2"></i>
        <span id="errorText"></span>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show message function
        function showMessage(message, isSuccess = true) {
            const messageElement = isSuccess ? 
                document.getElementById('successMessage') : 
                document.getElementById('errorMessage');
            const textElement = isSuccess ? 
                document.getElementById('successText') : 
                document.getElementById('errorText');
            
            textElement.textContent = message;
            messageElement.classList.add('show');
            
            setTimeout(() => {
                messageElement.classList.remove('show');
            }, 3000);
        }

        // Update order status
        function updateOrderStatus(orderId, newStatus) {
            const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
            if (!row) return;
            
            // Show loading state
            row.classList.add('loading');
            
            // Prepare form data
            const formData = new FormData();
            formData.append('action', 'update_status');
            formData.append('order_id', orderId);
            formData.append('status', newStatus);
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, true);
                    // Reload page to refresh the data
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showMessage(data.message, false);
                    row.classList.remove('loading');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred. Please try again.', false);
                row.classList.remove('loading');
            });
        }

        // Delete order
        function deleteOrder(orderId) {
            if (!confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
                return;
            }
            
            const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
            if (!row) return;
            
            // Show loading state
            row.classList.add('loading');
            
            // Prepare form data
            const formData = new FormData();
            formData.append('action', 'delete_order');
            formData.append('order_id', orderId);
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, true);
                    // Remove the row with animation
                    row.style.transition = 'opacity 0.5s ease';
                    row.style.opacity = '0';
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    showMessage(data.message, false);
                    row.classList.remove('loading');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred. Please try again.', false);
                row.classList.remove('loading');
            });
        }

        // Add hover effects to action buttons
        document.addEventListener('DOMContentLoaded', function() {
            const actionButtons = document.querySelectorAll('.action-btn:not(.btn-disabled)');
            actionButtons.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-1px)';
                });
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>