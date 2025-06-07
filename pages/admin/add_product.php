<?php
// ...existing code...
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
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
        }
        .product-table th, .product-table td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid #ececf6;
            text-align: left;
        }
        .product-table th {
            background: #f0f1fa;
            color: #2d2d44;
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
        }
        .delete-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <?php include '../admin/includes/admin_nav.php'; ?>
    <div class="add-product-form">
        <h2>Add New Product</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="product_image">Product Image</label>
            <input type="file" name="product_image" id="product_image" accept="image/*" required onchange="previewProductImage(event)">
            <div id="image-preview-container" style="margin-bottom:1.2rem; display:none;">
                <img id="image-preview" src="#" alt="Image Preview" style="max-width:120px; max-height:120px; border-radius:8px; box-shadow:0 1px 4px rgba(44,44,100,0.10); margin-top:0.5rem;" />
            </div>

            <label for="product_name">Product Name</label>
            <input type="text" name="product_name" id="product_name" required>

            <label for="product_price">Price</label>
            <input type="number" name="product_price" id="product_price" min="0" step="0.01" required>

            <label for="product_category">Category</label>
            <select name="product_category" id="product_category" required>
                <option value="">Select Category</option>
                <option value="Cakes">Cakes</option>
                <option value="Pastries">Pastries</option>
                <option value="Cookies">Cookies</option>
                <option value="Breads">Breads</option>
                <!-- Add more categories as needed -->
            </select>

            <label for="product_description">Description</label>
            <textarea name="product_description" id="product_description" rows="4" required></textarea>

            <button type="submit" name="add_product">Add Product</button>
        </form>
    </div>
    <div class="product-list-section">
        <h2>Added Products</h2>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example static row, replace with PHP loop for dynamic data -->
                <tr>
                    <td><img src="/Sweet_Cake/images/sample_cake.jpg" alt="Sample Cake"></td>
                    <td>Chocolate Cake</td>
                    <td>$15.00</td>
                    <td>Cakes</td>
                    <td>Rich chocolate cake with creamy frosting.</td>
                    <td><form method="POST" style="display:inline;"><button class="delete-btn" name="delete_product" value="1">Delete</button></form></td>
                </tr>
                <!-- End static row -->
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