<?php
session_start();
include '../config/db.php';

// Fetch all categories for the filter dropdown
try {
    $categoryStmt = $conn->prepare("SELECT id, category_name FROM category ORDER BY category_name");
    $categoryStmt->execute();
    $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}

// Get filter parameters
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'all';
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build the query based on filters
$sql = "SELECT p.*, c.category_name FROM products p 
        LEFT JOIN category c ON p.category_id = c.id 
        WHERE 1=1";
$params = [];

if ($selectedCategory !== 'all') {
    $sql .= " AND p.category_id = ?";
    $params[] = $selectedCategory;
}

if (!empty($searchTerm)) {
    $sql .= " AND p.name LIKE ?";
    $params[] = "%{$searchTerm}%";
}

$sql .= " ORDER BY p.created_at DESC";

try {
    $productStmt = $conn->prepare($sql);
    $productStmt->execute($params);
    $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | Sweety Cake</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">    <link rel="stylesheet" href="../css/main.css">
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
            transform: translateY(-8px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }
        
        .product-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .product-card:hover .product-img {
            transform: scale(1.05);
        }
        
        .product-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-chocolate, #2c2c2c);
            margin: 1rem 1rem 0.5rem 1rem;
            line-height: 1.3;
        }
        
        .product-category {
            color: var(--primary-pink, #e75480);
            font-size: 0.9rem;
            font-weight: 500;
            margin: 0 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-pink, #e75480);
            margin: 0.5rem 1rem;
        }
        
        .product-actions {
            margin: 0 1rem 1rem 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            margin-top: auto;
        }
        
        .stock-warning {
            margin: 0 1rem 1rem 1rem;
            padding: 0.3rem 0.8rem;
            background: rgba(231, 76, 60, 0.1);
            border-left: 3px solid #e74c3c;
            border-radius: 0 5px 5px 0;
        }
        
        .no-products {
            background: #fff;
            border-radius: 2rem;
            padding: 3rem 2rem;
            text-align: center;
            margin: 2rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        @media (max-width: 768px) {
            .product-card {
                margin-bottom: 1.5rem;
            }
            
            .product-img {
                height: 200px;
            }
            
            .product-actions {
                flex-direction: column;
            }
            
            .filter-bar {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 1rem !important;
            }
            
            .filter-bar > div {
                justify-content: center !important;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include '../includes/nav.php'; ?>

    <!-- Gallery Hero Section -->
    <section class="gallery-hero text-center d-flex align-items-center justify-content-center" style="min-height: 32vh; background: var(--gradient-main); color: white; padding-top: 110px; padding-bottom: 30px;">
        <div class="container">
            <h1 class="hero-title mb-3" style="font-size: 3rem;">Our Sweet Cakes</h1>
            <p class="hero-subtitle" style="font-size: 1.25rem; max-width: 600px; margin: 0 auto;">Browse our delicious cakes. Filter by category or search for your favorite treat!</p>
        </div>
    </section>    <!-- Filter/Search Bar -->
    <div class="container">
        <div class="filter-bar mt-0 mb-4" style="background: var(--cream); border-radius: 20px; box-shadow: 0 4px 16px rgba(255,107,157,0.08); padding: 1.5rem 2rem; margin-top: -3rem; margin-bottom: 2.5rem; display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;">
            <div class="d-flex align-items-center gap-2">
                <label for="categoryFilter" class="me-2 mb-0"><i class="fas fa-filter"></i> Category:</label>
                <select id="categoryFilter" class="form-select" style="border-radius: 50px; border: 1px solid var(--secondary-pink); min-width: 180px;" onchange="filterProducts()">
                    <option value="all" <?php echo $selectedCategory === 'all' ? 'selected' : ''; ?>>All</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $selectedCategory == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="searchInput" class="me-2 mb-0"><i class="fas fa-search"></i> Search:</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search cakes..." style="border-radius: 50px; border: 1px solid var(--secondary-pink); min-width: 180px;" value="<?php echo htmlspecialchars($searchTerm); ?>" onkeyup="filterProducts()">
            </div>
        </div>
    </div>    <!-- Product Gallery -->
    <div class="container">
        <div class="row g-4" id="productGallery">            <?php if (empty($products)): ?>
                <div class="col-12">
                    <div class="no-products">
                        <i class="fas fa-search" style="font-size: 4rem; color: var(--primary-pink, #e75480); margin-bottom: 1rem;"></i>
                        <h3 style="color: var(--dark-chocolate, #2c2c2c); margin-bottom: 1rem;">No products found</h3>
                        <p style="color: #888; font-size: 1.1rem;">Try adjusting your search criteria or browse all products.</p>
                        <a href="gallery.php" class="btn" style="background: var(--primary-pink, #e75480); color: white; border: none; border-radius: 25px; padding: 0.75rem 2rem; margin-top: 1rem; text-decoration: none;">
                            <i class="fas fa-refresh me-2"></i>Show All Products
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 product-item" data-category="<?php echo $product['category_id']; ?>" data-name="<?php echo strtolower($product['name']); ?>">
                        <div class="product-card">
                            <img src="<?php echo !empty($product['image']) ? '../uploads/products/' . htmlspecialchars($product['image']) : 'https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=400&q=80'; ?>" 
                                 class="product-img" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 onerror="this.src='https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=400&q=80'">
                            <div class="product-title"><?php echo htmlspecialchars($product['name']); ?></div>
                            <div class="product-category"><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></div>
                            <div class="product-price">₹<?php echo number_format($product['price'], 0); ?></div>
                            <div class="product-actions mt-3">
                                <button class="btn btn-sm" style="background: var(--primary-pink); color: white; border: none; border-radius: 20px; padding: 0.5rem 1rem; margin: 0.2rem;" onclick="viewProduct(<?php echo $product['id']; ?>)">
                                    <i class="fas fa-eye me-1"></i> View Details
                                </button>
                                <?php if ($product['qty'] > 0): ?>
                                    <button class="btn btn-sm" style="background: var(--secondary-pink); color: var(--dark-chocolate); border: none; border-radius: 20px; padding: 0.5rem 1rem; margin: 0.2rem;" onclick="addToCart(<?php echo $product['id']; ?>)">
                                        <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-sm" style="background: #ccc; color: #666; border: none; border-radius: 20px; padding: 0.5rem 1rem; margin: 0.2rem;" disabled>
                                        <i class="fas fa-times me-1"></i> Out of Stock
                                    </button>
                                <?php endif; ?>
                            </div>
                            <?php if ($product['qty'] <= 5 && $product['qty'] > 0): ?>
                                <div class="stock-warning mt-2" style="color: #e74c3c; font-size: 0.85rem; text-align: center;">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Only <?php echo $product['qty']; ?> left!
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
  <?php include '../includes/footer.php'; ?>    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter products function
        function filterProducts() {
            const category = document.getElementById('categoryFilter').value;
            const search = document.getElementById('searchInput').value;
            
            // Update URL with current filters
            const url = new URL(window.location);
            if (category === 'all') {
                url.searchParams.delete('category');
            } else {
                url.searchParams.set('category', category);
            }
            
            if (search.trim() === '') {
                url.searchParams.delete('search');
            } else {
                url.searchParams.set('search', search);
            }
            
            // Reload page with new parameters
            window.location.href = url.toString();
        }

        // Real-time search with debounce
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterProducts();
            }, 1000); // Wait 1 second after user stops typing
        });

        // View product details
        function viewProduct(productId) {
            window.location.href = `details.php?id=${productId}`;
        }        // Add to cart function
        function addToCart(productId) {
            <?php if (isset($_SESSION['user_id'])): ?>
                // User is logged in, add to cart
                fetch('../function/add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=1`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Product added to cart successfully!', 'success');
                    } else {
                        showNotification(data.message || 'Failed to add product to cart', 'error');
                    }
                })
                .catch(error => {
                    showNotification('An error occurred. Please try again.', 'error');
                });
            <?php else: ?>
                // User not logged in, redirect to signin
                showNotification('Please sign in to add products to cart', 'warning');
                setTimeout(() => {
                    window.location.href = 'signin.php';
                }, 2000);
            <?php endif; ?>
        }

        // Simple notification function
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'warning'} position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

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

        // Observe all product cards
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.product-card').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.6s ease';
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
