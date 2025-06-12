<?php
session_start();
require_once '../config/db.php';

$message = '';
$messageType = '';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validation
    $errors = [];
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    }
    
    // If no validation errors, attempt login
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("SELECT id, name, email, password, role, created_at FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                  // Verify password
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_created_at'] = $user['created_at'];
                    
                    $message = 'Welcome back, ' . htmlspecialchars($user['name']) . '!';
                    $messageType = 'success';
                    
                    // Set redirect flag for JavaScript
                    $redirect = true;
                } else {
                    $errors[] = 'Invalid email or password';
                }
            } else {
                $errors[] = 'Invalid email or password';
            }
        } catch (PDOException $e) {
            $errors[] = 'Database error occurred. Please try again.';
        }
    }
      // Set error message if there are errors
    if (!empty($errors)) {
        $message = implode('<br>', $errors);
        $messageType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Sweety Cake</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">    <style>
        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 9999;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            transform: translateX(400px);
            transition: all 0.3s ease;
            max-width: 350px;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification.success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        
        .notification.error {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
        }
        
        .notification .close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body {
            background: linear-gradient(135deg, #fff0f6 0%, #ffe3ec 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        .signin-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            gap: 0;
        }
        .signin-visual {
            flex: 1.2;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(120deg, #ffe3ec 60%, #fff0f6 100%);
            border-radius: 2rem 0 0 2rem;
            min-height: 420px;
            position: relative;
            overflow: hidden;
        }
        .signin-visual img {
            max-width: 480px;
            width: 100%;
            border-radius: 1.5rem;
            box-shadow: 0 8px 40px rgba(231,84,128,0.13);
            object-fit: cover;
            z-index: 2;
        }
        .signin-visual::before {
            content: '';
            position: absolute;
            top: 10%;
            left: 10%;
            width: 80%;
            height: 80%;
            background: rgba(231,84,128,0.07);
            border-radius: 2rem;
            z-index: 1;
        }
        .signin-card {
            flex: 1;
            background: #fff;
            border-radius: 0 2rem 2rem 0;
            box-shadow: 0 8px 40px rgba(0,0,0,0.10);
            padding: 2.2rem 1.2rem 1.2rem 1.2rem;
            max-width: 320px;
            min-width: 220px;
            margin: 0;
            min-height: 350px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        .signin-card .fa-birthday-cake {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }
        .signin-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-pink, #e75480);
            font-weight: 700;
            margin-bottom: 1.2rem;
            text-align: center;
            letter-spacing: 1px;
            font-size: 1.3rem;
        }
        .form-label {
            font-weight: 500;
            color: #d13c6a;
            font-size: 0.98rem;
        }
        .form-control {
            border-radius: 0.7rem;
            border: 1.5px solid #ffe3ec;
            background: #fff8fa;
            font-size: 0.98rem;
            padding: 0.5rem 0.8rem;
        }
        .form-control:focus {
            border-color: var(--primary-pink, #e75480);
            box-shadow: 0 0 0 0.2rem rgba(231,84,128,0.13);
        }
        .btn-theme {
            background: var(--primary-pink, #e75480);
            color: #fff;
            border: none;
            border-radius: 0.7rem;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.55rem 0;
            margin-top: 0.5rem;
            box-shadow: 0 2px 12px rgba(231,84,128,0.10);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-theme:hover {
            background: #d13c6a;
            box-shadow: 0 4px 24px rgba(231,84,128,0.18);
            color: #fff;
        }
        .signin-footer {
            text-align: center;
            margin-top: 1.2rem;
            color: #888;
            font-size: 0.95rem;
        }        .signin-footer a {
            color: var(--primary-pink, #e75480);
            text-decoration: underline;
            font-weight: 500;
        }
        @media (max-width: 900px) {
            .signin-container {
                flex-direction: column;
                padding: 2rem 0;
            }
            .signin-visual, .signin-card {
                border-radius: 2rem;
                max-width: 100%;
                min-width: unset;
            }
            .signin-visual {
                margin-bottom: 2rem;
            }
            .signin-card {
                max-width: 340px;
                min-width: 180px;
                padding: 1.5rem 0.7rem 1rem 0.7rem;
            }
            .signin-visual img {
                max-width: 320px;
            }
        }
        @media (max-width: 600px) {
            .signin-card {
                padding: 1.2rem 0.3rem 0.7rem 0.3rem;
                max-width: 98vw;
            }
            .signin-visual img {
                max-width: 180px;
            }
        }
    </style>
</head>
<body>
    <div class="container signin-container">
        <div class="signin-visual">
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=600&q=80" alt="Cake Sign In">
        </div>
        <div class="signin-card">
            <div class="text-center mb-3">
                <i class="fas fa-birthday-cake" style="color: var(--primary-pink, #e75480);"></i>
            </div>            <h2 class="signin-title">Sign In to Sweety Cake</h2>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                </div>                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
                </div>
                <button type="submit" class="btn btn-theme w-100">Sign In</button>
            </form>
            <div class="signin-footer">
                Don't have an account? <a href="./signup.php">Sign up</a>
            </div>        </div>
    </div>

    <!-- Notification -->
    <?php if (!empty($message)): ?>
    <div class="notification <?php echo $messageType; ?>" id="notification">
        <button class="close-btn" onclick="closeNotification()">&times;</button>
        <?php echo $message; ?>
    </div>
    <?php endif; ?>

    <script>
        // Show notification
        <?php if (!empty($message)): ?>
        window.addEventListener('DOMContentLoaded', function() {
            const notification = document.getElementById('notification');
            if (notification) {
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100);
                
                // Auto hide after 5 seconds
                setTimeout(() => {
                    closeNotification();
                }, 5000);
            }
        });
        <?php endif; ?>

        function closeNotification() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }

        // Redirect to index page after successful login
        <?php if (isset($redirect) && $redirect): ?>
        setTimeout(() => {
            window.location.href = '../index.php';
        }, 2000);
        <?php endif; ?>
    </script>
</body>
</html>
