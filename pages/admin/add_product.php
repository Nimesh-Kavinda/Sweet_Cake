<?php
session_start();
require_once '../../config/db.php';

// Check if admin is logged in (you can add admin authentication later)

// Handle form submission
if (isset($_POST['add_product'])) {
    $product_name = trim($_POST['product_name']);
    $product_price = floatval($_POST['product_price']);
    $product_category = intval($_POST['product_category']);
    $product_qty = intval($_POST['product_qty']);
    $product_description = trim($_POST['product_description']);
    
    // Handle file upload
    $image_name = null;
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../uploads/products/';
        $file_extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $image_name;
        
        // Check if upload directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $upload_path)) {
            // File uploaded successfully
        } else {
            $error_message = "Failed to upload image.";
        }
    }
    
    // Insert product into database
    if (empty($error_message)) {
        try {
            $stmt = $conn->prepare("INSERT INTO products (category_id, name, price, qty, image, description, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$product_category, $product_name, $product_price, $product_qty, $image_name, $product_description]);
            $success_message = "Product added successfully!";
        } catch (PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    }
}

// Handle product deletion
if (isset($_POST['delete_product'])) {
    $product_id = intval($_POST['delete_product']);
    try {
        // Get image filename before deleting
        $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete product from database
        $deleteStmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $deleteStmt->execute([$product_id]);
        
        // Delete image file if exists
        if ($product && $product['image']) {
            $image_path = '../../uploads/products/' . $product['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        $success_message = "Product deleted successfully!";
    } catch (PDOException $e) {
        $error_message = "Error deleting product: " . $e->getMessage();
    }
}

// Fetch categories for dropdown
try {
    $categoryStmt = $conn->prepare("SELECT id, category_name FROM category ORDER BY category_name");
    $categoryStmt->execute();
    $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}

// Fetch all products for display
try {
    $productStmt = $conn->prepare("SELECT p.*, c.category_name FROM products p LEFT JOIN category c ON p.category_id = c.id ORDER BY p.created_at DESC");
    $productStmt->execute();
    $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Admin - Sweet Cake</title>
    <link rel="stylesheet" href="/Sweet_Cake/css/admin.css">
    <style>
        .add-product-form {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(44,44,100,0.07);
            max-width: 600px;
            margin: 2rem auto 2rem auto;
        }
        .add-product-form h2 {
            margin-bottom: 1.5rem;
            color: #2d2d44;
        }
        .add-product-form label {
            display: block;
            margin-bottom: 0.5rem;
            color: #44446a;
            font-weight: 500;
        }
        .add-product-form input[type="text"],
        .add-product-form input[type="number"],
        .add-product-form textarea,
        .add-product-form select {
            width: 100%;
            padding: 0.7rem;
            margin-bottom: 1.2rem;
            border: 1px solid #d1d1e0;
            border-radius: 5px;
            font-size: 1rem;
            background: #f7f7fa;
        }
        .add-product-form input[type="file"] {
            margin-bottom: 1.2rem;
        }
        .add-product-form button {
            background: #2d2d44;
            color: #fff;
            border: none;
            padding: 0.7rem 2rem;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .add-product-form button:hover {
            background: #44446a;
        }
        .product-list-section {
            max-width: 900px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(44,44,100,0.07);
            padding: 2rem;
        }
        .product-list-section h2 {
            color: #2d2d44;
            margin-bottom: 1.5rem;
        }        .product-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        .product-table th, .product-table td {
            padding: 0.6rem 0.5rem;
            border-bottom: 1px solid #ececf6;
            text-align: left;
            vertical-align: middle;
        }
        .product-table th {
            background: #f0f1fa;
            color: #2d2d44;
            font-weight: 600;
            white-space: nowrap;
        }
        .product-table td img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
        .delete-btn {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }        .delete-btn:hover {
            background: #c0392b;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .add-product-form {
                margin: 1rem;
                padding: 1.5rem;
            }
            .product-list-section {
                margin: 1rem;
                padding: 1.5rem;
                overflow-x: auto;
            }
            .product-table {
                font-size: 0.8rem;
            }
            .product-table th, 
            .product-table td {
                padding: 0.4rem 0.3rem;
                min-width: 80px;
            }
            .product-table td img {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/admin_nav.php'; ?>    <div class="add-product-form">
        <h2>Add New Product</h2>
        
        <?php if (isset($success_message)): ?>
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #c3e6cb;">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="product_image">Product Image</label>
            <input type="file" name="product_image" id="product_image" accept="image/*" required onchange="previewProductImage(event)">
            <div id="image-preview-container" style="margin-bottom:1.2rem; display:none;">
                <img id="image-preview" src="#" alt="Image Preview" style="max-width:120px; max-height:120px; border-radius:8px; box-shadow:0 1px 4px rgba(44,44,100,0.10); margin-top:0.5rem;" />
            </div>

            <label for="product_name">Product Name</label>
            <input type="text" name="product_name" id="product_name" required>

            <label for="product_price">Price (₹)</label>
            <input type="number" name="product_price" id="product_price" min="0" step="0.01" required>

            <label for="product_category">Category</label>
            <select name="product_category" id="product_category" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="product_qty">Stock Quantity</label>
            <input type="number" name="product_qty" id="product_qty" min="0" required>

            <label for="product_description">Description</label>
            <textarea name="product_description" id="product_description" rows="4" required></textarea>

            <button type="submit" name="add_product">Add Product</button>
        </form>
    </div>    <div class="product-list-section">
        <h2>Manage Products</h2>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Description</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: #888; padding: 2rem;">
                            No products found. Add your first product above.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <?php if ($product['image']): ?>
                                    <img src="../../uploads/products/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <div style="width: 60px; height: 60px; background: #f0f1fa; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #888;">
                                        No Image
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>₹<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td>
                            <td>
                                <span style="<?php echo $product['qty'] <= 5 ? 'color: #e74c3c; font-weight: bold;' : 'color: #27ae60;'; ?>">
                                    <?php echo $product['qty']; ?>
                                    <?php if ($product['qty'] <= 5): ?>
                                        <i class="fas fa-exclamation-triangle" style="margin-left: 0.5rem;"></i>
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($product['description']); ?>">
                                <?php echo htmlspecialchars(substr($product['description'], 0, 50)) . (strlen($product['description']) > 50 ? '...' : ''); ?>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($product['created_at'])); ?></td>
                            <td>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    <button class="delete-btn" name="delete_product" value="<?php echo $product['id']; ?>">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script>
    function previewProductImage(event) {
        const input = event.target;
        const previewContainer = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            previewContainer.style.display = 'none';
        }
    }
    </script>
</body>
</html>