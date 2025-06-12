<?php
session_start();
include '../../config/db.php';

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../signin.php');
    exit;
}

// Handle order status updates and deletions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'update_status' && isset($_POST['order_id']) && isset($_POST['status'])) {
            $order_id = intval($_POST['order_id']);
            $status = $_POST['status'];
            
            // Validate status
            $valid_statuses = ['pending', 'processing', 'shipped', 'complete', 'canceled'];
            if (in_array($status, $valid_statuses)) {
                $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
                $stmt->execute([$status, $order_id]);
                $_SESSION['success_message'] = "Order status updated successfully.";
            }
        } elseif ($_POST['action'] === 'delete' && isset($_POST['order_id'])) {
            $order_id = intval($_POST['order_id']);
            $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
            $stmt->execute([$order_id]);
            $_SESSION['success_message'] = "Order deleted successfully.";
        }
    }
    header('Location: manage_orders.php');
    exit;
}

// Fetch orders from database with user information
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$stmt = $conn->prepare("SELECT o.*, u.name as user_name, u.email as user_email 
                       FROM orders o 
                       LEFT JOIN users u ON o.user_id = u.id 
                       ORDER BY o.order_date DESC 
                       LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total count for pagination
$countStmt = $conn->prepare("SELECT COUNT(*) as total FROM orders");
$countStmt->execute();
$totalOrders = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalOrders / $limit);

// Function to get status badge class
function getStatusBadgeClass($status) {
    switch(strtolower($status)) {
        case 'pending': return 'badge-pending';
        case 'processing': return 'badge-processing';
        case 'shipped': return 'badge-shipped';
        case 'complete': return 'badge-delivered';
        case 'canceled': return 'badge-cancelled';
        default: return 'badge-pending';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders | Admin - Sweet Cake</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin.css">    <style>
        .orders-section {
            max-width: 1400px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        .orders-section h2 {
            color: var(--dark-chocolate, #2d2d44);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        .orders-table th, .orders-table td {
            padding: 0.8rem 0.6rem;
            border-bottom: 1px solid #ececf6;
            text-align: left;
            vertical-align: top;
        }
        .orders-table th {
            background: var(--soft-lavender, #f8f6ff);
            color: var(--dark-chocolate, #2d2d44);
            font-weight: 600;
            position: sticky;
            top: 0;
            white-space: nowrap;
        }
        .orders-table tbody tr:hover {
            background: rgba(255, 107, 157, 0.05);
        }
        
        /* Column width adjustments */
        .orders-table th:nth-child(1), .orders-table td:nth-child(1) { width: 8%; } /* Order ID */
        .orders-table th:nth-child(2), .orders-table td:nth-child(2) { width: 15%; } /* Customer */
        .orders-table th:nth-child(3), .orders-table td:nth-child(3) { width: 25%; } /* Order Details */
        .orders-table th:nth-child(4), .orders-table td:nth-child(4) { width: 8%; } /* Total */
        .orders-table th:nth-child(5), .orders-table td:nth-child(5) { width: 12%; } /* Status */
        .orders-table th:nth-child(6), .orders-table td:nth-child(6) { width: 10%; } /* Badge */
        .orders-table th:nth-child(7), .orders-table td:nth-child(7) { width: 10%; } /* Date */
        .orders-table th:nth-child(8), .orders-table td:nth-child(8) { width: 12%; } /* Actions */
        
        .status-select {
            padding: 0.4rem 0.6rem;
            border-radius: 6px;
            border: 2px solid #e1e5e9;
            background: #fff;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 120px;
        }
        .status-select:focus {
            border-color: var(--primary-pink, #ff6b9d);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 157, 0.25);
        }
        .update-btn {
            background: var(--primary-pink, #ff6b9d);
            color: #fff;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 0.3rem;
            font-size: 0.75rem;
            margin-bottom: 0.3rem;
        }
        .update-btn:hover {
            background: #e55a8a;
            transform: translateY(-1px);
        }
        .update-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        .delete-btn {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.75rem;
            white-space: nowrap;
        }
        .delete-btn:hover {
            background: #c0392b;
            transform: translateY(-1px);
        }
        .order-details {
            font-size: 0.8rem;
            color: #666;
            line-height: 1.3;
        }
        .order-details strong {
            color: var(--dark-chocolate, #2d2d44);
        }
        .status-badge {
            display: inline-block;
            padding: 0.3em 0.8em;
            border-radius: 15px;
            font-size: 0.75em;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .badge-pending { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .badge-processing { background: linear-gradient(135deg, #3498db, #2980b9); }
        .badge-shipped { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
        .badge-delivered { background: linear-gradient(135deg, #2ecc71, #27ae60); }
        .badge-cancelled { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        
        .no-orders {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        .no-orders i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #ddd;
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow-x: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .stats-row {
            margin-bottom: 2rem;
        }
        .stat-card {
            background: linear-gradient(135deg, var(--primary-pink, #ff6b9d), #e55a8a);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Action buttons container */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            min-width: 100px;
        }
        
        /* Responsive improvements */
        @media (max-width: 1200px) {
            .orders-table {
                font-size: 0.8rem;
            }
            .orders-table th, .orders-table td {
                padding: 0.6rem 0.4rem;
            }
            .action-buttons {
                min-width: 90px;
            }
        }
        
        @media (max-width: 992px) {
            .orders-section {
                margin: 1rem;
                padding: 1rem;
            }
            .table-responsive {
                max-height: 60vh;
            }
            .orders-table {
                font-size: 0.75rem;
            }
            .delete-btn, .update-btn {
                padding: 0.3rem 0.6rem;
                font-size: 0.7rem;
            }
        }
        
        /* Order ID styling */
        .order-id {
            font-weight: 700;
            color: var(--primary-pink, #ff6b9d);
            font-size: 0.9rem;
        }
        
        /* Customer info styling */
        .customer-info {
            font-size: 0.8rem;
        }
        .customer-name {
            font-weight: 600;
            color: var(--dark-chocolate, #2d2d44);
            margin-bottom: 0.2rem;
        }
        .customer-email {
            color: #666;
            font-size: 0.75rem;
        }
        
        /* Total amount styling */
        .total-amount {
            font-weight: 700;
            color: var(--primary-pink, #ff6b9d);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <?php include './includes/admin_nav.php'; ?>
    
    <div class="admin-dashboard-container">
        <div class="admin-dashboard-title">
            <i class="fas fa-shopping-cart me-2"></i>Manage Orders
        </div>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Stats Row -->
        <div class="row stats-row">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $totalOrders; ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <?php 
                    $pendingStmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
                    $pendingStmt->execute();
                    $pendingCount = $pendingStmt->fetch()['count'];
                    ?>
                    <div class="stat-number"><?php echo $pendingCount; ?></div>
                    <div class="stat-label">Pending Orders</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                    <?php 
                    $completeStmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE status = 'complete'");
                    $completeStmt->execute();
                    $completeCount = $completeStmt->fetch()['count'];
                    ?>
                    <div class="stat-number"><?php echo $completeCount; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <?php 
                    $canceledStmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE status = 'canceled'");
                    $canceledStmt->execute();
                    $canceledCount = $canceledStmt->fetch()['count'];
                    ?>
                    <div class="stat-number"><?php echo $canceledCount; ?></div>
                    <div class="stat-label">Canceled</div>
                </div>
            </div>
        </div>
        
        <?php if (empty($orders)): ?>
            <div class="no-orders">
                <i class="fas fa-shopping-cart"></i>
                <h4>No orders yet</h4>
                <p>Orders will appear here when customers make purchases.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Order Details</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>#</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><span class="order-id">#<?php echo $order['order_id']; ?></span></td>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-name"><?php echo htmlspecialchars($order['user_name'] ?? 'Guest'); ?></div>
                                        <div class="customer-email"><?php echo htmlspecialchars($order['user_email'] ?? 'N/A'); ?></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="order-details">
                                        <strong>Payment:</strong> <?php echo htmlspecialchars($order['payment_method']); ?><br>
                                        <strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?><br>
                                        <strong>Address:</strong> <?php echo htmlspecialchars(substr($order['shipping_address'], 0, 50) . (strlen($order['shipping_address']) > 50 ? '...' : '')); ?>
                                    </div>
                                </td>
                                <td><span class="total-amount">₹<?php echo number_format($order['total_amount'], 2); ?></span></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <select name="status" class="status-select" onchange="this.form.submit()">
                                            <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                            <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="complete" <?php echo $order['status'] === 'complete' ? 'selected' : ''; ?>>Complete</option>
                                            <option value="canceled" <?php echo $order['status'] === 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo getStatusBadgeClass($order['status']); ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($order['order_date'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="delete-btn" onclick="deleteOrder(<?php echo $order['order_id']; ?>)" title="Delete Order">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Orders pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteOrder(orderId) {
            if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="order_id" value="${orderId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Auto-submit status changes
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });
    </script>
</body>
</html>
