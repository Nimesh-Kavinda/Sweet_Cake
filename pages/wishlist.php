<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist - Sweety Cake</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
    <style>
        body {
            background: linear-gradient(135deg, #fff0f6 0%, #ffe3ec 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        .wishlist-section {
            padding: 3.5rem 0 2rem 0;
            margin-top: 70px;
        }
        .wishlist-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-pink, #e75480);
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            letter-spacing: 1px;
        }
        .wishlist-table {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 4px 32px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .wishlist-table th, .wishlist-table td {
            text-align: center;
        }
        .wishlist-table th {
            background: #ffe3ec;
            color: #d13c6a;
            font-weight: 600;
            border: none;
            font-size: 1.08rem;
            padding: 1rem 0.5rem;
        }
        .wishlist-table td {
            font-size: 1.05rem;
            padding: 1.1rem 0.5rem;
            vertical-align: middle;
            border-top: 1.5px solid #fff0f6;
        }
        .wishlist-img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 1.2rem;
            box-shadow: 0 2px 8px rgba(231,84,128,0.10);
            margin: 0 auto;
            display: block;
        }
        .wishlist-table td:nth-child(2) {
            text-align: left;
            font-weight: 600;
            color: #d13c6a;
        }
        .wishlist-table td:nth-child(3) {
            color: #e75480;
            font-weight: 500;
        }
        .wishlist-action {
            color: var(--primary-pink, #e75480);
            cursor: pointer;
            font-size: 1.2rem;
            margin: 0 0.3rem;
        }
        .wishlist-action:hover {
            color: #d13c6a;
        }
        .empty-wishlist {
            text-align: center;
            color: #d13c6a;
            font-size: 1.2rem;
            margin: 3rem 0 2rem 0;
        }
        @media (max-width: 900px) {
            .wishlist-section {
                padding: 2rem 0 1rem 0;
            }
            .wishlist-img {
                width: 60px;
                height: 60px;
            }
            .wishlist-table th, .wishlist-table td {
                font-size: 0.98rem;
                padding: 0.7rem 0.2rem;
            }
        }
    </style>
</head>
<body>


    <?php include '../includes/nav.php'; ?>


    <section class="wishlist-section">
        <div class="container">
            <div class="wishlist-table p-3 p-md-4 mb-4">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Example wishlist item -->
                        <tr>
                            <td><img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=80&q=80" class="wishlist-img" alt="Cake"></td>
                            <td>Chocolate Dream Cake</td>
                            <td>$25.00</td>
                            <td>
                                <span class="wishlist-action" title="Add to Cart"><i class="fas fa-cart-plus"></i></span>
                                <span class="wishlist-action" title="Remove"><i class="fas fa-trash"></i></span>
                            </td>
                        </tr>
                        <tr>
                            <td><img src="https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=80&q=80" class="wishlist-img" alt="Cake"></td>
                            <td>Red Velvet Cupcake</td>
                            <td>$5.00</td>
                            <td>
                                <span class="wishlist-action" title="Add to Cart"><i class="fas fa-cart-plus"></i></span>
                                <span class="wishlist-action" title="Remove"><i class="fas fa-trash"></i></span>
                            </td>
                        </tr>
                        <!-- End example wishlist item -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
