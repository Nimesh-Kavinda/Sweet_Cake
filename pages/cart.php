<?php
session_start();
include '../config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Sweety Cake</title>
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
        .cart-section {
            padding: 3.5rem 0 2rem 0;
            margin-top: 80px;
        }
        .cart-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-pink, #e75480);
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            letter-spacing: 1px;
        }
        .cart-table {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 4px 32px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .cart-table th, .cart-table td {
            text-align: center;
        }
        .cart-table th {
            font-size: 1.08rem;
            padding: 1rem 0.5rem;
        }
        .cart-table td {
            font-size: 1.05rem;
            padding: 1.1rem 0.5rem;
        }
        .cart-img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 1.2rem;
            box-shadow: 0 2px 8px rgba(231,84,128,0.10);
            margin: 0 auto;
            display: block;
        }
        .cart-table td:nth-child(2) {
            text-align: left;
            font-weight: 600;
            color: #d13c6a;
        }
        .cart-table td:nth-child(3),
        .cart-table td:nth-child(5) {
            color: #e75480;
            font-weight: 500;
        }
        .cart-table input[type="number"] {
            text-align: center;
            border-radius: 0.5rem;
            border: 1.5px solid #ffe3ec;
            background: #fff8fa;
            width: 70px;
            margin: 0 auto;
        }
        .cart-remove {
            color: var(--primary-pink, #e75480);
            cursor: pointer;
            font-size: 1.2rem;
        }
        .cart-remove:hover {
            color: #d13c6a;
        }
        .cart-summary {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 2px 16px rgba(231,84,128,0.08);
            padding: 2rem 1.5rem;
            margin-top: 2rem;
        }
        .cart-summary-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-pink, #e75480);
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
        }
        .btn-theme {
            background: var(--primary-pink, #e75480);
            color: #fff;
            border: none;
            border-radius: 0.7rem;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.7rem 0;
            margin-top: 0.5rem;
            box-shadow: 0 2px 12px rgba(231,84,128,0.10);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-theme:hover {
            background: #d13c6a;
            box-shadow: 0 4px 24px rgba(231,84,128,0.18);
        }
        @media (max-width: 900px) {
            .cart-section {
                padding: 2rem 0 1rem 0;
            }
            .cart-summary {
                margin-top: 1.2rem;
            }
            .cart-img {
                width: 60px;
                height: 60px;
            }
            .cart-table th, .cart-table td {
                font-size: 0.98rem;
                padding: 0.7rem 0.2rem;
            }
        }
    </style>
</head>
<body>

<?php include '../includes/nav.php'; ?>

    <section class="cart-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cart-table p-3 p-md-4">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example cart item -->
                                <tr>
                                    <td><img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=80&q=80" class="cart-img" alt="Cake"></td>
                                    <td>Chocolate Dream Cake</td>
                                    <td>$25.00</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" value="1" min="1" style="width: 70px;">
                                    </td>
                                    <td>$25.00</td>
                                    <td><span class="cart-remove"><i class="fas fa-trash"></i></span></td>
                                </tr>
                                <tr>
                                    <td><img src="https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=80&q=80" class="cart-img" alt="Cake"></td>
                                    <td>Red Velvet Cupcake</td>
                                    <td>$5.00</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" value="2" min="1" style="width: 70px;">
                                    </td>
                                    <td>$10.00</td>
                                    <td><span class="cart-remove"><i class="fas fa-trash"></i></span></td>
                                </tr>
                                <!-- End example cart item -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <div class="cart-summary-title">Order Summary</div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>$35.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery</span>
                            <span>$3.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong>$38.00</strong>
                        </div>
                        <button class="btn btn-theme w-100"><i class="fas fa-credit-card me-2"></i>Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
