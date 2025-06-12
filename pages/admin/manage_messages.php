<?php
session_start();
include '../../config/db.php';

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../signin.php');
    exit;
}

// Handle message actions (mark as read, delete, delete all)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'delete' && isset($_POST['message_id'])) {
            $message_id = intval($_POST['message_id']);
            $stmt = $conn->prepare("DELETE FROM message WHERE id = ?");
            $stmt->execute([$message_id]);
            $_SESSION['success_message'] = "Message deleted successfully.";
        } elseif ($_POST['action'] === 'delete_all') {
            $stmt = $conn->prepare("DELETE FROM message");
            $stmt->execute();
            $_SESSION['success_message'] = "All messages deleted successfully.";
        }
    }
    header('Location: manage_messages.php');
    exit;
}

// Fetch messages from database
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$stmt = $conn->prepare("SELECT m.*, u.name as username FROM message m 
                       LEFT JOIN users u ON m.user_id = u.id 
                       ORDER BY m.created_at DESC 
                       LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total count for pagination
$countStmt = $conn->prepare("SELECT COUNT(*) as total FROM message");
$countStmt->execute();
$totalMessages = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalMessages / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Messages | Sweet Cake Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin.css">
    <style>
        .message-card {
            background: #fff;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 4px solid var(--primary-pink, #e75480);
        }        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 0.5rem;
        }
        .message-meta {
            font-size: 0.9rem;
            color: #666;
        }
        .message-actions {
            display: flex;
            gap: 0.5rem;
        }
        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <?php include './includes/admin_nav.php'; ?>
    
    <div class="admin-dashboard-container">
        <div class="admin-dashboard-title">
            <i class="fas fa-envelope me-2"></i>Contact Messages
        </div>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <h5>Total Messages: <span class="badge bg-primary"><?php echo $totalMessages; ?></span></h5>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteAllMessages()" <?php echo $totalMessages == 0 ? 'disabled' : ''; ?>>
                        <i class="fas fa-trash me-1"></i>Delete All
                    </button>
                </div>
            </div>
        </div>
        
        <?php if (empty($messages)): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
                <h4>No messages yet</h4>
                <p class="text-muted">Contact messages will appear here when customers submit the contact form.</p>
            </div>
        <?php else: ?>
            <?php foreach ($messages as $message): ?>
                <div class="message-card">
                    <div class="message-header">
                        <div>
                            <h6 class="mb-1">
                                <?php echo htmlspecialchars($message['name']); ?>
                                <?php if ($message['username']): ?>
                                    <small class="text-muted">(Registered User: <?php echo htmlspecialchars($message['username']); ?>)</small>
                                <?php endif; ?>
                            </h6>
                            <div class="message-meta">
                                <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($message['email']); ?>
                                <i class="fas fa-clock ms-3 me-1"></i><?php echo date('M j, Y g:i A', strtotime($message['created_at'])); ?>
                            </div>
                        </div>
                        <div class="message-actions">
                            <button class="btn btn-outline-primary btn-sm" onclick="replyToMessage('<?php echo htmlspecialchars($message['email']); ?>', '<?php echo htmlspecialchars($message['subject']); ?>')">
                                <i class="fas fa-reply"></i> Reply
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="deleteMessage(<?php echo $message['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <?php if (!empty($message['subject'])): ?>
                        <h6 class="mb-2">Subject: <?php echo htmlspecialchars($message['subject']); ?></h6>
                    <?php endif; ?>
                    
                    <div class="message-content">
                        <?php echo nl2br(htmlspecialchars($message['text'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Messages pagination">
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
        function deleteMessage(messageId) {
            if (confirm('Are you sure you want to delete this message?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="message_id" value="${messageId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
          function deleteAllMessages() {
            if (confirm('Are you sure you want to delete ALL messages? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_all">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function replyToMessage(email, subject) {
            const replySubject = subject ? `Re: ${subject}` : 'Re: Your message';
            const mailtoLink = `mailto:${email}?subject=${encodeURIComponent(replySubject)}`;
            window.location.href = mailtoLink;
        }
    </script>
</body>
</html>
