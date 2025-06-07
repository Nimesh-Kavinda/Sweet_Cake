<nav class="admin-navbar">
        <div class="logo">Sweet Cake Admin</div>
        <button class="admin-nav-toggle" aria-label="Toggle navigation" onclick="document.querySelector('.admin-navbar ul').classList.toggle('show')">
            <span></span><span></span><span></span>
        </button>
        <ul>
            <li><a href="./dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
            <li><a href="./add_product.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add_product.php' ? 'active' : ''; ?>">Add Products</a></li>
            <li><a href="./add_category.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add_category.php' ? 'active' : ''; ?>">Add Categories</a></li>
            <li><a href="./manage_orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_orders.php' ? 'active' : ''; ?>">Manage Orders</a></li>
            <li><a href="./manage_users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>">Manage Users</a></li>
            <li style="margin-left:auto;"><a href="#" style="background:#e74c3c; color:#fff; border-radius:4px; padding:0.5rem 1.2rem;">Logout</a></li>
        </ul>
        <style>
            .admin-navbar {
                position: relative;
            }
            .admin-navbar ul {
                display: flex;
                align-items: center;
                margin: 0;
                padding: 0;
                list-style: none;
                transition: all 0.3s;
            }
            .admin-navbar .admin-nav-toggle {
                display: none;
                background: none;
                border: none;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 40px;
                width: 40px;
                cursor: pointer;
                margin-left: auto;
            }
            .admin-navbar .admin-nav-toggle span {
                display: block;
                width: 26px;
                height: 3px;
                background: #fff;
                margin: 4px 0;
                border-radius: 2px;
                transition: 0.3s;
            }
            @media (max-width: 900px) {
                .admin-navbar ul {
                    flex-direction: column;
                    position: absolute;
                    top: 60px;
                    left: 0;
                    width: 100%;
                    background: #2d2d44;
                    display: none;
                    z-index: 100;
                }
                .admin-navbar ul.show {
                    display: flex;
                }
                .admin-navbar .admin-nav-toggle {
                    display: flex;
                }
                .admin-navbar ul li {
                    width: 100%;
                    text-align: left;
                    margin: 0;
                    border-bottom: 1px solid #44446a;
                }
                .admin-navbar ul li:last-child {
                    border-bottom: none;
                }
                .admin-navbar ul li a {
                    display: block;
                    width: 100%;
                    padding: 1rem 2rem;
                }
                .admin-navbar .logo {
                    padding: 0 1rem;
                }
            }
        </style>
    </nav>