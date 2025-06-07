<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Sweety Cake</title>
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
        .signup-flex {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            gap: 0;
        }
        .signup-visual {
            flex: 1.1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(120deg, #ffe3ec 60%, #fff0f6 100%);
            border-radius: 2rem 0 0 2rem;
            min-height: 320px;
            position: relative;
            overflow: hidden;
        }
        .signup-visual img {
            max-width: 320px;
            width: 100%;
            border-radius: 1.5rem;
            box-shadow: 0 8px 40px rgba(231,84,128,0.13);
            object-fit: cover;
            z-index: 2;
        }
        .signup-visual::before {
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
        .signup-card {
            flex: 1;
            background: #fff;
            border-radius: 0 2rem 2rem 0;
            box-shadow: 0 8px 40px rgba(0,0,0,0.10);
            padding: 1.5rem 1.2rem 1.2rem 1.2rem;
            max-width: 320px;
            min-width: 220px;
            margin: 0;
            min-height: 320px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        .signup-card .fa-user-plus {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .signup-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-pink, #e75480);
            font-weight: 700;
            margin-bottom: 1rem;
            text-align: center;
            letter-spacing: 1px;
            font-size: 1.2rem;
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
        .signup-footer {
            text-align: center;
            margin-top: 1.2rem;
            color: #888;
            font-size: 0.95rem;
        }
        .signup-footer a {
            color: var(--primary-pink, #e75480);
            text-decoration: underline;
            font-weight: 500;
        }
        @media (max-width: 900px) {
            .signup-flex {
                flex-direction: column;
                padding: 2rem 0;
            }
            .signup-visual, .signup-card {
                border-radius: 2rem;
                max-width: 100%;
                min-width: unset;
            }
            .signup-visual {
                margin-bottom: 2rem;
            }
            .signup-card {
                max-width: 340px;
                min-width: 180px;
                padding: 1.2rem 0.7rem 1rem 0.7rem;
            }
        }
        @media (max-width: 600px) {
            .signup-card {
                padding: 1.2rem 0.3rem 0.7rem 0.3rem;
                max-width: 98vw;
            }
            .signup-visual img {
                max-width: 140px;
            }
        }
    </style>
</head>
<body>
    <div class="container signup-flex">
        <div class="signup-visual">
            <img src="https://images.unsplash.com/photo-1519864600258-abb23847ef2c?auto=format&fit=crop&w=600&q=80" alt="Cake Sign Up">
        </div>
        <div class="signup-card">
            <div class="text-center mb-3">
                <i class="fas fa-user-plus" style="color: var(--primary-pink, #e75480);"></i>
            </div>
            <h2 class="signup-title">Create Your Account</h2>
            <form method="post" action="#">
                <div class="mb-2">
                    <label for="username" class="form-label">User Name</label>
                    <input type="text" class="form-control" id="username" name="username" required placeholder="Enter your name">
                </div>
                <div class="mb-2">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
                </div>
                <div class="mb-2">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required placeholder="Enter your phone number">
                </div>
                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Create a password">
                </div>
                <div class="mb-2">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Confirm your password">
                </div>
                <button type="submit" class="btn btn-theme w-100">Sign Up</button>
            </form>
            <div class="signup-footer">
                Already have an account? <a href="./signin.php">Sign in</a>
            </div>
        </div>
    </div>
</body>
</html>
