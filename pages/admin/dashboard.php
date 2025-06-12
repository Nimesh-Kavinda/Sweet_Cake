<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Sweet Cake</title>
    <link rel="stylesheet" href="/Sweet_Cake/css/admin.css">
</head>
<body>
  <?php include './includes/admin_nav.php'; ?>
    <div class="admin-dashboard-container">
        <div class="admin-dashboard-title">Welcome, Admin!</div>
        <div class="admin-cards">
            <div class="admin-card">
                <h3>Add Products</h3>
                <p>Add new cakes and products to your store.</p>
            </div>
            <div class="admin-card">
                <h3>Add Categories</h3>
                <p>Organize your products by categories.</p>
            </div>            <div class="admin-card">
                <h3>Manage Orders</h3>
                <p>View and process customer orders.</p>
            </div>
            <div class="admin-card">
                <h3>Manage Users</h3>
                <p>View and manage registered users.</p>
            </div>
            <div class="admin-card">
                <h3><a href="manage_messages.php" style="text-decoration: none; color: inherit;">Contact Messages</a></h3>
                <p>View and respond to customer messages.</p>
            </div>
        </div>
    </div>
</body>
</html>