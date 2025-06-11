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
                    <a href="./pages/cart.php" class="nav-link p-0 position-relative" title="Cart">
                        <i class="fas fa-shopping-cart fa-lg"></i>';
                        
                        if ($cartCount > 0) {
                            echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color: var(--primary-pink); font-size: 0.7rem; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center;">' . $cartCount . '</span>';
                        }
                        
                   
                    }
                ?>


                    <?php
                if(!isset($_SESSION['user_id'])) {
                    // Show Sign Up and Sign In buttons for guests
                    echo '<a href="./pages/signin.php"><button class="btn btn-outline-primary ms-3" id="signInBtn" type="button" style="border-color: var(--primary-pink); color: var(--primary-pink);">Sign In</button></a>';
                } else {
                    // Show user info and logout button for logged-in users
                    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                        echo '<a href="./pages/admin/dashboard.php"><button class="btn btn-outline-success ms-3" id="adminDashboardBtn" type="button">Dashboard</button></a>';
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
                    <a href="#gallery" class="btn-custom">
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
    </section>

    <!-- Testimonials Section -->
  <section id="testimonials" class="testimonial-section py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-5">What Our Customers Say</h2>
        <div class="row">
            <!-- Testimonial 1 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text mb-4">"The birthday cake was absolutely delicious! The design was exactly what I wanted and it tasted even better than it looked. Will definitely order again!"</p>
                        <div class="d-flex align-items-center">
                            
                            <div>
                                <h6 class="mb-0">Sarah Johnson</h6>
                                <small class="text-muted">Happy Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       
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
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all feature cards and gallery items
        document.querySelectorAll('.feature-card, .gallery-item').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>