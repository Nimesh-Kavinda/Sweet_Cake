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

$user_id = $_SESSION['user_id'];

try {
    // Clear all cart items for the user
    $deleteStmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $deleteStmt->execute([$user_id]);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Cart cleared successfully'
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>
