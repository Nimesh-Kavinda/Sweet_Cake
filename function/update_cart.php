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
if (!isset($_POST['cart_id']) || !isset($_POST['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_id = intval($_POST['cart_id']);
$quantity = intval($_POST['quantity']);

// Validate quantity
if ($quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
    exit();
}

try {
    // Check if cart item belongs to user and get product info
    $cartStmt = $conn->prepare("
        SELECT c.cart_id, c.quantity, p.id, p.name, p.price, p.qty as stock 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.cart_id = ? AND c.user_id = ?
    ");
    $cartStmt->execute([$cart_id, $user_id]);
    $cartItem = $cartStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$cartItem) {
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit();
    }
    
    // Check if enough stock is available
    if ($cartItem['stock'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock available. Only ' . $cartItem['stock'] . ' items left.']);
        exit();
    }
    
    // Update cart item quantity
    $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ? AND user_id = ?");
    $updateStmt->execute([$quantity, $cart_id, $user_id]);
    
    // Calculate new total for this item
    $itemTotal = $cartItem['price'] * $quantity;
    
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
        'message' => 'Cart updated successfully',
        'item_total' => number_format($itemTotal, 2),
        'cart_count' => $summary['total_items'] ?? 0,
        'subtotal' => number_format($summary['subtotal'] ?? 0, 2),
        'total' => number_format(($summary['subtotal'] ?? 0) + 3, 2) // Adding delivery fee
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>
