<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | Sweety Cake</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
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
    </section>

    <!-- Filter/Search Bar -->
    <div class="container">
        <div class="filter-bar mt-0 mb-4" style="background: var(--cream); border-radius: 20px; box-shadow: 0 4px 16px rgba(255,107,157,0.08); padding: 1.5rem 2rem; margin-top: -3rem; margin-bottom: 2.5rem; display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;">
            <div class="d-flex align-items-center gap-2">
                <label for="categoryFilter" class="me-2 mb-0"><i class="fas fa-filter"></i> Category:</label>
                <select id="categoryFilter" class="form-select" style="border-radius: 50px; border: 1px solid var(--secondary-pink); min-width: 180px;">
                    <option value="all">All</option>
                    <option value="Birthday">Birthday Cakes</option>
                    <option value="Wedding">Wedding Cakes</option>
                    <option value="Chocolate">Chocolate Cakes</option>
                    <option value="Fruit">Fruit Cakes</option>
                    <option value="Special">Special Cakes</option>
                </select>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="searchInput" class="me-2 mb-0"><i class="fas fa-search"></i> Search:</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search cakes..." style="border-radius: 50px; border: 1px solid var(--secondary-pink); min-width: 180px;">
            </div>
        </div>
    </div>

    <!-- Product Gallery -->
    <div class="container">
        <div class="row g-4" id="productGallery">
            <!-- Example Product Cards -->
            <div class="col-md-4 product-item" data-category="Birthday">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Birthday Cake">
                    <div class="product-title">Rainbow Birthday Cake</div>
                    <div class="product-category">Birthday Cakes</div>
                    <div class="product-price">₹799</div>
                </div>
            </div>
            <div class="col-md-4 product-item" data-category="Wedding">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1505250469679-203ad9ced0cb?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Wedding Cake">
                    <div class="product-title">Classic Wedding Cake</div>
                    <div class="product-category">Wedding Cakes</div>
                    <div class="product-price">₹2499</div>
                </div>
            </div>
            <div class="col-md-4 product-item" data-category="Chocolate">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Chocolate Cake">
                    <div class="product-title">Signature Chocolate Truffle</div>
                    <div class="product-category">Chocolate Cakes</div>
                    <div class="product-price">₹1499</div>
                </div>
            </div>
            <div class="col-md-4 product-item" data-category="Fruit">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1464306076886-debca5e8a6b0?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Fruit Cake">
                    <div class="product-title">Fresh Fruit Cake</div>
                    <div class="product-category">Fruit Cakes</div>
                    <div class="product-price">₹999</div>
                </div>
            </div>
            <div class="col-md-4 product-item" data-category="Special">
                <div class="product-card">
                    <img src="https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=400&q=80" class="product-img" alt="Special Cake">
                    <div class="product-title">Anniversary Special Cake</div>
                    <div class="product-category">Special Cakes</div>
                    <div class="product-price">₹1299</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
  <?php include '../includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Category filter
        document.getElementById('categoryFilter').addEventListener('change', function() {
            filterProducts();
        });
        // Search filter
        document.getElementById('searchInput').addEventListener('input', function() {
            filterProducts();
        });
        function filterProducts() {
            const category = document.getElementById('categoryFilter').value;
            const search = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('.product-item').forEach(function(item) {
                const itemCategory = item.getAttribute('data-category');
                const title = item.querySelector('.product-title').textContent.toLowerCase();
                const showByCategory = (category === 'all' || itemCategory === category);
                const showBySearch = (title.includes(search));
                if (showByCategory && showBySearch) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
