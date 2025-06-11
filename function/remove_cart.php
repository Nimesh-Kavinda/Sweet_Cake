<?php
session_start();
require_once '../config/db.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please sign in']);
    exit();
}

// Check if required data is provided
if (!isset($_POST['cart_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_id = intval($_POST['cart_id']);

try {
    // Check if cart item belongs to user
    $cartStmt = $conn->prepare("SELECT cart_id FROM cart WHERE cart_id = ? AND user_id = ?");
    $cartStmt->execute([$cart_id, $user_id]);
    $cartItem = $cartStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$cartItem) {
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit();
    }
    
    // Remove cart item
    $deleteStmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
    $deleteStmt->execute([$cart_id, $user_id]);
    
    // Get updated cart count and subtotal
    $summaryStmt = $conn->prepare("
        SELECT 
            SUM(c.quantity) as total_items,
            SUM(c.quantity * p.price) as subtotal
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $summaryStmt->execute([$user_id]);
    $summary = $summaryStmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Item removed from cart',
        'cart_count' => $summary['total_items'] ?? 0,
        'subtotal' => number_format($summary['subtotal'] ?? 0, 2),
        'total' => number_format(($summary['subtotal'] ?? 0) + 3, 2) // Adding delivery fee
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>
