<?php
session_start();
require_once '../config/db.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please sign in to add products to cart']);
    exit();
}

// Check if required data is provided
if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

// Validate quantity
if ($quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
    exit();
}

try {
    // Check if product exists and get stock
    $productStmt = $conn->prepare("SELECT id, name, price, qty FROM products WHERE id = ?");
    $productStmt->execute([$product_id]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }
    
    // Check if enough stock is available
    if ($product['qty'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
        exit();
    }
      // Check if item already exists in cart
    $cartStmt = $conn->prepare("SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $cartStmt->execute([$user_id, $product_id]);
    $cartItem = $cartStmt->fetch(PDO::FETCH_ASSOC);
      if ($cartItem) {
        // Update existing cart item
        $newQuantity = $cartItem['quantity'] + $quantity;
        
        // Check if new quantity exceeds stock
        if ($newQuantity > $product['qty']) {
            echo json_encode(['success' => false, 'message' => 'Cannot add more items. Stock limit exceeded']);
            exit();
        }
        
        $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
        $updateStmt->execute([$newQuantity, $cartItem['cart_id']]);
        
        // Different message for updating existing item
        $message = "Product quantity updated! Now you have {$newQuantity} items in cart.";
    } else {
        // Add new item to cart
        $insertStmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, added_at) VALUES (?, ?, ?, NOW())");
        $insertStmt->execute([$user_id, $product_id, $quantity]);
        
        $message = "Product added to cart successfully!";
    }
    
    // Get updated cart count
    $countStmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM cart WHERE user_id = ?");
    $countStmt->execute([$user_id]);
    $cartCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total_items'] ?? 0;
      echo json_encode([
        'success' => true, 
        'message' => $message,
        'cart_count' => $cartCount,
        'product_name' => $product['name'],
        'is_update' => isset($newQuantity),
        'quantity' => isset($newQuantity) ? $newQuantity : $quantity
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>
