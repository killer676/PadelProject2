<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Book padel courts online easily. Join our platform for convenient court reservations.">
    <title>Padel Court Booking System</title>
    <link rel="stylesheet" href="style.css">
    <style>
       
    </style>
</head>
<body>


<header>
    <h1>Padel Court Booking</h1>
    <nav>
        <?php if(isset($_SESSION['user_id'])): ?>
            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="admin.php">Admin Dashboard</a>
            <?php else: ?>
                <a href="userpage.php">My Account</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>

    <main>
        <section class="hero-section">
            <div class="hero-content">
                <h2>Experience Premium Padel Courts</h2>
                <p>Join our community of padel enthusiasts and book your perfect court time. 
                   Whether you're a beginner or a pro, we have the perfect court for you.</p>
                <div class="hero-buttons">
                    <a href="register.php" class="cta-button">Register Now</a>
            
                </div>
            </div>
        </section>

        <section class="features">
            <div class="feature-card">
                <div class="feature-symbol">⏰</div>
                <h3>24/7 Booking</h3>
                <p>Book your court anytime, anywhere with our simple booking system</p>
            </div>
            <div class="feature-card">
                <div class="feature-symbol">📱</div>
                <h3>Easy Access</h3>
                <p>Book and manage your reservations from any device</p>
            </div>
            <div class="feature-card">
                <div class="feature-symbol">🔒</div>
                <h3>Secure Booking</h3>
                <p>Safe and secure payment processing</p>
            </div>
        </section>

        <section class="benefits-section">
            <h2>Why Choose Our Courts?</h2>
            <div class="benefits-grid">
                <div class="benefit-item">
                    <h3>Professional Courts</h3>
                    <p>High-quality surfaces and equipment</p>
                </div>
                <div class="benefit-item">
                    <h3>Flexible Hours</h3>
                    <p>Open early until late</p>
                </div>
                <div class="benefit-item">
                    <h3>Great Location</h3>
                    <p>Easy to reach with parking</p>
                </div>
                <div class="benefit-item">
                    <h3>Online Support</h3>
                    <p>Help when you need it</p>
                </div>
            </div>
        </section>
        
        
    </main>

    <footer>
        <div class="footer-content">
            
            <div>
                <p><a href="index.php">Padel Court Booking</a></p>
                <p>&copy; <?php echo date('Y'); ?> - Developed by Ahmed AL SABARI & HAMED AL KINDI</p>
                <p>University of Technology and Applied Sciences - Nizwa</p>
            </div>
        </div>
    </footer>
</body>
</html>