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
$searchTerm = ''; // Always empty - search is now client-side only

// Build the query based on category filter only
$sql = "SELECT p.*, c.category_name FROM products p 
        LEFT JOIN category c ON p.category_id = c.id 
        WHERE 1=1";
$params = [];

if ($selectedCategory !== 'all') {
    $sql .= " AND p.category_id = ?";
    $params[] = $selectedCategory;
}

// Remove search functionality from server-side
// Search is now handled entirely on the client-side

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
        
        /* Beautiful Custom Alerts */
        .sweet-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            min-width: 320px;
            padding: 20px 25px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
            transform: translateX(500px);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            opacity: 0;
            border: 2px solid transparent;
        }
        
        .sweet-alert.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .sweet-alert.success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-color: #27ae60;
            color: #155724;
        }
        
        .sweet-alert.update {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-color: #f39c12;
            color: #856404;
        }
        
        .sweet-alert.error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-color: #e74c3c;
            color: #721c24;
        }
        
        .sweet-alert.warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
            border-color: #ffc107;
            color: #856404;
        }
        
        .alert-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .alert-icon {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 14px;
        }
        
        .success .alert-icon {
            background: #27ae60;
            color: white;
        }
        
        .update .alert-icon {
            background: #f39c12;
            color: white;
        }
        
        .error .alert-icon {
            background: #e74c3c;
            color: white;
        }
        
        .warning .alert-icon {
            background: #ffc107;
            color: #856404;
        }
        
        .alert-message {
            font-size: 0.95rem;
            line-height: 1.4;
            margin-bottom: 12px;
        }
        
        .alert-details {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-bottom: 15px;
        }
        
        .alert-close {
            position: absolute;
            top: 8px;
            right: 12px;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.2s;
            color: inherit;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .alert-close:hover {
            opacity: 1;
        }
        
        .alert-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }
        
        .alert-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }
        
        .btn-view-cart {
            background: var(--primary-pink);
            color: white;
        }
          .btn-view-cart:hover {
            background: #d13c6a;
            transform: translateY(-1px);
        }
        
        /* Mobile Responsive Alerts */
        @media (max-width: 480px) {
            .sweet-alert {
                top: 10px;
                right: 10px;
                left: 10px;
                max-width: none;
                min-width: auto;
                transform: translateY(-200px);
            }
            
            .sweet-alert.show {
                transform: translateY(0);
            }
            
            .alert-header {
                font-size: 1rem;
            }
            
            .alert-message {
                font-size: 0.9rem;
            }
            
            .alert-details {
                font-size: 0.8rem;
            }
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
            </div>            <div class="d-flex align-items-center gap-2">
                <label for="searchInput" class="me-2 mb-0"><i class="fas fa-search"></i> Search:</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search cakes..." style="border-radius: 50px; border: 1px solid var(--secondary-pink); min-width: 180px;" value="<?php echo htmlspecialchars($searchTerm); ?>">
            </div>
        </div>
    </div>    <!-- Category Breadcrumb (if coming from category) -->
    <?php if (isset($_GET['category']) && $_GET['category'] !== 'all'): 
        $categoryName = '';
        foreach ($categories as $cat) {
            if ($cat['id'] == $_GET['category']) {
                $categoryName = $cat['category_name'];
                break;
            }
        }
        if ($categoryName):
    ?>
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); border-radius: 15px; padding: 1rem 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <li class="breadcrumb-item">
                    <a href="../index.php#categories" style="color: var(--primary-pink); text-decoration: none;">
                        <i class="fas fa-home me-2"></i>Categories
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page" style="color: var(--dark-chocolate);">
                    <i class="fas fa-birthday-cake me-2"></i><?php echo htmlspecialchars($categoryName); ?>
                </li>
            </ol>
        </nav>
    </div>
    <?php endif; endif; ?>    <!-- Product Gallery -->
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
    <script>        // Filter products function - ONLY for category changes (page reload)
        function filterProducts() {
            const category = document.getElementById('categoryFilter').value;
            
            // Update URL with current category filter only
            const url = new URL(window.location);
            if (category === 'all') {
                url.searchParams.delete('category');
            } else {
                url.searchParams.set('category', category);
            }
            
            // Remove search from URL when changing category
            url.searchParams.delete('search');
            
            // Reload page with new category
            window.location.href = url.toString();
        }        // Client-side search function - NO page reload
        function filterProductsClientSide() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const productCards = document.querySelectorAll('.product-item');
            let visibleCount = 0;
            
            productCards.forEach(card => {
                const productName = card.getAttribute('data-name');
                const shouldShow = searchTerm === '' || productName.includes(searchTerm);
                
                if (shouldShow) {
                    card.style.display = 'block';
                    card.style.opacity = '1';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Handle no results case
            const existingNoResults = document.querySelector('.search-no-results');
            if (existingNoResults) {
                existingNoResults.remove();
            }
            
            if (visibleCount === 0 && searchTerm.length > 0) {
                // Show "no results" message
                const gallery = document.getElementById('productGallery');
                const noResultsDiv = document.createElement('div');
                noResultsDiv.className = 'col-12 search-no-results';
                noResultsDiv.innerHTML = `
                    <div class="no-products">
                        <i class="fas fa-search" style="font-size: 4rem; color: var(--primary-pink, #e75480); margin-bottom: 1rem;"></i>
                        <h3 style="color: var(--dark-chocolate, #2c2c2c); margin-bottom: 1rem;">No products found</h3>
                        <p style="color: #888; font-size: 1.1rem;">No products match your search "<strong>${searchTerm}</strong>".</p>
                        <button class="btn" style="background: var(--primary-pink, #e75480); color: white; border: none; border-radius: 25px; padding: 0.75rem 2rem; margin-top: 1rem;" onclick="clearSearch()">
                            <i class="fas fa-times me-2"></i>Clear Search
                        </button>
                    </div>
                `;
                gallery.appendChild(noResultsDiv);
            }
            
            // Update search input styling
            const searchInput = document.getElementById('searchInput');
            if (searchTerm.length > 0) {
                searchInput.style.borderColor = 'var(--primary-pink)';
                searchInput.style.backgroundColor = '#fff8fa';
            } else {
                searchInput.style.borderColor = 'var(--secondary-pink)';
                searchInput.style.backgroundColor = '#f7f7fa';
            }
        }        // Clear search function
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            
            // Remove no results message
            const noResults = document.querySelector('.search-no-results');
            if (noResults) {
                noResults.remove();
            }
            
            // Show all products
            document.querySelectorAll('.product-item').forEach(card => {
                card.style.display = 'block';
                card.style.opacity = '1';
            });
            
            // Reset search input styling
            const searchInput = document.getElementById('searchInput');
            searchInput.style.borderColor = 'var(--secondary-pink)';
            searchInput.style.backgroundColor = '#f7f7fa';
        }// Real-time search with debounce - CLIENT SIDE ONLY
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterProductsClientSide();
            }, 300); // Faster response, no page reload
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
                .then(response => response.json())                .then(data => {
                    if (data.success) {
                        // Update cart count in navigation
                        updateNavCartCount(data.cart_count);
                        
                        if (data.is_update) {
                            // Product quantity was updated (same product added again)
                            showSweetAlert('update', 'Product Updated!', data.message, {
                                product: data.product_name,
                                quantity: data.quantity,
                                cartCount: data.cart_count
                            });
                        } else {
                            // New product added to cart
                            showSweetAlert('success', 'Added to Cart!', data.message, {
                                product: data.product_name,
                                quantity: data.quantity,
                                cartCount: data.cart_count
                            });
                        }
                    } else {
                        showSweetAlert('error', 'Failed to Add!', data.message);
                    }
                })
                .catch(error => {
                    showSweetAlert('error', 'Error Occurred!', 'An error occurred. Please try again.');
                });
            <?php else: ?>
                // User not logged in, redirect to signin
                showSweetAlert('warning', 'Sign In Required!', 'Please sign in to add products to cart', {}, () => {
                    setTimeout(() => {
                        window.location.href = 'signin.php';
                    }, 2000);
                });
            <?php endif; ?>
        }        // Beautiful Sweet Alert System
        function showSweetAlert(type, title, message, details = {}, callback = null) {
            // Remove any existing alerts
            const existingAlert = document.querySelector('.sweet-alert');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            // Create alert element
            const alert = document.createElement('div');
            alert.className = `sweet-alert ${type}`;
            
            // Get appropriate icon
            let icon = '';
            switch(type) {
                case 'success':
                    icon = '<i class="fas fa-check"></i>';
                    break;
                case 'update':
                    icon = '<i class="fas fa-sync-alt"></i>';
                    break;
                case 'error':
                    icon = '<i class="fas fa-times"></i>';
                    break;
                case 'warning':
                    icon = '<i class="fas fa-exclamation"></i>';
                    break;
            }
            
            // Build alert content
            let alertContent = `
                <button class="alert-close" onclick="this.parentElement.remove()">×</button>
                <div class="alert-header">
                    <div class="alert-icon">${icon}</div>
                    ${title}
                </div>
                <div class="alert-message">${message}</div>
            `;
            
            // Add details if provided
            if (details.product) {
                alertContent += `
                    <div class="alert-details">
                        <strong>${details.product}</strong><br>
                        Quantity: ${details.quantity} | Cart Total: ${details.cartCount} items
                    </div>
                `;
            }
            
            // Add action buttons for cart operations
            if (type === 'success' || type === 'update') {
                alertContent += `
                    <div class="alert-actions">
                        <button class="alert-btn btn-view-cart" onclick="window.location.href='cart.php'">
                            <i class="fas fa-shopping-cart me-1"></i>View Cart
                        </button>
                    </div>
                `;
            }
            
            alert.innerHTML = alertContent;
            document.body.appendChild(alert);
            
            // Trigger animation
            setTimeout(() => {
                alert.classList.add('show');
            }, 100);
            
            // Auto remove after 6 seconds
            setTimeout(() => {
                if (alert.parentElement) {
                    alert.classList.remove('show');
                    setTimeout(() => {
                        if (alert.parentElement) {
                            alert.remove();
                        }
                    }, 400);
                }
            }, 6000);
            
            // Execute callback if provided
            if (callback) {
                callback();
            }
        }        // Legacy notification function for backward compatibility
        function showNotification(message, type) {
            showSweetAlert(type, type === 'success' ? 'Success!' : type === 'error' ? 'Error!' : 'Notice!', message);
        }

        // Update cart count in navigation
        function updateNavCartCount(count) {
            const cartLink = document.querySelector('a[href="./cart.php"]');
            if (cartLink) {
                const badge = cartLink.querySelector('.badge');
                if (count > 0) {
                    if (badge) {
                        badge.textContent = count;
                    } else {
                        // Create badge if it doesn't exist
                        const newBadge = document.createElement('span');
                        newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill';
                        newBadge.style.cssText = 'background-color: var(--primary-pink); font-size: 0.7rem; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center;';
                        newBadge.textContent = count;
                        cartLink.appendChild(newBadge);
                    }
                } else {
                    // Remove badge if count is 0
                    if (badge) {
                        badge.remove();
                    }
                }
            }
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
