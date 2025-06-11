    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <i class="fas fa-birthday-cake me-2"></i>Sweety Cake
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./contact.php">Contact</a>
                    </li>                    </ul>                
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <!-- Logged-in user navigation -->
                        <div class="d-flex align-items-center ms-3 gap-3">
                            <!-- Cart Icon -->
                            <?php
                            // Get cart count for logged-in user
                            $cartCount = 0;
                            try {
                                $cartCountStmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM cart WHERE user_id = ?");
                                $cartCountStmt->execute([$_SESSION['user_id']]);
                                $cartResult = $cartCountStmt->fetch(PDO::FETCH_ASSOC);
                                $cartCount = $cartResult['total_items'] ?? 0;
                            } catch (PDOException $e) {
                                $cartCount = 0;
                            }
                            ?>
                            
                            <a href="./cart.php" class="nav-link p-0 position-relative" title="Cart">
                                <i class="fas fa-shopping-cart fa-lg"></i>
                                <?php if ($cartCount > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color: var(--primary-pink); font-size: 0.7rem; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center;">
                                        <?php echo $cartCount; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                              <!-- User Dropdown Menu -->
                            <div class="dropdown">
                                <a href="#" class="nav-link p-0" data-bs-toggle="dropdown" aria-expanded="false" title="Account" style="text-decoration: none;">
                                    <i class="fas fa-user fa-lg"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="./orders.php"><i class="fas fa-box me-2"></i>My Orders</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="showLogoutConfirmation()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>
                            </div>
                            
                            <!-- Admin Dashboard Button (if admin) -->
                            <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                <a href="./admin/dashboard.php">
                                    <button class="btn btn-outline-success ms-2" type="button">Dashboard</button>
                                </a>
                            <?php endif; ?>
                            
                            <!-- Welcome Message -->
                            <span class="navbar-text ms-3" style="color: var(--primary-pink);">
                                Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
                            </span>
                        </div>
                        
                    <?php else: ?>
                        <!-- Guest user navigation -->
                        <a href="./signin.php">
                            <button class="btn btn-outline-primary ms-3" type="button" style="border-color: var(--primary-pink); color: var(--primary-pink);">
                                Sign In
                            </button>
                        </a>
                    <?php endif; ?>            </div>
        </div>
    </nav>

    <script>
        function showLogoutConfirmation() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '../function/logout.php';
            }
        }
    </script>