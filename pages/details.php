<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details | Sweety Cake</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <!-- Navigation -->
    <?php include '../includes/nav.php'; ?>

    <!-- Product Details Section -->
    <section class="product-details-section" style="padding: 8rem 0 6rem; background: var(--gradient-light);">
        <div class="container">
            <div class="row g-5">
                <!-- Product Image -->                <div class="col-lg-6">
                    <div class="product-image-container">
                        <div class="main-image">
                            <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=600&q=80" 
                                 class="img-fluid product-main-img" alt="Chocolate Birthday Cake" 
                                 style="width: 100%; height: 400px; object-fit: cover; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.15);">
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-lg-6">
                    <div class="product-info">
                        <!-- Product Title -->
                        <h1 class="product-title mb-3" style="font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 700; color: var(--dark-chocolate);">
                            Deluxe Chocolate Birthday Cake
                        </h1>                        <!-- Product Category -->
                        <div class="product-category mb-3">
                            <span class="badge" style="background: var(--secondary-pink); color: var(--dark-chocolate); font-size: 0.9rem; padding: 0.5rem 1rem; border-radius: 50px;">
                                <i class="fas fa-birthday-cake me-2"></i>Birthday Cakes
                            </span>
                        </div>

                        <!-- Product Price -->
                        <div class="product-price mb-4">
                            <span class="current-price" style="font-size: 2rem; font-weight: 700; color: var(--primary-pink);">₹1,299</span>
                            <span class="original-price ms-3" style="font-size: 1.2rem; text-decoration: line-through; color: #999;">₹1,599</span>
                            <span class="discount-badge ms-3" style="background: var(--accent-gold); color: var(--dark-chocolate); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">19% OFF</span>
                        </div>

                        <!-- Product Description -->
                        <div class="product-description mb-4">
                            <h5 style="color: var(--dark-chocolate); margin-bottom: 1rem;">Description</h5>
                            <p style="color: var(--dark-chocolate); line-height: 1.7;">
                                Indulge in our signature Deluxe Chocolate Birthday Cake - a masterpiece of rich, moist chocolate layers filled with 
                                premium dark chocolate ganache and topped with silky smooth chocolate buttercream. Decorated with edible gold accents 
                                and fresh berries, this cake is perfect for making birthdays truly special.
                            </p>
                        </div>

                        <!-- Product Specifications -->
                        <div class="product-specs mb-4">
                            <h5 style="color: var(--dark-chocolate); margin-bottom: 1rem;">Specifications</h5>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="spec-item">
                                        <i class="fas fa-weight-hanging me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Weight:</strong> 1 kg
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="spec-item">
                                        <i class="fas fa-users me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Serves:</strong> 8-10 people
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="spec-item">
                                        <i class="fas fa-clock me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Prep Time:</strong> 24 hours
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="spec-item">
                                        <i class="fas fa-leaf me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Type:</strong> Eggless available
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="quantity-selector mb-4">
                            <h6 style="color: var(--dark-chocolate); margin-bottom: 1rem;">Quantity</h6>
                            <div class="d-flex align-items-center">
                                <button class="btn-quantity" onclick="decreaseQuantity()" style="background: var(--secondary-pink); border: none; width: 40px; height: 40px; border-radius: 50%; color: var(--dark-chocolate); font-weight: bold;">-</button>
                                <span id="quantity" style="margin: 0 1.5rem; font-size: 1.2rem; font-weight: 600; min-width: 30px; text-align: center;">1</span>
                                <button class="btn-quantity" onclick="increaseQuantity()" style="background: var(--secondary-pink); border: none; width: 40px; height: 40px; border-radius: 50%; color: var(--dark-chocolate); font-weight: bold;">+</button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="product-actions">
                            <div class="row g-3">
                                <div class="col-12 col-md-8">
                                    <button class="btn-add-to-cart w-100" onclick="addToCart()" 
                                            style="background: var(--gradient-main); color: white; border: none; padding: 1rem 2rem; border-radius: 50px; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(255, 107, 157, 0.4);">
                                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                    </button>
                                </div>
                                <div class="col-12 col-md-4">
                                    <button class="btn-wishlist w-100" onclick="toggleWishlist()" 
                                            style="background: white; color: var(--primary-pink); border: 2px solid var(--primary-pink); padding: 1rem; border-radius: 50px; font-weight: 600; transition: all 0.3s ease;">
                                        <i id="wishlistIcon" class="far fa-heart me-2"></i><span id="wishlistText">Wishlist</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="additional-info mt-4">
                            <div class="info-badges d-flex flex-wrap gap-2">
                                <span class="info-badge" style="background: var(--cream); color: var(--dark-chocolate); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem;">
                                    <i class="fas fa-truck me-2"></i>Free Delivery
                                </span>
                                <span class="info-badge" style="background: var(--cream); color: var(--dark-chocolate); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem;">
                                    <i class="fas fa-undo me-2"></i>Easy Returns
                                </span>
                                <span class="info-badge" style="background: var(--cream); color: var(--dark-chocolate); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem;">
                                    <i class="fas fa-shield-alt me-2"></i>Quality Guaranteed
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products Section -->
    <section class="related-products-section" style="padding: 4rem 0; background: white;">
        <div class="container">
            <h2 class="section-title mb-5">You Might Also Like</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Vanilla Cake">
                        <div class="product-title">Classic Vanilla Cake</div>
                        <div class="product-category">Birthday Cakes</div>
                        <div class="product-price">₹899</div>
                        <button class="btn btn-sm mt-2" style="background: var(--primary-pink); color: white; border: none; border-radius: 20px; padding: 0.5rem 1rem;">View Details</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="https://images.unsplash.com/photo-1464306076886-debca5e8a6b0?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Fruit Cake">
                        <div class="product-title">Fresh Fruit Cake</div>
                        <div class="product-category">Fruit Cakes</div>
                        <div class="product-price">₹999</div>
                        <button class="btn btn-sm mt-2" style="background: var(--primary-pink); color: white; border: none; border-radius: 20px; padding: 0.5rem 1rem;">View Details</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="https://images.unsplash.com/photo-1486427944299-d1955d23e34d?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Red Velvet">
                        <div class="product-title">Red Velvet Delight</div>
                        <div class="product-category">Special Cakes</div>
                        <div class="product-price">₹1,199</div>
                        <button class="btn btn-sm mt-2" style="background: var(--primary-pink); color: white; border: none; border-radius: 20px; padding: 0.5rem 1rem;">View Details</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
      <script>
        // Quantity functionality
        let currentQuantity = 1;

        function increaseQuantity() {
            currentQuantity++;
            document.getElementById('quantity').textContent = currentQuantity;
        }

        function decreaseQuantity() {
            if (currentQuantity > 1) {
                currentQuantity--;
                document.getElementById('quantity').textContent = currentQuantity;
            }
        }

        // Add to cart functionality
        function addToCart() {
            // Here you would typically send data to server
            const button = document.querySelector('.btn-add-to-cart');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-check me-2"></i>Added to Cart!';
            button.style.background = 'var(--accent-gold)';
            button.style.color = 'var(--dark-chocolate)';
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = 'var(--gradient-main)';
                button.style.color = 'white';
            }, 2000);
            
            // Show notification
            showNotification('Product added to cart successfully!', 'success');
        }

        // Wishlist functionality
        let isInWishlist = false;

        function toggleWishlist() {
            const icon = document.getElementById('wishlistIcon');
            const text = document.getElementById('wishlistText');
            const button = document.querySelector('.btn-wishlist');
            
            if (isInWishlist) {
                icon.className = 'far fa-heart me-2';
                text.textContent = 'Wishlist';
                button.style.background = 'white';
                button.style.color = 'var(--primary-pink)';
                showNotification('Removed from wishlist', 'info');
            } else {
                icon.className = 'fas fa-heart me-2';
                text.textContent = 'In Wishlist';
                button.style.background = 'var(--primary-pink)';
                button.style.color = 'white';
                showNotification('Added to wishlist!', 'success');
            }
            
            isInWishlist = !isInWishlist;
        }

        // Notification system
        function showNotification(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.style.cssText = `
                position: fixed;
                top: 100px;
                right: 20px;
                background: ${type === 'success' ? 'var(--accent-gold)' : 'var(--primary-pink)'};
                color: var(--dark-chocolate);
                padding: 1rem 1.5rem;
                border-radius: 10px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                z-index: 1000;
                font-weight: 600;
                animation: slideIn 0.3s ease;
            `;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Add animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            
            .btn-add-to-cart:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 30px rgba(255, 107, 157, 0.6);
            }
            
            .btn-wishlist:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(255, 107, 157, 0.3);
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>