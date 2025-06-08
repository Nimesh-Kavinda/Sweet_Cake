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
        }
        .delete-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>

        <?php include './includes/admin_nav.php'; ?>

    <div class="add-category-form">
        <h2>Add New Category</h2>
        <form id="" autocomplete="off">
            <label for="category_name">Category Name</label>
            <input type="text" name="category_name" id="category_name" required>
            <button type="submit">Add Category</button>
        </form>
    </div>
    <div class="category-list-section">
        <h2>All Categories</h2>
        <table class="category-table" id="categoryTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="3" style="text-align:center; color:#888;">No categories added yet.</td></tr>
            </tbody>
        </table>
    </div>
</body>
</html>
