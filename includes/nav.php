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
                    </li>                
                    </ul>                
                      <?php 

                    if(isset ($_SESSION['user_id'])) {
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

                        echo '     <div class="d-flex align-items-center ms-3 gap-3">
                    <a href="./cart.php" class="nav-link p-0 position-relative" title="Cart">
                        <i class="fas fa-shopping-cart fa-lg"></i>';
                        
                        if ($cartCount > 0) {
                            echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color: var(--primary-pink); font-size: 0.7rem; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center;">' . $cartCount . '</span>';
                        }
                        
                        echo '</a>
                    <a href="./wishlist.php" class="nav-link p-0" title="Wishlist"><i class="fas fa-heart fa-lg"></i></a>
                    </div>';
                    }
                ?>


                    <?php
                if(!isset($_SESSION['user_id'])) {
                    // Show Sign Up and Sign In buttons for guests
                    echo '<a href="./signin.php"><button class="btn btn-outline-primary ms-3" id="signInBtn" type="button" style="border-color: var(--primary-pink); color: var(--primary-pink);">Sign In</button></a>';
                } else {
                    // Show user info and logout button for logged-in users
                    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                        echo '<a href="./admin/dashboard.php"><button class="btn btn-outline-success ms-3" id="adminDashboardBtn" type="button">Dashboard</button></a>';
                    } 
                    else{
                        echo '<button class="btn btn-outline-danger ms-3" id="logoutBtn" type="button" onclick="showLogoutConfirmation()">Logout</button>';
                    }
                    
                    echo '<span class="navbar-text ms-3" style="color: var(--primary-pink);">Welcome, ' . htmlspecialchars($_SESSION['user_name']) . '!</span>';
                }
                ?>
            </div>
        </div>
    </nav>