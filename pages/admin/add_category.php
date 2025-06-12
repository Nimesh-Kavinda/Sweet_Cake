<?php
session_start();
require_once '../../config/db.php';

// Handle form submission
if (isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);
    
    if (!empty($category_name)) {
        try {
            // Check if category already exists
            $checkStmt = $conn->prepare("SELECT id FROM category WHERE category_name = ?");
            $checkStmt->execute([$category_name]);
            
            if ($checkStmt->rowCount() > 0) {
                $error_message = "Category already exists!";
            } else {
                // Insert new category
                $stmt = $conn->prepare("INSERT INTO category (category_name) VALUES (?)");
                $stmt->execute([$category_name]);
                $success_message = "Category added successfully!";
            }
        } catch (PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    } else {
        $error_message = "Category name cannot be empty!";
    }
}

// Handle category deletion
if (isset($_POST['delete_category'])) {
    $category_id = intval($_POST['delete_category']);
    try {
        // Check if any products are using this category
        $checkProductsStmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ?");
        $checkProductsStmt->execute([$category_id]);
        $productCount = $checkProductsStmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        if ($productCount > 0) {
            $error_message = "Cannot delete category. It has {$productCount} products associated with it.";
        } else {
            // Delete category
            $deleteStmt = $conn->prepare("DELETE FROM category WHERE id = ?");
            $deleteStmt->execute([$category_id]);
            $success_message = "Category deleted successfully!";
        }
    } catch (PDOException $e) {
        $error_message = "Error deleting category: " . $e->getMessage();
    }
}

// Fetch all categories
try {
    $categoryStmt = $conn->prepare("SELECT c.*, COUNT(p.id) as product_count FROM category c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id ORDER BY c.category_name");
    $categoryStmt->execute();
    $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category | Admin - Sweet Cake</title>
    <link rel="stylesheet" href="/Sweet_Cake/css/admin.css">
    <style>
        .add-category-form {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(44,44,100,0.07);
            max-width: 400px;
            margin: 2rem auto 2rem auto;
        }
        .add-category-form h2 {
            margin-bottom: 1.5rem;
            color: #2d2d44;
        }
        .add-category-form label {
            display: block;
            margin-bottom: 0.5rem;
            color: #44446a;
            font-weight: 500;
        }
        .add-category-form input[type="text"] {
            width: 100%;
            padding: 0.7rem;
            margin-bottom: 1.2rem;
            border: 1px solid #d1d1e0;
            border-radius: 5px;
            font-size: 1rem;
            background: #f7f7fa;
        }
        .add-category-form button {
            background: #2d2d44;
            color: #fff;
            border: none;
            padding: 0.7rem 2rem;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .add-category-form button:hover {
            background: #44446a;
        }
        .category-list-section {
            max-width: 500px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(44,44,100,0.07);
            padding: 2rem;
        }
        .category-list-section h2 {
            color: #2d2d44;
            margin-bottom: 1.5rem;
        }
        .category-table {
            width: 100%;
            border-collapse: collapse;
        }
        .category-table th, .category-table td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid #ececf6;
            text-align: left;
        }
        .category-table th {
            background: #f0f1fa;
            color: #2d2d44;
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
        .delete-btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
            opacity: 0.6;
        }
        .delete-btn:disabled:hover {
            background: #bdc3c7;
        }
    </style>
</head>
<body>

        <?php include './includes/admin_nav.php'; ?>    <div class="add-category-form">
        <h2>Add New Category</h2>
        
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
        
        <form action="" method="POST">
            <label for="category_name">Category Name</label>
            <input type="text" name="category_name" id="category_name" required>
            <button type="submit" name="add_category">Add Category</button>
        </form>
    </div>    <div class="category-list-section">
        <h2>Manage Categories</h2>
        <table class="category-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Products</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #888; padding: 2rem;">
                            No categories found. Add your first category above.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo $category['id']; ?></td>
                            <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                            <td>
                                <span style="<?php echo $category['product_count'] > 0 ? 'color: #27ae60; font-weight: bold;' : 'color: #888;'; ?>">
                                    <?php echo $category['product_count']; ?> products
                                </span>
                            </td>
                            <td>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    <button class="delete-btn" name="delete_category" value="<?php echo $category['id']; ?>" 
                                            <?php echo $category['product_count'] > 0 ? 'disabled title="Cannot delete: has products"' : ''; ?>>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
