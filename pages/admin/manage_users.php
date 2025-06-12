<?php
session_start();
include '../../config/db.php';

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../signin.php');
    exit;
}

// Handle user role updates and deletions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'update_role' && isset($_POST['user_id']) && isset($_POST['role'])) {
            $user_id = intval($_POST['user_id']);
            $role = $_POST['role'];
            
            // Validate role
            $valid_roles = ['user', 'admin'];
            if (in_array($role, $valid_roles)) {
                // Prevent changing your own role
                if ($user_id != $_SESSION['user_id']) {
                    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
                    $stmt->execute([$role, $user_id]);
                    $_SESSION['success_message'] = "User role updated successfully.";
                } else {
                    $_SESSION['error_message'] = "You cannot change your own role.";
                }
            }
        } elseif ($_POST['action'] === 'delete' && isset($_POST['user_id'])) {
            $user_id = intval($_POST['user_id']);
            
            // Prevent deleting your own account
            if ($user_id != $_SESSION['user_id']) {
                $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $_SESSION['success_message'] = "User deleted successfully.";
            } else {
                $_SESSION['error_message'] = "You cannot delete your own account.";
            }
        }
    }
    header('Location: manage_users.php');
    exit;
}

// Fetch users from database
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$stmt = $conn->prepare("SELECT id, name, email, role, created_at 
                       FROM users 
                       ORDER BY created_at DESC 
                       LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total count for pagination
$countStmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
$countStmt->execute();
$totalUsers = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalUsers / $limit);

// Function to get role badge class
function getRoleBadgeClass($role) {
    switch(strtolower($role)) {
        case 'admin': return 'badge-admin';
        case 'user': return 'badge-user';
        default: return 'badge-user';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Admin - Sweet Cake</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin.css">    <style>
        .users-section {
            max-width: 1200px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        .users-section h2 {
            color: var(--dark-chocolate, #2d2d44);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        .users-table th, .users-table td {
            padding: 0.8rem 0.6rem;
            border-bottom: 1px solid #ececf6;
            text-align: left;
            vertical-align: middle;
        }
        .users-table th {
            background: var(--soft-lavender, #f8f6ff);
            color: var(--dark-chocolate, #2d2d44);
            font-weight: 600;
            position: sticky;
            top: 0;
            white-space: nowrap;
        }
        .users-table tbody tr:hover {
            background: rgba(255, 107, 157, 0.05);
        }
        
        /* Column width adjustments */
        .users-table th:nth-child(1), .users-table td:nth-child(1) { width: 8%; } /* ID */
        .users-table th:nth-child(2), .users-table td:nth-child(2) { width: 20%; } /* Name */
        .users-table th:nth-child(3), .users-table td:nth-child(3) { width: 25%; } /* Email */
        .users-table th:nth-child(4), .users-table td:nth-child(4) { width: 15%; } /* Role Select */
        .users-table th:nth-child(5), .users-table td:nth-child(5) { width: 12%; } /* Role Badge */
        .users-table th:nth-child(6), .users-table td:nth-child(6) { width: 12%; } /* Created Date */
        .users-table th:nth-child(7), .users-table td:nth-child(7) { width: 8%; } /* Actions */
        
        .type-badge {
            display: inline-block;
            padding: 0.4em 1em;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .badge-user { background: linear-gradient(135deg, #3498db, #2980b9); }
        .badge-admin { background: linear-gradient(135deg, #2ecc71, #27ae60); }
        
        .type-select {
            padding: 0.4rem 0.6rem;
            border-radius: 6px;
            border: 2px solid #e1e5e9;
            background: #fff;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 120px;
        }
        .type-select:focus {
            border-color: var(--primary-pink, #ff6b9d);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 157, 0.25);
        }
        .type-select:disabled {
            background: #f8f9fa;
            cursor: not-allowed;
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
        .delete-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        
        .users-table td.actions {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            align-items: flex-start;
        }
        
        .no-users {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        .no-users i {
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
        
        /* User info styling */
        .user-id {
            font-weight: 700;
            color: var(--primary-pink, #ff6b9d);
        }
        .user-name {
            font-weight: 600;
            color: var(--dark-chocolate, #2d2d44);
            margin-bottom: 0.2rem;
        }
        .user-email {
            color: #666;
            font-size: 0.85rem;
        }
        
        /* Current user highlight */
        .current-user {
            background: rgba(255, 107, 157, 0.1) !important;
            border-left: 4px solid var(--primary-pink, #ff6b9d);
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .users-section {
                margin: 1rem;
                padding: 1rem;
            }
            .table-responsive {
                max-height: 60vh;
            }
            .users-table {
                font-size: 0.8rem;
            }
            .delete-btn, .update-btn {
                padding: 0.3rem 0.6rem;
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <?php include './includes/admin_nav.php'; ?>
    
    <div class="admin-dashboard-container">
        <div class="admin-dashboard-title">
            <i class="fas fa-users me-2"></i>Manage Users
        </div>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Stats Row -->
        <div class="row stats-row">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $totalUsers; ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                    <?php 
                    $adminStmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'admin'");
                    $adminStmt->execute();
                    $adminCount = $adminStmt->fetch()['count'];
                    ?>
                    <div class="stat-number"><?php echo $adminCount; ?></div>
                    <div class="stat-label">Administrators</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                    <?php 
                    $userStmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
                    $userStmt->execute();
                    $userCount = $userStmt->fetch()['count'];
                    ?>
                    <div class="stat-number"><?php echo $userCount; ?></div>
                    <div class="stat-label">Regular Users</div>
                </div>
            </div>
        </div>
        
        <?php if (empty($users)): ?>
            <div class="no-users">
                <i class="fas fa-users"></i>
                <h4>No users yet</h4>
                <p>Users will appear here when they register on the website.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>User Role</th>
                            <th>Role Badge</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr <?php echo $user['id'] == $_SESSION['user_id'] ? 'class="current-user"' : ''; ?>>
                                <td><span class="user-id">#<?php echo $user['id']; ?></span></td>
                                <td>
                                    <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
                                    <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                        <small class="text-muted">(You)</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                                </td>
                                <td>
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="update_role">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <select name="role" class="type-select" onchange="this.form.submit()">
                                                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                            </select>
                                        </form>
                                    <?php else: ?>
                                        <select class="type-select" disabled>
                                            <option><?php echo ucfirst($user['role']); ?></option>
                                        </select>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="type-badge <?php echo getRoleBadgeClass($user['role']); ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                <td class="actions">
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <button class="delete-btn" onclick="deleteUser(<?php echo $user['id']; ?>)" title="Delete User">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    <?php else: ?>
                                        <button class="delete-btn" disabled title="Cannot delete your own account">
                                            <i class="fas fa-ban"></i> Protected
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Users pagination" class="mt-4">
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
        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="user_id" value="${userId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Auto-submit role changes
        document.querySelectorAll('.type-select').forEach(select => {
            select.addEventListener('change', function() {
                if (!this.disabled) {
                    this.form.submit();
                }
            });
        });
    </script>
</body>
</html>
