<?php
session_start();
include '../config/db.php';
?>


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
                <div class="col-lg-6 mb-4 mb-lg-0">                    <div class="contact-card">
                        <h2 class="section-title" style="font-size:2rem; margin-bottom:2rem;">Send Us a Message</h2>
                        
                        <!-- Alert container for messages -->
                        <div id="contactAlert" class="alert" style="display: none; border-radius: 15px; margin-bottom: 1.5rem;"></div>
                        
                        <form id="contactForm">
                            <div class="mb-3">
                                <label for="contactName" class="form-label">Name *</label>
                                <input type="text" id="contactName" name="name" class="form-control" placeholder="Your Name" required 
                                       style="border-radius: 12px; border: 2px solid #e1e5e9; padding: 0.75rem 1rem; transition: all 0.3s ease;"
                                       value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="contactEmail" class="form-label">Email *</label>
                                <input type="email" id="contactEmail" name="email" class="form-control" placeholder="Your Email" required
                                       style="border-radius: 12px; border: 2px solid #e1e5e9; padding: 0.75rem 1rem; transition: all 0.3s ease;"
                                       value="<?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="contactSubject" class="form-label">Subject</label>
                                <input type="text" id="contactSubject" name="subject" class="form-control" placeholder="Subject"
                                       style="border-radius: 12px; border: 2px solid #e1e5e9; padding: 0.75rem 1rem; transition: all 0.3s ease;">
                            </div>
                            <div class="mb-3">
                                <label for="contactMessage" class="form-label">Message *</label>
                                <textarea id="contactMessage" name="message" class="form-control" rows="5" placeholder="Your Message" required
                                          style="border-radius: 12px; border: 2px solid #e1e5e9; padding: 0.75rem 1rem; transition: all 0.3s ease; resize: vertical;"></textarea>
                            </div>
                            <button type="submit" id="submitBtn" class="btn-custom w-100" style="position: relative;">
                                <span id="btnText">Send Message</span>
                                <span id="btnLoader" class="spinner-border spinner-border-sm" style="display: none;" role="status" aria-hidden="true"></span>
                            </button>
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
   <?php include '../includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Contact Form Styles */
        .form-control:focus {
            border-color: var(--primary-pink) !important;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 157, 0.25) !important;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-chocolate);
            margin-bottom: 0.5rem;
        }
        
        .contact-card {
            background: #fff;
            border-radius: 2rem;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            height: 100%;
        }
        
        .contact-info {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .contact-icon {
            width: 50px;
            height: 50px;
            background: var(--gradient-main);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .contact-info h5 {
            margin-bottom: 0.5rem;
            color: var(--dark-chocolate);
            font-weight: 600;
        }
        
        .contact-info p {
            margin: 0;
            color: #666;
            line-height: 1.5;
        }
        
        #submitBtn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
    
    <script>
        document.getElementById('contactForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            const alertDiv = document.getElementById('contactAlert');
            
            // Show loading state
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoader.style.display = 'inline-block';
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch('../function/submit_contact.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                // Show alert
                alertDiv.style.display = 'block';
                
                if (result.success) {
                    alertDiv.className = 'alert alert-success';
                    alertDiv.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>
                                <strong>Success!</strong> ${result.message}
                            </div>
                        </div>
                    `;
                    
                    // Reset form
                    this.reset();
                    
                    // Auto-hide success message after 5 seconds
                    setTimeout(() => {
                        alertDiv.style.display = 'none';
                    }, 5000);
                    
                } else {
                    alertDiv.className = 'alert alert-danger';
                    
                    let errorMessage = `
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                            <div>
                                <strong>Error!</strong> ${result.message}
                    `;
                    
                    if (result.errors && result.errors.length > 0) {
                        errorMessage += '<ul class="mb-0 mt-2">';
                        result.errors.forEach(error => {
                            errorMessage += `<li>${error}</li>`;
                        });
                        errorMessage += '</ul>';
                    }
                    
                    errorMessage += '</div></div>';
                    alertDiv.innerHTML = errorMessage;
                }
                
            } catch (error) {
                console.error('Error:', error);
                alertDiv.style.display = 'block';
                alertDiv.className = 'alert alert-danger';
                alertDiv.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <strong>Error!</strong> Something went wrong. Please try again later.
                        </div>
                    </div>
                `;
            } finally {
                // Reset button state
                submitBtn.disabled = false;
                btnText.style.display = 'inline';
                btnLoader.style.display = 'none';
            }
        });
        
        // Add smooth focus effects for form inputs
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 15px rgba(255, 107, 157, 0.2)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'translateY(0)';
                if (!this.matches(':focus')) {
                    this.style.boxShadow = 'none';
                }
            });
        });
    </script>
</body>
</html>
