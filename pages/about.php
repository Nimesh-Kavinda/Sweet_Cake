<?php
session_start();
require_once '../config/db.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Sweety Cake</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <!-- Navigation -->
  <?php include '../includes/nav.php'; ?>

    <!-- About Hero Section -->
    <section class="hero-section d-flex align-items-center" style="min-height: 60vh;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 hero-content">
                    <h1 class="hero-title">About Sweety Cake</h1>
                    <p class="hero-subtitle">At Sweety Cake, we believe every celebration deserves a touch of sweetness. Our passion is crafting cakes that are as beautiful as they are delicious, using only the finest ingredients and a sprinkle of creativity.</p>
                </div>
                <div class="col-lg-5 text-center">
                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80" alt="Cake" class="img-fluid rounded shadow" style="max-height: 300px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="about-section">
        <div class="container">
            <h2 class="section-title">Our Story</h2>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="feature-card" style="background: var(--cream);">
                        <p style="font-size: 1.2rem;">
                            Founded in 2015, Sweety Cake started as a small family bakery with a big dream: to make every occasion memorable with the perfect cake. Over the years, our team has grown, but our commitment to quality, creativity, and customer happiness remains at the heart of everything we do. From whimsical birthday cakes to elegant wedding masterpieces, we pour love and artistry into every creation.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Meet the Team Section -->
    <section class="gallery-section" style="background: var(--secondary-pink);">
        <div class="container">
            <h2 class="section-title">Meet Our Team</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon"><i class="fas fa-user-tie"></i></div>
                        <h3 class="feature-title">Nisha Patel</h3>
                        <p>Founder & Master Baker</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon"><i class="fas fa-user-astronaut"></i></div>
                        <h3 class="feature-title">Rahul Mehra</h3>
                        <p>Creative Designer</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon"><i class="fas fa-user-nurse"></i></div>
                        <h3 class="feature-title">Priya Singh</h3>
                        <p>Pastry Chef</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
