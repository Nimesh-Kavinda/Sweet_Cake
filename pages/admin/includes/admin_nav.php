  <nav class="admin-navbar">
        <div class="logo">Sweet Cake Admin</div>
        <ul>
            <li><a href="./dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
            <li><a href="./add_product.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add_product.php' ? 'active' : ''; ?>">Add Products</a></li>
            <li><a href="./add_category.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add_category.php' ? 'active' : ''; ?>">Add Categories</a></li>
            <li><a href="./manage_orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_orders.php' ? 'active' : ''; ?>">Manage Orders</a></li>
            <li><a href="./manage_users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>">Manage Users</a></li>
        </ul>
    </nav>