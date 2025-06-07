<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Sweety Cake</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <!-- Navigation -->
    <?php include '../includes/nav.php'; ?>

    <!-- Contact Hero Section -->
    <section class="gallery-hero text-center d-flex align-items-center justify-content-center" style="min-height: 32vh; background: var(--gradient-main); color: white; padding-top: 110px; padding-bottom: 30px;">
        <div class="container">
            <h1 class="hero-title mb-3" style="font-size: 3rem;">Contact Us</h1>
            <p class="hero-subtitle" style="font-size: 1.25rem; max-width: 600px; margin: 0 auto;">We'd love to hear from you! Reach out for orders, questions, or just to say hello.</p>
        </div>
    </section>

    <!-- Contact Form & Info -->
    <section class="contact-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="contact-card">
                        <h2 class="section-title" style="font-size:2rem; margin-bottom:2rem;">Send Us a Message</h2>
                        <form>
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Your Email" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Subject">
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                            </div>
                            <button type="submit" class="btn-custom w-100">Send Message</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 d-flex align-items-stretch">
                    <div class="contact-card d-flex flex-column justify-content-center" style="background: var(--cream);">
                        <h2 class="section-title" style="font-size:2rem; margin-bottom:2rem;">Contact Details</h2>
                        <div class="contact-info mb-3">
                            <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <h5>Visit Our Shop</h5>
                                <p>123 Sweet Street, Cake City<br>Your Location 12345</p>
                            </div>
                        </div>
                        <div class="contact-info mb-3">
                            <div class="contact-icon"><i class="fas fa-phone"></i></div>
                            <div>
                                <h5>Call Us</h5>
                                <p>+1 (555) 123-CAKE<br>Mon-Sun: 8AM-8PM</p>
                            </div>
                        </div>
                        <div class="contact-info mb-3">
                            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                            <div>
                                <h5>Email Us</h5>
                                <p>hello@sweetycake.com<br>orders@sweetycake.com</p>
                            </div>
                        </div>
                        <div class="contact-info">
                            <div class="contact-icon"><i class="fas fa-calendar-alt"></i></div>
                            <div>
                                <h5>Order Ahead</h5>
                                <p>Custom cakes require<br>48-72 hours notice</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer mt-5">
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
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
