<?php
session_start();
include '../config/db.php';

// Get product ID from URL parameter
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($productId <= 0) {
    header("Location: gallery.php");
    exit();
}

// Fetch product details
try {
    $productStmt = $conn->prepare("SELECT p.*, c.category_name FROM products p 
                                  LEFT JOIN category c ON p.category_id = c.id 
                                  WHERE p.id = ?");
    $productStmt->execute([$productId]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        header("Location: gallery.php");
        exit();
    }
} catch (PDOException $e) {
    header("Location: gallery.php");
    exit();
}

// Fetch related products from the same category
try {
    $relatedStmt = $conn->prepare("SELECT p.*, c.category_name FROM products p 
                                  LEFT JOIN category c ON p.category_id = c.id 
                                  WHERE p.category_id = ? AND p.id != ? 
                                  ORDER BY RAND() LIMIT 3");
    $relatedStmt->execute([$product['category_id'], $productId]);
    $relatedProducts = $relatedStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $relatedProducts = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> | Sweety Cake</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .product-card {
            background: #fff;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }
        
        .product-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .product-card:hover .product-img {
            transform: scale(1.05);
        }
        
        .product-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-chocolate, #2c2c2c);
            margin: 1rem 1rem 0.5rem 1rem;
            line-height: 1.3;
        }
        
        .product-category {
            color: var(--primary-pink, #e75480);
            font-size: 0.85rem;
            font-weight: 500;
            margin: 0 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-pink, #e75480);
            margin: 0.5rem 1rem 1rem 1rem;
        }
        
        .section-title {
            font-family: 'Playfair Display', serif;
            color: var(--dark-chocolate, #2c2c2c);
            font-weight: 700;
            text-align: center;
        }
        
        .btn-quantity:hover {
            background: var(--primary-pink) !important;
            color: white !important;
            transform: scale(1.1);
        }
        
        .btn-add-to-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(255, 107, 157, 0.6) !important;
        }
        
        .btn-wishlist:hover {
            background: var(--primary-pink) !important;
            color: white !important;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .product-details-section {
                padding: 6rem 0 4rem !important;
            }
            
            .product-title {
                font-size: 2rem !important;
            }
            
            .current-price {
                font-size: 1.5rem !important;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include '../includes/nav.php'; ?>

    <!-- Breadcrumb -->
    <nav style="background: var(--cream); padding: 1rem 0; margin-top: 80px;">
        <div class="container">
            <ol class="breadcrumb mb-0" style="background: transparent;">
                <li class="breadcrumb-item"><a href="../index.php" style="color: var(--primary-pink); text-decoration: none;">Home</a></li>
                <li class="breadcrumb-item"><a href="gallery.php" style="color: var(--primary-pink); text-decoration: none;">Gallery</a></li>
                <li class="breadcrumb-item active" style="color: var(--dark-chocolate);"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </div>
    </nav>    <!-- Product Details Section -->
    <section class="product-details-section" style="padding: 4rem 0 6rem; background: var(--gradient-light);">
        <div class="container">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="gallery.php" class="btn" style="background: var(--secondary-pink); color: var(--dark-chocolate); border: none; border-radius: 50px; padding: 0.5rem 1.5rem; text-decoration: none; font-weight: 500;">
                    <i class="fas fa-arrow-left me-2"></i>Back to Gallery
                </a>
            </div>
            
            <div class="row g-5">                <!-- Product Image -->
                <div class="col-lg-6">
                    <div class="product-image-container">
                        <div class="main-image">
                            <?php 
                            $imagePath = !empty($product['image']) ? '../uploads/products/' . $product['image'] : 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=600&q=80';
                            ?>                            <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                 class="img-fluid product-main-img" alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 style="width: 100%; height: 400px; object-fit: cover; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.15);">
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-lg-6">
                    <div class="product-info">                        <!-- Product Title -->
                        <h1 class="product-title mb-3" style="font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 700; color: var(--dark-chocolate);">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h1>                        <!-- Product Category -->
                        <div class="product-category mb-3">
                            <span class="badge" style="background: var(--secondary-pink); color: var(--dark-chocolate); font-size: 0.9rem; padding: 0.5rem 1rem; border-radius: 50px;">
                                <i class="fas fa-birthday-cake me-2"></i><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?>
                            </span>
                        </div>                        <!-- Product Price -->
                        <div class="product-price mb-4">
                            <?php
                            $currentPrice = $product['price'];
                            $originalPrice = $currentPrice * 1.25; // Assuming 20% discount for display
                            $discountPercent = round((($originalPrice - $currentPrice) / $originalPrice) * 100);
                            ?>
                            <span class="current-price" style="font-size: 2rem; font-weight: 700; color: var(--primary-pink);">₹<?php echo number_format($currentPrice, 0); ?></span>
                            <span class="original-price ms-3" style="font-size: 1.2rem; text-decoration: line-through; color: #999;">₹<?php echo number_format($originalPrice, 0); ?></span>
                            <span class="discount-badge ms-3" style="background: var(--accent-gold); color: var(--dark-chocolate); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600;"><?php echo $discountPercent; ?>% OFF</span>
                        </div>                        <!-- Product Description -->
                        <div class="product-description mb-4">
                            <h5 style="color: var(--dark-chocolate); margin-bottom: 1rem;">Description</h5>
                            <p style="color: var(--dark-chocolate); line-height: 1.7;">
                                <?php echo nl2br(htmlspecialchars($product['description'] ?? 'No description available for this product.')); ?>
                            </p>
                        </div>

                        <!-- Stock Information -->
                        <div class="stock-info mb-4">
                            <?php if ($product['qty'] > 0): ?>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle me-2" style="color: #28a745;"></i>
                                    <span style="color: #28a745; font-weight: 600;">In Stock (<?php echo $product['qty']; ?> available)</span>
                                </div>
                                <?php if ($product['qty'] <= 5): ?>
                                    <div class="mt-2">
                                        <small style="color: #e74c3c; font-weight: 500;">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Only <?php echo $product['qty']; ?> left! Order soon.
                                        </small>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-times-circle me-2" style="color: #dc3545;"></i>
                                    <span style="color: #dc3545; font-weight: 600;">Out of Stock</span>
                                </div>
                            <?php endif; ?>
                        </div><!-- Quantity Selector -->
                        <div class="quantity-selector mb-4">
                            <h6 style="color: var(--dark-chocolate); margin-bottom: 1rem;">Quantity</h6>
                            <div class="d-flex align-items-center">
                                <button class="btn-quantity minus" style="background: var(--secondary-pink); border: none; width: 40px; height: 40px; border-radius: 50%; color: var(--dark-chocolate); font-weight: bold;">-</button>
                                <span id="quantity" style="margin: 0 1.5rem; font-size: 1.2rem; font-weight: 600; min-width: 30px; text-align: center;">1</span>
                                <button class="btn-quantity plus" style="background: var(--secondary-pink); border: none; width: 40px; height: 40px; border-radius: 50%; color: var(--dark-chocolate); font-weight: bold;">+</button>
                            </div>
                        </div>                        <!-- Action Buttons -->
                        <div class="product-actions">
                            <?php if ($product['qty'] > 0): ?>
                                <button class="btn-add-to-cart w-100" 
                                        data-product-id="<?php echo $product['id']; ?>"
                                        style="background: var(--gradient-main); color: white; border: none; padding: 1rem 2rem; border-radius: 50px; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(255, 107, 157, 0.4);">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                            <?php else: ?>
                                <button class="btn w-100" disabled
                                        style="background: #ccc; color: #666; border: none; padding: 1rem 2rem; border-radius: 50px; font-weight: 600; font-size: 1.1rem;">
                                    <i class="fas fa-times me-2"></i>Out of Stock
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- Related Products Section -->
    <section class="related-products-section" style="padding: 4rem 0; background: white;">
        <div class="container">
            <h2 class="section-title mb-5">You Might Also Like</h2>
            <div class="row g-4">
                <?php if (!empty($relatedProducts)): ?>
                    <?php foreach ($relatedProducts as $relatedProduct): ?>                        <div class="col-md-4">
                            <div class="product-card">
                                <?php 
                                $relatedImagePath = !empty($relatedProduct['image']) ? '../uploads/products/' . $relatedProduct['image'] : 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=400&q=80';
                                ?>
                                <img src="<?php echo htmlspecialchars($relatedImagePath); ?>" class="product-img" alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>">
                                <div class="product-title"><?php echo htmlspecialchars($relatedProduct['name']); ?></div>
                                <div class="product-category"><?php echo htmlspecialchars($relatedProduct['category_name'] ?? 'Uncategorized'); ?></div>
                                <div class="product-price">₹<?php echo number_format($relatedProduct['price'], 0); ?></div>
                                <a href="details.php?id=<?php echo $relatedProduct['id']; ?>" class="btn btn-sm mt-2" style="background: var(--primary-pink); color: white; border: none; border-radius: 20px; padding: 0.5rem 1rem; text-decoration: none;">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Default fallback products if no related products found -->
                    <div class="col-md-4">
                        <div class="product-card">
                            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Vanilla Cake">
                            <div class="product-title">Classic Vanilla Cake</div>
                            <div class="product-category">Birthday Cakes</div>
                            <div class="product-price">₹899</div>
                            <a href="gallery.php" class="btn btn-sm mt-2" style="background: var(--primary-pink); color: white; border: none; border-radius: 20px; padding: 0.5rem 1rem; text-decoration: none;">Browse More</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <img src="https://images.unsplash.com/photo-1464306076886-debca5e8a6b0?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Fruit Cake">
                            <div class="product-title">Fresh Fruit Cake</div>
                            <div class="product-category">Fruit Cakes</div>
                            <div class="product-price">₹999</div>
                            <a href="gallery.php" class="btn btn-sm mt-2" style="background: var(--primary-pink); color: white; border: none; border-radius: 20px; padding: 0.5rem 1rem; text-decoration: none;">Browse More</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-card">
                            <img src="https://images.unsplash.com/photo-1486427944299-d1955d23e34d?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Red Velvet">
                            <div class="product-title">Red Velvet Delight</div>
                            <div class="product-category">Special Cakes</div>
                            <div class="product-price">₹1,199</div>
                            <a href="gallery.php" class="btn btn-sm mt-2" style="background: var(--primary-pink); color: white; border: none; border-radius: 20px; padding: 0.5rem 1rem; text-decoration: none;">Browse More</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
      <script>
        // Quantity selector functionality
        let quantity = 1;
        const quantitySpan = document.getElementById('quantity');
        const minusBtn = document.querySelector('.btn-quantity.minus');
        const plusBtn = document.querySelector('.btn-quantity.plus');
        
        minusBtn.addEventListener('click', function() {
            if (quantity > 1) {
                quantity--;
                quantitySpan.textContent = quantity;
            }
        });
        
        plusBtn.addEventListener('click', function() {
            quantity++;
            quantitySpan.textContent = quantity;
        });
        
        // Add to cart functionality
        const addToCartBtn = document.querySelector('.btn-add-to-cart');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const currentQuantity = parseInt(quantitySpan.textContent);
                
                // Check if user is logged in
                <?php if (!isset($_SESSION['user_id'])): ?>
                    alert('Please log in to add items to cart');
                    window.location.href = 'signin.php';
                    return;
                <?php endif; ?>
                
                // Disable button during request
                this.disabled = true;
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
                
                // Add to cart via AJAX
                fetch('../function/add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=${currentQuantity}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        this.innerHTML = '<i class="fas fa-check me-2"></i>Added to Cart!';
                        this.style.background = '#28a745';
                        
                        // Reset button after 2 seconds
                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.style.background = 'var(--gradient-main)';
                            this.disabled = false;
                        }, 2000);
                        
                        // Update cart count in navigation
                        const cartBadge = document.querySelector('.badge');
                        if (cartBadge && data.cart_count) {
                            cartBadge.textContent = data.cart_count;
                            cartBadge.style.display = 'inline-block';
                        }
                    } else {
                        alert(data.message || 'Error adding item to cart');
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error adding item to cart');
                    this.innerHTML = originalText;
                    this.disabled = false;
                });
            });
        }
    </script>
</body>
</html>