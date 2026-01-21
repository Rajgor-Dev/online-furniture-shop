<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Home Decor - Furniture</title>
    <link rel="icon" href="img/icon/shop.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        header {
            text-align: center;
            padding: 20px;
            background: #007BFF;
            color: white;
        }
        h1 {
            margin: 0;
        }
        h2 {
            color: #333;
        }
        p {
            line-height: 1.6;
         
        }
        .contact-section {
            margin-top: 40px;
            padding: 20px;
            background: #e9ecef;
            border-radius: 5px;
        }
        .contact-section h3 {
            margin-top: 0;
        }
        .contact-info {
            margin: 10px 0;
        }
    </style>
</head>
<body> 
    <header>
        <h1>About Us</h1>
    </header>
    <nav class="menu-bar">
    <div class="menu-icons">
        <a href="index.php" title="Home"><img src="img/icon/home.jpg" alt="Home-icon" class="icon" /></a>
        <?php if (isset($_SESSION['UID'])): ?>
            <a href="customerProfile_manage.php" title="Profile"><img src="img/icon/user.jpg" alt="Profile-icon" class="icon" /></a>
        <?php endif; ?>
        <a href="product_display.php" title="Product"><img src="img/icon/sofa.jpg" alt="category-icon" class="icon" /></a> 
	
        <a href="feedback.php" title="Feedback"><img src="img/icon/feedback.jpg" alt="feedback-icon" class="icon" /></a>
       
    </div>
    <form method="GET" action="product_display.php">
      <div class="search-container">
        <input type="text" name="search_query" placeholder="Search..." required />
        <button type="submit"><img src="img/icon/search.jpg" alt="Search" class="icon" /></button>
    </form>
	</div>
	<div class="add-to-cart-icon">
		<a href="cart.php" title="View Cart"><img src="img/icon/add-to-cart.jpg" alt="Add to Cart" class="icon" /></a>
		<?php if (isset($_SESSION['UID'])): ?>
		<a href="order_history.php" title="Order History"><img src="img/icon/order-history.jpg" alt="Order History" class="icon" /></a>
		<?php endif; ?>	 
	</div>
	</nav>
    <div class="container">
        <h2>Welcome to Home Decor Furniture</h2>
        <p>At Home Decor Furniture, we believe that your home should reflect your personal style and comfort. Our mission is to provide high-quality furniture that combines functionality with aesthetic appeal. With years of experience in the industry, we have curated a collection of furniture that caters to diverse tastes and preferences.</p>
        
        <h2>Our Vision</h2>
        <p>We envision a world where every home is beautifully furnished and designed to meet the needs of its inhabitants. Our commitment to quality and customer satisfaction drives us to continuously improve our offerings and services.</p>

        <h2>Our Values</h2>
        <p>Integrity, quality, and customer satisfaction are at the core of our business. We strive to build lasting relationships with our customers by providing exceptional service and support.</p>

        <div class="contact-section">
            <h3>Contact Us</h3>
            <p class="contact-info">For any inquiries or support, please reach out to our customer care team:</p>
            <p class="contact-info"><strong>Email:</strong> homedecorfurniture1@gmail.com</p>
            <p class="contact-info"><strong>Phone:</strong> +91 7041501208</p>
            <p class="contact-info"><strong>Availability:</strong> 24/7 Customer Support</p>
        </div>
    </div>

<?php
	include 'footer.php';
?>
</body>
</html>