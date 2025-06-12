<?php
 session_start();
 include 'config/db.php';
?> 


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweety Cake - Premium Cake Shop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">    <link rel="stylesheet" href="css/main.css">
    <style>
        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 9999;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            transform: translateX(400px);
            transition: all 0.3s ease;
            max-width: 350px;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification.success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        
        .notification.error {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
        }
        
        .notification.warning {
            background: linear-gradient(135deg, #ffc107, #ff8f00);
        }
        
        .notification.info {
            background: linear-gradient(135deg, #17a2b8, #138496);
        }
        
        .notification .close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Confirmation Modal Styles */
        .confirmation-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .confirmation-modal.show {
            opacity: 1;
            visibility: visible;
        }
        
        .confirmation-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        
        .confirmation-modal.show .confirmation-content {
            transform: scale(1);
        }
        
        .confirmation-icon {
            font-size: 3rem;
            color: var(--primary-pink, #e75480);
            margin-bottom: 20px;
        }
        
        .confirmation-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .confirmation-message {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.5;
        }
        
        .confirmation-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .btn-confirm {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        
        .btn-confirm:hover {
            background: #c82333;
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s ease;
        }
          .btn-cancel:hover {
            background: #5a6268;
        }
        
        /* Categories Section Styles */
        .categories-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .category-card {
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            height: 100%;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        
        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.1);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .category-card:hover::before {
            opacity: 1;
        }
        
        .category-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }
        
        .category-content {
            position: relative;
            z-index: 2;
        }
        
        .category-icon {
            font-size: 3rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 1rem;
        }
        
        .category-title {
            color: white;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 0.8rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .category-description {
            color: rgba(255,255,255,0.9);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        
        .btn-category {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .btn-category:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            border-color: rgba(255,255,255,0.5);
            transform: translateY(-2px);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
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
                        <a class="nav-link" href="./index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./pages/about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./pages/gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./pages/contact.php">Contact</a>
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
                            
                            <a href="./pages/cart.php" class="nav-link p-0 position-relative" title="Cart">
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
                                    <li><a class="dropdown-item" href="./pages/orders.php"><i class="fas fa-box me-2"></i>My Orders</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="showLogoutConfirmation()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>
                            </div>
                            
                            <!-- Admin Dashboard Button (if admin) -->
                            <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                <a href="./pages/admin/dashboard.php">
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
                        <a href="./pages/signin.php">
                            <button class="btn btn-outline-primary ms-3" type="button" style="border-color: var(--primary-pink); color: var(--primary-pink);">
                                Sign In
                            </button>
                        </a>
                    <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="hero-title">Sweet Dreams Come True</h1>
                    <p class="hero-subtitle">Crafting extraordinary cakes that make every celebration unforgettable. From custom designs to classic favorites, we bring sweetness to life.</p>
                    <a href="./pages/gallery.php" class="btn-custom">
                        <i class="fas fa-heart me-2"></i>Explore Our Cakes
                    </a>
                </div>
                <div class="col-lg-6 text-center">
                    <div style="font-size: 12rem; color: rgba(255,255,255,0.2);">
                        <i class="fas fa-birthday-cake"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <h2 class="section-title">Why Choose Sweety Cake?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3 class="feature-title">Premium Quality</h3>
                        <p>We use only the finest ingredients to create cakes that not only look amazing but taste incredible too.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-paint-brush"></i>
                        </div>
                        <h3 class="feature-title">Custom Designs</h3>
                        <p>Every cake is a masterpiece. We work with you to create unique designs that match your vision perfectly.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="feature-title">Fresh Daily</h3>
                        <p>All our cakes are baked fresh daily with love and attention to ensure maximum flavor and quality.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>    <!-- Categories Section -->
    <section id="categories" class="categories-section py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">Browse Our Categories</h2>
            <div class="row g-4">
                <?php
                // Fetch categories from database
                try {
                    $categoryStmt = $conn->prepare("SELECT id, category_name FROM category ORDER BY category_name");
                    $categoryStmt->execute();
                    $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
                      // Define colors and icons for categories
                    $colors = [
                        '#FF6B9D', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', 
                        '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9'
                    ];
                    
                    // Define icons for different category types
                    $categoryIcons = [
                        'birthday' => 'fa-birthday-cake',
                        'wedding' => 'fa-heart',
                        'chocolate' => 'fa-cookie-bite',
                        'fruit' => 'fa-apple-alt',
                        'cupcake' => 'fa-cubes',
                        'cheese' => 'fa-cheese',
                        'anniversary' => 'fa-gift',
                        'custom' => 'fa-magic',
                        'default' => 'fa-birthday-cake'
                    ];
                    
                    function getCategoryIcon($categoryName, $iconMap) {
                        $name = strtolower($categoryName);
                        foreach ($iconMap as $key => $icon) {
                            if (strpos($name, $key) !== false) {
                                return $icon;
                            }
                        }
                        return $iconMap['default'];
                    }
                    
                    $colorIndex = 0;
                    
                    if (!empty($categories)): 
                        foreach ($categories as $category):
                            $bgColor = $colors[$colorIndex % count($colors)];
                            $icon = getCategoryIcon($category['category_name'], $categoryIcons);
                            $colorIndex++;
                ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="category-card" style="background: linear-gradient(135deg, <?php echo $bgColor; ?>, <?php echo $bgColor; ?>CC);">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fas <?php echo $icon; ?>"></i>
                                </div>
                                <h4 class="category-title"><?php echo htmlspecialchars($category['category_name']); ?></h4>
                                <p class="category-description">Explore our delicious collection</p>
                                <a href="./pages/gallery.php?category=<?php echo $category['id']; ?>" class="btn-category">
                                    View Products <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php 
                        endforeach;
                    else:                        // Default categories if database is empty
                        $defaultCategories = [
                            ['name' => 'Birthday Cakes', 'color' => '#FF6B9D', 'icon' => 'fa-birthday-cake'],
                            ['name' => 'Wedding Cakes', 'color' => '#4ECDC4', 'icon' => 'fa-heart'],
                            ['name' => 'Chocolate Cakes', 'color' => '#45B7D1', 'icon' => 'fa-cookie-bite'],
                            ['name' => 'Fruit Cakes', 'color' => '#96CEB4', 'icon' => 'fa-apple-alt'],
                        ];
                        
                        foreach ($defaultCategories as $category):
                ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="category-card" style="background: linear-gradient(135deg, <?php echo $category['color']; ?>, <?php echo $category['color']; ?>CC);">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fas <?php echo $category['icon']; ?>"></i>
                                </div>
                                <h4 class="category-title"><?php echo $category['name']; ?></h4>
                                <p class="category-description">Explore our delicious collection</p>
                                <a href="./pages/gallery.php" class="btn-category">
                                    View Products <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div><?php 
                        endforeach;
                    endif;
                } catch (PDOException $e) {
                    // Default categories if database error
                    $defaultCategories = [
                        ['name' => 'Birthday Cakes', 'color' => '#FF6B9D'],
                        ['name' => 'Wedding Cakes', 'color' => '#4ECDC4'],
                        ['name' => 'Chocolate Cakes', 'color' => '#45B7D1'],
                        ['name' => 'Fruit Cakes', 'color' => '#96CEB4'],
                    ];
                    
                    foreach ($defaultCategories as $category):
                ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="category-card" style="background: linear-gradient(135deg, <?php echo $category['color']; ?>, <?php echo $category['color']; ?>CC);">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fas fa-birthday-cake"></i>
                                </div>
                                <h4 class="category-title"><?php echo $category['name']; ?></h4>
                                <p class="category-description">Explore our delicious collection</p>
                                <a href="./pages/gallery.php" class="btn-category">
                                    View Products <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php 
                    endforeach;
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <h2 class="section-title">Get In Touch</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-card">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="contact-info">
                                    <div class="contact-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <h5>Visit Our Shop</h5>
                                        <p>123 Sweet Street, Cake City<br>Your Location 12345</p>
                                    </div>
                                </div>
                                <div class="contact-info">
                                    <div class="contact-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div>
                                        <h5>Call Us</h5>
                                        <p>+1 (555) 123-CAKE<br>Mon-Sun: 8AM-8PM</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-info">
                                    <div class="contact-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <h5>Email Us</h5>
                                        <p>hello@sweetycake.com<br>orders@sweetycake.com</p>
                                    </div>
                                </div>
                                <div class="contact-info">
                                    <div class="contact-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <h5>Order Ahead</h5>
                                        <p>Custom cakes require<br>48-72 hours notice</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="social-icons">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-pinterest"></i></a>
                    </div>
                    <p>&copy; 2025 Sweety Cake. All rights reserved. Made with <i class="fas fa-heart" style="color: var(--primary-pink);"></i> and lots of sugar!</p>
                </div>
            </div>
        </div>    </footer>

    <!-- Logout Confirmation Modal -->
    <div class="confirmation-modal" id="logoutModal">
        <div class="confirmation-content">
            <div class="confirmation-icon">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <h3 class="confirmation-title">Confirm Logout</h3>
            <p class="confirmation-message">Are you sure you want to logout? You will need to sign in again to access your account.</p>
            <div class="confirmation-buttons">
                <button class="btn-confirm" onclick="confirmLogout()">Yes, Logout</button>
                <button class="btn-cancel" onclick="cancelLogout()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notificationContainer"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>    <script>
        // Logout confirmation functions
        function showLogoutConfirmation() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('show');
        }

        function confirmLogout() {
            // Show logout notification
            showNotification('Logging out...', 'info');
            
            // Redirect to logout after a brief delay
            setTimeout(() => {
                window.location.href = './function/logout.php';
            }, 1000);
        }

        function cancelLogout() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('show');
            showNotification('Logout cancelled', 'warning');
        }

        // Notification function
        function showNotification(message, type = 'info') {
            const container = document.getElementById('notificationContainer');
            
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <button class="close-btn" onclick="closeNotification(this.parentElement)">&times;</button>
                ${message}
            `;
            
            container.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            // Auto hide after 4 seconds
            setTimeout(() => {
                closeNotification(notification);
            }, 4000);
        }

        function closeNotification(notification) {
            if (notification) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.parentElement.removeChild(notification);
                    }
                }, 300);
            }
        }

        // Close modal when clicking outside
        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                cancelLogout();
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background change on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = 'none';
            }
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all feature cards, category cards and gallery items
        document.querySelectorAll('.feature-card, .category-card, .gallery-item').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>